//SAMPLE FUNCTION CALL
//gbxAjaxReq('boardform', 'true', 'forms/ajax/createboard.php?name=' + document.boardform.name.value + '&description=' + document.boardform.description.value + '&catid=' + document.boardform.catid.value);

function createRequestObject(){
	var request_o;
	var browser = navigator.appName;
	if(browser == "Microsoft Internet Explorer"){
		request_o = new ActiveXObject("Microsoft.XMLHTTP");
	}else{
		request_o = new XMLHttpRequest();
		request_o.overrideMimeType('text/xml');
	}
	return request_o;

}

var http = createRequestObject();

function gbxAjaxReq(dataCheck, validate, gbxReq, gbxArgs){

if(validate){ if(!performCheck(dataCheck, rules, 'innerHtml')){ return; } }

	if(gbxReq != 'ajax/deletepost.php')
	{
	//Darken page
	//var gbxpage = document.getElementById('screen');
	//gbxpage.style.height = document.body.parentNode.scrollHeight + 'px';
	//gbxpage.style.display = 'block';

	//Scroll to top of page
	scroll(0,0);

	//Show loading screen
	var gbxload = document.getElementById('loading');
	w = 300;
	h = 300;
	xc = Math.round((document.body.clientWidth/2)-(w/2))
	yc = Math.round((document.body.clientHeight/2)-(h/2))
	gbxload.style.left = xc + "px";
	gbxload.style.top = yc + "px";
	gbxload.style.display = 'block';
	}

	http.onreadystatechange = gbxAjaxReqSuccess;
	http.open('POST', gbxReq, true);
	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.setRequestHeader('Content-length', gbxArgs.length);
	http.setRequestHeader('Connection', 'close');
	http.send(gbxArgs);

}

function gbxAjaxReqSuccess(){
	if(http.readyState == 4){
		var response = http.responseText;
		document.getElementById('ajaxStatus').innerHTML = response;

		//Hide loading screen
		var loading = document.getElementById('loading');
		loading.style.display = 'none';

		//Lighten page
		//var gbxpage = document.getElementById('screen');
		//gbxpage.style.display = 'none';
	}
}