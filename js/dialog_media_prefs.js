/*global dotclear */
'use strict';

dotclear.ready(() => {
  document.getElementById('media-insert-cancel')?.addEventListener('click', (event) => {
    event.preventDefault();
    window.close();
  });
  document.getElementById('media-insert-ok')?.addEventListener('click', (event) => {
    event.preventDefault();
    sendCloseMultiple();
    window.close();
  });

  function sendCloseMultiple() {
    // Return back settings
    const tb = window.the_toolbar;
    const { data } = tb?.elements.mm_select;

    data.pref = {
      size: document.querySelector('input[name="src"][type=radio]:checked').getAttribute('value'),
      alignment: document.querySelector('input[name="alignment"][type=radio]:checked').getAttribute('value'),
      link: document.querySelector('input[name="insertion"][type=radio]:checked').getAttribute('value') === 'link' ? '1' : '0',
      legend: document.querySelector('input[name="legend"][type=radio]:checked').getAttribute('value'),
      mediadef: document.querySelector('input[name="mediadef"]').getAttribute('value'),
    };

    // Let the magic happen
    tb.elements.mm_select.fncall[tb.mode].call(tb);
  }
});
