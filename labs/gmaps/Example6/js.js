//creates HTTP Request
function createRequest(){
     var request;
	 /*
	 try{
            if (window.XMLHttpRequest) {
                    request = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                 try {
                        request = new ActiveXObject("Msxml2.XMLHTTP");
                 } catch (e) {
                        try {
                            request = new ActiveXObject("Microsoft.XMLHTTP");
                        } catch (e) {}
                }
            }
        }catch(e) { }
		*/
		request = GXmlHttp.create();
		
        return request;
}

//is Response ready
function isResponseReady(){
	//alert(http_request.readyState);
	return http_request.readyState == 4;
}
function getResponseAsXML(){
	var response ;
	if(http_request.status == 200){
			response = http_request.responseXML;
	}
	return response;
}
//generates a GET Request
function do_GET(url, responseHandler){
	http_request = createRequest();
	http_request.onreadystatechange = responseHandler;
	http_request.open('GET', url, true);
    http_request.send("");
}

//generates POST Request
function do_POST(params, url, responseHandler){
	http_request = createRequest();
	http_request.onreadystatechange = responseHandler;
        http_request.open('POST', url, true);
        http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      	http_request.setRequestHeader("Content-length", params.length);
        http_request.send(params);
}
function toggleTabs(tNum) {
    for(var i=1; i<5; i++) {
      var t = "t" + i;		  
      var p = "p" + i;		  
      if(i == tNum) {
        document.getElementById(t).className = "ts_on";
	document.getElementById(p).className = "ps_on";	
      } else {
        document.getElementById(t).className = "ts"; 
	document.getElementById(p).className = "ps";      
      }
    }
  }