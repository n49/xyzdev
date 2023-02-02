const setCookie = (k, v, path) => {
	document.cookie = `${k}=${v};path=${path}`;
    window.location = path
}

document.querySelectorAll(".select-space")
.forEach(b => b.onclick = (e) =>{
    e.preventDefault();
	var plan = b.getAttribute("data-plan");
	console.log(`Rental ${plan} clicked`, plan, dataLayer);
	_gaq.push(['_trackEvent', `Rental ${plan} Clicked`, 'Click', `Rental ${plan} Clicked`]);
	gtag('event', `Rental ${plan} Clicked`);
// 	window.dataLayer.push({'event': `Rental ${plan} Clicked`});
    setCookie("unitRent", b.getAttribute("data-price"), "/rent-now")

})

document.querySelector("#go-back-button").onclick = e => {
    e.preventDefault()
    history.back()
}