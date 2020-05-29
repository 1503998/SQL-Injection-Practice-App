<div id="comment">
<h1>{$LAN_70}</h1>
{if isset($smarty.cookies.id)}
{$message}
<form method="post" action="games.php?id={$video[video].id}">
<input type="hidden" name="id" value="{$video[video].id}">
 <b>{$LAN_71}</b><br /><input type="text" name="nom" value="{$user}"><br />
<b>{$LAN_72}</b><br /><textarea name="comment" row="10" cols="60"></textarea><br />
<input type="submit" name="go" value="{$LAN_73}">
</form>
<br />
{else}
{$LAN_94}
{/if}
<table width="100%">
{section name=game_co loop=$game_co}
     <tr>
	     <td width="60" align="left">
		 <img src="avatar/{$game_co[game_co].comment}" width="50" height="50" align="left">
		 </td>
		 <td width="400" valign="top">{$LAN_16} <b>{$game_co[game_co].nom}</b><br />
	  {$game_co[game_co].comment}</td><tr>
	 <br />
	 {/section}
	 </table>
	 </div>