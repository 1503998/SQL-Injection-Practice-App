<?php
session_start();
 include_once("include/header.php"); ?>
<br>
<form name="form1" method="post" action="register_2.php">
  <table width="66%" border="0" align="center" cellpadding="4" cellspacing="0">
    <tr> 
      <td height="1" align="left" valign="top" class="eb_menu1"><div align="center">Register</div></td>
    </tr>
    <tr>
      <td align="left" valign="top" class="eb_showforum_lower_left"><div align="right"></div>        <div align="right">
        <table width="102%"  border="0" cellspacing="0">
          <tr>
            <td width="40%" class="eb_txt"><div align="right" style="color: #FFFFFF">Username:</div></td>
            <td width="60%">
              <input name="username" type="text" class="eb_header" id="username3" value="<? echo $username; ?>" style="width: 200px;">
            </td>
          </tr>
          <tr>
            <td class="eb_txt"><div align="right" style="color: #FFFFFF">Password:</div></td>
            <td>
              <input name="password" type="text" class="eb_header" id="username4" value="<? echo $password; ?>" style="width: 200px;">
            </td>
          </tr>
          <tr>
            <td class="eb_txt"><div align="right" style="color: #FFFFFF">First Name:</div></td>
            <td>
              <input name="first_name" type="text" class="eb_header" id="first_name5" value="<? echo $first_name; ?>" style="width: 200px;">
            </td>
          </tr>
          <tr>
            <td class="eb_txt"><div align="right" style="color: #FFFFFF">Last Name:</div></td>
            <td>
              <input name="last_name" type="text" class="eb_header" id="last_name6" value="<? echo $last_name; ?>" style="width: 200px;">
            </td>
          </tr>
          <tr>
            <td class="eb_txt"><div align="right" style="color: #FFFFFF">Email Address:</div></td>
            <td>
              <input name="email_address" type="text" class="eb_header" id="email_address4" value="<? echo $email_address; ?>" style="width: 200px;">
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>
              <input name="Submit" type="submit" class="eb_header" value="Join Now!">
            </td>
          </tr>
        </table>
      </div>        <div align="right"></div>        </td>
    </tr>
  </table>
  </form>
<? include("include/footer.php"); ?>