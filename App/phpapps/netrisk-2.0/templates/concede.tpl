<!-- BEGIN: CONCEDE -->
<div id="concede">
	<div>
		You may only concede during another players turn
		<br/ ><br />
		Are you sure you wish to concede?
	</div>
	<form action="./functions/function_game_concede.php" method="post">
		<input name="concede" type="checkbox" value="" /> <- Verify.
		<br/ ><br />
		<input type="submit" value="Concede ?" class="button" onClick="return confirm('Are you sure wish to concede the game?')" />
	</form>
</div>
<!-- END: CONCEDE -->