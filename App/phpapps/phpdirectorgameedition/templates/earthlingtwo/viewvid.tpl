{include file="header.tpl"}
<div id="banner">{include file="ads.tpl"}</div>
			<div class="post">
			{section name=video loop=$video}
{if $reject eq "1"}
{$LAN_26}
{/if}
				<h2 class="title"><a href="#">{$video[video].name}</a>&nbsp;{if isset($smarty.cookies.id)}
<a href="addfavorite.php?g={$video[video].id}&u={$user}"><img src="templates/earthlingtwo/images/favorites.png" border="0" title="{$LAN_95}"></a>&nbsp;
 <a target="_blank" href="mailto:?subject={$video[video].name} - {$config_name}&amp;body=http://www.play-online.bzh.be{$smarty.server.REQUEST_URI}" title="E-mail this to a friend"><img alt="E-mail this to a friend" border="0" src="templates/earthlingtwo/images/send.png" title="E-mail this to a friend" /></a>
{/if}</h2>
{include file="players.tpl"}<br />
{rating_bar units='5' id=$video[video].id}
<div align="center">

{$leaderboard}
<div id="leaderboard_bridge"></div>
{$widget}
</div>
<br />
<table width="100%">
<h2>Classement</h2>
{section name=hscore loop=$hscore}
     <tr>
	     <td  align="left"><font color="white"> 
		 {$hscore[hscore].username} 
	  : {$hscore[hscore].thescore}</font></td>
	  </tr>
	 {/section}
	 </table>
<br />{include file="comment.tpl"}

			</div>
			<div style="clear: both;">&nbsp;</div>
		</div>
		<!-- end #content -->
		<div id="sidebar">
			<ul>
			{include file="memberbar.tpl}
				<li>
					<h2>{$LAN_74}</h2>
					<p>
<b>{$LAN_32}: </b>{$video[video].views}<br />
<b>{$LAN_36}: </b><a href="{$video[video].url_creator}" target="_blank">{$video[video].creator}</a><br />
<strong>{$LAN_35}:</strong>
		<br />
{$video[video].description}
</p>
				</li>
				<li>
					<h2>{$LAN_75}</h2>
					<ul>
						<li><textarea id="ta1" name="ta1" rows="5" cols="32">
	{include file="players.tpl"}
	</textarea></li>
					</ul>
				</li>
			</ul>
			{/section}
		</div>
		<!-- end #sidebar -->
{include file="footer.tpl"}