var canClick = true;
var loading = false;

console.log("you're at the prospects pages");

jQuery(document).ready(function($){

	const mode = getWindowMode();
	

	var mods = $('.span-mod').toArray();
	var mods_ing = $('.span-mod-ing').toArray();
	console.log(mods)
	
	mods.forEach(mod =>{
		$(mod).text(mode)
	})
	mods_ing.forEach((mod =>{
		if(mode === "reservation") 
			$(mod).text("reserving")
		else 
			$(mod).text("renting")
	}))
	
  var location = getLocation(getCookie("unitLocation"));

  var unitType = decodeCookie("unit");
  var unitQuantity = decodeCookie("quantity");


  document.cookie = "unitType="+unitType;
  document.cookie = "unitQuantity="+unitQuantity;
  
	var multiplier = 9;
	
	var unitRent = parseInt(getCookie("unitPrice"));
	
	var reservationValue = unitRent*unitQuantity*multiplier ;
	
	var special = 0;
  
  var utm = getUTM();
  $('.fake-btn').click(function(){
    if(canClick){
      if(!validateFields()){
        $('.real-btn').click();
        allowClick(true);
      }
      else{
        $('.real-btn').click();
        allowClick(true);
      }
      
      if(false){
        allowClick(false);
    		var phoneNum = $('.wpcf7 .phone input').val();
    		var phoneFix = phoneNum.replace('(', '');
    		phoneFix = phoneFix.replace(')', '');
    		phoneFix = phoneFix.replace('-', '');
    		phoneFix = phoneFix.replace(' ', '');
    		
        document.cookie = "date_xyz1="+$('.wpcf7 .date input').val();
        
    		var reserve_url = 'https://'+window.location.hostname+
                          '/wp-json/myplugin/v1/reserve/'+getCookie("unitLocation")+
                          '/'+$('.wpcf7 .date input').val()+
                          '/'+unitType+
          								'/'+getCookie("unitRent")+
      							  		'/'+$('.wpcf7 .first-name input').val()+
                          '/'+$('.wpcf7 .last-name input').val()+
                          '/'+encodeURIComponent($('.wpcf7 .your-email input').val())+
                          '/'+phoneFix+
                          '/'+unitQuantity+'/%20'+encodeURIComponent(utm + "customer notes: "+$('.wpcf7 .your-message textarea').val());
    		var settings = {
      			"async": true,
      			"crossDomain": true,
      			"url": reserve_url,
      			"method": "GET",
      			"data": ""
    		}
    		var settings2 = {
  				"async": true,
  				"crossDomain": true,
  				"url": "https://"+window.location.hostname+"/wp-json/myplugin/v1/error_notification/",
  				"method": "POST",
  				"dataType": "json",
  				"data": {
  					error_name: "SSM failed submission", 
  					SSM_response: "no data", 
  					location: getCookie("unitLocation"), 
  					value: getCookie("unitRent"),
  					unit: unitType, 
  					client_email: $('.wpcf7 .your-email input').val(),
  					url: reserve_url
  				}
			  }
    		//console.log("here's the call", reserve_url)
    		if(unitType && unitType != ''){
    			$.ajax(settings).done(
    				function (response) {
						if(response.SaveSuccessful == "TRUE") {
							document.cookie = "snp_snppopup-exit=1";
							dataLayer.push({'event': 'Reservation Successful'});
							$('.real-btn').click();
							document.getElementById('triggerTab3').click();
						}
						else {
							if(response){ 
								var response_string = JSON.stringify(response);
								settings2.data.SSM_response = response.ErrorMessage || response;
								settings2.data.fullMessage = response_string;
							}
							else settings2.data.SSM_response = 'no data';
							$.ajax(settings2).done(function(resp){
								console.log("email sent", settings2);
								alert('The reservation could not be completed - some error occurred. Please try again or contact us for inquiry');
								window.location.replace(document.referrer);
							});
						}
    				})
    		};
        
      }
     //$('.real-btn').click();
    }
  })
  
  
  function validateFields(){
    if( $('.wpcf7 .date input').val() != '' &&
        $('.wpcf7 .first-name input').val() != '' &&
        $('.wpcf7 .last-name input').val() != '' &&
        $('.wpcf7 .phone input').val() != '' &&
        $('.wpcf7 .your-email input').val() != '' 
      ){
        var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,}$/i;
        if (testEmail.test($('.wpcf7 .your-email input').val())) return true;
        else {
          return false; 
        }
      }
    else return false;
  }
  
  function allowClick(boo){
    if(!boo){
      canClick = false;
      $('.fake-btn').css("cursor", "progress");
    }
    else {
      $('.fake-btn').css("cursor", "pointer");
      canClick = true;
    }
  }

  
});


function decodeCookie(cookie){
  var returnString = getCookie('unit');
  returnString = returnString.split('"').join('');
  returnString = returnString.substring(1,returnString.length-1);
  var temp = returnString.split(',');
  
  if(cookie == "unit"){
    return temp[0].split('unit:')[1];
  }
  else if(cookie == "quantity"){
    return temp[1].split('amount:')[1];
  }
}

function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

function getLocation(code){
  var location = "";
  switch(code){
    case '1':
      location = "Scarborough";
      break;
    case '2':
      location = "Mississauga";
      break;
    case '3':
      location = "Toronto West";
      break;
    case '4':
      location = "Etobicoke";
      break;
    case '5':
      location = "Mobile Storage";
      break;
    case '6':
      location = "Toronto Midtown";
      break;
    case '7':
      location - "Toronto Downtown";
      break;
  }
  return location;
}


function getUTM(){
  var utm = "";
	var referrer_url = getCookie("referrer_url");
	var referrer = '';
	if(referrer_url != ""){
		referrer = "referrer=" + referrer_url+",";
	}
	var utm_source = getCookie("utm_source");
	var utm_medium = getCookie("utm_medium");
	var utm_campaign = getCookie("utm_campaign");
	if(utm_source != "" && utm_medium != "" && utm_campaign != ""){
		utm = "utm_source=" + utm_source + ",utm_medium=" + utm_medium + ",utm_campaign=" + utm_campaign + ". ";
	}
	return referrer + utm;
}

function getWindowMode(){
	var params = window.location.search;
	var rental = params.includes('?rental');
	var reservation = params.includes('?reservation');
	if(reservation){
	   return 'reservation'
	}
	else{
		return 'rental'		
	}
}

