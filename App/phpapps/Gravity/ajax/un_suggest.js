function getXmlHttpRequestObject() {
	if (window.XMLHttpRequest) {
		return new XMLHttpRequest();
	} else if(window.ActiveXObject) {
		return new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		alert("Your browser does not support this page!\nWe recommend upgrading to Firefox at getfirefox.com.");
	}
}

var searchReq = getXmlHttpRequestObject();

function unSuggest() {
	if (searchReq.readyState == 4 || searchReq.readyState === 0) {
		var str = escape(document.getElementById('imto').value);
		searchReq.open("GET", 'ajax/un_suggest.php?search=' + str, true);
		searchReq.onreadystatechange = handleSearchSuggest; 
		searchReq.send(null);
	}		
}

function handleSearchSuggest() {
	if (searchReq.readyState == 4) {
		var ss = document.getElementById('un_suggest');
		ss.innerHTML = '';
		var str = searchReq.responseText.split("\n");
		for(i=0; i < str.length - 1; i++) {
			//Build our element string.  This is cleaner using the DOM, but
			//IE doesn't support dynamically added attributes.
			var suggest = '<div onMouseOver="javascript:suggestOver(this);" ';
			suggest += 'onMouseOut="javascript:suggestOut(this);" ';
			suggest += 'onClick="javascript:setText(this.innerHTML);" ';
			suggest += 'class="suggest">' + str[i] + '</div>';
			ss.innerHTML += suggest;
		}
	}
}

function suggestOver(div) {
	div.className = 'suggest_over';
}

function suggestOut(div) {
	div.className = 'suggest';
}

function setText(searchText) {
	document.getElementById('imto').value = searchText;
	document.getElementById('un_suggest').innerHTML = '';
}