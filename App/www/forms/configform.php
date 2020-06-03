<?php

//Takes care of security vulnerability
if(file_exists("../global.php")){ include_once("../global.php"); }

if($_SESSION['perm'] == '1')
{

?>

<script type="text/javascript" language="JavaScript">
var rules=new Array();
rules[0]='boardname|required|Please enter a board name.';
rules[1]='boardname|maxlength|40|Board name must be less than 40 characters.';
rules[2]='hostname|required|Please enter your host name.';
rules[3]='username|required|Please enter your MySQL username.';
rules[4]='password|required|Please enter your MySQL password.';
rules[5]='passwordconfirm|required|Please confirm your MySQL password.';
rules[6]='password|equal|$passwordconfirm|Your passwords do not match.';
rules[7]='dbname|required|Please enter your MySQL database name.';
</script>

<div class="headermid">

<div class="header">
  <span class="headerfont">Board Configuration</span>
</div>

<div class="content">

<div id="ajaxStatus"></div>
<div id="errorsDiv"></div>

<table>
<form name="configform">
  <tr>
    <td width="25%"
    </td>
    <td width="25%">
      <p align="center">Board Name
    </td>
    <td width="25%">
      <p align="center"><input type="text" class="textbox" id="boardname" name="boardname" size="30" value="<?php echo $boardname; ?>">
    </td>
    <td width="25%">
    </td>
  </tr>
  <tr>
    <td width="25%"
    </td>
    <td width="25%">
      <p align="center">Host Name
    </td>
    <td width="25%">
      <p align="center"><input type="text" class="textbox" id="hostname" name="hostname" size="30" value="<?php echo $hostname; ?>">
    </td>
    <td width="25%">
    </td>
  </tr>
  <tr>
    <td width="25%"
    </td>
    <td width="25%">
      <p align="center">MySQL Username
    </td>
    <td width="25%">
      <p align="center"><input type="text" class="textbox" id="username" name="username" size="30" value="<?php echo $username; ?>">
    </td>
    <td width="25%">
    </td>
  </tr>
  <tr>
    <td width="25%"
    </td>
    <td width="25%">
      <p align="center">MySQL Password
    </td>
    <td width="25%">
      <p align="center"><input type="password" class="textbox" id="password" name="password" size="30" value="<?php echo $password; ?>">
    </td>
    <td width="25%">
    </td>
  </tr>
  <tr>
    <td width="25%"
    </td>
    <td width="25%">
      <p align="center">Confirm MySQL Password
    </td>
    <td width="25%">
      <p align="center"><input type="password" class="textbox" id="passwordconfirm" name="passwordconfirm" size="30" value="<?php echo $password; ?>">
    </td>
    <td width="25%">
    </td>
  </tr>
  <tr>
    <td width="25%"
    </td>
    <td width="25%">
      <p align="center">MySQL Database Name
    </td>
    <td width="25%">
      <p align="center"><input type="text" class="textbox" id="dbname" name="dbname" size="30" value="<?php echo $dbname; ?>">
    </td>
    <td width="25%">
    </td>
  </tr>
  <tr>
    <td width="25%"
    </td>
    <td width="25%">
    </td>
    <td width="25%">
      <p align="center"><input type="hidden" id="prefix" name="prefix" value="<?php echo $prefix; ?>"><button type="button" class="button" onClick="gbxAjaxReq('configform', true, 'forms/ajax/configure.php', 'boardname=' + document.getElementById('boardname').value + '&hostname=' + document.getElementById('hostname').value + '&username=' + document.getElementById('username').value + '&password=' + document.getElementById('password').value + '&passwordconfirm=' + document.getElementById('passwordconfirm').value + '&dbname=' + document.getElementById('dbname').value + '&prefix=' + document.getElementById('prefix').value);">Save Changes</button>
    </td>
    <td width="25%">
    </td>
  </tr>
</form>
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
