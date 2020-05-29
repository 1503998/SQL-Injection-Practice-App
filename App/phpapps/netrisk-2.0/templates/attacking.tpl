<!-- BEGIN: ATTACKING -->
<script type="text/javascript">
<!--
function go_there()
{
 var where_to= confirm("Are you sure you want to stop attacking??");
 if (where_to== true)
 {
   window.location="./functions/function_nextstatus.php";
 }
 
}
//-->
</script>
<script type="text/javascript">
	{JScriptAttack}
</script>
<hr />
<form action="./functions/function_attack.php" method="post" name="attacking" id="attacking">
	Armies:
			<select name="armies" size="1">
				<option value="0">0</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3" selected="selected">3</option>
			</select>
			<br /><br />
			<select id="fromstate" name="fromstate" class="button_grey">
				<option value="invalid">Attack From:</option>
				<!-- START BLOCK : player -->
				<option value="{SelectFrom},{Attackable},{FromTID}" {fromselected}>{FromCountry}</option>
				<!-- END BLOCK : player -->
			</select>
			<input name="GID" value="{GID}" type="hidden" /> 
			<select id="tostate" name="tostate" class="button_grey">
				<option value="invalid">To From:</option>
				<!-- START BLOCK : opponent -->
				<option value="{SelectTo},{Defendable},{ToTID}" {toselected}>{ToCountry}</option>
				<!-- END BLOCK : opponent -->
			</select>
	<br /><br />
	<div class="right"><input type="submit" name="submit" value="Attack Now!" class="button_grey" /></div>
	<br /><br />
</form>
<hr />
Next Action...<br /><br/>

<input type="button" class="button_red" value="Fortify >>" onClick="go_there()" />

<!-- END: ATTACKING -->