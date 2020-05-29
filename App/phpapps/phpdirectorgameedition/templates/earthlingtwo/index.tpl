{include file="header.tpl"}
<div id="banner"><object class="flash" type="application/x-shockwave-flash" data="recentplayer.swf?x=recent.php&t={$LAN_125}" width="550" height="110" align="middle">
       <param name="allowScriptAccess" value="sameDomain">

       <param name="movie" value="recentplayer.swf?x=recent.php&t={$LAN_125}">
       <param name="quality" value="high">
       <param name="bgcolor" value="#FFFFFF">
     </object></div>
			<div class="post">
			{if isset($smarty.get.act) && $smarty.get.act == 'Arcade'}
{$msg}
{else}
				{section name=mysec loop=$videos}

   <table border="0" cellspacing="20" >      
{section name=i loop=$videos step=3 max=15}

<tr valign="top" >
{section name=mysec loop=$videos start=$smarty.section.i.index max=3}
<td width="160px">
<a href="games.php?id={$videos[mysec].id}"><img height='120' width='160' src="{$videos[mysec].picture}" class="thumbnail" title="{$videos[mysec].description|truncate:132:'...'}" alt="{$videos[mysec].description|truncate:132:'...'}" /></a>
<br /><h4><a href="games.php?id={$videos[mysec].id}">{$videos[mysec].name|truncate:32:'...'}</a></h4>






</td>
      

      
   {/section}
        </tr>

   {/section}
  </table>
  {sectionelse}
   
   No Results
   
   {/section}	
   <p align="center">&nbsp;&nbsp;{paginate_prev}&nbsp;&nbsp;{paginate_next} <br />{paginate_middle page_limit="20"} </p>
   {/if}
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
				<li>
					<h2>{$LAN_124}</h2>
					   <table border="0" cellspacing="20" >      
{section name=i loop=$lastuser step=3 max=9}

<tr valign="top" >
{section name=last loop=$lastuser start=$smarty.section.i.index max=3}
<td width="55px">
<a href="user.php?u={$lastuser[last].user}"><img height='50' width='50' border='0' src="avatar/{$lastuser[last].avatar}" class="thumbnail" title="since : {$lastuser[last].register}" alt="since : {$lastuser[last].register}" />
<h4>{$lastuser[last].user}</a></h4>






</td>
      

      
   {/section}
        </tr>

   {/section}
  </table> 
  </li>
			</ul>
		</div>
		<!-- end #sidebar -->
{include file="footer.tpl"}