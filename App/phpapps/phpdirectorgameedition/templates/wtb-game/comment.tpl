<div id="respond">

<h3>{$LAN_70}</h3>
{if isset($smarty.cookies.id)}
{$message}
<form method="post" action="games.php?id={$video[video].id}">
<input type="hidden" name="id" value="{$video[video].id}">
<p><input type="text" name="nom" value="{$user}" id="author" size="22" tabindex="1" aria-required='true' />
<label for="author"><small>{$LAN_71}</small></label></p>


<p><textarea name="comment" id="comment" cols="58" rows="10" tabindex="4"></textarea></p>

<p><input name="go" type="submit" id="submit" tabindex="5" value="{$LAN_73}" />

 
</p>

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
