<?php

//Takes care of security vulnerability
if(file_exists("../global.php")){ include_once("../global.php"); }

if($_SESSION['perm'] == '1')
{

?>

<div class="headermid">

<div id="header_yourinfo" class="header">
  <span class="headerfont">Board Settings</span>
</div>

<div class="content">

<div id="ajaxStatus"></div>
<div id="errorsDiv"></div>

<table>
  
<?php

$setquery = mysql_query("SELECT * FROM " . $prefix . "settings") OR DIE("Gravity Board X was unable to read the board settings from the database: " . mysql_error());
while($set = mysql_fetch_assoc($setquery))
{
    if($set['debugon'] == 1){ $debugon = 'checked'; }else{ $debugon = ''; }
?>

<form name="settingsform">
  <tr>
    <td>
      <p align="left">User Agreement</p>
    </td>
    <td>
      <p align="left"><textarea id="useragreement" class="textbox" rows="15" cols="75"><?php echo $set['useragreement']; ?></textarea></p>
    </td>
  </tr>
  <tr>
    <td>
      <p align="left">Welcome Email Message</p>
    </td>
    <td>
      <p align="left"><textarea id="regemail" class="textbox" rows="5" cols="75"><?php echo $set['regemail']; ?></textarea></p>
    </td>
  </tr>
  <tr>
    <td width="30%">
      <p align="left">Administrator Name Color</p>
    </td>
    <td width="70%">
      <p align="left"><input type="text" class="textbox" id="admincolorform" value="<?php echo $set['admincolor']; ?>"></p>
    </td>
  </tr>
  <tr>
    <td>
      <p align="left">Moderator Name Color</p>
    </td>
    <td>
      <p align="left"><input type="text" class="textbox" id="modcolorform" value="<?php echo $set['modcolor']; ?>"></p>
    </td>
  </tr>
  <tr>
    <td>
      <p align="left">Maximum User Icon Width</p>
    </td>
    <td>
      <p align="left"><input type="text" class="textbox" id="maxiconwidth" value="<?php echo $set['maxiconwidth']; ?>"></p>
    </td>
  </tr>
  <tr>
    <td>
      <p align="left">Maximum User Icon Height</p>
    </td>
    <td>
      <p align="left"><input type="text" class="textbox" id="maxiconheight" value="<?php echo $set['maxiconheight']; ?>"></p>
    </td>
  </tr>
  <tr>
    <td>
      <p align="left">Enable Debugger</p>
    </td>
    <td>
      <p align="left"><input type="checkbox" id="debugon" <?php echo $debugon; ?>></p>
    </td>
  </tr>
  <tr>
    <td>
      <p align="left"><button type="button" class="button" name="submit" onClick="gbxAjaxReq('settingsform', false, 'forms/ajax/boardsettings.php', 'useragreement=' + document.getElementById('useragreement').value + '&regemail=' + document.getElementById('regemail').value + '&admincolorform=' + document.getElementById('admincolorform').value + '&modcolorform=' + document.getElementById('modcolorform').value + '&maxiconwidth=' + document.getElementById('maxiconwidth').value + '&maxiconheight=' + document.getElementById('maxiconheight').value + '&debugon=' + document.getElementById('debugon').checked);">Save Changes</button></p>
</form>

<?php

}

?>

    </td>
  </tr>
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

