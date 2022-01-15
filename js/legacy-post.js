/*global dotclear, jsToolBar */
'use strict';

// Toolbar button for multiple media insertion
jsToolBar.prototype.elements.mm_select = {
  type: 'button',
  title: 'Multiple image chooser',
  context: 'post',
  icon: 'index.php?pf=multipleMedia/icon.svg',
  fn: {},
  fncall: {},
  open_url: 'media.php?popup=1&plugin_id=dcLegacyEditor&select=2', // select=2 : sélection multiple
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
  const d = this.elements.mm_select.data;
  if (d === undefined || d.list.length === 0) {
    return;
  }
  const doInsert = (tb, infos) => {
    // insert selected media
    Object.values(infos.list).forEach((media) => {
      tb.encloseSelection('', '', (str) => {
        const alt = str ? str : media.title;
        let res = `((${tb.stripBaseURL(media.src)}|${alt}`;

        if (infos.settings.alignment == 'left') {
          res += '|L';
        } else if (infos.settings.alignment == 'right') {
          res += '|R';
        } else if (infos.settings.alignment == 'center') {
          res += '|C';
        } else if (media.description) {
          res += '|';
        }
        if (media.title) {
          res += `|${media.title}`;
        }
        if (media.description) {
          res += `|${media.description}`;
        }

        res += '))';

        if (infos.settings.link) {
          return `[${res}|${tb.stripBaseURL(media.url)}${alt ? `||${alt}` : ''}]\n`;
        }

        return `${res}\n`;
      });
    });
  };
  // Get medias info
  const that = this;
  dotclear.mm_select.getInfos(d.path, d.list, d.pref, that, doInsert);
};

// HTML (source)
jsToolBar.prototype.elements.mm_select.fncall.xhtml = function () {
  const d = this.elements.mm_select.data;
  if (d === undefined || d.list.length === 0) {
    return;
  }
  const doInsert = (tb, infos) => {
    // insert selected media
    Object.values(infos.list).forEach((media) => {
      tb.encloseSelection('', '', (str) => {
        const alt = str ? str : media.title;
        let res = `<img src="${tb.stripBaseURL(media.src)}" alt="${alt
          .replace('&', '&amp;')
          .replace('>', '&gt;')
          .replace('<', '&lt;')
          .replace('"', '&quot;')}"`;

        if (infos.settings.alignment == 'left') {
          res += ' style="float: left; margin: 0 1em 1em 0;"';
        } else if (infos.settings.alignment == 'right') {
          res += ' style="float: right; margin: 0 0 1em 1em;"';
        } else if (infos.settings.alignment == 'center') {
          res += ' style="margin: 0 auto; display: block;"';
        }

        if (media.description) {
          res += ` title="${media.description
            .replace('&', '&amp;')
            .replace('>', '&gt;')
            .replace('<', '&lt;')
            .replace('"', '&quot;')}"`;
        }

        res += ' />';

        if (infos.settings.link) {
          const ltitle = alt
            ? ` title="${alt.replace('&', '&amp;').replace('>', '&gt;').replace('<', '&lt;').replace('"', '&quot;')}"`
            : '';
          return `<a href="${tb.stripBaseURL(media.url)}"${ltitle}>${res}</a>`;
        }

        return `${res}\n`;
      });
    });
  };
  // Get medias info
  const that = this;
  dotclear.mm_select.getInfos(d.path, d.list, d.pref, that, doInsert);
};

