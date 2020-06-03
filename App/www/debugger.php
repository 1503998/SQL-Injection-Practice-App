<?php

/*****************************************************************************/
/* debugger.php                                                              */
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

?>

<center>
<table class=station width="800">
  <tr class=header height="18">
    <td>
      <p align="center"><font class=headerfont>Debugger</font>
    </td>
  </tr>
  <tr>
    <td>
    
<?php

if($support_gzip){ $sgzip = 'true'; }else{ $sgzip = 'false'; }
if($support_deflate){ $sdeflate = 'true'; }else{ $sdeflate = 'false'; }

echo '<b>SESSION VALUES</b><br/>';
echo '$displayname: ' . $_SESSION['displayname'] . '<br/>';
echo '$sr: ' . $_SESSION['sr'] . '<br/>';
echo '$email: ' . $_SESSION['email'] . '<br/>';
echo '$pw: ' . $_SESSION['pw'] . '<br/>';
echo '$memberid: ' . $_SESSION['memberid'] . '<br/>';
echo '$verified: ' . $_SESSION['verified'] . '<br/>';
echo '$perm: ' . $_SESSION['perm'] . '<br/>';
echo '$usertimediff: ' . $_SESSION['timediff'] . '<br/>';
echo '$messageeditor: ' . $_SESSION['messageeditor'] . '<br/>';
echo 'Browser supports gzip: ' . $sgzip . '<br/>';
echo 'Browser supports deflate: ' . $sdeflate . '<br/>';
echo '<b>POST VALUES</b><br/>';
echo '$loggedposts: |' . $loggedposts . '|<br/>';
echo '$newloggedposts: |' . $newloggedposts . '|<br/>';
echo '$logarray: |';
echo print_r($logarray);
echo '|<br/>';
echo '$nlp: |';
echo print_r($nlp);
echo '|<br/>';

?>
    </td>
  </tr>
</table>
</center>

