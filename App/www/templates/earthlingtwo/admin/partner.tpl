{include file="admin_header.tpl"}
<center>
	<h3>{$LAN_65}</h3>
	

<table>
{section name=partner loop=$partner}<td width="579">
	<a href="{$partner[partner].url}" target="_blank">{$partner[partner].title}</a> {$LAN_87} : {$partner[partner].status}</td>
	<td width="242">
<a href='partner.php?pt=partner&amp;pag=options&amp;del={$partner[partner].id}'>{$LAN_56}</a> - <a href='partner.php?pt=partner&amp;pag=options&amp;edit={$partner[partner].id}'>{$LAN_88}</a>
		</td></tr>
{/section}
</table>





</center>
<div class="clearer"> </div>
<br />
{include file="admin_footer.tpl"}