{include file="header.tpl"}
<div id="banner"><object class="flash" type="application/x-shockwave-flash" data="recentplayer.swf?x=recent.php&t=Games actually played Right Now..." width="550" height="110" align="middle">
       <param name="allowScriptAccess" value="sameDomain">

       <param name="movie" value="recentplayer.swf?x=recent.php&t=Games actually played Right Now...">
       <param name="quality" value="high">
       <param name="bgcolor" value="#FFFFFF">
     </object></div>
			<div class="post">
				<h2 class="title"><a href="#">{$LAN_106}</a></h2>
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