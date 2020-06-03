<?php

//Takes care of security vulnerability
if(file_exists("../global.php")){ include_once("../global.php"); }

if($_SESSION['perm'] == '1')
{

?>

<script type="text/javascript">
function updateCategories()
{
	var orderlist = Sortable.serialize('editcats');
	gbxAjaxReq('', false, 'forms/ajax/ordercats.php', orderlist);

	return true;
}
</script>

<script type="text/javascript" language="JavaScript">
var rules=new Array();
rules[0]='newname|required|Please enter a category name.';
rules[1]='newname|maxlength|40|Category name must be less than 40 characters.';
</script>

<div class="headermid">

<div class="header">
  <span class="headerfont">Edit Categories</span>
</div>

<div class="content">

<div id="ajaxStatus"></div>
<div id="errorsDiv"></div>

<p><b>Create A New Category</b></p>
<div id="createcat" class="row">
  <div>
<form name="newcatform">
    <p>Name:<input type="text" class="textbox" size="50" id="newname"/>
    <button type="button" onClick="gbxCreateCategory();">Create Category</button></p>
</form>
  </div>
</div>

<div id="editcats" class="move">

<?php

    $boardquery = mysql_query("SELECT * FROM " . $prefix . "categories ORDER BY catorder") OR DIE("Gravity Board X was unable to retrieve the categories from the database: " . mysql_error());
    while($cat = mysql_fetch_assoc($boardquery))
    {

?>
<div class="board_row" id="row_<?php echo $cat['cat_id']; ?>">
  <div class="board">
      <input type="text" class="textbox" size="50" id="name_<?php echo $cat['cat_id']; ?>" value="<?php echo $cat['catname']; ?>">

      <button type="button" onClick="gbxEditCategory('<?php echo $cat['cat_id']; ?>');">Save Changes</button><button type="button" onClick="gbxDeleteCategory('<?php echo $cat['cat_id']; ?>');">Delete Category</button>
  </div>
</div>
<?php

    }

?>

</div>

<script type="text/javascript">
// <![CDATA[
Sortable.create('editcats', {tag:'div',"onUpdate":updateCategories});
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
