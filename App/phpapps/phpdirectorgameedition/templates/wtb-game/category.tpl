{include file="header.tpl"}

<div class="indextop">
<div class="randomgames">
<div class="randomtop2"><h2>{$LAN_40|default:"Categories"}</h2></div>

<div class="randomgame">
{section name=cat loop=$cat start=$smarty.section.i.index}<!-- COLUMNS EG change max=??? to the ammount and step=??? to the same ammount-->
<div class="gamethumb">

<div class="thumbs">
<img border="0" width="96" height="80" src="{$cat[cat].picture}" {if $firefox eq "1"} alt="No Image Yet"{/if} />
</a>
</div>
<div class="thumbtitle">
<a href="index.php?cat={$cat[cat].id}"><b>{$cat[cat].name}</b></a>
</div>

</div>
{/section}
</div>

<div class="randombottom"></div>
</div>

{include file="ads/ad-336-280.tpl"}

</div>




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

		 

  
{include file="footer.tpl"}
