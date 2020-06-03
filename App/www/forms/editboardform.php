<?php

//Takes care of security vulnerability
if(file_exists("../global.php")){ include_once("../global.php"); }

if($_SESSION['perm'] == '1')
{

	global $gbxCategories;

	$catquery = mysql_query("SELECT * FROM " . $prefix . "categories ORDER BY catorder") OR DIE("Gravity Board X was unable to retrieve the information requested: " . mysql_error());
        while($cat = mysql_fetch_assoc($catquery))
        {
		$gbxCategories = $gbxCategories . '<option value="' . $cat['cat_id'] . '">' . stripslashes($cat['catname']) . '</option>';
        }

?>

<script type="text/javascript">
function updateBoards()
{
	var orderlist = Sortable.serialize('editboards');
	gbxAjaxReq('', false, 'forms/ajax/orderboards.php', orderlist);

	return true;
}
</script>

<script type="text/javascript" language="JavaScript">
var rules=new Array();
rules[0]='newname|required|Please enter a board name.';
rules[1]='newname|maxlength|40|Board name must be less than 40 characters.';
rules[2]='newdescription|maxlength|250|Board description must be less than 250 characters.';

gbxCategories = '<?php echo $gbxCategories; ?>';
</script>

<div class="headermid">

<div class="header">
  <span class="headerfont">Edit Boards</span>
</div>

<div class="content">

<div id="ajaxStatus"></div>
<div id="errorsDiv"></div>

<p><b>Create A New Board</b></p>
<div id="createboard" class="row">
  <div>
<form name="newboardform">
    <p>Name:<input type="text" class="textbox" size="25" id="newname"/>
    Category:<select id="new_cat_id" class="textbox">
<?php

        $catquery = mysql_query("SELECT * FROM " . $prefix . "categories ORDER BY catorder") OR DIE("Gravity Board X was unable to retrieve the information requested: " . mysql_error());
        while($cat = mysql_fetch_assoc($catquery))
        {

?>

<option value="<?php echo $cat['cat_id']; ?>"><?php echo stripslashes($cat['catname']); ?></option>

<?php

        }

?>
</select>
    <br/>Description:<input type="text" class="textbox" size="40" id="newdescription"/>
    <button type="button" onClick="gbxCreateBoard();">Create Board</button></p>
</form>
  </div>
</div>

<div id="editboards" class="move">
  
<?php

    $boardquery = mysql_query("SELECT * FROM " . $prefix . "boards ORDER BY cat_id, boardorder") OR DIE("Gravity Board X was unable to retrieve the boards from the database: " . mysql_error());
    while($bi = mysql_fetch_assoc($boardquery))
    {

?>

<div class="board_row" id="row_<?php echo $bi['board_id']; ?>">
<div class="board" id="board_<?php echo $bi['board_id']; ?>">
      <input type="text" class="textbox" size="25" id="name_<?php echo $bi['board_id']; ?>" value="<?php echo stripslashes($bi['name']); ?>"/>
<select id="cat_id_<?php echo $bi['board_id']; ?>" class="textbox">

<?php

        $catquery = mysql_query("SELECT * FROM " . $prefix . "categories ORDER BY catorder") OR DIE("Gravity Board X was unable to retrieve the information requested: " . mysql_error());
        while($cat = mysql_fetch_assoc($catquery))
        {

?>

<option value="<?php echo $cat['cat_id']; ?>"<?php if($bi['cat_id'] == $cat['cat_id']){ echo ' selected'; } ?>><?php echo stripslashes($cat['catname']); ?></option>

<?php

        }

?>

</select>
      <input type="text" class="textbox" size="40" id="description_<?php echo $bi['board_id']; ?>" value="<?php echo stripslashes($bi['description']); ?>">
      <button type="button" onClick="gbxEditBoard('<?php echo $bi['board_id']; ?>');">Save Changes</button><button type="button" onClick="gbxDeleteBoard('<?php echo $bi['board_id']; ?>');">Delete Board</button>
</div>
</div><br/>

<?php

    }

?>

</div>

<script type="text/javascript">
// <![CDATA[
Sortable.create('editboards', {tag:'div',"onUpdate":updateBoards});
// ]]>
</script>

</div>

<div class="row">
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
