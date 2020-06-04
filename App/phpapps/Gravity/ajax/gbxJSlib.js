function deletePost(msg_id){

//Confirm post deletion
if(confirm("Are you sure you would like to delete this post?\nThis may NOT be undone!"))
{
	//Create AJAX request to delete post from database
	gbxAjaxReq('', false, 'ajax/deletepost.php', 'msg_id=' + msg_id);

	//Do disappearing animation via scriptaculous library
	new Effect.Squish('post_row_' + msg_id);
}

}

function gbxReply(){

	if(undefined===window.replySent)
	{
		oReply.ReplaceTextarea();
	}
	new Effect.Grow('messagereply');
	replySent = true;

}

function gbxQuote(msg_id){

	document.getElementById('gbxReplyContents').innerHTML = '<blockquote><hr/>' + document.getElementById('member_' + msg_id).innerHTML + ' wrote:<br/>' + document.getElementById('message_' + msg_id).innerHTML + '<hr/></blockquote><br/>';
	oReply.ReplaceTextarea();
	document.location = '#pagebottom';
	new Effect.SlideDown('messagereply');
	document.location = '#pagebottom';
}

function gbxReplySubmit(thread_id, board_id){

new Effect.Squish('messagereply', {duration: 2.0});

var oEditor = FCKeditorAPI.GetInstance('gbxReplyContents');
var gbxMessage = oEditor.GetXHTML(true);

gbxAjaxReq('', false, 'ajax/ajaxreply.php', 'message=' + escape(gbxMessage) + '&thread_id=' + thread_id + '&board_id=' + board_id);

var randomid = Math.floor(Math.random()*10000000);
gbxAddNewMessage(randomid, gbxMessage);

oEditor.Value = "";

}

function gbxAddNewMessage(newMessage, message){

//Create new message HTML
//Set user icon
if(myinfo['icon_url'] == '')
{
	var usericon = '';
}else
{
	var usericon = '<img src="' + myinfo['icon_url'] + '" height="' + myinfo['icon_height'] + '" width="' + myinfo['icon_width'] + '"/>';
}
document.getElementById('newmessages').innerHTML = document.getElementById('newmessages').innerHTML + '<div class="post_mid"><div class="post_top"></div><div class="post_row" id="post_row_' + newMessage + '"><div class="post_info" id="post_info_' + newMessage + '"><span><div id="member_' + newMessage + '"><b><a href="?action=viewprofile&member_id=' + myinfo['memberid'] + '"><span class="post_username" style="color: ' + myinfo['color'] + ';">' + myinfo['displayname'] + '</span></a></b></div><span class="small">' + myinfo['group_name'] + '</span><br/><span class="small">' + myinfo['rank'] + '</span><br/><span class="small">Posts: ' + myinfo['pc'] + '</span><br/><span class="small">Status: <b>Online</b></span><br/><br/>' + usericon + '</span><br/><p align="center"><span class="small">' + globaltime + '</span></p></div><div class="post_main" id="post_main_' + newMessage + '"><span style="float: right;"><font class="small"><b>Post Options: </b>Reload page for options<a name="' + newMessage + '"></a></font></span><br/><br/><div id="message_' + newMessage + '">' + message + '</div><div><br/><hr/><span>' + myinfo['signature'] + '</span></div></div><div class="post_bot"></div></div>';

//Make new message appear
//Fix DIV heights
fixHeight('post_info_' + newMessage + ',post_main_' + newMessage + '');
document.location = '#pagebottom';
new Effect.SlideDown('post_row_' + newMessage, {duration: 2.0, delay: 1.0});
document.location = '#pagebottom';

}

function gbxReplyCancel(){

	new Effect.Fade('messagereply');

}

function gbxEditBoard(board_id){

var argString = 'board_id=' + board_id + '&name=' + document.getElementById('name_' + board_id + '').value + '&cat_id=' + document.getElementById('cat_id_' + board_id + '').value + '&description=' + document.getElementById('description_' + board_id + '').value;

gbxAjaxReq('', false, 'ajax/editboard.php', argString);

}

function gbxDeleteBoard(board_id){

gbxAjaxReq('', false, 'ajax/deleteboard.php', 'board_id=' + board_id);

new Effect.SlideUp('row_' + board_id);

}

function gbxCreateBoard(){

var argString = 'name=' + document.getElementById('newname').value + '&cat_id=' + document.getElementById('new_cat_id').value + '&description=' + document.getElementById('newdescription').value;

gbxAjaxReq('newboardform', true, 'ajax/createboard.php', argString);

document.getElementById('editboards').innerHTML = document.getElementById('editboards').innerHTML + '<div class="row" id="row_x" style="display: none;"><div class="board" id="board_"><input type="text" class="textbox" size="25" id="name_" value="' + document.getElementById('newname').value + '"/>&nbsp;<select id="cat_id_" class="textbox">' + gbxCategories + '</select>&nbsp;<input type="text" class="textbox" size="40" id="description_" value="' + document.getElementById('newdescription').value + '">&nbsp;<button type="button" onClick="gbxEditBoard(\'\');">Save Changes</button><button type="button" onClick="gbxDeleteBoard(\'\');">Delete Board</button>';

document.getElementById('newname').value = '';
document.getElementById('newdescription').value = '';

new Effect.SlideDown('row_x');

Sortable.create('editboards', {tag:'div',"onUpdate":updateBoards});

}

function gbxEditCategory(cat_id){

var argString = 'cat_id=' + cat_id + '&name=' + document.getElementById('name_' + cat_id + '').value;

gbxAjaxReq('', false, 'ajax/editcat.php', argString);

}

function gbxDeleteCategory(cat_id){

gbxAjaxReq('', false, 'ajax/deletecat.php', 'cat_id=' + cat_id);

new Effect.SlideUp('row_' + cat_id);

}

function gbxCreateCategory(){

var argString = 'name=' + document.getElementById('newname').value;

gbxAjaxReq('newcatform', true, 'ajax/createcat.php', argString);

document.getElementById('editcats').innerHTML = document.getElementById('editcats').innerHTML + '<div class="row" id="row_x" style="display: none;"><div class="board" id="board_"><input type="text" class="textbox" size="50" id="name_" value="' + document.getElementById('newname').value + '"/>&nbsp;<button type="button" onClick="gbxEditCategory(\'\');">Save Changes</button><button type="button" onClick="gbxDeleteCategory(\'\');">Delete Category</button>';

document.getElementById('newname').value = '';

new Effect.SlideDown('row_x');

Sortable.create('editcats', {tag:'div',"onUpdate":updateCats});

}

function gbxCreateRank(){

var argString = 'name=' + document.getElementById('newname').value + '&color=' + document.getElementById('newcolor').value + '&posts=' + document.getElementById('newposts').value;

gbxAjaxReq('newrankform', true, 'ajax/createrank.php', argString);

document.getElementById('newname').value = '';
document.getElementById('newcolor').value = '';
document.getElementById('newposts').value = '';

}

function gbxEditRank(rankid){

var argString = 'rankid=' + rankid + '&rank=' + document.getElementById('rank_' + rankid + '').value + '&color=' + document.getElementById('color_' + rankid + '').value + '&posts=' + document.getElementById('posts_' + rankid + '').value;

gbxAjaxReq('', false, 'ajax/editrank.php', argString);

}

function gbxDeleteRank(rankid){

gbxAjaxReq('', false, 'ajax/deleterank.php', 'rankid=' + rankid);

new Effect.SlideUp('rank_' + rankid);

}