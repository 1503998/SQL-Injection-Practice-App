<?php

//Takes care of security vulnerability
if(file_exists("../global.php")){ include_once("../global.php"); }

if($_SESSION['perm'] == '1')
{
    $cssfile = "skins/" . $currentskin . "/skin.css";
    $fp = fopen ($cssfile, "r");
    $csscontent = fread ($fp, filesize ($cssfile));
?>

<div class="headermid">

<div class="header">
  <span class="headerfont">Edit CSS Layout</span>
</div>

<div class="content">

<?php

if(isset($_POST['skintoapply']))
{
	mysql_query("UPDATE " . $prefix . "settings SET currentskin = '{$_POST['skintoapply']}'") OR DIE("Gravity Board X was unable to update the skin: " . mysql_error());
	echo '<font color="#FF0000"><b>Skin updated successfully.</b></font>';
}
?>

<form method="POST" action="<?PHP echo $PHP_SELF; ?>?action=editcss&mode=newskin">

<p align="center">Skin To Apply<br/><br/>
    
<?php

//READ SKIN DIRECTORIES
function dirTree($dir) {
   $d = dir($dir);
   while (false !== ($entry = $d->read())) {
       if($entry != '.' && $entry != '..' && is_dir($dir.$entry))
           $arDir[$entry] = dirTree($dir.$entry.'/');
   }
   $d->close();
   return $arDir;
}

function printTree($array, $level=0) {
   foreach($array as $key => $value) {
       echo '<option name="' . $key . '" value="' . $key . '">' . $key . '</option>';
       if(is_array($value))
           printTree($value, $level+1);
   }
}
    
?>

<select name="skintoapply" class="textbox">

<?php

//USAGE
$dir = "skins/";
$arDirTree = dirTree($dir);
printTree($arDirTree);

?>
</select>
<br/><br/>
<input type="submit" class=button value="Save Changes" name="btnsavechanges"></p>
    </form>

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
