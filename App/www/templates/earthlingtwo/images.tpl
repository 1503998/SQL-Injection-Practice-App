{include file="header.tpl"}
<div id="banner"><object class="flash" type="application/x-shockwave-flash" data="recentplayer.swf?x=recent.php&t=Games actually played Right Now..." width="550" height="110" align="middle">
       <param name="allowScriptAccess" value="sameDomain">

       <param name="movie" value="recentplayer.swf?x=recent.php&t=Games actually played Right Now...">
       <param name="quality" value="high">
       <param name="bgcolor" value="#FFFFFF">
     </object></div>
			<div class="post">
				<table border="0" align="center">
	 {section name=i loop=$images step=4 max=16}
	<!--ROWS EG change max=???-->
	<tr> {section name=images loop=$images start=$smarty.section.i.index max=4}
		<!-- COLUMNS EG change max=??? to the ammount and step=??? to the same ammount-->
		<td align="center"><a href="gaes.php?id={$images[images].id}"><img src="{$images[images].picture}" border="0" height="97" width="130" alt="" {if $firefox eq "1"}class="reflect rheight20 ropacity50"{/if}/></a></td>
		{/section} </tr>
	{/section}
</table>
			</div>
			<div style="clear: both;">&nbsp;</div>
		</div>
		<!-- end #content -->
		<div id="sidebar">
			<ul>
			{include file="memberbar.tpl"}
				<li>
					<h2>{$LAN_7}</h2>
						&nbsp;{$LAN_31}
			<!--Sort By Rating-->
			<a href="?sort=rating&amp;order=up&amp;next={$next}&amp;pt={$pagetype}"><img src="templates/Photine/images/arrowup.gif" alt="error" border="0"/></a> <a href="?sort=rating&amp;order=down&amp;next={$next}&amp;pt={$pagetype}" ><img src="templates/Photine/images/arrowdown.gif" border="0" alt="error"/></a>&nbsp;&nbsp;	&nbsp;	&nbsp;		{$LAN_32}
			<!--Sort By Views-->
			<a href="?sort=views&amp;order=up&amp;next={$next}&amp;pt={$pagetype}"><img src="templates/Photine/images/arrowup.gif" border="0" alt="error"/></a> <a href="?sort=views&amp;order=down&amp;next={$next}&amp;pt={$pagetype}"><img src="templates/Photine/images/arrowdown.gif" border="0" alt="error"/></a><br />
			&nbsp;{$LAN_33}
			<!--Sort By Name-->
			<a href="?sort=name&amp;order=up&amp;next={$next}&amp;pt={$pagetype}"><img src="templates/Photine/images/arrowup.gif" border="0" alt="error"/></a> <a href="?sort=name&amp;order=down&amp;next={$next}&amp;pt={$pagetype}"><img src="templates/Photine/images/arrowdown.gif" border="0" alt="error"/></a>&nbsp;	&nbsp;	&nbsp;	&nbsp;	{$LAN_34}
			<!--Sort By Date-->
			<a href="?sort=date&amp;order=up&amp;next={$next}&amp;pt={$pagetype}"> <img src="templates/Photine/images/arrowup.gif" border="0" alt="error"/></a> <a href="?sort=date&amp;order=down&amp;next={$next}&amp;pt={$pagetype}"><img src="templates/Photine/images/arrowdown.gif" border="0" alt="error"/></a><br />
			{paginate_prev}&nbsp;&nbsp;{paginate_next}
				</li>
				<li>
					<h2>{$LAN_76}</h2>
					<ul>{section name=lastcomment loop=$lastcomment}
						<li><span>{$LAN_16} <b><a href="user.php?u={$lastcomment[lastcomment].nom}">{$lastcomment[lastcomment].nom}</a></b></span><a href="games.php?id={$lastcomment[lastcomment].file_id}" alt="Play this game" title="Play this game" >
	  {$lastcomment[lastcomment].comment}</a></li>
						{/section}
					</ul>
				</li>
			</ul>
		</div>
		<!-- end #sidebar -->
{include file="footer.tpl"}