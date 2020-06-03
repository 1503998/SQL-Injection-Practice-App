<?php

//Takes care of security vulnerability
if(file_exists("../global.php")){ include_once("../global.php"); }

if($_SESSION['perm'] == '1')
{

?>

<script type="text/javascript" language="JavaScript">
var rules=new Array();
rules[0]='newname|required|Please enter a rank name.';
rules[1]='newname|maxlength|25|Category name must be less than 25 characters.';
rules[2]='newcolor|required|Please enter a rank color.';
rules[3]='newposts|required|Please enter the amount of posts needed.';
rules[4]='newposts|integer|Posts needed must be an integer.';
</script>

<div class="headermid">

<div class="header">
  <span class="headerfont">Edit Ranks</span>
</div>

<div class="content">

<div id="ajaxStatus"></div>
<div id="errorsDiv"></div>

<p>Create A New Rank</p>
<div>
<form name="newrankform">
    Name:<input type="text" class="textbox" size="25" id="newname"/>&nbsp;Color:<input type="text" class="textbox" size="25" id="newcolor"/>&nbsp;Posts Needed:<input type="text" class="textbox" size="25" id="newposts"/>
    <button type="button" class="button" onClick="gbxCreateRank();">Create Rank</button>
</form>
</div>

<table>

  <tr>
    <td width="20%">
      Name
    </td>
    <td width="20%">
      Color
    </td>
    <td width="20%">
      Posts Needed
    </td>
    <td width="20%">
      Preview
    </td>
    <td width="20%">
    </td>
  </tr>
</table>

<?php

$rankquery = mysql_query("SELECT * FROM " . $prefix . "ranks ORDER BY postsneeded") OR DIE("Gravity Board X was unable to retrieve the ranks from the database: " . mysql_error());
while($rank = mysql_fetch_assoc($rankquery))
{

?>

<div id="rank_<?php echo $rank['rankid']; ?>">
      <input class="textbox" type="text" id="rank_<?php echo $rank['rankid']; ?>" value="<?php echo $rank['rank']; ?>">
      <input class="textbox" type="text" id="color_<?php echo $rank['rankid']; ?>" value="<?php echo $rank['color']; ?>">
      <input class="textbox" type="text" id="posts_<?php echo $rank['rankid']; ?>" value="<?php echo $rank['postsneeded']; ?>">
      <b><span class="small" style="color: <?php echo $rank['color']; ?>;"><?php echo $rank['rank']; ?></span></b>
      <button type="button" class="button" onClick="gbxEditRank(<?php echo $rank['rankid']; ?>);">Save Changes</button><button type="button" class="button" onClick="gbxDeleteRank(<?php echo $rank['rankid']; ?>);">Delete</button>
  <input type="hidden" value="<?php echo $rank['rankid']; ?>" name="rankid">
  <input type="hidden" value="yes" name="formsent">
</div>

<?php

}

?>

</div>

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
