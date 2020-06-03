<?php

/*****************************************************************************/
/* announcedisplay.php                                                       */
/*****************************************************************************/
/* Gravity Board X                                                           */
/* Open-Source Project started by Jonathan Taft (admin@gravityboardx.com)    */
/* Software Version: GBX Version 2.0                                         */
/* ========================================================================= */
/* Copyright (c) 2002-2006 Gravity Board X Developers. All Rights Reserved   */
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

///////////////////////////////////////////////////////////////////////////////
//----------------------------SCRIPT INFORMATION-----------------------------//
//This script displays the announcements for the entire board, or for a      //
//specific board.                                                            //
///////////////////////////////////////////////////////////////////////////////

$announcequery = mysql_query("SELECT text FROM " . $prefix . "announcements WHERE enabled = '1' AND board_id = '0'") OR DIE("Gravity Board X was unable to retrieve forum announcements: " . mysql_error());
if(mysql_num_rows($announcequery) == '1')
{
    list($maintext) = mysql_fetch_row($announcequery);
    
?>

<div class="headermid" style="margin-bottom: 8px;">

<div class="header">
  <span class="headerfont">Announcements</span>
</div>

<div class="content">
    
<?php echo $maintext; ?>
  
<?php

    if(isset($_GET['board_id']))
    {

        $boardannouncequery = mysql_query("SELECT * FROM " . $prefix . "announcements WHERE enabled = '1' AND board_id = '{$_GET['board_id']}'") OR DIE("Gravity Board X was unable to retrieve forum announcements: " . mysql_error());
        while($announce = mysql_fetch_assoc($boardannouncequery))
        {

?>

	 <hr>
	 
<?php echo $announce['text']; ?>
<br>

  
<?php

        }
    }
    
?>

</div>

<div class="headerbot">
</div>

</div>

<?php

}else
{
    if(isset($_GET['board_id']))
    {
        $boardannouncequery = mysql_query("SELECT * FROM " . $prefix . "announcements WHERE enabled = '1' AND board_id = '{$_GET['board_id']}'") OR DIE("Gravity Board X was unable to retrieve forum announcements: " . mysql_error());
        while($announce = mysql_fetch_assoc($boardannouncequery))
        {

?>

<div class="headermid">

  <div id="header">
    <p align="center"><font class="headerfont">Announcements</font>
  </div>

  <div class="content">
	
<?php echo $announce[text]; ?>

  </div>

  <div class="headerbot">
  </div>

</div>
  
<?php

        }

    }
}

?>