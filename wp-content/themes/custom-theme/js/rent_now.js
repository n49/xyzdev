console.log("rent_now_loaded")

function uet_report_conversion() { window.uetq = window.uetq || []; window.uetq.push('event', 'rental_complete', {}); }

jQuery(document).ready(function($){``
	var locationName = "";
	var unitRent = "";
	var reservationValue = "";

	var utm_message = `referrer_url:${getCookieM("referrer_url")}, `;
	utm_message += `utm_source:${getCookieM("utm_source")}, `;
	utm_message += `utm_medium:${getCookieM("utm_medium")}, `;
	utm_message += `utm_campaign:${getCookieM("utm_campaign")},`;
	utm_message += `utm_content:${getCookieM("utm_content")}`;

								   
								   function getCookieM(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
}

	function getCookieMs(cname){
		var name = cname + "=";
		console.log(document.cookie)
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

	function bookingConfirmation(settings, redirect, retry, data, unitSize){
		if(retry === 0) return false;
		$.ajax(settings).done( response => {
				console.log("email", response)
				if(response == "failed"){
					console.log("retrying")
					bookingConfirmation(settings, redirect, retry - 1, data, unitSize)
				}
				else if(response == "successful") {
					console.log("email is successful")
					cmoEmail(data, redirect, unitSize)
				}
			}
		);
	}

	function cmoEmail(data, redirect, unitSize){
		var mydata = data.split(": ")[1];
		var dArray = mydata.split(",");
		var locationId = dArray[0];
		unitRent = dArray[1];
		unitRent = unitRent.replace("$","");
		var unitName = dArray[2];
		console.log("array: ",dArray);
		switch(locationId){
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
		reservationValue = 9 * parseFloat(unitRent);

		/* google Analytics */
		if(unitName){
			dataLayer.push({'event': 'Rental Complete'});
			uet_report_conversion()

		}
		var referrerInfo = " ";
		var referrer = getCookieM("referrer_url");
		var utm_campaign = getCookieM("utm_campaign");
		var utm_medium = getCookieM("utm_medium");
		var utm_source = getCookieM("utm_source");
			var utm_content = getCookieM("utm_content");
		var gclid = getCookieM("gclid");
		var unitDimensions =  unitSize;
		var dimensions = " ";
		if(!!referrer){
		 referrerInfo += referrer+" ";
		}
		if(!!utm_campaign){
		 referrerInfo += "utm_campaign="+utm_campaign+" ";
		}
		if(!!utm_medium){
		 referrerInfo += "utm_medium="+utm_medium+" ";
		}
		if(!!utm_source){
		 referrerInfo += "utm_source="+utm_source+" ";
		}
			if(!!utm_content){
		 referrerInfo += "utm_content="+utm_content+" ";
		}
		if(!!gclid){
		 referrerInfo += "gclid="+gclid+" ";
		}
		if(!!unitDimensions){
			dimensions = encodeURIComponent(unitDimensions+" unit:"+unitName).replace(/'/g, "%27");
		}
		var email_url = "https://www.xyzstorage.com/wp-json/myplugin/v1/rental_email2/"
				+locationName+"/"
				+unitRent+"/"+dimensions+"/"+encodeURIComponent(encodeURIComponent(referrerInfo));
		console.log("url: ", email_url);
		/* send email notification to cmo */
		if(unitName != ""){
			 $.ajax({
				url: email_url,
				success: function(result){
					document.cookie="snp_snppopup-exit=1";
					console.log("success cmo")
					/* redirect to booking confirm */
					window.location.href = redirect;
				}
			})
		}
	}

	$(window).on("message", function(e) {
			var data = e.originalEvent.data;
			console.log("got a message: ",data);
			if(data == "ssmSubmissionClicked"){
				document.body.scrollTop = 0; // For Safari
				document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
				$("#loadingMessage").show();
				$("#ssm-iframe-element").prop("onload", null);
		$('#holds-the-iframe').height("110px");
		$("#ssm-iframe-element").height("0px");
			}
			if(data.includes && data.includes("Thank you page loaded")){
				var mydata = data.split(": ")[1];
				var dArray = mydata.split(",");
				var locationId = dArray[0];
				unitRent = dArray[1];
				var encodedRent = encodeURIComponent(unitRent);
				var customerName = encodeURIComponent(dArray[3]);
				var date = dArray[4];
				date = date.replace(/\//g, "-");
				var phoneNumber = dArray[5];
				var customerEmail = encodeURIComponent(dArray[6]);
				var unitSize = encodeURIComponent(dArray[7]);

				var lease_url = dArray[8];
				var lease = '';
				if(lease_url.includes('https://')){
					lease = encodeURIComponent(lease_url);
				}
				else if(lease_url.includes('http://')){
					lease = encodeURIComponent(lease_url.replace('http://', 'https://'));
				}
				else {
					lease = encodeURIComponent('https://'+lease_url);
				}
				var confirmationUrl = `https://www.xyzstorage.com/booking-confirmed/?user_name=${customerName}&user_location=${locationId}&rent=${encodedRent}&phone=${phoneNumber}&email=${customerEmail}&movedate=${date}&size=${unitSize}&lease=${lease}`;
				var settings = {
					"url": "https://xyzstorage.com/wp-json/email/test",
					"method": "POST",
					"data": {
						location_id: locationId,
						size: unitSize,
						date: date,
						phone: phoneNumber,
						email: decodeURIComponent(customerEmail),
						rent: unitRent,
						full_name: decodeURIComponent(customerName),
						lease: decodeURIComponent(lease)
					}
				};
				/* sending booking confirmation email */
				bookingConfirmation(settings, confirmationUrl, 5, data, unitSize);
			}

			if(data.includes && data.includes("reservation complete:")){
				var mydata = data.split(": ")[1];
				var locationId = mydata.split(", ")[1];
				unitRent = mydata.split(" ,")[0];
				console.log("Location:", locationId, "rent:" , unitRent);
				switch(locationId){
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
				reservationValue = 9*parseFloat(unitRent);

				dataLayer.push({'event': 'Rental Successful'});
			}
	})

	var lease = $('#lease-button').attr('href');
	$('#lease-link').attr("href", lease);
	$('#lease-link').click(function(){
		var rentValue = $('#rent-value').text();
		reservationValue = 9*parseFloat(rentValue.replace("$", ""));
		dataLayer.push({'event': 'Rental Successful'});
	});
	$('#lease-button').click(function(){
		var rentValue = $('#rent-value').text();
		reservationValue = 9*parseFloat(rentValue.replace("$", ""));
		dataLayer.push({'event': 'Rental Successful'});
	});

	var SSM_source = $("#ssm-iframe-url").attr('src-data');

	$('#ssm-iframe-element')
	.attr('src', SSM_source)
	.attr('width', '100%')
	.attr('allowfullscreen', true)
	.on('load', function() {
		document.getElementById('ssm-iframe-element').contentWindow.postMessage(utm_message, SSM_source)
		console.log("message sent to ssm: ", utm_message)
		$("#loadingMessage").hide();
		var iframeheight = 2350;
	  if($(window).width()<600){
			window.scrollTo(0, 0);
		}
	  $('#holds-the-iframe').height(iframeheight);
	  $('#ssm-iframe-element').height(iframeheight)
	}).appendTo('#holds-the-iframe');

});