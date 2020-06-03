<?php

//Takes care of security vulnerability
if(file_exists("../global.php")){ include_once("../global.php"); }

if($_SESSION['sr'] == '2')
{
##START BODY

if(isset($_POST['formsent']) && trim($_POST['subject']) != '' && trim($_POST['FCKeditorContents']) != '' && $_GET['action'] == 'editpost')
    {
	     if(!get_magic_quotes_gpc())
        {
	         $newmessage = addslashes($_POST['FCKeditorContents']);
	         $newsubject = addslashes($subject);
	     }else
        {
	         $newmessage = $_POST['FCKeditorContents'];
	         $newsubject = $_POST['subject'];
	     }
        echo '<tr><td>Information has been validated. Please wait...</td></tr>';
        header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/index.php?action=edit&thread_id=" . $thread_id . "&msg_id=" . $_POST['msg_id'] . "&board_id=" . $_POST['board_id'] . "&subject=" . $newsubject . "&message=" . $newmessage . "");
    }else
    {

?>

<script type="text/javascript" language="JavaScript">
var rules=new Array();
rules[0]='subject|required|Please enter a subject.';
rules[1]='subject|maxlength|100|Subject must be less than 100 characters.';
</script>

<div>

<div id="header_post" class="header">
  <span class="headerfont">

<?php

            if($_GET['action'] == 'postnew')
            {
                echo 'New Thread';
            }elseif($_GET['action'] == 'postreply')
            {
                echo 'Reply';
            }elseif($_GET['action'] == 'editpost')
            {
                echo 'Edit';
            }

?>
  </span>
</div>

<div id="errorsDiv"></div>

<table width="100%">
  <tr>
    <td width="100%">
<form name="post" method="POST" action="

<?php

        if($_GET['action'] == 'postnew')
        {
            echo 'index.php?action=postnewsubmit&amp;board_id=' , urlencode($_GET['board_id']) , '';
        }elseif($_GET['action'] == 'postreply')
        {
            echo 'index.php?action=postreplysubmit&amp;board_id=' , urlencode($_GET['board_id']) , '&amp;thread_id=' , urlencode($_GET['thread_id']) , '';
        }elseif($_GET['action'] == 'editpost')
        {
            echo '' . $_SERVER['PHP_SELF'] . '?action=edit&amp;board_id=' , urlencode($_GET['board_id']) , '&amp;subject=' , urlencode($subject) , '&amp;message=' , urlencode($_POST['FCKeditorContents']) , '&amp;thread_id=' , urlencode($thread_id) , '&amp;msg_id=' , urlencode($msg_id) , '';
        }
        
?>
" onsubmit="return performCheck('post', rules, 'innerHtml');">
<p>
<?php
        if(isset($_POST['formsent']) && trim($_POST['subject']) == '' && $_GET['action'] != 'postreply')
        {
            echo '<font color="#FF0000"><b>You must enter a subject</b></font>';
        }
        if($_GET['action'] == 'editpost')
        {
            $editquery = mysql_query("SELECT * FROM " . $prefix . "posts WHERE msg_id = '$msg_id'") OR DIE("Gravity Board X was unable to gather post information: " . mysql_error());
	         while($editinfo = mysql_fetch_assoc($editquery))
            {
	             $message = stripslashes($editinfo['message']);
	             $subject = $editinfo['subject'];
            }
        }

?>

</p>
<p align="left">
<?php

if($_GET['action'] == 'postnew')
{

?>
  Subject:<input class="textbox" name="subject" size="63" maxlength="100" style="float: left<?php if(isset($_POST['formsent']) && $_POST['subject'] == ''){ echo '; border: 2px solid #FF0000'; } ?>" value="<?php if(isset($_POST['subject'])){ echo $_POST['subject']; } if(isset($subject)){ echo $subject; } ?>">
  <br>
<?php

}

?>
  Message:<br>

<?php include("post/editor.php"); ?>
</p>
  <p align="center"><input type="hidden" name="board_id" value="<?php echo $_GET['board_id']; ?>"><button class="button" type="button" onClick="gbxReplySubmit('<?php echo $_GET['thread_id']; ?>', '<?php echo $_GET['board_id']; ?>');">Post</button>
  <button class="button" type="button" onClick="gbxReplyCancel();">Cancel</button></p>
</form></td>
  </tr>

</table>
</div>

<?php
    }
//END BODY
}else
{
    accessdenied();
}
?>
