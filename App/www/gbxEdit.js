//Create variable needed for gbxAjaxReq()
rules = '';

//Create global variable
var gbxContent;

function gbxEdit(msg_id) {

gbxContent = document.getElementById(msg_id).innerHTML;
document.getElementById(msg_id).className = 'post_edit';
document.getElementById(msg_id).innerHTML = '<form name="edit_' + msg_id + '"><textarea id="FCKeditorContents" name="FCKeditorContents">' + gbxContent + '</textarea><center><button type="button" class="button" onClick="gbxEditSubmit(\'' + msg_id + '\');">Save Post</button>&nbsp;<button type="button" class="button" onClick="gbxEditCancel(\'' + msg_id + '\');">Cancel</button></center></form>';

var oFCKeditor = new FCKeditor('FCKeditorContents');
oFCKeditor.BasePath = "./post/FCKeditor/";
oFCKeditor.Height = 400;
oFCKeditor.Width = 755;
oFCKeditor.ToolbarSet = "GBX";
oFCKeditor.ReplaceTextarea();

}

function gbxEditSubmit(msg_id) {

var oEditor = FCKeditorAPI.GetInstance('FCKeditorContents');
var newmessage = oEditor.GetXHTML(true);
document.getElementById(msg_id).innerHTML = newmessage;

document.getElementById(msg_id).className = '';

var tempid = msg_id.split("_");
var newmsg_id = tempid[1];

gbxAjaxReq('', false, 'ajax/editpost.php', 'message=' + escape(newmessage) + '&msg_id=' + newmsg_id);

}

function gbxEditCancel(msg_id) {

document.getElementById(msg_id).innerHTML = gbxContent;
document.getElementById(msg_id).className = '';

}