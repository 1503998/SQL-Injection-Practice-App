{include file="header.tpl"}
<div id="banner">{include file="ads.tpl"}</div>
			<div class="post">
				<h2 class="title"><a href="#">{$LAN_106}</a></h2>
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