<?php
/*********************************************
<!-- 
*   	DOTA OPENSTATS
*   
*	Developers: Ivan.
*	Contact: ivan.anta@gmail.com - Ivan
*
*	
*	Please see http://openstats.iz.rs
*	and post your webpage there, so I know who's using it.
*
*	Files downloaded from http://openstats.iz.rs
*
*	Copyright (C) 2010  Ivan
*
*
*	This file is part of DOTA OPENSTATS.
*
* 
*	 DOTA OPENSTATS is free software: you can redistribute it and/or modify
*    it under the terms of the GNU General Public License as published by
*    the Free Software Foundation, either version 3 of the License, or
*    (at your option) any later version.
*
*    DOTA OPEN STATS is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License
*    along with DOTA OPENSTATS.  If not, see <http://www.gnu.org/licenses/>
*
-->
**********************************************/
  include('header.php');
  $pageTitle = "$lang[chat]";
  
  if (isset($_SESSION["logged"]) AND $_SESSION['user_name'])
  {$myname = $_SESSION['user_name'];
  $adm_message = "Welcome, <b>$myname</b>";
  $disabled = "disabled";}
  else {$myname = ""; $disabled = "";$adm_message="";}
  
  if (isset($_SESSION["logged"]) AND $_SESSION['user_name'] AND isset($_GET["delete"]) 
  AND isset($_SESSION["user_level"]) AND $_SESSION["user_level"]==1)
  {$handle = fopen ($chat_file, 'w'); fwrite ($handle, "");fclose($handle);
  echo "All messages successfully deleted!<br /><a href='chat.php'>Refresh page</a>";}
  
  if (!file_exists($chat_file)) 
  {
  $handle = fopen ($chat_file, 'w');
  $date =  "";
  if ($chat_show_date == 1)
  $date = "<td width='150px'>(".date($date_format).")</td>"; else $date = "";
  fwrite ($handle, "<table><tr class='row'>".$date."<td valign='middle' align='left'><b>OpenStats</b>: Welcome to chat page! <img alt='' style='vertical-align:middle;' border=0 src='smilies/wink.gif' /></td></tr></table>
"); 
  fclose($handle);}
  ?>
  <script type="text/javascript">
  var waittime=<?=$refresh_messages?>;
  var intUpdate = setTimeout("ajax_read('<?=$chat_file."?r=".rand(100,20000)?>')", 1);
  
  /* Request for Reading the Chat Content */
function ajax_read(url) {
	if(window.XMLHttpRequest){
		xmlhttp=new XMLHttpRequest();
		if(xmlhttp.overrideMimeType){
			xmlhttp.overrideMimeType('text/xml');
		}
	} else if(window.ActiveXObject){
		try{
			xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
		} catch(e) {
			try{
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			} catch(e){
			}
		}
	}

	if(!xmlhttp) {
		alert('Giving up :( Cannot create an XMLHTTP instance');
		return false;
	}

	xmlhttp.onreadystatechange = function() {
	if (xmlhttp.readyState==4) {
		document.getElementById("divActivities2").innerHTML = xmlhttp.responseText;

		datum = new Date(); 
		ms = (datum.getHours() * 24 * 60 * 1000) + (datum.getMinutes() * 60 * 1000) + (datum.getSeconds() * 1000) + datum.getMilliseconds(); 
		intUpdate = setTimeout("ajax_read('<?=$chat_file."?r=".rand(100,20000)?>?x=" + ms + "')", waittime)
		}
	}
	xmlhttp.open('GET',url,true);
	xmlhttp.send(null);
}
  
/* Submit the Message */
function submit_msg(){
	nick = document.getElementById("chatnick").value;
	msg = document.getElementById("chatmsg").value;
/*
	if (nick == "") { 
		check = prompt("Please enter username:"); 
		if (check === null) return 0; 
		if (check == "") check = "Anonymous"; 
		document.getElementById("chatnick").value = check;
		nick = check;
	}
*/  
    if (nick == "") { 
	document.getElementById("chatnick").value = "Anonymous";
	nick = "Anonymous";
	}
	document.getElementById("chatmsg").value = "";
	ajax_write("chat_write.php?m=" + msg + "&n=" + nick);
	document.all.info.innerText = "<?=$lang["message_posted"]?>";
	setTimeout('document.all.info.innerText = ""',3000);
	var objDiv = document.getElementById("chatwindow");
    objDiv.scrollTop = objDiv.scrollHeight;
	//disDelay("chatnick");

}

  /* Request for Writing the Message */
function ajax_write(url){
	if(window.XMLHttpRequest){
		xmlhttp2=new XMLHttpRequest();
		if(xmlhttp2.overrideMimeType){
			xmlhttp2.overrideMimeType('text/xml');
		}
	} else if(window.ActiveXObject){
		try{
			xmlhttp2=new ActiveXObject("Msxml2.XMLHTTP");
		} catch(e) {
			try{
				xmlhttp2=new ActiveXObject("Microsoft.XMLHTTP");
			} catch(e){
			}
		}
	}

	if(!xmlhttp2) {
		alert('Giving up :( Cannot create an XMLHTTP instance');
		return false;
	}

	xmlhttp2.open('GET',url,true);
	xmlhttp2.send(null);
}
  
  function disDelay(obj){
obj.setAttribute('disabled','disabled');
setTimeout(function(){obj.removeAttribute('disabled')},<?=$wait_time?>)
}
function formatText(el, tagstart, tagend) {
    if (el.setSelectionRange) {
        var start = el.value.substring(0, el.selectionStart);
        var selected = el.value.substring(el.selectionStart, el.selectionEnd);
        var end = el.value.substring(el.selectionEnd, el.value.length);

        var scroll = el.scrollTop;
        var caret = el.selectionStart;

        el.value = start + tagstart + selected + tagend + end;
        el.focus();
        el.scrollTop = scroll;

        if (selected.length == 0) {
            el.setSelectionRange(caret + tagstart.length, caret + tagstart.length);
        } else {
            el.setSelectionRange(caret, caret + tagstart.length + selected.length + tagend.length);
        }
    } else if (el.createTextRange) {
        // IE code here...
		var start = el.value.substring(0, el.selectionStart);
		var selected = el.value.substring(el.selectionStart, el.selectionEnd);
		var end = el.value.substring(el.selectionEnd, el.value.length);
		var caret = el.selectionStart;
		
		sel = document.selection.createRange();
		el.value = start + tagstart + selected + tagend + end;
		el.focus();
		
		if (selected.length == 0) {
            el.createTextRange(caret + tagstart.length, caret + tagstart.length);
        } else {
            el.createTextRange(caret, caret + tagstart.length + selected.length + tagend.length);
        }
		
    }
}

function doSmile(tag) {
formatText(document.getElementById('chatmsg'),''+tag+'','');
}
  </script>
  <div align="center">
  <table><tr><th><div align="center"><?=$lang["chat"]?></div></td></tr></table>
  </div>
  
  <div id="chatwindow" align="center">
  <table><tr>
          <td valign="top"><table><tr>
  
  <td valign="center" align="center" width="200px">Name:<br />
  <input <?=$disabled?> value="<?=$myname?>" id="chatnick" type="text" size="20" maxlength="20" >
  </td></tr>
  <tr><td align="center">
  Message:</td></tr>
  <tr><td align="center">
		<textarea id="chatmsg" name="chatmsg" style="width:170px;height:72px;" onkeyup="keyup(event.keyCode);"></textarea></td></tr>
  <?php if (file_exists('chat_func.php')) {?>
            <tr><td align="center">
			<table style="width:170px"><tr><td align="justify" style="padding:6px;">
			<a href="JavaScript:doSmile(':)')"><img src="smilies/smile.gif" border="0" title=":)" /></a>
            <a href="JavaScript:doSmile(';)')"><img src="smilies/wink.gif" border="0" title=";)" /></a>
			<a href="JavaScript:doSmile(':p')"><img src="smilies/razz.gif" border="0" title=":-p" /></a> 
			<a href="JavaScript:doSmile(':(')"><img src="smilies/sad.gif" border="0" title=":-(" /></a> 
			<a href="JavaScript:doSmile(':D')"><img src="smilies/D.gif" border="0" title=":-D" /></a> 
	<a href="JavaScript:doSmile(':cool:')"><img src="smilies/qliranje.gif" border="0" title=":qliranje:" /></a> 
	<a href="JavaScript:doSmile(':angry:')"><img src="smilies/twisted.gif" border= "0" title=":angry:" /></a>
	<a href="JavaScript:doSmile(':bravo:')"><img src="smilies/aplauz.gif" border="0" title=":bravo:" /></a> 
	<a href="JavaScript:doSmile(':gamer:')"><img src="smilies/gamer.gif" border="0" title=":gamer:" /></a> 
	<a href="JavaScript:doSmile(':rambo:')"><img src="smilies/rambo.gif" border="0" title=":rambo:" /></a>
    <a href="JavaScript:doSmile(':O')"><img src="smilies/eek.gif" border="0" title=":eek:" /></a> 
	<a href="JavaScript:doSmile(':love:')"><img src="smilies/wub.gif" border= "0" title=":love:" /></a>
	<a href="JavaScript:doSmile(':evil:')"><img src="smilies/evil.gif" border= "0" title=":evil:" /></a>
    <a href="JavaScript:doSmile(':heh:')"><img src="smilies/kreza.gif" border= "0" title=":heh:" /></a>
	<a href="JavaScript:doSmile(':rofl:')"><img src="smilies/biglaugh.gif" border= "0" title=":rofl:" /></a>
	<a href="JavaScript:doSmile(':unworthy:')"><img src="smilies/unworthy.gif" border= "0" title=":unworthy:" /></a>
	<a href="JavaScript:doSmile(':applause:')"><img src="smilies/aplauz.gif" border= "0" title=":applause:" /></a>
	<a href="JavaScript:doSmile(':zzz:')"><img src="smilies/zzz.gif" border= "0" title=":zzz:" /></a>
	</td></tr></table>
	</td></tr>
		<?php }?>
		<tr><td align="center" style="padding:8px;">
		<input class="menuButtons" type="button" value="Submit" onclick="submit_msg();disDelay(this);disDelay(chatmsg);" style="cursor:pointer;border:1px solid gray;">
		</td></tr>
		<tr><td><div id="info"></div>
		</td></tr>
		<? 
		if (isset($_SESSION["logged"]) AND isset($_SESSION['user_name']))
		{echo "<tr><td align='center'>$adm_message</td></tr>";
		if (isset($_SESSION["user_level"]) AND $_SESSION["user_level"]==1)
		echo "<tr><td align='center'><A href='chat.php?delete'>Delete all messages!</a></td></tr>";}
		?>
		</table>
  </td>
  <td>
  <div style="overflow:scroll;height:520px;width:800px;" id='divActivities2'></div>
  </td>
  </tr></table>
  
  </div>
  <br />
  <?php
  
  $pageContents = ob_get_contents();
  ob_end_clean();
  echo str_replace('<!--TITLE-->', $pageTitle, $pageContents);
  include('footer.php');
  ?>