// HTML (wysiwyg)
jsToolBar.prototype.elements.mm_select.fncall.wysiwyg = function () {
  const d = this.elements.mm_select.data;
  if (d === undefined || d.list.length === 0) {
    return;
  }
  const doInsert = (tb, infos) => {
    // insert selected media
    Object.values(infos.list)
      .reverse()
      .forEach((media) => {
        const alt = tb.getSelectedText() ? tb.getSelectedText() : media.title;
        if (media.src == undefined) {
          return;
        }

        const fig = media.description ? tb.iwin.document.createElement('figure') : null;
        const img = tb.iwin.document.createElement('img');
        const block = media.description ? fig : img;

        if (infos.settings.alignment == 'left') {
          if (block.style.styleFloat == undefined) {
            block.style.cssFloat = 'left';
          } else {
            block.style.styleFloat = 'left';
          }
          block.style.marginTop = 0;
          block.style.marginRight = '1em';
          block.style.marginBottom = '1em';
          block.style.marginLeft = 0;
        } else if (infos.settings.alignment == 'right') {
          if (block.style.styleFloat == undefined) {
            block.style.cssFloat = 'right';
          } else {
            block.style.styleFloat = 'right';
          }
          block.style.marginTop = 0;
          block.style.marginRight = 0;
          block.style.marginBottom = '1em';
          block.style.marginLeft = '1em';
        } else if (infos.settings.alignment == 'center') {
          if (media.description) {
            block.style.textAlign = 'center';
          } else {
            block.style.marginTop = 0;
            block.style.marginRight = 'auto';
            block.style.marginBottom = 0;
            block.style.marginLeft = 'auto';
            block.style.display = 'block';
          }
        }

        img.src = tb.stripBaseURL(media.src);
        img.setAttribute('alt', alt);
        if (media.title) {
          img.setAttribute('title', media.title);
        }
        if (media.description) {
          const figcaption = tb.iwin.document.createElement('figcaption');
          figcaption.appendChild(tb.iwin.document.createTextNode(d.description));
          fig.appendChild(img);
          fig.appendChild(figcaption);
        }

        if (infos.settings.link) {
          const a = tb.iwin.document.createElement('a');
          a.href = tb.stripBaseURL(media.url);
          if (alt) {
            a.setAttribute('title', alt);
          }
          a.appendChild(block);
          tb.insertNode(a);
        } else {
          tb.insertNode(block);
        }
      });
  };

  // Get medias info
  const that = this;
  dotclear.mm_select.getInfos(d.path, d.list, d.pref, that, doInsert);
};

// Markdown
jsToolBar.prototype.elements.mm_select.fncall.markdown = function () {
  const d = this.elements.mm_select.data;
  if (d === undefined || d.list.length === 0) {
    return;
  }
  const doInsert = (tb, infos) => {
    // insert selected media
    Object.values(infos.list).forEach((media) => {
      tb.encloseSelection('', '', (str) => {
        const alignments = {
          left: 'float: left; margin: 0 1em 1em 0;',
          right: 'float: right; margin: 0 0 1em 1em;',
          center: 'margin: 0 auto; display: table;',
        };
        const alt = (str ? str : media.title)
          .replace('&', '&amp;')
          .replace('>', '&gt;')
          .replace('<', '&lt;')
          .replace('"', '&quot;');
        const legend =
          media.description !== ''
            ? media.description.replace('&', '&amp;').replace('>', '&gt;').replace('<', '&lt;').replace('"', '&quot;')
            : false;
        let img = `<img src="${tb.stripBaseURL(media.src)}" alt="${alt}"`;
        let figure = '<figure';
        const caption = legend ? `<figcaption>${legend}</figcaption>\n` : '';

        if (legend) {
          img = `${img} title="${legend}"`;
        }

        // Cope with required alignment
        if (infos.settings.alignment in alignments) {
          if (legend) {
            figure = `${figure} style="${alignments[infos.settings.alignment]}"`;
          } else {
            img = `${img} style="${alignments[infos.settings.alignment]}"`;
          }
        }

        img = `${img} />`;
        figure = `${figure}>`;

        if (infos.settings.link) {
          // Enclose image with link
          const ltitle = alt
            ? ` title="${alt.replace('&', '&amp;').replace('>', '&gt;').replace('<', '&lt;').replace('"', '&quot;')}"`
            : '';
          img = `<a href="${tb.stripBaseURL(media.url)}"${ltitle}>${img}</a>`;
        }

        return legend ? `${figure}\n${img}\n${caption}</figure>\n` : `${img}\n`;
      });
    });
  };
  // Get medias info
  const that = this;
  dotclear.mm_select.getInfos(d.path, d.list, d.pref, that, doInsert);
};

// Get multiple media insertion config
dotclear.mm_select = dotclear.getData('mm_select');
jsToolBar.prototype.elements.mm_select.title = dotclear.mm_select.title;

// Multiple media insertion helpers
dotclear.mm_select.getInfos = (path, list, pref, tb, fn) => {
  // Call REST Service
  $.get('services.php', {
    f: 'getMediaInfos',
    xd_check: dotclear.nonce,
    path,
    list,
    pref,
  })
    .done((data) => {
      if ($('rsp[status=failed]', data).length > 0) {
        // For debugging purpose only:
        // console.log($('rsp',data).attr('message'));
        window.console.log('Dotclear REST server error');
      } else {
        // ret -> status (true/false)
        // data -> media infos
        const ret = Number($('rsp>mm_select', data).attr('ret'));
        if (ret) {
          const json = $('rsp>mm_select', data).attr('data');
          fn(tb, JSON.parse(json));
        }
      }
    })
    .fail((jqXHR, textStatus, errorThrown) => {
      window.console.log(`AJAX ${textStatus} (status: ${jqXHR.status} ${errorThrown})`);
    })
    .always(() => {
      // Nothing here
    });
  return null;
};
