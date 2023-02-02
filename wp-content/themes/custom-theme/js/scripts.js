jQuery(document).ready(function($){
	/* Sticky */
	
	console.log('script here');
	
	function getCookieM(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
	}
	var locationId = getCookieM('unitLocation');
	var locationName = 'Toronto Downtown';
	
	if(locationId == '3') {
		locationName = 'Toronto West';
	}
	if(locationId == '4') {
		locationName = 'Etobicoke';
	}
	if(locationId == '1') {
		locationName = 'Scarborough';
	}
	if(locationId == '2') {
		locationName = 'Mississauga';
	}
	if(locationId == '5') {
		locationName = 'Mobile';
	}
		if(locationId == '6') {
		locationName = 'Toronto Midtown';
	}
	
	
// 	var locations = {
// 				'3' : 'Toronto West',
// 				4 => 'Etobicoke',
// 				1 => 'Scarborough',
// 				2 => 'Mississauga',
// 				5 => 'Mobile',
// 				6 => 'Toronto Midtown',
// 				7 => 'Toronto Downtown'
// 	};
	document.addEventListener( 'wpcf7submit', function( event ) {
		if ( '26914' == event.detail.contactFormId ) {
    gtag('event', 'Shredding ' + locationName);
  }
  if ( '249' == event.detail.contactFormId ) {
    gtag('event', 'Truck ' + locationName);
  }
}, false );
	

	var sticky = new Waypoint.Sticky({
		element: $('.header')[0],
		offset: -210
	})

	/* ScrollUp Button */

	$(window).scroll(function(){
	    if ($(this).scrollTop() > 100) {
	        $('.scrollup').fadeIn();
	    } else {
	        $('.scrollup').fadeOut();
	    }
	});

	$('.scrollup').click(function(){
	    $('html, body').animate({ scrollTop: 0 }, 600);
	    return false;
	});

	/* Date */

	$('.date input').flatpickr();

	/* Select */

	$('.wpcf7-select').chosen({
		inherit_select_classes: true,
		disable_search_threshold: 100
	});

	$('.wpcf7-select').on('change', function () {
		$('.chosen-container-single').addClass('active');
	});

	/* Dynamic Select */

	$('.wpcf7cf_add').on('click', function () {
		var prefix = $(this).parents('.wpcf7cf_repeater_controls').prev().data('repeater_sub_suffix');
		var select = 'select[name=location' + prefix + ']';

		$(select).chosen({
			inherit_select_classes: true,
			disable_search_threshold: 100
		});
	});

	/* Smooth Scroll */

	$('a[href*=#]:not(.normal):not([href=#])').click(function() {
		if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') || location.hostname == this.hostname) {
			var target = $(this.hash);
			target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
			if (target.length) {
				$('html,body').animate({
					scrollTop: target.offset().top - 80
			}, 1000);
				return false;
			}
		}
	});

	/* Notice Slider */

	$('.carousel-notices').slick({
		infinite: true,
		dots: true,
		slidesToShow: 1,
		slidesToScroll: 1,
	});

	/* Gallery */

	$('.wp-slick-slider').slick({
		infinite: false,
		dots: true,
		slidesToShow: 1,
		slidesToScroll: 1,
		fade: true,
		cssEase: 'linear',
	});

	$('.wp-slick-slider.has-lightbox').lightGallery({
		selector: 'a',
		download: false,
		counter: false
	});

	$('.has-lightbox-simple').lightGallery({
		selector: 'a',
		download: false,
		counter: false
	});

	$('.review-slider .columns').slick({
		infinite: false,
		arrows: true,
		appendArrows: '.review-slider .slider-nav',
		dots: true,
		appendDots: '.review-slider .slider-nav',
		slidesToShow: 3,
		slidesToScroll: 3,
		responsive: [
			{
				breakpoint: 800,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
				}
			},
		]
	});

	$('.review-slider-half .columns').slick({
		infinite: false,
		arrows: true,
		appendArrows: '.review-slider-half .slider-nav',
		dots: true,
		appendDots: '.review-slider-half .slider-nav',
		slidesToShow: 2.3,
		slidesToScroll: 1,
		responsive: [
			{
				breakpoint: 800,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
				}
			},
		]
	});

	$('.carousel').slick({
		infinite: true,
		arrows: false,
		dots: false,
		slidesToShow: 5,
		slidesToScroll: 1,
		autoplay: true,
		autoplaySpeed: 2000,
		responsive: [
			{
				breakpoint: 640,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 3,
				}
			},
		]
	});


	/* Storage Unit Slider */

	if($(window).width() <= 480) {
		$('.slider-units').on( 'dragStart.flickity', function( event, pointer ) {
			//console.log("drag started")
			document.ontouchmove = function (e) {
				e.preventDefault();
			}
		});
		$('.slider-units').on( 'dragEnd.flickity', function( event, pointer ) {
			//console.log("drag ended")
			document.ontouchmove = function (e) {
				return true;
			}
		});
		$('.slider-units').each(function(key, item) {
			var sliderIdName = 'slider' + key;
			var sliderNavIdName = 'sliderNav' + key;

			this.id = sliderIdName;
			$('.slider-units-dots')[key].id = sliderNavIdName;

			var sliderId = '#' + sliderIdName;
			var sliderNavId = '#' + sliderNavIdName;

			$(sliderId).on('ready.flickity', function() {
				$(this).addClass('is-loaded');
			});

			$(sliderId).flickity({
				// options
				cellAlign: 'left',
				contain: true,
				pageDots: false,
				freeScroll: true,
				dragThreshold: 3,
			});

			$(sliderNavId).flickity({
				asNavFor: sliderId,
				contain: true,
				pageDots: false,
				freeScroll: true
			});
		});

		/*
		$('.flickity-page-dots').css("bottom", "-45px");

		$('.flickity-page-dots').each( function() {
		  var quantity = $(this).children().length;
		  if(quantity > 10){
	      var dot_width = 10;
	      var parent = $(this).width();
	      var margin = (parent - quantity * dot_width)/(quantity*2);
		    $(this).children().each( function() {
		      $(this).css("margin", "0 "+margin+"px");
		    });
		  }
		});
		*/

		$('.notice').css("padding", "45px 18px 0px 18px");
	}

	$('.btn-hide').click(function(event){
		event.preventDefault();
		$(this).closest('.columns').toggleClass('show');
		$(this).closest('.slider-units').toggleClass('active');
	});

	/* Savings Calculator */

	input = 'input.price';
	submit = '.btn-calculate';

	$('.btn-calculate').click(function(event){
		event.preventDefault();

		$(this).addClass('active');

		price = $('.calc-wrap .price').val().replace('$', '');

		total = (((price / 4) * 52) / 12);
		monthly = total - price;
		yearly = monthly.toFixed(2) * 12;

		total_final = '$' + total.toFixed(2);
		monthly_final = '$' + monthly.toFixed(2);
		yearly_final = '$' + yearly.toFixed(2);

		if(price) {
			$('.calc-result, .calc-wrap .total').addClass('active');

			$('.calc-result .monthly').text(monthly_final);
			$('.calc-result .yearly').text(yearly_final);
			$('.calc-wrap .total strong').text(total_final);
		}
	});

	$(document).on('keyup', input, function() {
		var empty = false;

		/*$(input).each(function() {
			if (($(this).val() == '')) {
				empty = false;
			}
		});*/

		if (($('.calc-wrap .price').val() == '')) {
			empty = true;
		}

		//console.log(empty);

		$(submit).prop('disabled', empty);
	});


	/* Show Tooltip */

	$('body').click(function(){
		$('.btn-tooltip').removeClass('show');
	});

	$('.btn-tooltip').click(function(event){
		event.stopPropagation();

		$('.btn-tooltip').not(this).removeClass('show');
	    $(this).toggleClass('show');
	});

	/* Maximum Height Section */

	if ($('.has-shadow').height() > 450) {
		$('.has-shadow').addClass('show');
	}

	$('.has-shadow .btn-more').click(function(ev){
		ev.preventDefault();

	    $(this).closest('.has-shadow').removeClass('show');
		$(this).remove();
	});

	/* Tabs */

	$('.tabs a.inactive').click(function(event){
		event.preventDefault();
		event.stopImmediatePropagation();
	});

	$('.tabs.active').tabslet({
		deeplinking: false
	});

	$('.tabs.has-slider').on('_after', function() {
		$('.wp-slick-slider').slick('reinit');
	});

