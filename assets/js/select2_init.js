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


  $( '.js-basic-multiple-tags' ).select2( {
    minimumInputLength: 1,
    tags: true,
    tokenSeparators: [','],
    ajax: {
      url: '/wp-json/mla-academic-interests/v1/terms',
      cache: true
    },
    templateResult: function( result ) {
      // hide result which exactly matches user input to avoid confusion with differently-cased matches
      if ( $('.select2-search__field').val() == result.text ) {
        result.text = null;
      }

      return result.text;
    }
  } );

  // ensure user-input terms conform to existing terms regardless of case
  // e.g. if user enters "music" and "Music" exists, select "Music"
  $( '.js-basic-multiple-tags' ).on( 'select2:selecting', function( e ) {
    var input_term = e.params.args.data.id;
    var existing_terms = $( '.select2-results__option' ).not( '.select2-results__option--highlighted' );
    var Select2 = $( '.js-basic-multiple-tags' ).data( 'select2' );

    $.each( existing_terms, function( i, term_el ) {
      var term = $( term_el ).text();

      // if this term already exists with a different case, select that instead
      if ( input_term.toUpperCase() == term.toUpperCase() ) {
        // overwrite the user-input term with the canonical one
        e.params.args.data.id = term;
        e.params.args.data.text = term;

        // trigger another select event with the updated term
        Select2.constructor.__super__.trigger.call( Select2, 'select', e.params.args );

        e.preventDefault();
      }
    } );
  } );

} );
