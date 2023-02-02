var isLoading = false;
var processing = false;
jQuery(document).ready(function($){
	console.log('loading js here');
	var directionsService = new google.maps.DirectionsService();
	var directionsDisplay = new google.maps.DirectionsRenderer();

  var defaultBounds = new google.maps.LatLngBounds(
    new google.maps.LatLng(43.2557, -79.8711),
    new google.maps.LatLng(43.8971, -78.8658)
    );
  var options = {
    bounds: defaultBounds,
  };

	geocoder = new google.maps.Geocoder();
	input = (document.getElementById('location-address'));
	
	
  autocomplete = new google.maps.places.Autocomplete(input, options);

	autocomplete.addListener('place_changed', function() {
		var place = autocomplete.getPlace();
		if (!place.geometry) {
			// User entered the name of a Place that was not suggested and
			// pressed the Enter key, or the Place Details request failed.
			//window.alert("No details available for input: '" + place.name + "'");
			return;
		}
		
		var place_lat = place.geometry.location.lat();
		var place_lng = place.geometry.location.lng();
		var place_pos = new google.maps.LatLng(place_lat, place_lng);
		updateDistance(place_pos);
		
	});
	
  var loading_elem = $("#btn-search");
	var container = document.querySelector('.mixitup');
	var mixer = mixitup(container, {
		behavior: {
	        liveSort: true
	    }
	});
  var messageError = false;
  var notFoundError = false;
	$('.btn-search').on('click', function(ev){
		ev.preventDefault();
		gtag('event', 'Location Search');
		search_place = $('#location-address').val();
		if (search_place == '' && messageError == false) {
			showErrorMessage('Address field can\'t be blank.');
			messageError = true;
		}

		if (search_place && !isLoading) {
			geocoder.geocode({'address': search_place, 'bounds': defaultBounds}, geoResults);
		} else if(!isLoading){
			geoResults(false, 'OK');
		}
	});

	$('#location-address').keypress(function(e){
		if(e.which == 13 && !isLoading){
			$('.btn-search').click();
		}
	});

	$('.btn-current').on('click', function(ev){
		ev.preventDefault();
		if(!isLoading) {
		  $('#location-address').val('');
		  initGeolocation()
		  };
	});

	function updateDistance(place_pos){
	  processing = true;
		var element = $('.columns.locations .col');
		var added = $('.columns.locations .col .added');
		var count = element.length;

		hideErrorMessage();

		element.removeClass('active');
		added.remove();

    var delayFactor = 0;
    var location_lat;
		var location_lng;
		var location_pos;
		recurseDirections(0,element, place_pos);

	}
	function moveMobile(){
	  if($(window).width() < 500){
      console.log("mobile needs to move")
		  var element = $('.columns.locations .col');
		  var storage = $('.storage-wrap');
		  $(element[0]).after(storage);
		  storage.css({margin: "40px 0px 20px 0px"})
		  $('.storage-wrap .lazyloaded').css({width: "30%"})
	  }
	}
	function recurseDirections(i, elem, origin_pos){
	  var count = elem.length;
	   if(i +1 === count && isLoading){
      var elem_temp = $('.columns.locations .col');
      var add = $('.columns.locations .col .added');
      elem_temp.removeClass('active');
      add.remove();
      mixer.sort('distance:asc', function() {
        moveMobile();
				var element_first = $('.columns.locations .col:first-child');
				element_first.addClass('active');
				element_first.prepend('<div class="added loc">your closest location</div>');
				element_first.prepend('<div class="added icon arrow-round"></div>');
			  toggleLoading(false, "after sort");
        processing = false;
			});
    }
    else if( i < count && processing){
      if(!isLoading) toggleLoading(true, "started");
      var location_lat = $(elem[i]).attr('data-location-lat');
			var location_lng = $(elem[i]).attr('data-location-lng');
			var location_pos = new google.maps.LatLng(location_lat, location_lng);
			var request = {
				origin: origin_pos,
				destination: location_pos,
				travelMode: google.maps.TravelMode.DRIVING
			};
      directionsService.route(request, function (response, status) {
    		if (status === google.maps.DirectionsStatus.OK) {
    			directionsDisplay.setDirections(response);
    
    			var distance = (response.routes[0].legs[0].distance.value / 1000).toFixed(2);
    			console.log("here's the counter", i, distance);
          var this_elem = $(elem[i]);
    			if(this_elem.find('.distance').length > 0){
    				this_elem.find('.distance').addClass('active');
    				this_elem.find('.distance span').text(distance);
    				this_elem.attr('data-distance', distance);
    			}
    			recurseDirections(i+1, elem, origin_pos)
    		} 
    		else if (status === google.maps.DirectionsStatus.OVER_QUERY_LIMIT) {
    		  //console.log("didn't work", i);
          //delayFactor=1;
          if(!isLoading)toggleLoading(true, "query limit" + i);
          setTimeout(function () {
            recurseDirections(i, elem, origin_pos);
          }, 1000);
        } 
        else if (status === google.maps.DirectionsStatus.ZERO_RESULTS) {
          showErrorMessage('No results found.');
          notFoundError = true;
          toggleLoading(false);
        }
        else{
    			//console.log('Something else happened: ' + status);
    		}
    	});
    }
  	
	}
	
	
	function calcDistance(place_pos, place_pos2) {
		return (google.maps.geometry.spherical.computeDistanceBetween(place_pos, place_pos2) / 1000).toFixed(2);
	}

	function hideErrorMessage(){
		$('.search-wrap #error-message').remove();
		$('.search-wrap #location-address').removeClass('wpcf7-not-valid');
	}

	function showErrorMessage(message){
	  if(messageError || notFoundError) hideErrorMessage();
		$('.search-wrap .input-wrap').parent().append('<span role="alert" id="error-message" class="wpcf7-not-valid-tip">'+ message +'</span>');
		$('.search-wrap #location-address').addClass('wpcf7-not-valid');
	}

	function geoResults(results, status) {
		if (status === 'OK') {
		  $('#location-address').val(results[0].formatted_address);
			if (results !== false) {
				var place_lat = results[0].geometry.location.lat();
				var place_lng = results[0].geometry.location.lng();
				var place_pos = new google.maps.LatLng(place_lat, place_lng);

				updateDistance(place_pos);
			}
		} else {
			console.log('Geocode was not successful for the following reason: ' + status);
			showErrorMessage('No results found.');
			toggleLoading(false, "not found");
      notFoundError = true;
		}
	}
  
	/* Find Current Location */

	function gotGeolocation() {
		updateDistance(current_pos);
	}

  function toggleLoading(boo, debug){
    //console.log(boo, debug);
    if(boo == isLoading) return 0;
    if(boo && isLoading){
      if(!$( ".search-wrap .loading" ).length){
        $('.search-wrap .input-wrap').parent().append(
        '<span class="wpcf7-not-valid-tip loading"><img src="https://'+ window.location.hostname +'/wp-content/uploads/2019/09/loading.gif" style="height:50px"></img></span>'
        );
      }
    }
    else if(boo && !isLoading){
      //$('.search-wrap .input-wrap').parent().append('<span class="wpcf7-not-valid-tip loading" style="font-size:14px; color: #54565a">Loading, please wait...</span>');
      isLoading = true;
      $('.search-wrap .input-wrap').parent().append(
        '<span class="wpcf7-not-valid-tip loading"><img src="https://'+ window.location.hostname +'/wp-content/uploads/2019/09/loading.gif" style="height:50px"></img></span>'
        );
      $('#location-address').prop('disabled', true);
      $('#btn-search').addClass('btn-disabled');
      $('.btn-current').removeClass('icon').addClass('btn-current-disabled');
      isLoading = boo;
      //console.log("loading", boo)
    }
    else if(isLoading && !boo){
      isLoading = false;
      $('.search-wrap .loading').remove();
      $('#location-address').prop('disabled', false);
      $('#btn-search').removeClass('btn-disabled');
      $('.btn-current').addClass('icon').removeClass('btn-current-disabled');
      //console.log("loading", boo)
    }
  }

	function initGeolocation() {
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(
				browserGeolocationSuccess,
				browserGeolocationFail,
				{maximumAge: 50000, timeout: 20000, enableHighAccuracy: true}
			);
		} else {
			current_pos = false;
		}
	}

	var apiGeolocationSuccess = function(position) {
		current_pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
		gotGeolocation();
	};

	var tryAPIGeolocation = function() {
		jQuery.post( "https://www.googleapis.com/geolocation/v1/geolocate?key=AIzaSyDsoPVSsFSUb6zlLJyJtFxzYdSiYf2BGSk", function(success) {
			apiGeolocationSuccess({coords: {latitude: success.location.lat, longitude: success.location.lng}});
		})
		.fail(function(err) {
			alert("API Geolocation error! \n\n"+err);
		});
	};

	var browserGeolocationSuccess = function(position) {
		current_pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
		gotGeolocation();
	};

	var browserGeolocationFail = function(error) {
		switch (error.code) {
			case error.TIMEOUT:
				alert("Browser geolocation error !\n\nTimeout.");
			break;
			case error.PERMISSION_DENIED:
			if(error.message.indexOf("Only secure origins are allowed") == 0) {
				tryAPIGeolocation();
			}
			break;
			case error.POSITION_UNAVAILABLE:
				alert("Browser geolocation error !\n\nPosition unavailable.");
			break;
		}
	};
});
