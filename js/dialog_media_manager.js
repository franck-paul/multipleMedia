/*global $ */
'use strict';

$(() => {
  const config = dotclear.getData('mm_media_manager');

  $('#select_medias').on('click', () => {
    sendCloseMultiple();
    window.close();
  });

  function sendCloseMultiple() {
    const insert_form = $('#form-medias').get(0);
    if (!insert_form) {
      return;
    }
    // Insert all selected media with default options
    const list = document.querySelectorAll('.files-group input[type=checkbox]:checked');
    if (!list?.length) {
      return;
    }
    // Get some useful info
    const media_path = document.querySelector('#form-medias input[name=d]').value;

    // Return back selection
    const tb = window.opener.the_toolbar;
    const { data } = tb.elements.mm_select;

    data.list = Array.from(list).map((a) => a.value); // media name(s)
    data.path = media_path; // current media path

    // Ask for prefs
    const window_pref = window.open(
      `${config.url}${media_path}`,
      'dc_popup_opt',
      'alwaysRaised=yes,dependent=yes,toolbar=yes,height=500,width=760,menubar=no,resizable=yes,scrollbars=yes,status=no',
    );
    window_pref.the_toolbar = tb;
    window_pref.opener = window.opener;
  }
});
