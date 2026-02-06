/**
 * RAEL Editor Import Template Notice JS
 * Handles template import click and shows instant review notice
 *
 * @package Responsive Addons for Elementor
 */

jQuery(document).ready(function ($) {
  // On click of template import button in editor
  $(document).on("click", ".rst-library-template-insert", function (e) {
    e.preventDefault();

    // AJAX request to mark template imported
    $.ajax({
      url: rael_editor_data.ajax_url, 
      type: "POST",
      data: {
        action: "rael_mark_template_imported",
        nonce: rael_editor_data.rael_import_nonce,
        post_id: rael_editor_data.post_id,
      },
      success: function (response) {
        if (response.success) {
          //Template marked as imported

        }
      },
      error: function () {
        //AJAX request failed
      },
    });
  });
});
