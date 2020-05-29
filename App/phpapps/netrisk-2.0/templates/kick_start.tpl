<!-- BEGIN: KICK -->
<div id="email_options">
	Start a vote to kick a player from the game?  
</div>
<br />
<div>
	{Message}
	<form action="./functions/function_kick_start.php" method="post">
	{StartSelect}		
		<!-- START BLOCK : Players -->		
			<option value="{PID}">{PName}</option>
		<!-- END BLOCK : Players -->
	{EndSelect}
	<br /><br />	
	{InputButton}
	</form>
	<br /><br />	
</div>
<!-- END: KICK -->