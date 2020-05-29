{include file="header.tpl"}


<div align="left">
	<div class="left">
	{$error}
   <table border="0" cellspacing="20" >      
{section name=i loop=$videos step=3 max=15}

<tr valign="top" >
{section name=mysec loop=$videos start=$smarty.section.i.index max=3}
<td width="160px"><a href="delfavorite.php?g={$videos[mysec].id}&u={$user}">Delete form favorite</a>
<a href="games.php?id={$videos[mysec].id}"><img height='120' width='160' src="{$videos[mysec].picture}" class="thumbnail" title="{$videos[mysec].description|truncate:132:'...'}" alt="{$videos[mysec].description|truncate:132:'...'}" /></a>
<br><br><br><br><br><br><br><h4><a href="games.php?id={$videos[mysec].id}">{$videos[mysec].name}</a></h4>






</td>
      

      
   {/section}
        </tr>

   {/section}
  </table>   
      
</div>

</div>


{include file="footer.tpl"}