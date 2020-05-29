function showPrintForm(id) {
	peng = "./showprintform.php?id=" + id;
	window.open(peng, "Drucken", "width=810,scrollbars=yes");
}

function anzeigen(das){
	if(document.getElementById(das).style.display=='none') {
		document.getElementById(das).style.display='block';
	}
	else {
		document.getElementById(das).style.display='none';
	}
}

function moveTostart(mode) {
	if (mode == 1) {
		if (document.getElementById('omode1').checked) {
			outmode = 'wcb';
		}
		else if (document.getElementById('omode2').checked) {
			outmode = 'mmf';
		}
		else if (document.getElementById('omode3').checked) {
			outmode = 'rkv';
		}
		var jetzt = new Date();
		dauer = 2*365*24*60*60*1000;
		var auszeit = new Date(jetzt.getTime()+dauer);
		cookiestring = "omode="+outmode+"; expires="+auszeit.toGMTString()+";";
		document.cookie = cookiestring;
	}
	self.location.href = "./";
}

function changeRank(rank) {
	document.rankform.ranking.value = rank;
	switch (rank) {
		case 1:
			document.getElementById('rank01').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank02').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank03').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank04').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank05').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank06').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank07').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank08').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank09').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank10').src = 'images/system/stahlknopf.jpg';
			break;
		case 2:
			document.getElementById('rank01').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank02').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank03').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank04').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank05').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank06').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank07').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank08').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank09').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank10').src = 'images/system/stahlknopf.jpg';
			break;
		case 3:
			document.getElementById('rank01').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank02').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank03').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank04').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank05').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank06').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank07').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank08').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank09').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank10').src = 'images/system/stahlknopf.jpg';
			break;
		case 4:
			document.getElementById('rank01').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank02').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank03').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank04').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank05').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank06').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank07').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank08').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank09').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank10').src = 'images/system/stahlknopf.jpg';
			break;
		case 5:
			document.getElementById('rank01').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank02').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank03').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank04').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank05').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank06').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank07').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank08').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank09').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank10').src = 'images/system/stahlknopf.jpg';
			break;
		case 6:
			document.getElementById('rank01').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank02').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank03').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank04').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank05').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank06').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank07').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank08').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank09').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank10').src = 'images/system/stahlknopf.jpg';
			break;
		case 7:
			document.getElementById('rank01').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank02').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank03').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank04').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank05').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank06').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank07').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank08').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank09').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank10').src = 'images/system/stahlknopf.jpg';
			break;
		case 8:
			document.getElementById('rank01').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank02').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank03').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank04').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank05').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank06').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank07').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank08').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank09').src = 'images/system/stahlknopf.jpg';
			document.getElementById('rank10').src = 'images/system/stahlknopf.jpg';
			break;
		case 9:
			document.getElementById('rank01').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank02').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank03').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank04').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank05').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank06').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank07').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank08').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank09').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank10').src = 'images/system/stahlknopf.jpg';
			break;
		case 10:
			document.getElementById('rank01').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank02').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank03').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank04').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank05').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank06').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank07').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank08').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank09').src = 'images/system/goldknopf.jpg';
			document.getElementById('rank10').src = 'images/system/goldknopf.jpg';
			break;
	}
}