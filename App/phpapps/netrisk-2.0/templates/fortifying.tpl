<!-- BEGIN: FORTIFYING -->
<script type="text/javascript">
	{JScriptFortify}
</script>
<table>
	<tr>
		<td>{Message}</td>
	</tr>
</table>
<hr />
	<form action="./functions/function_fortify.php" method="post" name="fortify" id="fortify">
		<div class="left">Armies:</div>
		<input class="button_grey" name="armies" type="text" size="1" />
		<br /> 
		<div class="left">From:</div>
		<select name="fromstate" class="button_grey right">
				<option value="invalid">Country</option>
				<!-- START BLOCK : fromstate -->
				<option value="{TID}">{Country}</option>
				<!-- END BLOCK : fromstate -->
			</select>
		<br />
		<div class="left">To:</div>
		<select name="tostate" class="button_grey right">
				<option value="invalid">Country</option>
				<!-- START BLOCK : tostate -->
				<option value="{TID}">{Country}</option>
				<!-- END BLOCK : tostate -->
		</select>
		<br /><br />		
		<div class="right"><input type="submit" value="Fortify" class="button_grey" /></div>
</form>
<br /><br />
<hr />
Next action...<br /><br/>
<a href="./functions/function_nextstatus.php" class="button_red">End Turn >> </a>
<br /><br />

<!-- END: FORTIFYING -->