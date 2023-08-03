jQuery(document).ready(function($){
	/* Sticky Filters on Mobile */

	if($(window).width() <= 800) {
		if ($('.mobile-fixed').length) {
			$('.header').addClass('unstuck');

			var sticky_menu = new Waypoint.Sticky({
				element: $('.mobile-fixed')[0],
				offset: -56
			});
		}
	}

	/* MixItUp */

	var targetSelector = '.mix';

	var mixer = mixitup('.mixitup-container-filters', {
		load: {
			filter: '.all'
		},
		controls: {
			toggleLogic: 'or'
		},
		animation: {
			enable: true
		},
		selectors: {
			target: targetSelector
		},
		multifilter: {
			enable: true
		},
	});

	$('.mixitup-container-filters').on('mixStart', function(e, state){
		$('.as-category').each(function() {
			$(this).removeClass('all-mix-hidden');
		});
	});

	$('.mixitup-container-filters').on('mixEnd', function(e, state){
		var state = mixer.getState();

		$('.filters-nav .count span, .count.as-btn span').text('(' + state.totalShow + ')');
	
		$('.as-category').each(function() {
			var allElementsHidden = true;

			$(this).find('.mix').each(function() {
				if ($(this).is(':visible')) {
					allElementsHidden = false;
					return false; // Stop the loop if any visible element is found
				}
			});

			if (allElementsHidden) {
				$(this).addClass('all-mix-hidden');
			}
		});
	});

	/* Toggle Filter Sidebar */

	$('.filters-nav.fullwidth .count').on('click', function(e){
		e.preventDefault();

		$(this).toggleClass('active');
		$('.mixitup-container-wrapper').toggleClass('active');
	});

	$('.count.as-btn').on('click', function(e){
		e.preventDefault();

		$(this).toggleClass('active');
		$('.filters-nav.side').toggleClass('active');
		$('.filters-box').slideToggle();
	});
});