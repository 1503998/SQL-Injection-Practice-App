<?php

/*****************************************************************************/
/* search.php                                                                */
/*****************************************************************************/
/* Gravity Board X                                                           */
/* Open-Source Project started by Jonathan Taft (admin@gravityboardx.com)    */
/* Software Version: GBX Version 2.0                                         */
/* ========================================================================= */
/* Copyright (c) 2002-2007 Gravity Board X Developers. All Rights Reserved   */
/* Software by: The Gravity Board X Development Team                         */
/*****************************************************************************/
/* This program is free software; you can redistribute it and/or modify it   */
/* under the terms of the GNU General Public License as published by the     */
/* Free Software Foundation; either version 2 of the License, or (at your    */
/* option) any later version.                                                */
/*                                                                           */
/* This program is distributed in the hope that it will be useful, but       */
/* WITHOUT ANY WARRANTY; without even the implied warranty of                */
/* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General */
/* Public License for more details.                                          */
/*                                                                           */
/* The GNU GPL can be found in gpl.txt, which came with your download of GBX */
/*****************************************************************************/

?>

<div class="headermid">

<div class="header">
  <span class="headerfont">Search Results</span>
</div>

<div class="content">

<p align="left"><b>Search Query:</b> <?php echo $_GET['searchquery']; ?></p>

<?php

//EXECUTE SEARCH QUERY
	if($_GET['searchin'] == 'subject')
	{
		$match = 'subject';
	}elseif($_GET['searchin'] == 'message')
	{
		$match = 'message';
	}else
	{
		$match = 'subject, message';
	}
	if($_GET['searchquery'] != '' && $_GET['byuser'] != '')
	{
		$memberidquery = mysql_query("SELECT memberid FROM " . $prefix . "members WHERE displayname = '{$_GET['byuser']}'");
			list($suserid) = mysql_fetch_row($memberidquery);
		$searchquery = mysql_query("SELECT thread_id FROM " . $prefix . "posts WHERE MATCH($match) AGAINST('{$_GET['searchquery']}') AND memberid = '$suserid'");
	}elseif($_GET['byuser'] != '')
	{
		$memberidquery = mysql_query("SELECT memberid FROM " . $prefix . "members WHERE displayname = '{$_GET['byuser']}'");
		list($suserid) = mysql_fetch_row($memberidquery);
		$searchquery = mysql_query("SELECT thread_id FROM " . $prefix . "posts WHERE memberid = '$suserid'");
	}else
	{
		$searchquery = mysql_query("SELECT thread_id FROM " . $prefix . "posts WHERE MATCH($match) AGAINST('{$_GET['searchquery']}')");
	}

global $searchStr;

if(mysql_num_rows($searchquery) != 0)
{
	while($searchArr = mysql_fetch_object($searchquery))
	{
		$searchStr = $searchStr . $searchArr->thread_id . ',';
	}
}

$searchStr = substr($searchStr, 0, -1);

getThreads($searchStr);

if(mysql_num_rows($searchquery) == 0)
{
	echo 'Your search - <b>' . $_GET['searchquery'] . '</b> - did not match any records.';
}

if(mysql_num_rows($searchquery) != 0)
{

?>

  <div class="pages">
	<span>Page:
	
<?php

$pages = ceil($count / $_SESSION['tperpage']);
if($pages == 0){ echo 'Empty'; }

$getstring = '';
foreach($_GET as $v1 => $v2)
{
	$getstring .= '&amp;' . $v1 . '=' . $v2;
}

for($pagenum = 1; $pagenum <= $pages; $pagenum++)
{
    if($pagenum != 1){ echo ' | '; }
    echo '<a href="index.php?' . substr($getstring, 5) . '&amp;page=' . $pagenum . '">';
    echo $pagenum;
    echo '</a>';
}

?>

    </span>
  </div>

<?php

}

?>

</div>

<div class="headerbot">
</div>

</div>