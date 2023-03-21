const setCookie = (k, v, path) => {
	document.cookie = `${k}=${v};path=/`;
//     window.location = path
}
function getCookie(name) {
    function escape(s) { return s.replace(/([.*+?\^$(){}|\[\]\/\\])/g, '\\$1'); }
    var match = document.cookie.match(RegExp('(?:^|;\\s*)' + escape(name) + '=([^;]*)'));
    return match ? match[1] : null;
}

// const getCookie = (name) => {
//         var value = "; " + document.cookie;
//         var parts = value.split("; " + name + "=");
//         if (parts.length == 2) return parts.pop().split(";").shift();
// }

jQuery(document).ready(function($){
	
	function convertTodayDate(rent) {
			var today = new Date();
    		var daysInMonth = new Date(today.getFullYear(), today.getMonth()+1, 0).getDate();
		    var dp = rent / daysInMonth;
			return dp;
	}
	
	var basicAmount = $('#basicAmount').text();
	var valueAmount = $('#valueAmount').text();
	var premiumAmount = $('#premiumAmount').text();
	
	var redBasicAmount = $('#basicRedAmount').text();
	var redValueAmount = $('#valueRedAmount').text();
	var redPremiumAmount = $('#premiumRedAmount').text();

	if(!basicAmount.includes('not')) {
		basicAmount = basicAmount.match(/\d+\.\d+/)[0];
	}
	if(!valueAmount.includes('not')) {
		valueAmount = valueAmount.match(/\d+\.\d+/)[0];
	}
	
	if(!premiumAmount.includes('not')) {
		premiumAmount = premiumAmount.match(/\d+\.\d+/)[0];
	}
	
		if(!basicAmount.includes('not')) {

	redBasicAmount = redBasicAmount.match(/\d+\.\d+/)[0];}
	redValueAmount = redValueAmount.match(/\d+\.\d+/)[0];
	redPremiumAmount = redPremiumAmount.match(/\d+\.\d+/)[0];

console.log('what is this', getCookie('priceMode'));
	if(getCookie('priceMode')) {
		var priceMode = getCookie('priceMode');
			console.log('got the price mode', priceMode);
		if(priceMode == 'daily') {
			$('.dailyMode').addClass('active');
			$('.monthlyMode').removeClass('active');
			
				if(!basicAmount.includes('not')) {

			var basicDayAmount = convertTodayDate(basicAmount).toFixed(2);}
			var valueDayAmount = convertTodayDate(valueAmount).toFixed(2);
			var premiumDayAmount = convertTodayDate(premiumAmount).toFixed(2);
			
				if(!basicAmount.includes('not')) {

			var redbasicDayAmount = convertTodayDate(redBasicAmount).toFixed(2);}
			var redvalueDayAmount = convertTodayDate(redValueAmount).toFixed(2);
			var redpremiumDayAmount = convertTodayDate(redPremiumAmount).toFixed(2);

	if(!basicAmount.includes('not')) {

			$('#basicAmount').text(`$${basicDayAmount}/day`);}
			$('#valueAmount').text(`$${valueDayAmount}/day`);
			$('#premiumAmount').text(`$${premiumDayAmount}/day`);
			
				if(!basicAmount.includes('not')) {

			$('#basicRedAmount').text(`$${redbasicDayAmount}/day`);}
			$('#valueRedAmount').text(`$${redvalueDayAmount}/day`);
			$('#premiumRedAmount').text(`$${redpremiumDayAmount}/day`);

		}
	}
	
		$('.dailySwitch').on("click", (ev) => {
// 			ev.preventDefault();
			setCookie('priceMode', 'daily', '/choose-your-space');
				if(!basicAmount.includes('not')) {

			var basicDayAmount = convertTodayDate(basicAmount).toFixed(2);}
			var valueDayAmount = convertTodayDate(valueAmount).toFixed(2);
			var premiumDayAmount = convertTodayDate(premiumAmount).toFixed(2);
				if(!basicAmount.includes('not')) {

			var redbasicDayAmount = convertTodayDate(redBasicAmount).toFixed(2);}
			var redvalueDayAmount = convertTodayDate(redValueAmount).toFixed(2);
			var redpremiumDayAmount = convertTodayDate(redPremiumAmount).toFixed(2);

				if(!basicAmount.includes('not')) {

			$('#basicAmount').text(`$${basicDayAmount}/day`);}
			$('#valueAmount').text(`$${valueDayAmount}/day`);
			$('#premiumAmount').text(`$${premiumDayAmount}/day`);
			
				if(!basicAmount.includes('not')) {

			$('#basicRedAmount').text(`$${redbasicDayAmount}/day`);}
			$('#valueRedAmount').text(`$${redvalueDayAmount}/day`);
			$('#premiumRedAmount').text(`$${redpremiumDayAmount}/day`);

		});

		$('.monthlySwitch').on("click", (ev) => {
// 			ev.preventDefault();
			setCookie('priceMode', 'monthly', '/choose-your-space');
// 			$('.dailyMode').addClass('active');
// 			$('.monthlyMode').removeClass('active');
				if(!basicAmount.includes('not')) {

			$('#basicAmount').text(`$${basicAmount}/mth`);}
			$('#valueAmount').text(`$${valueAmount}/mth`);
			$('#premiumAmount').text(`$${premiumAmount}/mth`);
			
				if(!basicAmount.includes('not')) {

			$('#basicRedAmount').text(`$${redBasicAmount}/mth`);}
			$('#valueRedAmount').text(`$${redValueAmount}/mth`);
			$('#premiumRedAmount').text(`$${redPremiumAmount}/mth`);


		});
	
	document.querySelectorAll(".select-space")
.forEach(b => b.onclick = (e) =>{
    e.preventDefault();
	var plan = b.getAttribute("data-plan");
	console.log(`Rental ${plan} clicked`, plan, dataLayer);
	_gaq.push(['_trackEvent', `Rental ${plan} Clicked`, 'Click', `Rental ${plan} Clicked`]);
	gtag('event', `Rental ${plan} Clicked`);
// 	window.dataLayer.push({'event': `Rental ${plan} Clicked`});
    setCookie("unitRent", b.getAttribute("data-price"), "/rent-now")
	setCookie("selectedSpace", plan, "/rent-now");
		window.location = '/rent-now';

})

document.querySelector("#go-back-button").onclick = e => {
    e.preventDefault()
    history.back()
}

});

