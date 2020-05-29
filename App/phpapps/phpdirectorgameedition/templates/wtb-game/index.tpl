{include file="header.tpl"}

<div class="indextop">
{if isset($smarty.get.act) && $smarty.get.act == 'Arcade'}
{$msg}
{else}
<div class="randomgames">
<div class="randomtop2"><h2>Welcome to</h2></div>

<div class="randomgame">
{section name=last loop=$lastuser start=$smarty.section.i.index max=10}
<div class="gamethumb">

<div class="thumbs">
<a href="user.php?u={$lastuser[last].user}">
<img height='80' width='96' border='0' src="avatar/{$lastuser[last].avatar}" class="thumbnail" title="since : {$lastuser[last].register}" alt="since : {$lastuser[last].register}" />
</div>
<div class="thumbtitle">
{$lastuser[last].user}</a>
</div>

</div>
{/section}
</div>

<div class="randombottom"></div>
</div>

{include file="ads/ad-336-280.tpl"}

</div>

<div class="indexbottom">

{include file="ads/ad-120-600.tpl"}

<div class="newgames">





<div class="newgamestop2"></div>

<div class="newgamesgame">

{section name=mysec loop=$videos start=$smarty.section.i.index max=28}
<div class="gamethumb2">

<div class="thumbs">
<a href="games.php?id={$videos[mysec].id}">
<img height='80' width='96' src="{$videos[mysec].picture}" class="thumbnail" title="{$videos[mysec].description|truncate:132:'...'}" alt="{$videos[mysec].description|truncate:132:'...'}" />
</a>
</div>
<div class="thumbtitle">
<a href="games.php?id={$videos[mysec].id}">{$videos[mysec].name|truncate:32:'...'}</a>
</div>

</div>
{/section}


</div>

<div class="newgamesbottom"></div>

</div>
<p align="center">&nbsp;&nbsp;{paginate_prev}&nbsp;&nbsp;{paginate_next} <br />{paginate_middle page_limit="28"} </p>


</div>
</div>

</div>

{/if}
</div>

<div id="pagemenu">
<ul>
<li class="home"><a title="INDEX;PHP" class="home" href="index.php/">Home</a></li>

</ul>
<div class="gotop">
<a class="home" href="index.php/#header">Top</a>
</div>
</div>
<!--
<div class="footermenus">

		 
<div class="footermenu">
<h2>{$LAN_76}</h2>
<ul>
{section name=lastcomment loop=$lastcomment}
      <li><a href="games.php?id={$lastcomment[lastcomment].file_id}" alt="Play this game" title="Play this game" >
	  {$lastcomment[lastcomment].comment}</a> - {$LAN_16} <b>{$lastcomment[lastcomment].nom}</a></li></p>
{/section}	
</ul>
</div>
     <div class="footermenu">
<h2>Partner</h2>
<ul>

      <li><a href="" alt="Play this game" title="Play this game" >
	  Partner link</a></li></p>
</ul>
</div>   
{include file="footer.tpl"}