// 	if ($('.tabs ul.horizontal.has-event').length > 0) {

// 	}

	if ($('.tabs ul.horizontal.has-deeplinking').length > 0 && window.location.hash) {
		var hash = '.tabs ul.horizontal li' + window.location.hash;

		setTimeout(function(){
			$(hash).click();
		}, 500);
	}

	$('.tabs ul.horizontal.slider').slick({
		infinite: false,
		arrows: false,
		slidesToShow: 6,
		slidesToScroll: 1,
		responsive: [
			{
				breakpoint: 800,
				settings: {
					infinite: true,
					slidesToShow: 2,
					slidesToScroll: 2,
					centerPadding: '70px',
					centerMode: true,
					focusOnSelect: true
				}
			},
			{
				breakpoint: 560,
				settings: {
					infinite: true,
					slidesToShow: 1,
					slidesToScroll: 1,
					centerPadding: '70px',
					centerMode: true,
					focusOnSelect: true
				}
			},
		]
	});

	/* formating phone number properly (XXX) XXX-XXX */
	$('.wpcf7-form-control-wrap.phone > .wpcf7-tel').change(function() {
		$(".wpcf7-form-control-wrap.phone > .wpcf7-tel").val(function(i, text) {
			text = text.replace(/(\d\d\d)(\d\d\d)(\d\d\d\d)/, "($1) $2-$3");
			return text;
		});
	});


	/* Mobile */

	if($(window).width() <= 800) {
		$('.footer .col ul.menu > li > a').click(function(event){
			event.preventDefault();

			$(this).toggleClass('active');
			$(this).next().slideToggle();
		});
	}


	$(function () {
					$(window).on("resize", function (e) {
						var w = e.target.innerWidth;
						var input = $("input[class=controls]");
						return input.css({})
							.attr("placeholder"
								  , (w > 1 && w < 500)
								  ? "enter address"
								  : "enter address or postal code")
					}).resize()
				});
	$(function () {
					$(window).on("resize", function (e) {
						var w = e.target.innerWidth;
					if(w < 500){
					  moveMobile();
						$(".no-special").removeClass("no-special");
					}
					}).resize()
				});

	function moveMobile(){
		console.log("mobile needs to move")
		var element = $('.columns.locations .col');
		var storage = $('.storage-wrap');
		$(element[0]).after(storage);
		storage.css({margin: "40px 0px 20px 0px"})
		$('.storage-wrap .lazyloaded').css({width: "30%"})
	}

	/* sets up referrer and utm cookies */
	$(document).ready(function () {
		var win_url = window.location.href;
		var referrer = document.referrer;
		function getCookie(cname){
			var name = cname + "=";
			var decodedCookie = decodeURIComponent(document.cookie);
			var ca = decodedCookie.split(';');
			for(var i = 0; i < ca.length; i++) {
				var c = ca[i];
				while (c.charAt(0) == ' ') {
					c = c.substring(1);
				}
				if (c.indexOf(name) == 0) {
					return c.substring(name.length, c.length);
				}
			  }
			  return false;
		}
		function setUtmCookies( type , value ){
			var d = new Date();
		  	d.setTime(d.getTime() + (24*60*60*1000));
		  	var expires = "expires="+ d.toUTCString();
			document.cookie = type + "=" + value + ";" + expires + ";" + "path=/";
			//console.log("utm code set up for", type, "value ", value, "will expire on", d.toUTCString());
		}
		if(document.referrer && document.referrer.indexOf(window.location.hostname) == -1 ){
			setUtmCookies("referrer_url", referrer);
		}
		else if (document.referrer.indexOf("www-xyzstorage-com.cdn.ampproject.org") != -1){
			setUtmCookies("referrer_url", "https://www.google.com/ - AMP page");
		}
		if(!document.referrer){
			var existingCookie = getCookie("referrer_url");
			if(!!existingCookie){ // if the cookie is not false (meaning there was a referrer before)
				setUtmCookies("referrer_url", existingCookie); // renew the expiry date for that cookie
			}
			else{
				setUtmCookies("referrer_url", "[NOT SET]");
			}
		}
	  if(win_url.indexOf("utm_source") > -1 && win_url.indexOf("utm_medium") > -1 && win_url.indexOf("utm_campaign") > -1 ) {
		  var codes = win_url.split("?")[1];
		  var utm_code_array = codes.split("&");
		  var i = 0;
		  var utm_info;
		  for (i = 0; i < utm_code_array.length ; i++){
			  utm_info = utm_code_array[i].split("=");
				setUtmCookies(utm_info[0], utm_info[1]);
		  }
	  }
	if(win_url.indexOf("gclid") > -1 && false){
			var gclid = win_url.split("gclid=")[1];
			setUtmCookies("gclid", gclid);
	}
	});

	/* Analytics */

	$('body').on('click', '.popupInit', function (event) {
		var popuptype = $(this).attr('data-type');
		var parameters = $(this).attr('data-params');
		clickHandler(popuptype, parameters, $(this));
	});

	function clickHandler(type, params, obj) {
		if(typeof(params) == 'undefined') { params = null; }
		if(typeof(obj) == 'undefined') { obj = null; }

		switch(type) {
			case 'callNow':
	            if(params != null) {
	                params = params.split(',');
	                obj.find('.descriptionText').addClass('hidden');
	                var objDataPhNumber = obj.attr('data-phNumber');
	                if (objDataPhNumber != 'undefined' && objDataPhNumber != '') {
                        obj.find('div.number').html('<a href="tel:' + objDataPhNumber + '">' + objDataPhNumber + '</a>');
	                }
	                obj.find('div.number').removeClass('hidden-indent');
	                obj.removeClass('popupInit');
	                _gaq.push(['_trackEvent', params[0], params[1], params[2]]);
	            }

            break;
		}
	}

	/* google optimize*/

