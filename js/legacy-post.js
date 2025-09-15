/*global dotclear, jsToolBar */
'use strict';

dotclear.ready(() => {
  dotclear.mm_select = dotclear.getData('mm_select');

  // Toolbar button for multiple media insertion
  jsToolBar.prototype.elements.mmSpaceBefore = {
    type: 'space',
    format: {
      wysiwyg: true,
      wiki: true,
      xhtml: true,
      markdown: true,
    },
  };
  jsToolBar.prototype.elements.mm_select = {
    type: 'button',
    title: 'Multiple image chooser',
    context: 'post',
    icon: dotclear.mm_select.icon,
    icon_dark: dotclear.mm_select.icon_dark,
    fn: {},
    fncall: {},
    open_url: dotclear.mm_select.open_url,
    data: {},
    popup() {
      window.the_toolbar = this;
      this.elements.mm_select.data = {};

      window.open(
        this.elements.mm_select.open_url,
        'dc_popup',
        'alwaysRaised=yes,dependent=yes,toolbar=yes,height=500,width=760,menubar=no,resizable=yes,scrollbars=yes,status=no',
      );
    },
  };
  jsToolBar.prototype.elements.mmSpaceAfter = {
    type: 'space',
    format: {
      wysiwyg: true,
      wiki: true,
      xhtml: true,
      markdown: true,
    },
  };

  jsToolBar.prototype.elements.mm_select.fn.wiki = function () {
    this.elements.mm_select.popup.call(this);
  };
  jsToolBar.prototype.elements.mm_select.fn.xhtml = function () {
    this.elements.mm_select.popup.call(this);
  };
  jsToolBar.prototype.elements.mm_select.fn.wysiwyg = function () {
    this.elements.mm_select.popup.call(this);
  };
  jsToolBar.prototype.elements.mm_select.fn.markdown = function () {
    this.elements.mm_select.popup.call(this);
  };

  // Helpers

  /**
   * Encodes html entities.
   *
   * @param      {string}  str     The string
   * @return     {string}  The encoded string
   */
  const encodeHtmlEntities = (str) => {
    if (str === null || str === '') return '';
    const ret = str.toString();
    const map = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#039;',
    };
    return ret.replace(/[&<>"']/g, (m) => map[m]);
  };

  /**
   * Validate media information (for editor insertion purpose)
   *
   * @param      {{selection: string, alt: string, description: string, link: boolean}}   media   The media
   * @return     {{alt: string, legend: string|false, link: boolean}} validated parameters
   */
  const validateMedia = (media) => {
    // Use selected text or media alternate text or empty string
    const alt = encodeHtmlEntities(media?.selection || media?.alt || '');
    // Use media description if exist and only if alternate text is not empty
    const legend = media?.description && alt.length ? encodeHtmlEntities(media.description) : false;
    // Return validated data
    return {
      alt,
      legend: alt === legend ? false : legend, // Don't repeat alternate text in legend
      link: media?.link && alt.length, // Allow link only if alternate text not empty
    };
  };

  // Wiki
  jsToolBar.prototype.elements.mm_select.fncall.wiki = function () {
    const { data } = this.elements.mm_select;
    if (data === undefined || data.list.length === 0) {
      return;
    }
    const doInsert = (tb, infos) => {
      // insert selected media
      if (infos.settings?.block) {
        const elt = infos.settings.block + (infos.settings?.class ? ` class="${infos.settings?.class}"` : '');
        tb.encloseSelection(`///html\n<${elt}>\n///\n`, `///html\n</${infos.settings.block}>\n///\n`);
      }
      for (const media of Object.values(infos.list)) {
        tb.encloseSelection('', '', (str) => {
          const alignments = {
            left: 'L',
            right: 'R',
            center: 'C',
          };
          const params = validateMedia({
            selection: str,
            alt: media?.title,
            description: media?.description,
            link: infos.settings?.link,
          });

          let res = `((${tb.stripBaseURL(media.src)}|${params.alt}`;
          if (infos.settings.alignment in alignments) {
            res += `|${alignments[infos.settings.alignment]}`;
          } else if (params.legend) {
            res += '|';
          }
          if (params.legend) {
            res += `||${params.legend}`;
          }
          res += '))';

          if (params.link) {
            const ltitle = dotclear.mm_select.img_link_title
              ? `||${encodeHtmlEntities(dotclear.mm_select.img_link_title)}`
              : '';
            return `[${res}|${tb.stripBaseURL(media.url)}${ltitle}]\n`;
          }

          return `${res}\n`;
        });
      }
    };
    dotclear.mm_select.getInfos(data.path, data.list, data.pref, this, doInsert);
  };

  // HTML (source)
  jsToolBar.prototype.elements.mm_select.fncall.xhtml = function () {
    const { data } = this.elements.mm_select;
    if (data === undefined || data.list.length === 0) {
      return;
    }
    const doInsert = (tb, infos) => {
      // insert selected media
      if (infos.settings?.block) {
        const elt = infos.settings.block + (infos.settings?.class ? ` class="${infos.settings?.class}"` : '');
        tb.encloseSelection(`<${elt}>\n`, `</${infos.settings.block}>\n`);
      }
      for (const media of Object.values(infos.list)) {
        tb.encloseSelection('', '', (str) => {
          const alignments = {
            left: dotclear.mm_select.style.left,
            right: dotclear.mm_select.style.right,
            center: dotclear.mm_select.style.center,
          };
          const params = validateMedia({
            selection: str,
            alt: media?.title,
            description: media?.description,
            link: infos.settings?.link,
          });

          let img = `<img src="${tb.stripBaseURL(media.src)}" alt="${params.alt}"`;
          let figure = '<figure';
          const caption = params.legend ? `<figcaption>${params.legend}</figcaption>\n` : '';

          // Cope with required alignment
          if (infos.settings.alignment in alignments) {
            if (params.legend) {
              figure = `${figure} class="${alignments[infos.settings.alignment]}"`;
            } else {
              img = `${img} class="${alignments[infos.settings.alignment]}"`;
            }
          }

          img = `${img}>`;
          figure = `${figure}>`;

          if (params.link) {
            const ltitle = dotclear.mm_select.img_link_title
              ? `title="${encodeHtmlEntities(dotclear.mm_select.img_link_title)}"`
              : '';
            img = `<a href="${tb.stripBaseURL(media.url)}"${ltitle}>${img}</a>`;
          }

          return params.legend ? `${figure}\n${img}\n${caption}</figure>\n` : `${img}\n`;
        });
      }
    };
    dotclear.mm_select.getInfos(data.path, data.list, data.pref, this, doInsert);
  };

  // HTML (wysiwyg)
  jsToolBar.prototype.elements.mm_select.fncall.wysiwyg = function () {
    const { data } = this.elements.mm_select;
    if (data === undefined || data.list.length === 0) {
      return;
    }
    const doInsert = (tb, infos) => {
      let container;
      // insert selected media
      if (infos.settings?.block) {
        container = tb.iwin.document.createElement(infos.settings.block);
        if (infos.settings?.class) {
          container.setAttribute('class', infos.settings?.class);
        }
      }
      for (const media of Object.values(infos.list).reverse()) {
        if (media.src === undefined) {
          return;
        }

        const alignments = {
          left: dotclear.mm_select.style.left,
          right: dotclear.mm_select.style.right,
          center: dotclear.mm_select.style.center,
        };
        const params = validateMedia({
          selection: str,
          alt: media?.title,
          description: media?.description,
          link: infos.settings?.link,
        });

        const fig = params.legend ? tb.iwin.document.createElement('figure') : null;
        const img = tb.iwin.document.createElement('img');
        const block = params.legend ? fig : img;

        // Cope with required alignment
        if (infos.settings.alignment in alignments) {
          block.classList.add(alignments[infos.settings.alignment]);
        }

        img.src = tb.stripBaseURL(media.src);
        img.setAttribute('alt', params.alt);
        if (params.legend) {
          const figcaption = tb.iwin.document.createElement('figcaption');
          figcaption.appendChild(tb.iwin.document.createTextNode(params.legend));
          fig.appendChild(img);
          fig.appendChild(figcaption);
        }

        if (params.link) {
          const ltitle = dotclear.mm_select.img_link_title ? encodeHtmlEntities(dotclear.mm_select.img_link_title) : '';
          const a = tb.iwin.document.createElement('a');
          a.href = tb.stripBaseURL(media.url);
          a.setAttribute('title', ltitle);
          a.appendChild(block);
          if (container === undefined) {
            tb.insertNode(a);
          } else {
            container.insertNode(a);
          }
          return;
        }
        if (container === undefined) {
          tb.insertNode(block);
        } else {
          container.insertNode(block);
        }
      }
      if (container !== undefined) {
        tb.insertNode(container);
      }
    };

    dotclear.mm_select.getInfos(data.path, data.list, data.pref, this, doInsert);
  };

  // Markdown
  jsToolBar.prototype.elements.mm_select.fncall.markdown = function () {
    const { data } = this.elements.mm_select;
    if (data === undefined || data.list.length === 0) {
      return;
    }
    const doInsert = (tb, infos) => {
      // insert selected media
      if (infos.settings?.block) {
        const elt = infos.settings.block + (infos.settings?.class ? ` class="${infos.settings?.class}"` : '');
        tb.encloseSelection(`<${elt}>\n`, `</${infos.settings.block}>\n`);
      }
      for (const media of Object.values(infos.list)) {
        tb.encloseSelection('', '', (str) => {
          const alignments = {
            left: dotclear.mm_select.style.left,
            right: dotclear.mm_select.style.right,
            center: dotclear.mm_select.style.center,
          };
          const params = validateMedia({
            selection: str,
            alt: media?.title,
            description: media?.description,
            link: infos.settings?.link,
          });

          let img = `<img src="${tb.stripBaseURL(media.src)}" alt="${params.alt}"`;
          let figure = '<figure';
          const caption = params.legend ? `<figcaption>${params.legend}</figcaption>\n` : '';

          // Cope with required alignment
          if (infos.settings.alignment in alignments) {
            if (params.legend) {
              figure = `${figure} ${dotclear.mm_select.style.class ? 'class' : 'style'}="${
                alignments[infos.settings.alignment]
              }"`;
            } else {
              img = `${img} ${dotclear.mm_select.style.class ? 'class' : 'style'}="${alignments[infos.settings.alignment]}"`;
            }
          }

          img = `${img}>`;
          figure = `${figure}>`;

          if (params.link) {
            const ltitle = dotclear.mm_select.img_link_title ? encodeHtmlEntities(dotclear.mm_select.img_link_title) : '';
            const title = ltitle ? `title="${ltitle}"` : '';
            img = `<a href="${tb.stripBaseURL(media.url)}"${title}>${img}</a>`;
          }

          return params.legend ? `${figure}\n${img}\n${caption}</figure>\n` : `${img}\n`;
        });
      }
    };
    dotclear.mm_select.getInfos(data.path, data.list, data.pref, this, doInsert);
  };

  // Get multiple media insertion config
  jsToolBar.prototype.elements.mm_select.title = dotclear.mm_select.title;

  // Multiple media insertion helpers
  dotclear.mm_select.getInfos = (path, list, pref, tb, fn) => {
    // Call REST Service
    dotclear.jsonServicesPost(
      'getMediaInfos',
      (data) => {
        if (data.ret) {
          fn(tb, data.info);
        }
      },
      {
        path,
        list: JSON.stringify(list),
        pref: JSON.stringify(pref),
      },
    );

    return null;
  };
});
