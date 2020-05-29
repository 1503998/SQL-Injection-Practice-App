<!-- BEGIN: PREFERENCES -->
<div id="email_options">
	<th>Would you like to recieve emails on your turn?</th> <br/><br/>
</div>
<div>
	<form action="./functions/function_email_preference.php" method="post">	
 		<input type="radio" name="notify" value="1" {CheckYes} />     <-- Yes This Game<br />
 			<input type="radio" name="notify" value="0" {CheckNo} /> <--  No This Game<br /><br />
 			<input name="all_mail" type="checkbox" value="" /> <-- Update All Games?<br /><br />
 			<input type="submit" value="Update" class="button" />
	</form>
</div>
<!-- END: PREFERENCES -->