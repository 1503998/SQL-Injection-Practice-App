{include file="header.tpl"}
<div id="banner"><object class="flash" type="application/x-shockwave-flash" data="recentplayer.swf?x=recent.php&t=Games actually played Right Now..." width="550" height="110" align="middle">
       <param name="allowScriptAccess" value="sameDomain">

       <param name="movie" value="recentplayer.swf?x=recent.php&t=Games actually played Right Now...">
       <param name="quality" value="high">
       <param name="bgcolor" value="#FFFFFF">
     </object></div>
			<div class="post">
				<h2 class="title"><a href="#">{$title}</a></h2>
				{$message}
{$date} - [from : {$from}]
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