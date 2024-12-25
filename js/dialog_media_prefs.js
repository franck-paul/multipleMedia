/*global $ */
'use strict';

dotclear.ready(() => {
  $('#media-insert-cancel').on('click', () => {
    window.close();
  });
  $('#media-insert-ok').on('click', () => {
    sendCloseMultiple();
    window.close();
  });

  function sendCloseMultiple() {
    // Return back settings
    const tb = window.the_toolbar;
    const { data } = tb.elements.mm_select;

    data.pref = {
      size: $('input[name="src"][type=radio]:checked').attr('value'),
      alignment: $('input[name="alignment"][type=radio]:checked').attr('value'),
      link: $('input[name="insertion"][type=radio]:checked').attr('value') === 'link' ? '1' : '0',
      legend: $('input[name="legend"][type=radio]:checked').attr('value'),
      mediadef: $('input[name="mediadef"]').attr('value'),
    };

    // Let the magic happen
    tb.elements.mm_select.fncall[tb.mode].call(tb);
  }
});
