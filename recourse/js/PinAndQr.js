$.ajax({
    url: "openclass/getPin",
    type: "POST",
	dataType : "text",
    success : function (res) {
        var Pin = res;
		if (res=="") {
			document.getElementById("Pin").innerHTML = Pin;
			document.getElementById("QRShow").innerHTML = "<img src='https://chart.googleapis.com/chart?chs=382x300&cht=qr&chl=" + encodeURIComponent(pin) + "'/>";
		}
	}
});

