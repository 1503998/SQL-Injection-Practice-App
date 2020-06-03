<?
session_start();
 include_once("include/header.php"); ?>
<br>
<table width="43%" height="109"  border="0" align="center" cellspacing="0">
  <tr>
    <td class="eb_menu1"><div align="center">Login</div></td>
  </tr>
  <tr>
    <td class="forum_footer" style="color: #FF0000"><div align="center">
      <form name="form1" method="post" action="checkuser.php">
        Please Login. <br>
        <table width="100%"  border="0" cellspacing="0">
          <tr>
            <td width="33%" class="eb_txt" style="color: #FFFFFF"><div align="right">Username:</div></td>
            <td width="67%"><input name="username" type="text" class="eb_header" id="username" style="width: 60%"></td>
          </tr>
          <tr>
            <td class="eb_txt" style="color: #FFFFFF"><div align="right">Password:</div></td>
            <td><input name="password" type="password" class="eb_header" id="password" style="width: 60%"></td>
          </tr>
          <tr>
            <td colspan="2"><div align="center"><br>
              <input name="Submit" type="submit" class="eb_header" value="Submit">
            </div></td>
          </tr>
        </table>
      </form></div>
</td>
  </tr>
</table><br>
<? include("include/footer.php"); ?>