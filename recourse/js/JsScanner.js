function onQRCodeScanned(scannedText) {
    let scannedTextMemo = document.getElementById("scannedTextMemo");
    if (scannedTextMemo) {
    	let url = new  URL(scannedText);
		scannedTextMemo.value = url.searchParams.get("Pin");
        document.getElementById("form_submit").submit();
    }
}

//this function will be called when JsQRScanner is ready to use
function JsQRScannerReady() {
    //create a new scanner passing to it a callback function that will be invoked when
    //the scanner succesfully scan a QR code
    let jbScanner = new JsQRScanner(onQRCodeScanned);
    //reduce the size of analyzed images to increase performance on mobile devices
    jbScanner.setSnapImageMaxSize(300);
    let scannerParentElement = document.getElementById("scanner");
    if (scannerParentElement) {
        //append the jbScanner to an existing DOM element
        jbScanner.appendTo(scannerParentElement);
    }
}
