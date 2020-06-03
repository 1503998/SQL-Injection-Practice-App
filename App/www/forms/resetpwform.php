<script type="text/javascript" language="JavaScript">
var rules=new Array();
rules[0]='emailreset|required|Please enter an email address.';
rules[1]='emailreset|email|Please enter a valid email address.';
</script>

<div class="headermid">

  <div class="header">
    <span class="headerfont">Reset Your Password</span>
  </div>

<div class="content">

<div id="errorsDiv"></div>
  <p align="left">For your security, your password may not be recovered. To reset your password, enter your email address below. An email will then be sent to you with a link to reset your password.</p>
  <form method="POST" action="index.php?action=resetpwemail" name="resetform" onsubmit="return performCheck('resetform', rules, 'innerHtml');">
    Enter your email address: <input type="text" class="textbox" name="emailreset" size="40">
    <br><br>
    <input type="submit" name="submit" class="button" value="Send reset email">
  </form>

</div>

<div class="headerbot">
</div>

</div>