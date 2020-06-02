<?php
/*********************************************
<!-- 
*   	DOTA OPENSTATS
*   
*	Developers: Ivan.
*	Contact: ivan.anta@gmail.com - Ivan
*
*	
*	Please see http://openstats.iz.rs
*	and post your webpage there, so I know who's using it.
*
*	Files downloaded from http://openstats.iz.rs
*
*	Copyright (C) 2010  Ivan
*
*
*	This file is part of DOTA OPENSTATS.
*
* 
*	 DOTA OPENSTATS is free software: you can redistribute it and/or modify
*    it under the terms of the GNU General Public License as published by
*    the Free Software Foundation, either version 3 of the License, or
*    (at your option) any later version.
*
*    DOTA OPEN STATS is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License
*    along with DOTA OPEN STATS.  If not, see <http://www.gnu.org/licenses/>
*
-->
**********************************************/
	
	include "header.php";
	
	  $pageTitle = "$lang[site_name] | Replays";
      
      $pageContents = ob_get_contents();
      ob_end_clean();
      echo str_replace('<!--TITLE-->', $pageTitle, $pageContents);
    
    // File extensions to be listed
   // $exts = array('w3g','html');
	$exts = array('w3g');
    $per_page = 10; // How many files per page
    $_file = $replayLocation;
    $files = array();
    $dir = opendir($_file);
    while( ($file = readdir($dir)) != false )
    {
        if( !is_dir($file) && !in_array($file,array('.','..')) && in_array(substr($file,strrpos($file,'.')+1),$exts) )
        {
            $files[] = array(
                    'path' => $file,
                    'filename' => pathinfo($file,PATHINFO_BASENAME),
                    'name' => pathinfo($file,PATHINFO_FILENAME)
                );
        }
    }
    closedir($dir);
    
    $file_count = count($files);
    $page_count = ceil( $file_count / $per_page );
    $page = ( isset($_GET['page']) && is_numeric($_GET['page']) ) ? (int) $_GET['page'] : 1;
    if( $page > $page_count )
    {
        $page = $page_count;
    }
    if( $page < 1 )
    {
        $page = 1;
    }

    $offset = ( ( $page - 1 ) * $per_page );

	$total_on_page = $per_page-(($page*$per_page) - $file_count);

    if ($total_on_page<$per_page) {$per_page = $total_on_page;}
	
	echo "<div align='center'><table class='tableHeroPageTop'><tr>
	<th style='padding-left:8px;'>Replay Browser</th></tr>";
		
    for( $i = $offset; $i < ( $offset + $per_page ); $i++ )
    {	
        //$files[$i]['path'];
        //$files[$i]['filename'];
       if (file_exists($_file."/".$files[$i]['filename']))
		{
		$current_file = $files[$i]['filename'];
		$replay_time = date($date_format,filemtime($_file."/".$files[$i]['filename']));
		$replay_name = substr($current_file,0,-4);
		$replayloc = "$_file/$current_file";
		
		if (file_exists("cdp"))
		{$getFile = str_replace(array($replayloc."/","+"),array("","%2B"),$current_file);
		$replay_info = ' | <a target="_blank" href="cdp/view_replay.php?file='.$getFile.'">Replay info</a>';
		} else $replay_info = "";
		
		//<td><a href='./".$_file."/".$current_file."'>".$replay_name."</a></td></tr>
		$img = "<img src='img/sig/$replay_name.jpg' />";
		?><tr class='row'>
		<td onClick='showhide("<?=$replay_name?>");' style='padding-left:8px;cursor:pointer' valign='top' align='left'><br />
		<a title='<?=$replay_name?>'><img alt='' border=0 style='vertical-align:middle;' src='img/replay.gif' /> <?=$replay_name?></a>  <p style='float:right;padding-right:18px;'><?=$replay_time?></p>
		<div style='display:none;' id = '<?=$replay_name?>'><br />
		<a title='Download: <?=$replay_name?>' onclick='location="<?=$_file?>/<?=$current_file?>"' href='javascript:;'><strong>Download</strong></a> <?=$replay_info?>
		<div style="clear: both;">&nbsp;</div>
		</div>
		<div style="clear: both;">&nbsp;</div><div style="clear: both;">&nbsp;</div>
		</td>
		</tr>
		<?php
		} 
    }
    
	echo '</table></div><br><table><tr><td style="padding-right:24px;" align="right" class="pagination"> Page: ';
    if( $page > 1 )
    {
        echo ' <a href="'.$_SERVER["PHP_SELF"].'?page=1">&lt;&lt;</a> <a href="'.$_SERVER["PHP_SELF"].'?page=',( $page - 1 ),'">&lt;</a> ';
    }
    $range = 5;
    for( $x = ($page - $range); $x < ( ($page + $range) + 1); $x++ )
    {
        if( $x > 0 && $x <= $page_count )
        {
            if( $x == $page )
            {
                echo ' [ ',$x,' ] ';
            }
            else
            {
                echo ' <a href="'.$_SERVER["PHP_SELF"].'?page=',$x,'">',$x,'</a> ';
            }
        }
    }
    if( $page != $page_count )
    {
        echo ' <a href="'.$_SERVER["PHP_SELF"].'?page=',( $page + 1 ),'">&gt;</a> <a href="'.$_SERVER["PHP_SELF"].'?page=',$page_count,'">&gt;&gt;</a> ';
    }
	echo '</td></tr></table><br />';
include("footer.php");
?>
