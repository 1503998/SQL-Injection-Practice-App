<?php

//Takes care of security vulnerability
if(file_exists("../global.php")){ include_once("../global.php"); }

if($_SESSION['perm'] == '1')
{

?>

<script type="text/javascript" language="JavaScript">
var rules=new Array();
rules[0]='group_name|required|Please enter a group name.';
rules[1]='group_name|maxlength|20|Group name must be less than 20 characters.';
rules[1]='group_type|required|Please select a group type.';
</script>

<div class="headermid">

<div class="header">
  <span class="headerfont">Edit Member Groups</span>
</div>

<div class="content">

<div id="ajaxStatus"></div>
<div id="errorsDiv"></div>

  <p><b>Create New Member Group</b></p>

      <form name="mgform">
      <p align="left">Member Group Name</p>
      <p align="left"><input type="text" class="textbox" size="40" name="group_name" maxlength="20"></p>
      <p align="left">Group Permissions</p>
      <p align="left"><input type="radio" name="group_type" value="0"> Regular</p>
      <p align="left"><input type="radio" name="group_type" value="2"> Moderator</p>
      <p align="left"><input type="radio" name="group_type" value="1"> Administrator</p>
      <p align="left"><button type="button" class="button" onClick="">Create Member Group</button></p>
      </form>
  
<?php

    $mgquery = mysql_query("SELECT * FROM " . $prefix . "membergroups ORDER BY group_id") OR DIE("Gravity Board X was unable to retrieve the member groups from the database: " . mysql_error());
	 while($mgi = mysql_fetch_assoc($mgquery))
    {

?>

      <p align="left"><input type="text" class="textbox" size="25" name="group_name" value="<?php echo $mgi['group_name']; ?>">

      <select name="group_type" class="textbox">
      <option value="0"<?php if($mgi['group_type'] == '0'){ echo ' selected'; } ?>>Normal</option>
      <option value="2"<?php if($mgi['group_type'] == '2'){ echo ' selected'; } ?>>Moderator</option>
      <option value="1"<?php if($mgi['group_type'] == '1'){ echo ' selected'; } ?>>Administrator</option>
      </select>

      <input type="submit" value="Save Changes To <?php echo $mgi['group_name']; ?>" class="button"></p>

<input type="hidden" name="group_id" value="<?php echo $mgi['group_id']; ?>">

<?php

}

?>

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
