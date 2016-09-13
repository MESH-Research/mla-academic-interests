// Select 2 controls
jQuery(document).ready( function($) {

	$(".js-basic-multiple").select2({
	});
	$(".js-basic-multiple-tags").select2({
		minimumInputLength: 1,
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
