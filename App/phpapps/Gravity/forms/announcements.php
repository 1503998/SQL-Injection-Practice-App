<?php

//Takes care of security vulnerability
if(file_exists("../global.php")){ include_once("../global.php"); }

if($_SESSION['perm'] == 1)
{

?>

<div class="headermid">

<div id="header_yourinfo" class="header">
  <span class="headerfont">Edit Announcements</span>
</div>

<div class="content">

<div id="ajaxStatus"></div>
<div id="errorsDiv"></div>

<table>
  
<?php

    if(!isset($_POST['board']))
    {

?>
<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=announcements">
  <tr>
    <td>
	   Select A Board:<br/>
	   <select name="board" class="textbox">
	   <option value="0">All Boards</option>
<?php

        $boardquery = mysql_query("SELECT * FROM " . $prefix . "boards") OR DIE("Gravity Board X was unable to retrieve board information from the database: " . mysql_error());
	while($boardinfo = mysql_fetch_assoc($boardquery))
        {
?>

	<option value="<?php echo $boardinfo['board_id']; ?>"><?php echo $boardinfo['name']; ?></option>

<?php

        }
    
?>

      </select>
	   <br/><br/>
	   <input type="submit" class="button" value="Edit Announcements">
    </td>
  </tr>
</form>

<?php

    }else
    {
        if($_POST['board'] == 0)
        {
            $name = 'All Boards';
        }else
        {
            $getnamequery = mysql_query("SELECT name FROM " . $prefix . "boards WHERE board_id = '{$_POST['board']}'") OR DIE("Gravity Board X was unable to retrieve the requested board information: " . mysql_error());
            list($name) = mysql_fetch_row($getnamequery);
        }
        $aquery = mysql_query("SELECT * FROM " . $prefix . "announcements WHERE board_id = '{$_POST['board']}'")OR DIE("Gravity Board X was unable to retrieve the requested announcement information: " . mysql_error());
	while($info = mysql_fetch_assoc($aquery))
        {
            if($info['enabled'] == 0)
            {
                $checked = '';
            }else
            {
                $checked = 'checked';
            }
            if($info['board_id'] == 0)
            {
                $id = 'All';
            }else
            {
                $id = $info['board_id'];
            }

?>

  <tr>
    <td>
	   <b>Board:</b> <?php echo $name; ?>
	   <br/>
	   <b>Board ID #:</b> <?php echo $id; ?>
    </td>
  </tr>
  <tr>
    <td>
	   <input type="checkbox" id="enabled" <?php echo $checked; ?> value="checked"> Enable Announcements
    </td>
  </tr>
  <tr>
    <td>
	   <textarea id="announcements" class="textbox" rows="10" cols="75"><?php echo $info['text']; ?></textarea>
	   <br/>
	   <button type="button" class="button" onClick="gbxAjaxReq('', false, 'forms/ajax/announce.php', 'announcements=' + document.getElementById('announcements').value + '&enabled=' + document.getElementById('enabled').checked);">Save Changes</button>
    </td>
  </tr>

<?php

        }
    }

?>

</table>

</div>

<div class="headerbot">
</div>

</div>

<?php

}else
{
    accessdenied();
}
?>
