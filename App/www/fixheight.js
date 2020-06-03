function fixHeight(args) {
	if(document.getElementById) {
		var divs = new Array();
		var tempargs = args.split(',');
		for(var i = 0; i < tempargs.length; i++) {
			divs[i] = document.getElementById(tempargs[i]);
		}

		var maxHeight = 0;
		for(var i = 0; i < divs.length; i++) {
			if(divs[i].offsetHeight > maxHeight) maxHeight = divs[i].offsetHeight;
		}

		for(var i = 0; i < divs.length; i++) {
			divs[i].style.height = maxHeight + 'px';

			if(divs[i].offsetHeight > maxHeight) {
				divs[i].style.height = (maxHeight - (divs[i].offsetHeight - maxHeight)) + 'px';
			}
		}
	}
}