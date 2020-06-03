function init() {
	tinyMCEPopup.resizeToInnerSize();
}

function insertEmotion(file_name, title) {
	title = tinyMCE.getLang(title);

	if (title == null)
		title = "";

	// XML encode
	title = title.replace(/&/g, '&amp;');
	title = title.replace(/\"/g, '&quot;');
	title = title.replace(/</g, '&lt;');
	title = title.replace(/>/g, '&gt;');

	var html = '<img src="Emoticons/1"' + file_name + '" />';

	tinyMCE.execCommand('mceInsertContent', false, html);
	tinyMCEPopup.close();
}
