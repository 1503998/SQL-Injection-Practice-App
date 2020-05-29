{include file="header.tpl"}
{literal}
<script>
function checkform()
{
	if (document.video.titletext.value == '') {
	alert('Please Enter A Title');
	return false;
	}	
	else if (document.video.descriptiontext.value == '')
	{
	alert('Please Enter A description');
	return false;
	}
	else if (document.video.authortext.value == '')
	{
	alert('Please Enter A Author');
	return false;
	}
	else if(!document.video.picture[0].checked && !document.video.picture[1].checked && !document.video.picture[2].checked) {
	alert('Please Choose A Screenshot');
	return false;
}
	// If the script gets this far through all of your fields
	// without problems, it's ok and you can submit the form

	return true;
}</script>
{/literal}
<div id="banner">{include file="ads.tpl"}</div>
			<div class="post">
			<h2 class="title"><a href="#">{$LAN_77}</a></h2>
			<div align="center">
			
				<form action="submit2.php?pt=submit" method="post" name="video" onSubmit="return checkform()">
	
	<table width="136" border="0">
	<tr>
				<td height="21" align="center"><b>SWF Url</b></td>
			</tr>
			<tr>
				<td align="center"><input name="file" type="text" value="" />
				</td>
				</tr>
		</table>
		<table width="136" border="0">
		<tr>
				<td height="21" align="center"><b>Thumbnail Url</b></td>
			</tr>
			<tr>
				<td align="center"><input name="picture" type="text" value="" />
				</td>
				</tr>
		</table>
				<table width="136" border="0">
		<tr>
				<td height="21" align="center"><b>Height of the game</b></td>
			</tr>
			<tr>
				<td align="center"><input name="height" type="text" value="" />
				</td>
				</tr>
		</table>
		<table width="136" border="0">
		<tr>
				<td height="21" align="center"><b>Width of the game</b></td>
			</tr>
			<tr>
				<td align="center"><input name="width" type="text" value="" />
				</td>
				</tr>
		</table>

		<table width="83" border="0">
			<tr>
				<td height="21" align="center"><b>Title</b></td>
			</tr>
			<tr>
				<td height="21" align="center"><textarea name="titletext" cols="50" rows="1" id="titletext" style="text-align:center; text-shadow:#990000"></textarea></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td width="77" height="21" align="center"><b>Author</b></td>
			</tr>
			<tr>
				<td height="42"><textarea name="authortext" cols="50" rows="1" style="text-align:center;"></textarea>				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td width="77" height="21" align="center"><b>Website url</b></td>
			</tr>
			<tr>
				<td height="42"><textarea name="creator_url" cols="50" rows="1" style="text-align:center;"></textarea>				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td height="21" align="center"><b>Description</b></td>
			</tr>
			<tr>
				<td height="104"><textarea name="descriptiontext" cols="50" rows="6" style="text-align:center;"></textarea>				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td height="19" align="center"><b>Category</b></td>
			</tr>
			<tr>
				<td height="104" align="center"><select name="category" size="6">
						
						
        {section name=cat loop=$cat}
						<option value="{$cat[cat].id}" {if $cat[cat].id eq "1"}selected="selected"{/if}>{$cat[cat].name}</option>
		{/section}

					
				
					</select>				</td>
				<td>&nbsp;</td>
			</tr>
		</table>
		<p align="center">
			<input type="submit" value="Submit">
		</p>
	</form></div>
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