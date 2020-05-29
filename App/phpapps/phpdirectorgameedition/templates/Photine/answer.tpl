{include file="header.tpl"}
<div class="left">
<h4>{$LAN_106}</h4>
{$message}
<table width="100%">
	<tr>
		<td align="center">
<form action="answer.php" method="post">
	{$LAN_107} :<br />{$to}
	<input type="hidden" name="destinataire" value="{$id}">
	<br />
	{$LAN_108} :<br /> <input type="text" name="titre" value="re : {$title}"><br />
	{$LAN_109} :<br /> <textarea name="message"></textarea><br /><br />
	<input type="submit" name="go" value="{$LAN_106}">
	</form>
		</td>
	</tr>
</table>
</div>

{include file="footer.tpl"}