$.ajax({
	url: "openclass/getPin",
	type: "POST",
	dataType: "text",
	success: function (res) {
		let Pin = res;
		let Pin_url = "https://querisma.ccu.edu.tw/~tlogben0709/www/www/scanQR?Pin=" + encodeURIComponent(Pin);
		document.getElementById("Pin").innerHTML = Pin;
		document.getElementById("QRShow").innerHTML = "<img src='https://chart.googleapis.com/chart?chs=546x547&cht=qr&chl=" +  encodeURIComponent(Pin_url) + "'data-action=\"zoom\"/>";
	}
});
