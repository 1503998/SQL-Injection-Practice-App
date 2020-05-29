{include file="header.tpl"}

<div class="indextop">

<div class="randomgames">

<div class="randomtop2">
{section name=video loop=$video}
{if $reject eq "1"}
{$LAN_26}
{/if}
		<h2 class="pagetitle">{$video[video].name}&nbsp;{if isset($smarty.cookies.id)}
{if count($isfav) eq 0}
<a href="addfavorite.php?g={$video[video].id}&u={$user}"><img src="templates/photine/images/favorites.png" border="0" title="{$LAN_95}"></a>
{else}
test
{/if}
&nbsp;        <a target="_blank" href="mailto:?subject={$video[video].name} - {$config_name}&amp;body=http://www.play-online.bzh.be{$smarty.server.REQUEST_URI}" title="E-mail this to a friend"><img alt="E-mail this to a friend" border="0" src="templates/photine/images/send.png" title="E-mail this to a friend" /></a>
{/if}</h2>
</div>

<div class="randomgame">




			<div class="game">
				{include file="players.tpl"}

</div>
			</div>
<div class="randombottom"></div>

<div class="randomtop3"></div>
<div class="randomgame">
{$leaderboard}
<div id="leaderboard_bridge"></div>
{$widget}
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
</div>
<div class="randombottom"></div>
{include file="comment.tpl"}
</div>
<div class="ad336">
<h2>{$LAN_74}</h2><br />
{rating_bar units='5' id=$video[video].id}
<b>{$LAN_32}: </b>{$video[video].views}<br />
<b>{$LAN_36}: </b><a href="{$video[video].url_creator}" target="_blank">{$video[video].creator}</a><br />
<h2>{$LAN_35}:</h2>
		<br />
<div id="description">{$video[video].description}</div>
<br />

<br />
<hr>
	<h2>{$LAN_75}</h2><br />
	<textarea id="ta1" name="ta1" rows="5" cols="32">
	{include file="players.tpl"}
	</textarea>
</div>
{include file="ads/ad-336-280.tpl}
{/section}
</div>

</div>
<div class="contentbottom"></div>


{include file="footer.tpl"}
