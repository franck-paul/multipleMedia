/*global dotclear, jsToolBar */
'use strict';

$(() => {
  dotclear.mm_select = dotclear.getData('mm_select');

  // Toolbar button for multiple media insertion
  jsToolBar.prototype.elements.mm_select = {
    type: 'button',
    title: 'Multiple image chooser',
    context: 'post',
    icon: dotclear.mm_select.icon,
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
      Object.values(infos.list).forEach((media) => {
        tb.encloseSelection('', '', (str) => {
          const alt = (str || media.title)
            .replace('&', '&amp;')
            .replace('>', '&gt;')
            .replace('<', '&lt;')
            .replace('"', '&quot;');
          const legend =
            media.description !== '' && alt.length // No legend if no alt
              ? media.description.replace('&', '&amp;').replace('>', '&gt;').replace('<', '&lt;').replace('"', '&quot;')
              : false;
          let res = `((${tb.stripBaseURL(media.src)}|${alt}`;

          if (infos.settings.alignment == 'left') {
            res += '|L';
          } else if (infos.settings.alignment == 'right') {
            res += '|R';
          } else if (infos.settings.alignment == 'center') {
            res += '|C';
          } else if (legend) {
            res += '|';
          }
          if (legend) {
            res += `|`; // no title in img
            res += `|${legend}`;
          }
          res += '))';

          if (infos.settings.link && alt.length) {
            // Link only if alt not empty
            return `[${res}|${tb.stripBaseURL(media.url)}${
              dotclear.mm_select.img_link_title ? `||${dotclear.mm_select.img_link_title}` : ''
            }]`;
          }

          return `${res}\n`;
        });
      });
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
      Object.values(infos.list).forEach((media) => {
        tb.encloseSelection('', '', (str) => {
          const alignments = {
            left: dotclear.mm_select.style.left,
            right: dotclear.mm_select.style.right,
            center: dotclear.mm_select.style.center,
          };
          const alt = (str || media.title)
            .replace('&', '&amp;')
            .replace('>', '&gt;')
            .replace('<', '&lt;')
            .replace('"', '&quot;');
          let legend =
            media.description !== '' && alt.length // No legend if no alt
              ? media.description.replace('&', '&amp;').replace('>', '&gt;').replace('<', '&lt;').replace('"', '&quot;')
              : false;
          // Do not duplicate information
          if (alt === legend) legend = false;
          let img = `<img src="${tb.stripBaseURL(media.src)}" alt="${alt}"`;
          let figure = '<figure';
          const caption = legend ? `<figcaption>${legend}</figcaption>\n` : '';

          // Cope with required alignment
          if (infos.settings.alignment in alignments) {
            if (legend) {
              figure = `${figure} class="${alignments[infos.settings.alignment]}"`;
            } else {
              img = `${img} class="${alignments[infos.settings.alignment]}"`;
            }
          }

          img = `${img}>`;
          figure = `${figure}>`;

          if (infos.settings.link && alt.length) {
            // Enclose image with link (only if non empty alt)
            const ltitle = dotclear.mm_select.img_link_title
              ? ` title="${dotclear.mm_select.img_link_title
                  .replace('&', '&amp;')
                  .replace('>', '&gt;')
                  .replace('<', '&lt;')
                  .replace('"', '&quot;')}"`
              : '';
            img = `<a href="${tb.stripBaseURL(media.url)}"${ltitle}>${img}</a>`;
          }

          return legend ? `${figure}\n${img}\n${caption}</figure>\n` : `${img}\n`;
        });
      });
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
      Object.values(infos.list)
        .reverse()
        .forEach((media) => {
          if (media.src == undefined) {
            return;
          }

          const alignments = {
            left: dotclear.mm_select.style.left,
            right: dotclear.mm_select.style.right,
            center: dotclear.mm_select.style.center,
          };
          const alt = (tb.getSelectedText() ? tb.getSelectedText() : media.title)
            .replace('&', '&amp;')
            .replace('>', '&gt;')
            .replace('<', '&lt;')
            .replace('"', '&quot;');
          let legend =
            media.description !== '' && alt.length // No legend if no alt
              ? media.description.replace('&', '&amp;').replace('>', '&gt;').replace('<', '&lt;').replace('"', '&quot;')
              : false;

          // Do not duplicate information
          if (alt === legend) legend = false;

          const fig = legend ? tb.iwin.document.createElement('figure') : null;
          const img = tb.iwin.document.createElement('img');
          const block = legend ? fig : img;

          // Cope with required alignment
          if (infos.settings.alignment in alignments) {
            block.classList.add(alignments[infos.settings.alignment]);
          }

          img.src = tb.stripBaseURL(media.src);
          img.setAttribute('alt', alt);
          if (legend) {
            const figcaption = tb.iwin.document.createElement('figcaption');
            figcaption.appendChild(tb.iwin.document.createTextNode(legend));
            fig.appendChild(img);
            fig.appendChild(figcaption);
          }

          if (infos.settings.link && alt.length) {
            // Enclose image with link (only if non empty alt)
            const ltitle = alt
              ? dotclear.mm_select.img_link_title
                  .replace('&', '&amp;')
                  .replace('>', '&gt;')
                  .replace('<', '&lt;')
                  .replace('"', '&quot;')
              : '';
            const a = tb.iwin.document.createElement('a');
            a.href = tb.stripBaseURL(media.url);
            a.setAttribute('title', ltitle);
            a.appendChild(block);
            if (container === undefined) {
              tb.insertNode(a);
            } else {
              container.insertNode(a);
            }
          } else {
            if (container === undefined) {
              tb.insertNode(block);
            } else {
              container.insertNode(block);
            }
          }
        });
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
      Object.values(infos.list).forEach((media) => {
        tb.encloseSelection('', '', (str) => {
          const alignments = {
            left: dotclear.mm_select.style.left,
            right: dotclear.mm_select.style.right,
            center: dotclear.mm_select.style.center,
          };
          const alt = (str || media.title)
            .replace('&', '&amp;')
            .replace('>', '&gt;')
            .replace('<', '&lt;')
            .replace('"', '&quot;');
          let legend =
            media.description !== '' && alt.length // No legend if no alt
              ? media.description.replace('&', '&amp;').replace('>', '&gt;').replace('<', '&lt;').replace('"', '&quot;')
              : false;
          // Do not duplicate information
          if (alt === legend) legend = false;
          let img = `<img src="${tb.stripBaseURL(media.src)}" alt="${alt}"`;
          let figure = '<figure';
          const caption = legend ? `<figcaption>${legend}</figcaption>\n` : '';

          // Cope with required alignment
          if (infos.settings.alignment in alignments) {
            if (legend) {
              figure = `${figure} ${dotclear.mm_select.style.class ? 'class' : 'style'}="${
                alignments[infos.settings.alignment]
              }"`;
            } else {
              img = `${img} ${dotclear.mm_select.style.class ? 'class' : 'style'}="${alignments[infos.settings.alignment]}"`;
            }
          }

          img = `${img}>`;
          figure = `${figure}>`;

          if (infos.settings.link && alt.length) {
            // Enclose image with link (only if non empty alt)
            const ltitle = alt
              ? ` title="${dotclear.mm_select.img_link_title
                  .replace('&', '&amp;')
                  .replace('>', '&gt;')
                  .replace('<', '&lt;')
                  .replace('"', '&quot;')}"`
              : '';
            img = `<a href="${tb.stripBaseURL(media.url)}"${ltitle}>${img}</a>`;
          }

          return legend ? `${figure}\n${img}\n${caption}</figure>\n` : `${img}\n`;
        });
      });
    };
    dotclear.mm_select.getInfos(data.path, data.list, data.pref, this, doInsert);
  };

  // Get multiple media insertion config
  jsToolBar.prototype.elements.mm_select.title = dotclear.mm_select.title;

  // Multiple media insertion helpers
  dotclear.mm_select.getInfos = (path, list, pref, tb, fn) => {
    list = JSON.stringify(list);
    pref = JSON.stringify(pref);
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
        list,
        pref,
      },
    );

    return null;
  };
});
