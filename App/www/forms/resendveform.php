<script type="text/javascript" language="JavaScript">
var rules=new Array();
rules[0]='validationemail|required|Please enter an email address.';
rules[1]='validationemail|email|Please enter a valid email address.';
</script>

<div class="headermid">

  <div class="header">
    <span class="headerfont">Resend Validation Email</span>
  </div>

<div class="content">

  <p align="left">If you have lost or did not receive your validation email, enter your email address below and it will be resent.</p>
  <form name="valform" method="POST" action="index.php?action=resendvalemail" onsubmit="return performCheck('valform', rules, 'innerHtml');">
    Enter your email address: <input type="text" class="textbox" name="validationemail" size="40"/>
    <br><br>
    <input type="submit" name="submit" class="button" value="Resend Email"/>
  </form>
<div id="errorsDiv"></div>

</div>

<div class="headerbot">
</div>

</div>