{include file="header.tpl"}
<div class="left">
{$LAN_110}<br />
<br />
{$message}
<table width="100%" style="border:1px solid blue;">
<tr>
<td width="20%" bgcolor="#CCCCCC" style="border:1px solid blue;"><b>&nbsp;date/time</b></td>
<td width="60%" bgcolor="#CCCCCC" style="border:1px solid blue;" ><b>&nbsp;Title</b></td>
<td width="20%" bgcolor="#CCCCCC" style="border:1px solid blue;"><b>&nbsp;From</b></td>
</tr>
{section name=select loop=$read}
<tr>
<td>{$read[select].date}</td>
<td><a href="read.php?id_message={$read[select].id}">{$read[select].titre}</a></td>
<td>{$read[select].sender}</td>
</tr>
	{/section}
</table>	
<table width="100%"><tr><td bgcolor="#CCCCCC" style="border:1px solid blue;"><a href="send.php">{$LAN_106}</a></td></tr></table>
</div>	
{include file="footer.tpl"}