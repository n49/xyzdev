jQuery(document).ready(function($){
	//console.log('party on the floor');
	/* Form Buttons */
	$('.wpcf7-form').on('wpcf7cf_change_step', function(e, previousStep, currentStep) {
		active = 'form-active-' + currentStep;

		$('.form-section-wrap').removeClass('form-active-1 form-active-2 form-active-3');
		$('.form-section-wrap').addClass(active);

		$('.form-section-wrap .tabs-fake ul.horizontal li').removeClass('active done');
		console.log('what is active', active);
		if(active === 'form-active-2'){
			$('.form-section-wrap .tabs-fake ul.horizontal li.first').addClass('done');
			$('.form-section-wrap .tabs-fake ul.horizontal li.second').addClass('active');
		}

		if(active === 'form-active-3'){
			$('.form-section-wrap .tabs-fake ul.horizontal li.first, .form-section-wrap .tabs-fake ul.horizontal li.second').addClass('done');
			$('.form-section-wrap .tabs-fake ul.horizontal li.third').addClass('active');
		}
	})

	/* Multiform switching */

	$form = $('.wpcf7-form');

	$('.multiform-steps .first').click(function(e){
		e.preventDefault();
		wpcf7cf.multistepMoveToStep($form, 1);
	});

	$('.multiform-steps .second').click(function(e){
		e.preventDefault();
		wpcf7cf.multistepMoveToStep($form, 2);
	});

	$('.multiform-steps .third').click(function(e){
		e.preventDefault();
		wpcf7cf.multistepMoveToStep($form, 3);
	});

	/* Add slash to card expiry field */

	var cardExpiry = document.getElementById('card-expiry');

	cardExpiry.addEventListener('keydown', function( e ){
	    if(e.which !== 8) {
	        var numChars = e.target.value.length;

	        if(numChars === 2){
	            var thisVal = e.target.value;
	            thisVal += '/';
	            e.target.value = thisVal;
	        }
	    }
	});

	/* Allow only numbers for card number field */

	function forceNumeric(){
	    var $input = $(this);
	    $input.val($input.val().replace(/[^\d]+/g,''));
	}

	$('body').on('input', '#card-number', forceNumeric);
});
