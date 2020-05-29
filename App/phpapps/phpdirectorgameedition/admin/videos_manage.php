	<?php
	ob_start(); 
	session_start();
	include("admin_header.php");
	$smarty->display('admin_header.tpl');
	
	if (checkLoggedin()){
	$query = "SELECT id, name, description FROM pp_files";
	$result = @mysql_query($query);
	 
	if ($result) {
	echo '<div align="center"><h1>All Videos</h1></div><table width="1364" border="0" align="center" font-style="bold">
  <tr>
    <td width="95"><strong>ID</strong></td>
    <td width="347"><strong>Title</strong></td>
    <td width="768"><strong>Description</strong></td>
    <td width="136"><strong>Edit</strong></td>
  </tr>

</table>';
	 
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	echo 


'
<table width="1364" border="0" align="center">
  <tr bgcolor="#D5F1C7">
    <td width="95">'.$row['id'].'</td>
    <td width="347">'.$row['name'].'</td>
    <td width="768">'.$row['description'].'</td>
    <td width="136"><a href="ve.php?id='.$row['id'].'">Edit</a></td>
  </tr>
<tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
'
;
	}
	} else {
	echo 'Sorry, but we could not retrieve any videos.';
	}
	
	
	}
	
	
	?> 
