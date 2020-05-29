{include file="header.tpl"}
<div class="left">
<h4>{$LAN_116}</h4>
<table width="100%">
	<tr>
		<td align="center">
<form action="member.php" method="post">
	{$LAN_113} :<br />{$to}
	<input type="text" name="website" value="{$website}">
	<br />
	{$LAN_114} :<br /> <textarea name="about">{$about}</textarea><br /><br />
	<input type="submit" name="go" value="{$LAN_115}">
	</form>
		</td>
	</tr>
</table>
</div>

{include file="footer.tpl"}