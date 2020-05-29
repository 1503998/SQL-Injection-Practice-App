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
<div class="left">
<div align="center">
<form action="submit2.php?pt=submit" method="post" name="video" onSubmit="return checkform()">
	<!--Center Page-->
	<h3><b>{$LAN_77}</b>:
	</h3>
	
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
	</form>
</div>
{include file="footer.tpl"}