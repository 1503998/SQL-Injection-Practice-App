{include file="header.tpl"}
<h4>{$LAN_106}</h4>
<div class="left">
{$message}
<table width="100%">
	<tr>
		<td align="center">
<form action="send.php" method="post">
	{$LAN_107} : <select name="destinataire">
	{section name=select loop=$select}
	<option value="{$select[select].id_dest}">{$select[select].user}</option>
	{/section}
	</select>
	<br />
	{$LAN_108} :<br /> <input type="text" name="titre" value=""><br />
	{$LAN_109} :<br /> <textarea name="message"></textarea><br /><br />
	<input type="submit" name="go" value="{$LAN_106}">
	</form>
		</td>
	</tr>
</table>
</div>

{include file="footer.tpl"}