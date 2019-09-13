jQuery(addCarouselEvents.bind($));

jQuery(function($) {
	$('.mo-openid-app-icons .btn').attr('style', '');
});

jQuery(function($) {
	$('.blogroll-aliment-healthy-bar').tooltip();
	$('.blogroll-aliment-moderate-bar').tooltip();
	$('.blogroll-aliment-unhealthy-bar').tooltip();
	$('.blogroll-aliment-unknown-bar').tooltip();
});

jQuery(function($) {
	// $('.blogroll-aliment-description').shave(32);
	// var options = { responsive: true };
	// ellipsed.ellipsis('.blogroll-aliment-description', 3, options);
});

function addCarouselEvents($) {
	$(document).on('slide.bs.carousel', onCarouselSlide);
}

function onCarouselSlide(e) {
	const nextH = jQuery(e.relatedTarget).height();

	jQuery(e.target)
		.find('.carousel-inner')
		.animate({ height: nextH }, 610, 'swing', animateSlide);

	function animateSlide() {
		jQuery(this).css('height', 'auto');
	}
}
