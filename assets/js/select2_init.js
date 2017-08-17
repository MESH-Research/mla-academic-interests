// Select 2 controls
jQuery(document).ready( function($) {

  $(".js-basic-single-required").select2({
    minimumResultsForSearch: "36",
  });

  $(".js-basic-single-optional").select2({
    allowClear: "true",
    minimumResultsForSearch: "36",
  });

  $(".js-basic-multiple").select2({
  });

  $(".js-basic-multiple-tags").select2({
    minimumInputLength: 1,
    tags: "true",
    tokenSeparators: [','],
    ajax: {
      url: '/wp-json/mla-academic-interests/v1/terms'
    }
  });

  // ensure user-input terms conform to existing terms regardless of case
  // e.g. if user enters "music" and "Music" exists, select "Music"
  $('.js-basic-multiple-tags').on('select2:selecting', function(e) {
    var input_term = e.params.args.data.text;
    var existing_terms = $('.select2-results__option').not('.select2-results__option--highlighted');
    var selected_terms = $('.js-basic-multiple-tags').val() || [];
    var Select2 = $('.js-basic-multiple-tags').data('select2');

    $.each(existing_terms, function(i, term_el) {
      var term = $(term_el).text();

      // this is an exact case-sensitive match already, accept & move on
      if (input_term === term) {
        console.log( 'exact match' );
        return;
      }

      // if this term already exists with a different case, select that instead
      if (input_term.toUpperCase() === term.toUpperCase()) {
        console.log( 'case-insensitive match' );
        // overwrite the user-input term with the canonical one
        e.params.args.data.id = term;
        e.params.args.data.text = term;

        // trigger another select event with the updated term
        Select2.constructor.__super__.trigger.call(Select2, 'select', e.params.args);

        e.preventDefault();
      }
    });
  });

} );