// 	var optimize_text = $("#promotion-term").text();
// 	$(".online-special-flag").text(optimize_text);
	/* 50% off promo cookies */

	function setPromoCookies(location,dimensions, rent, length, width, height, price, id){
		var url = "https://www.xyzstorage.com/rent-now/";
		  document.cookie = "unitLocation="+location+";path=/rent-now";
		  document.cookie = 'unitType='+id+'; path=/rent-now';
		  document.cookie = 'unitDimensions='+dimensions+'; path=/rent-now';
		  document.cookie = 'length='+length+'; path=/rent-now';
		  document.cookie = 'width='+width+'; path=/rent-now';
		  document.cookie = 'height='+height+'; path=/rent-now';
		  document.cookie = 'unitRent='+rent+'; path=/rent-now';
		  document.cookie = 'unitPrice='+price+'; path=/rent-now';
			var ssm_live = true;
		 if(ssm_live){
			 window.location = url;
		 }
		else{
			url ="https://e-storageonline.com/xyzstorage/Contents/RentOnline_TD.aspx"+
				"?FromPage=LocationDetails"+
				"&ReqPage=ALL"+
				"&LocId="+location+
				"&UnitId="+id+
				"&Len="+length+"&Bre="+width+"&UnitRent="+rent.toFixed(2);
			window.open(url, '_blank');
		}

	}

	$(".btn.reserve-promo").click(function(){
		var unit = $(this).attr("data-ref");
		console.log(unit)
		switch(unit){
			case "11":
				setPromoCookies(7,`5'w x 5'd X 8'h`,145.00,5,5,8,145.00,388)
				break;
			case "12":
				setPromoCookies(7,`5'w x 10'd X 8'h`,240.00,5,10,8,240.00,378)
				break;
			case "21":
				setPromoCookies(6,`5'w x 5'd X 8'h`,165.00,5,5,8,165.00,388)
				break;
			case "22":
				setPromoCookies(6,`5'w x 10'd X 8'h`,255.00,5,10,8,255.00,378)
				break;
			case "31":
				setPromoCookies(3,`5'w x 5'd X 8'h`,240.00,5,5,8,165.00,388)
				break;
			case "32":
				setPromoCookies(3,`5'w x 10'd X 8'h`,240.00,5,10,8,255.00,378)
				break;
			case "33":
				setPromoCookies(3,`10'w x 10'd X 8'h`,315.00,10,10,8,315.00,62)
				break;
			case "41":
				setPromoCookies(1,`5'w x 5'd X 8'h`,109.00,10,10,8,109.00,27)
				break;
			case "42":
				setPromoCookies(1,`5'w x 10'd X 8'h`,175.00,10,10,8,175.00,22)
				break;
			case "43":
				setPromoCookies(1,`10'w x 10'd X 8'h`,240.00,10,10,8,240.00,1)
				break;
			case "51":
				setPromoCookies(4,`5'w x 14'd X 8'h`,240.00,5,14,8,240.00,98)
				break;
		}
	})

		$(function () {
		$(window).on("resize", function (e) {
			var w = e.target.innerWidth;
			if(w < 500){
				$(".location-title-promo").attr('colspan', "2");
				$(".location-address-promo").attr('colspan', "2");
			}
		}).resize()
	});

	/* end of 50% off promo cookies */

	/* Mailchimp custom message */

	$('#mc_embed_signup .submit-wrap .btn').on('click', function(){
		// 		var fresh = true;
		const pageUrl = window.location.href;
		var cookieArr = document.cookie.split('; ');
		var invalid = false;
		var firstNameField = document.getElementById("first-name");
		var lastNameField = document.getElementById("last-name");
		var emailField = document.getElementById("mce-EMAIL");
		
		var errorField = document.getElementById("popuperrors");
					firstNameField.style.borderColor = "white";
					lastNameField.style.borderColor = "white";
					emailField.style.borderColor = "white";

		errorField.innerHTML = '';
// 		if(!firstNameField.value || !lastNameField.value || !emailField.value) {
// 			firstNameField.borderColor = "#e0581d";
// 			lastNameField.borderColor = "#e0581d";
// 			emailField.borderColor = "#e0581d";
// 			console.log('fill all the fields');
// 			return;
// 		}
		if(!firstNameField.value) {
			firstNameField.style.borderColor = "#e0581d";
			invalid = true;
			errorField.innerHTML = "First name missing";
		}
		if(!lastNameField.value) {
			lastNameField.style.borderColor = "#e0581d";
			invalid = true;
			errorField.innerHTML = document.getElementById("popuperrors").innerHTML + "<div>Last name is missing</div>";
		}
		if(!emailField.value) {
			emailField.style.borderColor = "#e0581d";
			invalid = true;
			errorField.innerHTML = document.getElementById("popuperrors").innerHTML + "<div>Email is missing</div>";

		}
		console.log('what is this ', invalid);
		if(invalid) {
			return;
		}
		
		const hubSpotCookie = typeof(cookieArr.find(row => row.startsWith('hubspotutk='))) !== 'undefined' ? cookieArr.find(row => row.startsWith('hubspotutk=')).split('=')[1]: "";
		
		var utm_campaign = typeof(cookieArr.find(row => row.startsWith('utm_campaign='))) !== 'undefined' ? cookieArr.find(row => row.startsWith('utm_campaign=')).split('=')[1] : "";
	
		var utm_medium = typeof(cookieArr.find(row => row.startsWith('utm_medium='))) !== 'undefined' ? cookieArr.find(row => row.startsWith('utm_medium=')).split('=')[1] : "";

		var utm_source = typeof(cookieArr.find(row => row.startsWith('utm_source='))) !== 'undefined' ? cookieArr.find(row => row.startsWith('utm_source=')).split('=')[1] : "";

		var utm_term = typeof(cookieArr.find(row => row.startsWith('utm_term='))) !== 'undefined' ? cookieArr.find(row => row.startsWith('utm_term=')).split('=')[1] : "";
		
		var referrer_url = typeof(cookieArr.find(row => row.startsWith('referrer_url='))) !== 'undefined' ? cookieArr.find(row => row.startsWith('referrer_url=')).split('=')[1] : "";

		console.log('utm_campaign',utm_campaign);
		console.log('utm_medium',utm_medium);
		console.log('utm_source',utm_source);
		console.log('referrer_url',referrer_url);
		
		var welcome_coupon_type = '';
		if (document.getElementById("mce-group[3549]-3549-0").checked === true) {
			welcome_coupon_type = '1 month free';
		} else if (document.getElementById("mce-group[3549]-3549-1").checked === true) {
			welcome_coupon_type = 'Free truck rental';
		} else if (document.getElementById("mce-group[3549]-3549-2").checked === true) {
			welcome_coupon_type = 'Free moving kit';
		}

		var hubSpotData = {
			"submittedAt": Date.now(),
			"fields": [
				{
					"objectTypeId": "0-1",
					"name": "email",
					"value": document.getElementById("mce-EMAIL").value
				},
				{
					"objectTypeId": "0-1",
					"name": "firstname",
					"value": document.getElementById("first-name").value
				},
				{
					"objectTypeId": "0-1",
					"name": "lastname",
					"value": document.getElementById("last-name").value
				},
				{
					"objectTypeId": "0-1",
					"name": "welcome_coupon_type",
					"value": welcome_coupon_type
				},
				{
					"objectTypeId": "0-1",
					"name": "utm_campaign",
					"value": utm_campaign
				},
				{
					"objectTypeId": "0-1",
					"name": "utm_medium",
					"value": utm_medium
				},
				{
					"objectTypeId": "0-1",
					"name": "utm_source",
					"value": utm_source
				},
				{
					"objectTypeId": "0-1",
					"name": "utm_term",
					"value": utm_term
				},
				{
					"objectTypeId": "0-1",
					"name": "Referrer",
					"value": referrer_url
				},
				{
					"objectTypeId": "0-1",
					"name": "terms_and_condition",
					"value": "Yes"
				}
				
			],
			"context": {
				"hutk": hubSpotCookie,
				"pageUri": pageUrl,
				"pageName": "XYZ Storage - home page"
			}
		};

		$.ajax({
			url: "https://api.hsforms.com/submissions/v3/integration/submit/21102818/340a34a7-f5eb-4bd0-883b-71d520e2febd",
			type: "post",
			contentType: "application/json",
			data: JSON.stringify({
				fields: hubSpotData.fields,
				context: hubSpotData.context
			}),
			success: function (response) {
				console.log('success', response);
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.log('check for error', jqXHR.responseJSON);
				var gotError = JSON.stringify(jqXHR.responseJSON.errors);
				var email_url = `https://xyzstorage.com/wp-json/myplugin/v1/hubspotemail2?errorMessage=${gotError}`;
				// send error email
					 $.ajax({
 				url: email_url,
 				success: function(result){
 					document.cookie="snp_snppopup-exit=1";
 				
 				}
			});
				console.log(textStatus, errorThrown);
			}
		});

		
		setTimeout(function(){
			
			if(!invalid) {
					console.log('popup cookie setting');
				document.cookie="snp_snppopup-exit=1;path=/";
			$('.snp-content').addClass('success');
			$.fancybox2.update();
			}
		}, 500);
	});


	/* detecting exit popup */

	var checked = false;
	var inter = setInterval(checkpopup, 100);
	function checkpopup(){
		if($('#snppopup-exit').is(":visible") && !checked){
			console.log("popup");
			clearInterval(inter);
			popupShowed();
		}
	}
	function popupShowed(){
		dataLayer.push({'event': 'Popup Showed'});
	}

// 	$("#mce-error-response").css("font-weight", "bold");


	/* change class for reschedule move out submit button */
	if(window.location.href.includes("move-out/?reschedule")){
	   console.log("it needs to reschedule")
		$(".move-out-btn").addClass("reschedule-btn");
		$(".move-out-btn").removeClass("move-out-btn");
	}

});

if(window.location.href == "https://www.xyzstorage.com/rent-now/" ||
   window.location.href == "https://www.xyzstorage.com/customer-details/" ||
   window.location.href == "https://www.xyzstorage.com/move-out/" ||
   window.location.href.includes("https://www.xyzstorage.com/booking-confirmed/") ||
   window.location.href.includes("https://www.xyzstorage.com/customer-portal/") ||
	window.location.href.includes("https://www.xyzstorage.com/facebook-promotion/") ||
   window.location.href.includes("https://www.xyzstorage.com/we-make-storage-promotion/") ||
   window.location.href.includes("https://www.xyzstorage.com/instagram-promotion/") ||
	window.location.href.includes("https://www.xyzstorage.com/all-canadian-self-storage-promotion/")
  )
{
	console.log("hiding popup forever");
	document.cookie="snp_snppopup-exit=1;path=/";
}
