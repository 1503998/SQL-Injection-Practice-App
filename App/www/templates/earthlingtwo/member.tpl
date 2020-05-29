{include file="header.tpl"}
<div id="banner">{include file="ads.tpl"}div>
			<div class="post">
				<h2 class="title"><a href="#">{$LAN_116}</a></h2>
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