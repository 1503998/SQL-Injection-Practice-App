<!-- BEGIN: KICK -->
<div id="email_options">
	A vote has been started to kick {PName}  
</div>
<br />
<div>
	{Message}
	<form action="./functions/function_kick_vote.php" method="post">
	Would You like to Kick this Player?<br />
	<input name="kickpid" type="hidden" value="{PID}"/></p>
	<input type="radio" name="kickvote" value="1" > <--  Yes<br />
	<input type="radio" name="kickvote" value="0" checked> <--  No <br /><br />
 	<input type="submit" value="Vote" class="button" />
	</form>
	<br />
</div>
<!-- END: KICK -->