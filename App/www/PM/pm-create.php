<?PHP
/************************************************/
/** PHP Personal Message System - By El Diablo **/
/**	 			 	  New PM	 		 	   **/
/************************************************/

session_start();
session_checker();

	echo '<script language="JavaScript" type="text/javascript" src="wysiwyg.js"></script>
	<link rel="stylesheet" type="text/css" href="Themes/Default/styles.css">';
if ( isset($_POST['sendto']) ) {
	$sendto = $_POST['sendto'];
	$title = $_POST['title'];
	$message = $_POST['textarea'];
	$from = $_SESSION['user_name'];
	$id = $_SESSION['userid'];
	$sql = "INSERT INTO `eb_pm` ( `id` , `message` , `title` , `from` , `to` , `postdate` , `UserID` )
	VALUES (
	NULL , '$message', '$title', '$from', '$sendto', NOW( ), '$id'
	);";

	mysql_query("$sql");

	echo "<table width='100%'  border='0' cellspacing='0'>
 	       <tr>
	          <td class='eb_forum'>&nbsp;Create Personal Message</td>
	        </tr>
	        <tr>
  	        <td class='forum_footer'><div align='center'>Message has been sendt to $sendto. </div></td>
    	    </tr>
   	   </table>";
	exit();
}
echo '    <form action="' . $PHP_SELF . '" method="post">
	  <table width="100%" border="0" cellspacing="0">
        <tr>
          <td width="100%" class="eb_forum">&nbsp;<b>Create Personal Message</b></td>
        </tr>
        <tr>
          <td class="forum_footer"><table width="500%"  border="0" align="left" cellspacing="0">
            <tr>
              <td width="10%"><div align="right" class="eb_txt">Send too: </div></td>';
			  if ( $_GET['sendto'] ) {
			  $sendtoid = $_GET['sendto'];
			  $sql = "SELECT * FROM eb_members WHERE `userid` = '$sendtoid'";
			  $sqlid = mysql_query( $sql );
			  while ( $getname = mysql_fetch_object( $sqlid ) )
			  {
			  $sendtoid = $getname -> username;
			  }
             echo ' <td width="90%"><input name="sendto" type="text" class="eb_header" style="width: 100%;" value="' . $sendtoid .'"></td> ';
			 } elseif ( !$_GET['sendto'] ) {
			 echo ' <td width="90%"><input name="sendto" type="text" class="eb_header" style="width: 100%;"></td> ';
			 }
            echo' </tr>
            <tr>
              <td><div align="right" class="eb_txt">Title:</div></td>
              <td><input name="title" type="text" class="eb_header" style="width: 100%;"></td>
            </tr>
            <tr>
              <td><div align="right" class="eb_txt">Message:</div></td>
              <td><textarea name="textarea" id="post" rows="10" class="eb_header" style="width: 100%;"></textarea></td>
            </tr>
            <tr>
              <td colspan="2"> <div align="center"><input type="submit" name="Submit" value="Submit" class="eb_header"></div></td>
            </tr>

          </table>
         
           </td>
        </tr>
      </table></form>';
	  
	  # WYSIWYG ACTIVATOR #
	  echo '<script language="javascript1.2">
  generate_wysiwyg("post");
</script>';

?>
<? include("include/footer.php"); ?>