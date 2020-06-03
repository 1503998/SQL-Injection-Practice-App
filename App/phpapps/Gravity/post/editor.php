<?php

if($_GET['action'] == 'postreply' && isset($_GET['msg_id']))
{
    $quotequery = mysql_query("SELECT * FROM " . $prefix . "posts WHERE msg_id = '{$_GET['msg_id']}'") OR DIE("Gravity Board X was unable to gather quote information: " . mysql_error());

    $posterid = '';

    while($postinfo = mysql_fetch_assoc($quotequery))
    {
        $posterid = $postinfo['memberid'];
        $quote = $postinfo['message'];
	 }
    $namequery = mysql_query("SELECT displayname FROM " . $prefix . "members WHERE memberid = '$posterid'") OR DIE("Gravity Board X was unable to gather name information: " . mysql_error());
    list($poster) = mysql_fetch_row($namequery);
}



//TEMPORARY
if(!isset($message))
{
    $message = 'Enter your message here.';
}

if($_SESSION['messageeditor'] == 1)
{
    //INITIATE FCKeditor
    include("./post/FCKeditor/fckeditor.php");

    $sBasePath = './post/FCKeditor/';

    $oFCKeditor = new FCKeditor('FCKeditorContents') ;
    $oFCKeditor->BasePath	= $sBasePath ;
    $oFCKeditor->Value		= $message;
    $oFCKeditor->Create() ;
}else
{
    echo '<textarea name="FCKeditorContents" cols="60" rows="10"></textarea>';
}

?>
