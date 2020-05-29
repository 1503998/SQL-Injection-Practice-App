{include file="header.tpl"}
<div class="left">
{$message}
{$date} - {$title} [from : {$from}]
<br />
{$mess}
<table width="100">
<tr><td valign="top"><form method="GET" action="delmessage.php">
<input type="hidden" name="id_message" value="{$messid}">
<input type="submit" value="{$LAN_56}">
</form></td>
<td valign="top">
<form method="POST" action="answer.php">
<input type="hidden" name="from" value="{$from}">
<input type="hidden" name="title" value="{$title}">
<input type="hidden" name="idfrom" value="{$idfrom}">
<input type="submit" value="{$LAN_112}">
</form>
</td>
</tr>
</table>
</div>
{include file="footer.tpl"}