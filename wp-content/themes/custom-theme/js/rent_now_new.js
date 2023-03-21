const setCookie = (k, v, path) => {
	document.cookie = `${k}=${v};path=/`;
//     window.location = path
}
function getCookie(name) {
    function escape(s) { return s.replace(/([.*+?\^$(){}|\[\]\/\\])/g, '\\$1'); }
    var match = document.cookie.match(RegExp('(?:^|;\\s*)' + escape(name) + '=([^;]*)'));
    return match ? match[1] : null;
}

$(document).on('reset', '.wpcf7-form', function(e) {
    e.preventDefault();

});

jQuery(document).ready(function($){
	
		function convertTodayDate(rent) {
			var today = new Date();
    		var daysInMonth = new Date(today.getFullYear(), today.getMonth()+1, 0).getDate();
		    var dp = rent / daysInMonth;
			return dp;
	}
	
		if(getCookie('priceMode')) {
			
					var priceMode = getCookie('priceMode');
			console.log('got the price mode', priceMode);
		if(priceMode == 'daily') {
			$('.dailyMode').addClass('active');
			$('.monthlyMode').removeClass('active');
		}


		}
	
	var monthlyRentAmount = $('#monthlyRentNowAmountSection').text();
		monthlyRentAmount = monthlyRentAmount.match(/\d+\.\d+/)[0];
	
	var dailyRentAmount = convertTodayDate(monthlyRentAmount).toFixed(2);
	
	
			$('.monthlySwitch').on("click", (ev) => {
				setCookie('priceMode', 'monthly', '/');
				$('#rentNowAmountSection').text(`$${monthlyRentAmount}/mth`);
// 				$('#rentalDescriptionLabel').text('prorated monthly rent');
// 				$('#monthlyProRatedAmountS').text(`$${monthlyRentAmount}`);
			});
	
			$('.dailySwitch').on("click", (ev) => {
				setCookie('priceMode', 'daily', '/');
				$('#rentNowAmountSection').text(`$${dailyRentAmount}/day`);
// 				$('#rentalDescriptionLabel').text('prorated daily rent');
// 				$('#monthlyProRatedAmountS').text(`$${dailyRentAmount}`);
			});
	
// 	function createToken() {
// 				customCheckout.createToken(function(result) {
// 		console.log('what ', result);
// 		if(result.error) {
// 			var nextButton = document.getElementsByClassName('wpcf7cf_next')[0];
// 			nextButton.disabled = true;
// 						console.log('disabling', nextButton.disabled);

// 			    nextButton.style.cursor = 'not-allowed';
// 		}	
//     // handle result.error or result.token
// });
// 	}
// 	if(1==1) {
// 		 var customCheckout = customcheckout();
//     var cardNumber = customCheckout.create('card-number');
// 	    var cardCVV = customCheckout.create('cvv');
// 	var cardExpiry = customCheckout.create('expiry');

//     cardNumber.mount('#card-number');
// cardCVV.mount('#card-cvv');
// 	cardExpiry.mount('#card-expiry');
	
// 	console.log('got it mate', customCheckout);
// 			var cardField = document.getElementById('card-number');
// 			var cvvField = document.getElementById('card-cvv');
// 				var expiryField = document.getElementById('card-expiry');

	
	
// 	cardField.addEventListener("change", function(token) {
		
// 	createToken();
// 		});
	
// 		cvvField.addEventListener("change", function(token) {
// 	createToken();
// 		});

// 		expiryField.addEventListener("change", function(token) {
// 	createToken();
// 		});
		
// 	}
	   
		  var selectedSpace = window.Cookies.get().selectedSpace;
			console.log('selected space is', selectedSpace);
	
	function getCookie(name) {
    var cookieValue = null;
    if (document.cookie && document.cookie != '') {
        var cookies = document.cookie.split(';');
        for (var i = 0; i < cookies.length; i++) {
            var cookie = jQuery.trim(cookies[i]);
            // Does this cookie string begin with the name we want?
            if (cookie.substring(0, name.length + 1) == (name + '=')) {
                cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                break;
            }
        }
    }
    return cookieValue;
}
	
	// get number of days in this month
function getDaysInMonth(month, year) {
    return new Date(year, month, 0).getDate();
}

// get number of days in this current month
function getCurrentDaysInMonth() {
    var d = new Date();
    return getDaysInMonth(d.getMonth(), d.getFullYear());
}
	
	function getDaysRemainingInMonth(moveInDate) {
    var d = new Date(moveInDate);
    return getDaysInMonth(d.getMonth(), d.getFullYear()) - d.getDate();
}
		var insuranceField = document.getElementById('insuranceField');

var insurances = JSON.parse(getCookie("InsuranceSchemes"));

	
	console.log('got it', insurances);


		var moveInDateSpan = document.querySelectorAll(`[data-name='date12']`)[0];
		var moveInField = document.getElementById('movedate');

		moveInDateSpan.addEventListener("change", function() {
							var insurancePrice = insuranceField.value || 0;
			
				insurancePrice =  insurancePrice.toString().replace(/\$/g, '');

		var moveInDate = moveInField.value;
		if(!moveInDate) {
			var date = new Date();
			moveInDate = (date.toISOString().substring(0,10)); 
		}
		var totalRent = getCookie('unitPrice');
		var dailyRent = totalRent / getCurrentDaysInMonth();
		var daysRemaining = getDaysRemainingInMonth(moveInDate);
		var newProRatedAmount = dailyRent * daysRemaining;
		console.log('check this out', newProRatedAmount, insurancePrice);
		var proRatedAmount = document.getElementById('monthlyProRatedAmount');
			proRatedAmount.innerText = parseFloat(newProRatedAmount).toFixed(2);
		var salesTaxText = document.getElementById('salesTaxText');
		var totalText = document.getElementById('totalCreditCardAmount');
		
		var newTotal = parseFloat(newProRatedAmount) + parseFloat(insurancePrice) + parseFloat(salesTaxText.innerText);
				console.log('new total', newTotal);
		 totalText.innerText = newTotal.toFixed(2);
		//document.getElementById('insuranceText').innerText = parseFloat(insurancePrice).toFixed(2);
  console.log('the new value', insuranceField.value);
			
});


	var insuranceSpan = document.querySelectorAll('[data-name="insurance"]')[0]
	var insuranceField = document.getElementById('insuranceField');
	['click','change', 'select'].forEach( evt => 
    insuranceSpan.addEventListener(evt, function() {
		var insurancePrice = insuranceField.value;
		insurancePrice =  insurancePrice.replace(/\$/g, '');
		console.log('this is the new insurance price', insurancePrice);
		if(!insurancePrice) {
			insurancePrice = 0.00;
		}
		var proRatedAmount = document.getElementById('monthlyProRatedAmount');
		var salesTaxText = document.getElementById('salesTaxText');
		var totalText = document.getElementById('totalCreditCardAmount');
		var newTax = (parseFloat(proRatedAmount.innerText) + parseFloat(insurancePrice))*0.13;
		var newTotal = parseFloat(proRatedAmount.innerText) + parseFloat(insurancePrice) + newTax;
				console.log('new total', newTotal);
		 totalText.innerText = newTotal.toFixed(2);
		document.getElementById('insuranceText').innerText = parseFloat(insurancePrice).toFixed(2);
		document.getElementById('salesTaxText').innerText = parseFloat(newTax).toFixed(2);

		
		
  console.log('the new value', insuranceField.value);
})
);

	

var wpcf7Elm = document.querySelector( '.wpcf7' );
	
	
 
wpcf7Elm.addEventListener( 'wpcf7mailsent', function( event ) {
	$(".wpcf7-form").hide();
	document.getElementById('loader').style.display = 'block';
	window.scrollTo(0,0);


	console.log('preventing', event);
	event.preventDefault();
			  function getCookieM(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
}
//  					document.cookie="referrer_url=1";
		  		var referrerInfo = " ";
		var hasUtm = false;
		var referrer = getCookieM("referrer_url");
		var utm_campaign = getCookieM("utm_campaign");
		var utm_medium = getCookieM("utm_medium");
		var utm_source = getCookieM("utm_source");
			var utm_content = getCookieM("utm_content");
		var gclid = getCookieM("gclid");
if(!!referrer && referrer!== "[NOT SET]"){
		 referrerInfo += referrer+",";
				hasUtm = true;

		}
		if(!!utm_campaign){
		 referrerInfo += "utm_campaign="+utm_campaign+",";
			hasUtm = true;
		}
		if(!!utm_medium){
		 referrerInfo += "utm_medium="+utm_medium+",";
			hasUtm = true;
		}
		if(!!utm_source){
		 referrerInfo += "utm_source="+utm_source+",";
			hasUtm = true;
		}
			if(!!utm_content){
		 referrerInfo += "utm_content="+utm_content+",";
				hasUtm = true;
		}
		if(!!gclid){
		 referrerInfo += "gclid="+gclid+",";
			hasUtm = true;
		}
// 	    if(hasUtm) {
// 			referrerInfo = referrerInfo.replace(/[NOT SET]/gi, '');
// 		}
	console.log('got it', referrerInfo);
// 		return;
	
	var inputs = event.detail.inputs;
	var sendReferrer = {
		name: "referrerInfo",
		value: referrerInfo
	}
	var locationCode = {
		name: "locationCode",
		value: window.Cookies.get().unitLocation
	}
	var unitType = {
		name: "unitType",
		value: window.Cookies.get().unitType
	}
	var unitAmount = {
		name: "unitAmount",
		value: window.Cookies.get().unitPrice
	}
	var unitRent = {
		name: "unitRent",
		value: window.Cookies.get().unitRent
	}
	var creditCardAmount = {
		name: "creditCardAmount",
		value: document.getElementById('totalCreditCardAmount').innerText
	}
	var insuranceAmount = {
		name: "insuranceAmount",
		value: document.getElementById('insuranceText').innerText
	}
	inputs.push(locationCode);
	inputs.push(unitType);
		inputs.push(unitAmount);
	inputs.push(sendReferrer);
	inputs.push(unitRent);
	inputs.push(creditCardAmount);
	inputs.push(insuranceAmount);

			var data = JSON.stringify(inputs);

	var xhr = new XMLHttpRequest();
	xhr.withCredentials = true;

	xhr.addEventListener("readystatechange", function() {
	  if(this.readyState === 4) {
		  function findObjectValue(obj, name) {
    for (var i = 0; i < obj.length; i++) {
        if (obj[i].name == name) {
            return obj[i].value;
        }
    }
}
		  

		  
		  var firstName = findObjectValue(inputs, 'first-name');
		  var lastName  = findObjectValue(inputs, 'last-name');
		  var location = findObjectValue(inputs, 'locationCode');
		  var unit_rent = '$' + findObjectValue(inputs, 'unitRent');
		  var phone = findObjectValue(inputs, 'phone');
		  var salesTax = '$' + document.getElementById('salesTaxText').innerText;
		  var totalAmount = '$' + document.getElementById('totalCreditCardAmount').innerText;
		  var dimensions = window.Cookies.get().unitDimensions.replace(/\//g, '');
		  var email = findObjectValue(inputs, 'email');
		  var moveDate = findObjectValue(inputs, 'date12');
		  var size = findObjectValue(inputs, 'unit-size');
		  var businessName = findObjectValue(inputs, 'business');
		  businessName = businessName ? businessName : 'N/A';
		  
		
		  var locationName = "Toronto";
		  	switch(location){
					case "1":
						locationName = "Scarborough";
						break;
					case "2":
						locationName = "Undefined";
						break;
					case "3":
						locationName = "Toronto West";
						break;
					case "4":
						locationName = "Etobicoke";
						break;
					case "5":
						locationName = "Mobile Storage";
						break;
					case "6":
						locationName = "Toronto Midtown";
						break;
					case "7":
						locationName = "Toronto Downtown";
						break;
				}
		  
		  //return;
		  //window.location.href = redirectUrl;a
					var response = JSON.parse(this.responseText);
					if(response.type == "error") {
						// construct the url and redirect to booking confirmed
						
							$(".wpcf7-form").show();
	document.getElementById('loader').style.display = 'none';
												     $form = $('.wpcf7-form');

             wpcf7cf.multistepMoveToStep($form, 2);
$(".wpcf7-response-output").text(response.message);
						$(".wpcf7-response-output").addClass('without-after-element');

						$(".wpcf7-response-output").css({ 'color': 'red', 'borderColor': 'red', 'paddingLeft': '20px' });
																		alert(`message: ${response.message}`);




					}
		  			if(response.type == "success") {
	// send the email here
	
							  var redirectUrl = "/booking-confirmed/?user_name=" + firstName + ' ' + lastName +"&user_location=" + location + "&rent=" + unit_rent +"&phone="+phone+"&email="+email+"&movedate="+moveDate+"&company_name="+businessName+"&size="+dimensions+"&tax="+salesTax+"&total="+totalAmount+"&lease="+response.lease;
							var settings1 = {
					"url": "https://www.xyzstorage.com/wp-json/email/test",
					"method": "POST",
					"data": {
						location_id: location,
						size: dimensions,
						date: moveDate,
						phone: phone,
						email: email,
						rent: unit_rent,
						tax: salesTax,
						total: unit_rent,
						full_name: firstName + ' ' + lastName,
						lease: response.lease
					}
				};
					var rentAmount = findObjectValue(inputs, 'unitRent');
 	

								$.ajax(settings1).done( response1 => {

										var email_url = `https://www.xyzstorage.com/wp-json/myplugin/v1/rental_email2?locationName=${locationName}&rentAmount=${rentAmount}&dimensions=${dimensions}&referrerInfo=${encodeURIComponent(encodeURIComponent(referrerInfo))}&confirmation=${window.location.hostname+encodeURIComponent(redirectUrl)}`;
				console.log('got it ', email_url);
																							//window.location.href = redirectUrl;

		/* send email notification to cmo */
 			 $.ajax({
 				url: email_url,
 				success: function(result){
 					document.cookie="snp_snppopup-exit=1";
 					console.log("success cmo")
 					/* redirect to booking confirm */
 					window.location.href = redirectUrl;
 				}
			});
		
			
			});


					}

	  }
	});
	xhr.open("POST", `https://www.xyzstorage.com/wp-json/myplugin/v1/rentNowSSM`);

	xhr.send(data);


  console.log('invalid', event);
}, false );
	
});