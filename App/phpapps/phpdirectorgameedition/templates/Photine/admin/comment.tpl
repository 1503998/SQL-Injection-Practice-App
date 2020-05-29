{include file="admin_header.tpl"}
<center>
	<h3>{$LAN_65}</h3>
	

<table>
{section name=com loop=$com}<td width="579">
	{$com[com].comment}</td>
	<td width="242"><a href='comment.php?pt=comment&amp;pag=options&amp;del={$com[com].id}'>{$LAN_56}</a>
		</td></tr>
{/section}
</table>





</center>
<div class="clearer"> </div>
<br />