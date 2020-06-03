<?php

/*****************************************************************************/
/* js.php                                                                    */
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
<script type="text/javascript">
myinfo = new Object();
myinfo['memberid'] = "<?php echo $_SESSION['memberid']; ?>";
myinfo['displayname'] = "<?php echo $_SESSION['myinfo']['displayname']; ?>";
myinfo['group_name'] = "<?php echo $_SESSION['myinfo']['group_name']; ?>";
myinfo['rank'] = "<?php echo $_SESSION['myinfo']['rank']; ?>";
myinfo['color'] = "<?php echo $_SESSION['myinfo']['color']; ?>";
myinfo['pc'] = "<?php echo $_SESSION['myinfo']['pc']; ?>";
myinfo['signature'] = "<?php echo $_SESSION['myinfo']['signature']; ?>";
myinfo['icon_url'] = "<?php echo $_SESSION['myinfo']['icon_url']; ?>";
myinfo['icon_height'] = "<?php echo $_SESSION['myinfo']['icon_height']; ?>";
myinfo['icon_width'] = "<?php echo $_SESSION['myinfo']['icon_width']; ?>";
var globaltime = "<?php echo date ("m/d/Y h:i:s A", time() + $timeadjust); ?>";
</script>