<?php

/*****************************************************************************/
/* switch.php                                                                */
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
//This script holds all of the possible behaviors for the URL-passed variable//
//$_GET['action'] (seen in the URL as $action).                              //
///////////////////////////////////////////////////////////////////////////////

switch($_GET['action'])
{
    case 'loginerror':
    echo '<font class=small color="#FF0000"><b>An error occurred while logging in: the username and password you supplied did not match. Please try again.</b></font>';
    break;
    
    case 'gpl':
    include("gpl.php");
    break;
    
    /////////////////
    //ADMIN ACTIONS//
    /////////////////
    case 'admin':
    include("forms/adminform.php");
    break;
    
    case 'announce':
    include("announce.php");
    break;
    
    case 'announcements':
    include("forms/announcements.php");
    break;
    
    case 'ban':
    include("ban.php");
    break;
    
    case 'banmembers':
    include("forms/banform.php");
    break;
    
    case 'boardsettings':
    include("forms/boardsettingsform.php");
    break;
    
    case 'censor':
    include("forms/censorform.php");
    break;
    
    case 'censorsubmit':
    include("censor.php");
    break;
    
    case 'createboard':
    include("createboard.php");
    break;
    
    case 'createcat':
    include("createcat.php");
    break;
    
    case 'createmembergroup':
    include("createmembergroup.php");
    break;
    
    case 'viewban':
    include("forms/viewban.php");
    break;
    
    case 'configure':
    include("forms/configform.php");
    break;
    
    case 'deleteboard':
    include("forms/deleteboardform.php");
    break;
    
    case 'deletethread':
    include("deletethread.php");
    break;
    
    case 'deletecategory':
    include("forms/deletecategoryform.php");
    break;
    
    case 'editboard':
    include("editboard.php");
    break;
    
    case 'editcat':
    include("editcat.php");
    break;
    
    case 'editcss':
    include("forms/editcss.php");
    break;
    
    case 'editmembergroup':
    include("forms/editmembergroupform.php");
    break;
    
    case 'editmg':
    include("editmg.php");
    break;
    
    case 'addrank':
    include("forms/addrankform.php");
    break;
    
    case 'addranksubmit':
    include("addrank.php");
    break;
    
    case 'editranks':
    include("forms/editrankform.php");
    break;
    
    case 'float':
    include("elevate.php");
    break;
    
    case 'lock':
    include("lock.php");
    break;
    
    case 'moveboards':
    include("forms/editboardform.php");
    break;
    
    case 'movecategories':
    include("forms/editcatform.php");
    break;
    
    case 'newboard':
    include("forms/boardform.php");
    break;
    
    case 'newcat':
    include("forms/categoryform.php");
    break;
    
    case 'newmemgroup':
    include("forms/membergroupform.php");
    break;
    
    case 'set':
    include("set.php");
    break;
    
    case 'unban':
    include("unban.php");
    break;
    
    case 'edit':
    include("edit.php");
    break;

    case 'softinfo':
    include("forms/softinfo.php");
    break;
    
    //////////////////
    //MEMBER ACTIONS//
    //////////////////
    case 'editpost':
    include("post/post.php");
    break;
    
    case 'editprofile':
    include("editprofile.php");
    break;
    
    case 'newpmsend':
    include("imsend.php");
    break;
    
    case 'register':
    include("forms/registerform.php");
    break;
    
    case 'logout':
    include("sessionlogout.php");
    break;
    
    case 'profile':
    include("forms/profileform.php");
    break;
    
    case 'viewprofile':
    include("viewprofile.php");
    break;
    
    case 'postnew':
    include("post/post.php");
    break;
    
    case 'postnewsubmit':
    include("postnew.php");
    break;
    
    case 'postreply':
    include("post/post.php");
    break;
    
    case 'postreplysubmit':
    include("postreply.php");
    break;
    
    case 'viewboard':
    include("viewthreads.php");
    break;
    
    case 'viewthread':
    include("viewposts.php");
    break;
    
    case 'phpinfo':
    include("phpinfo.php");
    break;
    
    case 'search':
    include("forms/searchform.php");
    break;
    
    case 'getsearch':
    include("search.php");
    break;
    
    case 'memberlist':
    include("memberlist.php");
    break;
    
    case 'verify':
    include("verify.php");
    break;
    
    case 'whosonline':
    include("whosonline.php");
    break;
    
    case 'resetpw':
    include("forms/resetpwform.php");
    break;
    
    case 'resetpwemail':
    include("resetpwemail.php");
    break;
    
    case 'resetpwnow':
    include("resetpw.php");
    break;

    case 'resendve':
    include("forms/resendveform.php");
    break;

    case 'resendvalemail':
    include("resendvalemail.php");
    break;
    
    case 'bookmark':
    include("bookmark.php");
    break;
    
    case 'logerror':
    echo 'The username and password you supplied to login did not match the information in the database.';
    break;
    
    case 'registered':
    echo $output;
    break;
    
    case 'registerfail':
    echo 'The passwords you entered do not match. Please use the back button on your browser to go back and try again.';
    break;
    
    default:
    include("viewmain.php");
}

?>
