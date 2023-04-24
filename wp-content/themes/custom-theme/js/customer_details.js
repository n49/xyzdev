var canClick = true;
var loading = false;

console.log("customer details, v1.1")

function uet_report_conversion() { window.uetq = window.uetq || []; window.uetq.push('event', 'reservation_form_submitted', {}); }

jQuery(document).ready(function($){

  var location = getLocation(getCookie("unitLocation"));

  var unitType = decodeCookie("unit");
  var unitQuantity = decodeCookie("quantity");


  document.cookie = "unitType="+unitType;
  document.cookie = "unitQuantity="+unitQuantity;
  
	var multiplier = 9;
	
	var unitRent = parseInt(getCookie("unitPrice"));
	
	var reservationValue = unitRent*unitQuantity*multiplier ;

  var utm = getUTM();
    $('.fake-btn').click(function(){
	  allowClick(false);
      console.log("fake button clicked")
      if(1==1){
        if(!validateFields()){
          console.log("failed validation")
          $('.real-btn').click();
          allowClick(true);
        }
      else{
        console.log("validated")
        allowClick(false);
        var phoneNum = $('#phone').val();
        var phoneFix = phoneNum.replace('(', '');
        phoneFix = phoneFix.replace(')', '');
        phoneFix = phoneFix.replace('-', '');
        phoneFix = phoneFix.replace(' ', '');
        
        document.cookie = "date_xyz1="+$('#date').val();
        
		  const businessName = encodeURIComponent($('#business-name').val()) || 'none';
        var reserve_url = 'https://'+window.location.hostname+
                                  '/wp-json/myplugin/v1/reserve/'+getCookie("unitLocation")+
                                  '/'+$('#date').val()+
                                  '/'+unitType+
                    '/'+getCookie("unitRent")+
                  '/'+$('#first-name').val()+
                                  '/'+$('#last-name').val()+
                                  '/'+encodeURIComponent($('#your-email').val())+
                                  '/'+phoneFix+
                                  '/'+unitQuantity+'/'+businessName+'/'+encodeURIComponent(encodeURIComponent(utm) + "customer notes: "+$('#your-message').val());
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
          client_email: $('#email').val(),
          url: reserve_url
        }
      }
      var settings3 = {
        "async": true,
        "crossDomain": true,
        "url": "https://"+window.location.hostname+"/wp-json/email/reservation_successful",
        "method": "POST",
        "data": {
          "location": getCookie("unitLocation"), 
          "value": getCookie("unitRent"),
          "unit": unitType, 
          "client_email": $('#email').val(),
          "url": reserve_url,
          "utm": encodeURIComponent(utm + "customer notes: "+$('#your-message').val()),
          "date": $('#date').val(),
          "name": $('#first-name').val(),
          "lastname": $('#last-name').val()
        }
      }
      console.log("API call url", reserve_url)
      if(unitType && unitType != ''){
        $.ajax(settings).done(
          function (response) {
          console.log("SSM API call completed", response)
          if(response.SaveSuccessful == "TRUE") {
            $.ajax(settings3).done( function(response) {
              document.cookie = "snp_snppopup-exit=1";
              dataLayer.push({'event': 'Reservation Successful'});
				gtag('event', 'Reservation Complete');
				uet_report_conversion()
              $('.real-btn').click();
              document.getElementById('triggerTab3').click();
            })
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
	  console.log($('#date').val(),
        $('#first-name').val(),
        $('#last-name').val(),
        $('#phone').val(),
        $('#email').val())
    if( $('#date').val() != '' &&
        $('#first-name').val() != '' &&
        $('#last-name').val() != '' &&
        $('#phone').val() != '' &&
        $('#your-email').val() != '' 
      ){
      var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,}$/i;
      if (testEmail.test($('#your-email').val())) return true;
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
  else if(cookie == "quantity" && temp[1]){
    return temp[1].split('amount:')[1];
  } else {
    console.log("this failed")
  }
}

function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  if(!decodedCookie) return ""
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
    case '6':
      location = "Toronto Midtown";
      break;
    case '7':
      location = "Toronto Downtown";
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
	var utm_content = getCookie("utm_content");
	if(utm_source != "" && utm_medium != "" && utm_campaign != ""){
		utm = "utm_source=" + utm_source + ",utm_medium=" + utm_medium + ",utm_campaign=" + utm_campaign + ",utm_content=" + utm_content + ".";
	}
	return referrer + utm;
}