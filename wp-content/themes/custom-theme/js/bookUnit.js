const setCookie = (k, v, path) => {
	document.cookie = `${k}=${v};path=${path}`;
}

jQuery(document).ready(function($){

	$('.btn-reserve').on("click", ({currentTarget}) => {
		gtag('event', 'Reservation Clicked');
		const data = $(currentTarget).data()

		const cookieData = {
			unitLocation: parseInt(data.location),
			unitType: parseInt(data.id),
			unitDimensions: data.dimensions,
			length: parseInt(data.length),
			width: parseInt(data.width),
			height: parseInt(data.height),
			unitRent: parseFloat(data.rent),
			unitPrice: parseFloat(data.price),
			availableUnits: parseInt(data.available),
		}

		const url = "/customer-details";

		Object.keys(cookieData).forEach(key => {
			setCookie(key, cookieData[key], url)
		})

		Cookies.set('unit', {unit: data.id, amount: 1, location: data.location});
	
		window.location = url;
	})

	$('.rent-now-button').on("click", ({currentTarget}) => {
		gtag('event', 'Rentnow Clicked');
		const data = $(currentTarget).data()

		const cookieData = {
			unitLocation: parseInt(data.location),
			unitType: parseInt(data.id),
			unitDimensions: data.dimensions,
			length: parseInt(data.length),
			width: parseInt(data.width),
			height: parseInt(data.height),
			unitRent: parseFloat(data.rent),
			unitPrice: parseFloat(data.price),
			promo: parseFloat(data.promo),
			promoLabel: data.promolabel
		}
		let url = "/rent-now";

		Object.keys(cookieData).forEach(key => {
			setCookie(key, cookieData[key], url)
		})

		url = "/choose-your-space";

		Object.keys(cookieData).forEach(key => {
			setCookie(key, cookieData[key], url)
		})

		window.location = url;
	})

	$('.retrieve-discount').each((index, element) => {
		const id = $(element).data('id')
		$(`#post-${id}`).find('.rent-now-button').hide();
		const locationId = $(element).data('location')
		let discountObject = {};

		$.ajax(
			{
				url: `/wp-json/myplugin/v1/unit-discounts/${locationId}/${id}/`,
				success: function(result) {
					console.log('got disacount');
		$(`#post-${id}`).find('.rent-now-button').show();

					discountObject[id] = result;
					if (!result.length) {
						$(`#post-${id}`).find('.rent-now-button').css("margin-top", "40px")
						return;
					}
					var elem2 = document.getElementById('price-tag-'+id);
					if(result[0]['DiscountName'] !== undefined){
						$(`#post-${id}`).find('.rent-now-button').attr("data-promolabel", result[0]['DiscountName'])
					}
					if(result[0]['DiscountName'].includes("50% Off Rent - 3 Months")){
						var elem = document.getElementById('price-tag-'+id);
						document.querySelector(`#special-flag-${id}`).style.display = "block";
						if(elem.classList.contains("no-price-tag") && $(window).width() >= 480){

							elem.style.width = "50px";
							elem.classList.remove("lazyloaded");
							elem.classList.remove("no-price-tag");
							elem.classList.add("price-tag");
							elem.src = "/wp-content/uploads/2019/10/Tag@2x.png";

						} else {
							document.getElementById("p-"+id).innerHTML = "50% OFF FIRST 3 MONTHS";
						}
						let tempPrice = parseFloat($(`#unit-sp-price-${id}`).text().replace("$", "").replace("/mo",""));
						const fullPrice = tempPrice;
						$(`#unit-price-${id}`).text(`$${fullPrice.toFixed(2)}/mo`)

						tempPrice = tempPrice / 2;
						$(`#unit-sp-price-${id}`).text(`$${tempPrice.toFixed(2)}/mo`)
						$(`#post-${id}`).find('.rent-now-button').attr("data-promo", "0.5")
						// find price for reservation and make it equal to fullPrice

						console.log("hello", fullPrice, result[0]['DiscountName'])
						$(`#reservation-price-${id}`).text(`$${fullPrice.toFixed(2)}/mo`)


					} else if(result[0]['DiscountName'].includes("One Month Free")){
						var elem = document.getElementById('price-tag-'+id);
						document.querySelector(`#special-flag-${id}`).style.display = "block";
						if(elem.classList.contains("no-price-tag") && $(window).width() >= 480){

							elem.style.width = "50px";
							elem.classList.remove("lazyloaded");
							elem.classList.remove("no-price-tag");
							elem.classList.add("price-tag");
							elem.src = "/wp-content/uploads/2021/12/Asset-1.png";

						} else {
							document.getElementById("p-"+id).innerHTML = "ONE MONTH FREE";
						}

					}
					else if(result[0]['DiscountName'].includes("50% off 1 Month")){
						var elem = document.getElementById('price-tag-'+id);
						document.querySelector(`#special-flag-${id}`).style.display = "block";

						if(elem.classList.contains("no-price-tag") && $(window).width() >= 480){

							elem.style.width = "50px";
							elem.classList.remove("lazyloaded");
							elem.classList.remove("no-price-tag");
							elem.classList.add("price-tag");
							elem.getElementsByTagName('img')[0].src = "https://www.xyzstorage.com/wp-content/uploads/2021/12/Asset-3.png";
							elem.getElementsByTagName('img')[0].setAttribute("data-src","https://www.xyzstorage.com/wp-content/uploads/2021/12/Asset-3.png") ;
							elem.getElementsByTagName('source')[0].setAttribute("data-srcset","https://www.xyzstorage.com/wp-content/uploads/2021/12/Asset-3.png") ;
							elem.getElementsByTagName('source')[0].setAttribute("srcset","https://www.xyzstorage.com/wp-content/uploads/2021/12/Asset-3.png") ;

							document.querySelector(`#special-flag-${id}`).style.display = "block";
							
						} else {
							document.getElementById("p-"+id).innerHTML = "50% OFF FIRST MONTH'S RENT";
						}
						let tempPrice = parseFloat($(`#unit-sp-price-${id}`).text().replace("$", "").replace("/mo",""));
						const fullPrice = tempPrice;
						$(`#unit-price-${id}`).text(`$${fullPrice.toFixed(2)}/mo`)
						tempPrice = tempPrice / 2;

						$(`#unit-sp-price-${id}`).text(`$${tempPrice.toFixed(2)}/mo`)
						$(`#post-${id}`).find('.rent-now-button').attr("data-promo", "0.5")
						
						// find price for reservation and make it equal to fullPrice

						console.log("hello", fullPrice, result[0]['DiscountName'])
						$(`#reservation-price-${id}`).text(`$${fullPrice.toFixed(2)}/mo`)
							
					}
					else if(result[0]['DiscountName'] == "3 Month 4x3x4 Promo"){
						var node = document.createElement("LI");
						var textnode = document.createTextNode("Online Special for first 3 full months");
						node.appendChild(textnode);
						document.getElementById("ul-"+id).appendChild(node);
						document.getElementById('discount-cookie-'+id).innerHTML = "Online Special Guaranteed for first 3 full months";
					}
					else if((result[0]['DiscountName'].includes("Student Discount") || result[0]['DiscountName'].includes("Senior Discount"))){
						document.getElementById("p-"+id).innerHTML = "10% OFF";
						var strArray = result[0]['DiscountName'].split("[");
						document.getElementById("discount-name-"+id).innerHTML = strArray[0];
						var expiry = strArray[1].split(']');
						document.getElementById("discount-expiration-"+id).innerHTML = "expires: "+expiry[0];
						document.getElementById('discount-cookie-'+id).innerHTML = strArray[0];
						$(".slider-units-dots").css("margin", "40px auto 0")
					} else {
						if(result[0]['DiscountName'].includes('[')){
							var strArray = result[0]['DiscountName'].split('[');
							var discountName = strArray[0];
							var expiry = strArray[1].split(']');
						}
						else{
							var discountName = result[0]['DiscountName'];
							var expiry = '';
						}
						var node = document.createElement("LI");
						var textnode = document.createTextNode(discountName);
						node.appendChild(textnode);
						document.getElementById("ul-"+id).appendChild(node);
						document.getElementById("discount-expiration-"+id).innerHTML = "expires: "+expiry[0];
						document.getElementById('discount-cookie-'+id).innerHTML = discountName;
					}
				}
			}
		);

	})
	
})