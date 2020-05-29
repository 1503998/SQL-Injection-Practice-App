<?php
	
	/*
	This file is php so that admins can control its look without editing the code.
	The headers below tell the browser to cache the file and also tell the browser it is css.
	*/
	
	header("Cache-Control: must-revalidate");
	$offset = 60*60*24*60;
	$ExpStr = "Expires: ".gmdate("D, d M Y H:i:s",time() + $offset)." GMT";
	header($ExpStr);
	header('Content-Type: text/css');	
?>

/*
This file controls the look of the Live shoutbox...
*/

#chatoutput {
height: 250px;
width: 100%;
font: 11px helvetica, arial, sans-serif;
color: white;
background: black;
overflow: auto;
margin-top: 2px;
}

#lastMessage {
padding-bottom: 2px;
text-align: center;
color: #ffffff;
background: #000000;
border-bottom: 2px dotted #ffffff;
}

#responseTime {
font-style: normal;
}

#chatoutput ul#outputList li {
padding: 4px;
margin: 0;
color: #ffffff;
background: none;
font-size: 12px;
}

.chat_user{
color: yellow;
font-weight: bold;
}

.chat_time{
color: red;
font-weight: bold;
font-style: italic;
}

#chatForm input, #chatForm textarea {
width: 110px;
display: block;
margin: 0 auto;
}

#chatForm textarea {
width: 95%;
}


#chatForm input#submitchat {
width: 20%;
margin: 10px auto;
border: 2px outset;
padding: 2px;
}