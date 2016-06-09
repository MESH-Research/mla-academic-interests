// Select 2 controls
jQuery(document).ready( function($) {

	$(".js-basic-multiple").select2({
		maximumSelectionLength: 5,
	});
	$(".js-basic-multiple-tags").select2({
		maximumSelectionLength: 5,
		tags: "true",
		tokenSeparators: [',']
	});

	$(".js-basic-single-required").select2({
		minimumResultsForSearch: "36",
	});
	$(".js-basic-single-optional").select2({
		allowClear: "true",
		minimumResultsForSearch: "36",
	});
} );
