$("body").on('DOMSubtreeModified', "#price-mode", function() { 

/* handles google optimize changes on loop-units.php*/
	var price_mode = $("#price-mode").text();
	console.log("hello", price_mode == "daily")
	
	if(price_mode == "daily"){
		var regular_price = $(".price-opt");
		var regular_price_red = $('.price-opt-red');
		var rent;
		var daily_rent;
		var p;
		for(p = 0 ; p < regular_price.length ; p++){
			rent = parseFloat($(regular_price[p]).data().price);
			daily_rent = (rent*12/365).toFixed(2);
			$(regular_price[p]).text("$"+daily_rent+"/day");
		}
		for(p = 0 ; p < regular_price_red.length ; p++){
			rent = parseFloat($(regular_price_red[p]).data().price);
			daily_rent = (rent*12/365).toFixed(2);
			$(regular_price_red[p]).text("$"+daily_rent);
		}
		
	}
});
