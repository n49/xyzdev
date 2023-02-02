jQuery(document).ready(function($){
	/* Timeline */

	$('.timeline-for').slick({
		slidesToShow: 3,
		slidesToScroll: 1,
		centerMode: true,
		centerPadding: '80px',
		focusOnSelect: true,
		arrows: false,
		asNavFor: '.timeline-nav',
		responsive: [
			{
				breakpoint: 640,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
					centerPadding: '20px',
				}
			},
		]
	});

	$('.timeline-nav').slick({
		slidesToShow: 5,
		slidesToScroll: 3,
		asNavFor: '.timeline-for',
		focusOnSelect: true,
		centerMode: true,
		responsive: [
			{
				breakpoint: 640,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 3
				}
			},
		]
	});
});
