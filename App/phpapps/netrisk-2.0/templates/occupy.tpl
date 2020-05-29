<!-- BEGIN: OCCUPY -->
You have conquered {to_country}<br />
Available armies: {maximum}<br />
Must Transfer: {minimum}<br />

<br />
<form action="./functions/function_occupy.php" method="post" name="statusaction">
	<div class="left">Armies:</div>
	<select name="armies">
		<!-- START BLOCK : armies -->
				<option value="{army}">{army}</option>
		<!-- END BLOCK : armies --> 
	</select>
	<input type="hidden" name="from_id" value="{from_id}" />
	<input type="hidden" name="to_id" value="{to_id}" />	
	<input type="hidden" name="min_occupy" value="{minimum}" />
	<input type="hidden" name="max_occupy" value="{maximum}" />	
	<br /><br />
	<div class="left"><input type="submit" value="Occupy" class="button_grey" /></div>
</form>
<br /><br />
<form action="./functions/function_occupy.php" method="post" name="statusaction"> 
	<input type="hidden" name="armies" value="{maximum}" />
	<input type="hidden" name="from_id" value="{from_id}" />
	<input type="hidden" name="to_id" value="{to_id}" />
	<input type="hidden" name="min_occupy" value="{minimum}" />
	<input type="hidden" name="max_occupy" value="{maximum}" />
<hr /><br />
	<input type="submit" value="Move All ({maximum})" class="button_grey" />
</form>
<!-- END: OCCUPY -->


