{include file="header.tpl"}
<div id="banner">{include file="ads.tpl"}</div>
			<div class="post">
				<h2 class="title"><a href="#">{$LAN_110}</a></h2>
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
			<div style="clear: both;">&nbsp;</div>
		</div>
		<!-- end #content -->
		<div id="sidebar">
			<ul>
			{include file="memberbar.tpl"}
					</ul>
				</li>
			</ul>
		</div>
		<!-- end #sidebar -->
{include file="footer.tpl"}