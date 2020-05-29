<script type="text/javascript">
function IsNumeric(strString)
   {
   var strValidChars = "0123456789";
   var strChar;
   var blnResult = true;
   if (strString.length == 0) return false;
   for (i = 0; i < strString.length && blnResult == true; i++)
      {
      strChar = strString.charAt(i);
      if (strValidChars.indexOf(strChar) == -1)
         {
         blnResult = false;
         }
      }
   return blnResult;
   }
function checkform(form)
{
   if (form.armies.value.length == 0) 
      {
      alert("You can't add zero armies, please go back.");
			return false ;
      } 
   else if (IsNumeric(form.armies.value) == false) 
      {
      alert("Sorry, decimals are not allowed. Please go back and try again.");
			return false ;
      }
		return true;
	}
</script>
<script type="text/javascript">
	{JScriptPlace}
</script>
<!-- BEGIN: PLACING -->
{Message}
<br /> 
<hr />
<form action="functions/function_addarms.php" method="post" name="addarmy" id="addarmy" onsubmit="return checkform(this);">
Armies:
	<select name="armies">
		<!-- START BLOCK : armies -->
				<option value="{army}">{army}</option>
		<!-- END BLOCK : armies --> 
	</select> 
	<input name="GID" value="{GID}" type="hidden" /> 
	<br /><br />
	<div class="left">Cntry:</div>
	<select name="state" class="button_grey">
		<option value="invalid">Country:</option>
		<!-- START BLOCK : states -->
		<option value="{PTID}" {Selected}>{Country}</option>
		<!-- END BLOCK : states -->
	</select>
	<br /><br />
	<div class="right"><input type="submit" value="Place Army" class="button_grey" /></div>
	<br /><br />
</form>

<!-- END: PLACING -->


