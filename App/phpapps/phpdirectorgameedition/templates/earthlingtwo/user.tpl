{include file="header.tpl"}
<div id="banner">{include file="ads.tpl"}</div>
			<div class="post">
				<h2 class="title"><a href="#">{$user}</a></h2>
<table width="100%">
	<tr>
		<td align="center" width="60" height="60">
<img src="avatar/{$avatar}" width="50" height="50">
		</td>
		<td valign="top">
		{$LAN_120}{$register}<br />
		{$LAN_117}{$plays}<br />
		{$LAN_118}<a href="{$website}" target="blank">{$website}</a><br />
		</td><td width="50">&nbsp;</td>
		<td valign="top">
		{$LAN_119} {$user} : <br />{$about}<br />
		
	</tr>
</table>

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