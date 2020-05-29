{include file="header.tpl"}
<div class="left">
{section name=video loop=$video}
{if $reject eq "1"}
{$LAN_26}
{/if}<h2>{$video[video].name}&nbsp;{if isset($smarty.cookies.id)}
{if count($isfav) eq 0}
<a href="addfavorite.php?g={$video[video].id}&u={$user}"><img src="templates/photine/images/favorites.png" border="0" title="{$LAN_95}"></a>
{else}
test
{/if}
&nbsp;        <a target="_blank" href="mailto:?subject={$video[video].name} - {$config_name}&amp;body=http://www.play-online.bzh.be{$smarty.server.REQUEST_URI}" title="E-mail this to a friend"><img alt="E-mail this to a friend" border="0" src="templates/photine/images/send.png" title="E-mail this to a friend" /></a>
{/if}</h2>
{include file="players.tpl"}<br />
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
	     <td  align="left">
		 {$hscore[hscore].username} 
	  : {$hscore[hscore].thescore}</td>
	  </tr>
	 {/section}
	 </table>
<br />{include file="comment.tpl"}
</div>
<div id="right">
<h2>{$LAN_74}</h2><br />
{rating_bar units='5' id=$video[video].id}
<b>{$LAN_32}: </b>{$video[video].views}<br />
<b>{$LAN_36}: </b><a href="{$video[video].url_creator}" target="_blank">{$video[video].creator}</a><br />
<strong>{$LAN_35}:</strong>
		<br />
<div id="description">{$video[video].description}</div>
<br />

<br />
<hr>
	<h2>{$LAN_75}</h2><br />
	<textarea id="ta1" name="ta1" rows="5" cols="32">
	{include file="players.tpl"}
	</textarea>
{/section}
</div>
{include file="footer.tpl"}