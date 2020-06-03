<?php

/*****************************************************************************/
/* install.php                                                               */
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

///////////////////////////////////////////////////////////////////////////////
//----------------------------SCRIPT INFORMATION-----------------------------//
//This script installs the MySQL database structure which the board relies   //
//on.  All data and information related to the board is contained within     //
//these mySQL database tables.                                               //
///////////////////////////////////////////////////////////////////////////////


////////////////////////////////////
//ACTIVATE AFTER FORM IS SUBMITTED//
////////////////////////////////////

if(isset($_POST['install_submit']))
{

//IF THE TWO PASSWORDS SUPPLIED BY THE USERS MATCH, CONTINUE WITH THE INSTALLATION
if ($_POST['password'] == $_POST['passwordconfirm']) {

$prefix = $_POST['prefix'];

//CONNECT TO MySQL DATABASE
mysql_connect(stripslashes($_POST['hostname']),stripslashes($_POST['username']),stripslashes($_POST['password'])) OR DIE("Gravity Board X experienced an error while trying to connect to your MySQL database: " . mysql_error());

//SELECT THE MySQL DATABASE
mysql_select_db(stripslashes($_POST['dbname'])) OR DIE ("Gravity Board X was unable to select the proper database: " . mysql_error());

//CREATE THE MySQL TABLES NEEDED TO RUN THE MESSAGE BOARD
//STORES THE BOARD ANNOUNCEMENTS
mysql_query("CREATE TABLE " . $prefix . "announcements (board_id TINYINT, text TEXT, enabled TINYINT, PRIMARY KEY(board_id))") OR DIE("Gravity Board X experienced an error while trying to create the announcements table: " . mysql_error());

//STORES A LIST OF BANNED USERS
mysql_query("CREATE TABLE " . $prefix . "banned (ip TINYTEXT, email TINYTEXT, bandate VARCHAR(255), banuntil VARCHAR(255), banreason TEXT, id TINYINT UNSIGNED NOT NULL AUTO_INCREMENT, PRIMARY KEY(id))") OR DIE("Gravity Board X experienced an error while trying to create the banned table: " . mysql_error());

//STORES A LIST OF BOARDS
mysql_query("CREATE TABLE " . $prefix . "boards (name VARCHAR(255), board_id INT UNSIGNED NOT NULL AUTO_INCREMENT, cat_id TINYINT DEFAULT '1', boardorder INT, description TINYTEXT, PRIMARY KEY(board_id))") OR DIE("Gravity Board X experienced an error while trying to create the boards table: " . mysql_error());

//STORES A LIST OF CATEGORIES FOR BOARDS TO GO IN
mysql_query("CREATE TABLE " . $prefix . "categories (catname TINYTEXT, cat_id INT UNSIGNED NOT NULL AUTO_INCREMENT, catorder INT, PRIMARY KEY(cat_id))") OR DIE ("Gravity Board X experienced an error while trying to create the categories table: " . mysql_error());

//STORES A LIST OF WORDS TO BE CENSORED ON THE BOARD
mysql_query("CREATE TABLE " . $prefix . "censor (wordlist TEXT, enabled TINYINT, id TINYINT)") OR DIE ("Gravity Board X experienced an error while trying to create the censor table: " . mysql_error());

//STORES ALL INSTANT MESSAGES SENT FROM MEMBER TO MEMBER
mysql_query("CREATE TABLE " . $prefix . "ims (imbody TEXT, imsubject TEXT, imdate VARCHAR(255), reply TINYINT DEFAULT '0', imread TINYINT DEFAULT '0', replytime VARCHAR(255), imfrom TINYINT, imto TINYINT, imid INT UNSIGNED NOT NULL AUTO_INCREMENT, im_parent INT, PRIMARY KEY(imid), FULLTEXT(imbody), FULLTEXT(imsubject), FULLTEXT(imbody, imsubject))") OR DIE ("Gravity Board X experienced an error while trying to create the IMs table: " . mysql_error());

//STORES A LIST OF MEMBERGROUPS
mysql_query("CREATE TABLE " . $prefix . "membergroups (group_id TINYINT UNSIGNED NOT NULL AUTO_INCREMENT, group_name TINYTEXT, group_type TINYTEXT, PRIMARY KEY(group_id))") OR DIE ("Gravity Board X experienced an error while trying to create the member groups table: " . mysql_error());

//STORES ALL VITAL DATA FOR ALL MEMBERS
mysql_query("CREATE TABLE " . $prefix . "members (memberid INT UNSIGNED NOT NULL AUTO_INCREMENT, displayname TINYTEXT, realname TINYTEXT, pw TINYTEXT, email TINYTEXT, signature TINYTEXT, user_text TINYTEXT, aim_id TINYTEXT, yahoo_id TINYTEXT, msn_id TINYTEXT, icq_id TINYTEXT, homepage_link TINYTEXT, memberGroup INT, group_rank TINYTEXT, location TINYTEXT, dateregistered VARCHAR(255), timediff TINYTEXT, icon_url TEXT, icon_width TINYTEXT, icon_height TINYTEXT, usecookie BOOL, otherinfo TEXT, pmssent INT, boardviews INT, threadviews INT, messageviews INT, pmsread INT, totalclicks INT, bookmarks TEXT, messageeditor BOOL, tperpage TINYINT, mperpage TINYINT, verified TINYINT, verifyid TINYTEXT, PRIMARY KEY(memberid), FULLTEXT(displayname))") OR DIE("Gravity Board X experienced an error while trying to create the users table: " . mysql_error());

//STORES ALL STATISTICAL DATA FOR ALL MEMBERS
mysql_query("CREATE TABLE " . $prefix . "memberstats (memberid INT, logins INT, pmssent INT, boardsviewed INT, threadsviewed INT, postsviewed INT, clicks INT)") OR DIE("Gravity Board X experienced an error while trying to create the users table: " . mysql_error());

//Message logs are not fully implemented for this release (see readme.txt release notes)
mysql_query("CREATE TABLE " . $prefix . "message_logs (memberid INT UNSIGNED NOT NULL AUTO_INCREMENT, messagesread TEXT, PRIMARY KEY(memberid))") OR DIE ("Gravity Board X experienced an error while trying to create the message logs table: " . mysql_error());

//CONTAINS A LIST OF MEMBERS CURRENTLY ONLINE
mysql_query("CREATE TABLE " . $prefix . "online (session_id VARCHAR(255) NOT NULL DEFAULT '', firstonline VARCHAR(255), lastactive VARCHAR(255), member TINYINT DEFAULT '0', memberid INT, ip_address VARCHAR(255) NOT NULL DEFAULT '', refurl VARCHAR(255) NOT NULL DEFAULT '', useragent VARCHAR(255) DEFAULT NULL, PRIMARY KEY (session_id), KEY session_id (session_id))") OR DIE("Gravity Board X experienced an error while trying to create the online table: " . mysql_error());

//STORES ALL POSTS MADE ON THE BOARD - THE MOST IMPORTANT DATABASE BY FAR
mysql_query("CREATE TABLE " . $prefix . "posts (board_id TINYINT, dateposted VARCHAR(255), thread_id TINYINT, msg_id INT(15) UNSIGNED NOT NULL AUTO_INCREMENT, subject TEXT, message TEXT, poster_email TINYTEXT, memberid INT, ip TINYTEXT, PRIMARY KEY(msg_id), FULLTEXT(subject), FULLTEXT(message), FULLTEXT(subject, message))") OR DIE("Gravity Board X experienced an error while trying to create the posts table: " . mysql_error());

//STORES ALL POSTS MADE ON THE BOARD - THE MOST IMPORTANT DATABASE BY FAR
mysql_query("CREATE TABLE " . $prefix . "pwreset (resetemail TINYTEXT, resetid TINYTEXT)") OR DIE("Gravity Board X experienced an error while trying to create the posts table: " . mysql_error());

//STORES ALL RANK NAMES, VALUES, ETC.
mysql_query("CREATE TABLE " . $prefix . "ranks (rank TINYTEXT, postsneeded INT, color TINYTEXT, rankid INT UNSIGNED NOT NULL AUTO_INCREMENT, PRIMARY KEY(rankid))") OR DIE("Gravity Board X experienced an error while trying to create the ranks table: " . mysql_error());

//STORES ALL THE SETTINGS FOR THE BOARD
mysql_query("CREATE TABLE " . $prefix . "settings (regemail TEXT, admincolor TINYTEXT, modcolor TINYTEXT, maxiconwidth TINYTEXT, maxiconheight TINYTEXT, timediff TINYINT, useragreement TEXT, debugon BOOL, currentskin TINYTEXT, tperpage TINYINT, mperpage TINYINT)") OR DIE("Gravity Board X experienced an error while trying to create the settings table: " . mysql_error());

//STORES ALL OF THE BOARD STATISTICS
mysql_query("CREATE TABLE " . $prefix . "stats (pmssent INT UNSIGNED NOT NULL, totalclicks INT UNSIGNED NOT NULL, boardviews INT UNSIGNED NOT NULL, threadviews INT UNSIGNED NOT NULL, messageviews INT UNSIGNED NOT NULL, pmsread INT UNSIGNED NOT NULL)") OR DIE("Gravity Board X experienced an error while trying to create the threads table: " . mysql_error());

//STORES ALL OF THE THREADS
mysql_query("CREATE TABLE " . $prefix . "threads (thread_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, subject VARCHAR(255), first_msg INT, last_msg INT, board_id TINYINT, view_num TINYINT DEFAULT '0', reply_num INT DEFAULT '0', sticky TINYINT DEFAULT '0', locked TINYINT DEFAULT '0', last_msg_time VARCHAR(255), PRIMARY KEY(thread_id))") OR DIE("Gravity Board X experienced an error while trying to create the threads table: " . mysql_error());

//TRACKS USER LOGIN ACTIONS, FOR SECURITY REASONS
mysql_query("CREATE TABLE " . $prefix . "tracking (memberid INT, logintime INT, logouttime INT, loginip TINYTEXT, logoutip TINYTEXT)") OR DIE("Gravity Board X experienced an error while trying to create the threads table: " . mysql_error());

//Create the first Administrator account
$savetime = time();
$apw = MD5("admin");
mysql_query("INSERT INTO " . $prefix . "members (displayname, pw, email, membergroup, dateregistered, timediff, pmssent, boardviews, threadviews, messageviews, pmsread, totalclicks, messageeditor, tperpage, mperpage, verified) VALUES ('Administrator','$apw','admin','1','$savetime','{$_POST['timediff']}','0','0','0','0','0','0','1','50','15','1')") OR DIE("Gravity Board X experienced an error while trying to create the first Administrator account: " . mysql_error());
mysql_query("INSERT INTO " . $prefix . "message_logs (memberid, messagesread) VALUES ('1', '0')") OR DIE("Gravity Board X was unable to create the administrator message log account: " . mysql_error());

//Create default membergroups
mysql_query("INSERT INTO " . $prefix . "membergroups (group_name, group_type) VALUES ('Administrator', '1')") OR DIE("Gravity Board X experienced an error while trying to create the admin member group: " . mysql_error());
mysql_query("INSERT INTO " . $prefix . "membergroups (group_name, group_type) VALUES ('Moderator', '2')") OR DIE("Gravity Board X experienced an error while trying to create the moderator member group: " . mysql_error());
mysql_query("INSERT INTO " . $prefix . "membergroups (group_name, group_type) VALUES ('Member', '0')") OR DIE("Gravity Board X experienced an error while trying to create the default member group: " . mysql_error());

//Create default announcement
mysql_query("INSERT INTO " . $prefix . "announcements (board_id, text, enabled) VALUES ('0','Welcome to Gravity Board X.','0')") OR DIE("Gravity Board X experienced an error while trying to create the default announcement: " . mysql_error());

//Create default censored words
mysql_query("INSERT INTO " . $prefix . "censor (wordlist, enabled, id) VALUES ('fuck,shit,bitch,cock,penis,vagina,fag','1','1')") OR DIE("Gravity Board X experienced an error while trying to set the default censored words: " . mysql_error());
//Create default stats table entry

mysql_query("INSERT INTO " . $prefix . "stats (pmssent, totalclicks, boardviews, threadviews, messageviews, pmsread) VALUES ('0','0','0','0','0','0')") OR DIE("Gravity Board X experienced an error while trying to set the default censored words: " . mysql_error());

//Set default settings
$regemail = "Congratulations, you have successfully registered for a Gravity Board X forum! Before you may login with your email and password you must first click the link below to confirm your registration.  The information you supplied during registration is also listed below.  We hope you enjoy using Gravity Board X!";
$useragreement = "By registering for use of this forum, you hereby agree to all terms described within this document.<br/><br/>
                  While the moderators of these forums attempt to remove any material in violation of these rules as soon as possible, we are not held responsible for the content on this board:<br/><br/>
                  You hereby agree to take responsibility for all of your actions within this board; your Internet Protocol address is recorded with each post, along with the time in an effort to enforce
                  these rules while moderators are not present.  If problems arise, your Internet Service Provider may be contacted without your permission, and a formal complaint may be filed.  We will not
                  be held responsible for any actions that your Internet Service Provider takes upon you, including but not limited to termination of your Internet Service contract.  We will enforce these
                  rules very strictly, and steps will be taken to show that we do, including but not limited to; locking and deletion of posts and/or material in violation of this agreement, timed and/or
                  permanent banning of users, and contacting a user\'s Internet Service Provider.<br/><br/>Although we will enforce these rules as strictly as possible, we reserve the right to take actions
                  within this board without the violation of this agreement.  This includes but not limited to; locking and/or deletion of posts, and timed and/or permanent banning.<br/><br/>By clicking
                  I Agree below, you hereby signify that you have read and understand all of the terms above, and agree to them, welcoming any discipline action that may be taken upon you while in violation of any of these rules.";
mysql_query("INSERT INTO " . $prefix . "settings (regemail, admincolor, modcolor, maxiconwidth, maxiconheight, timediff, useragreement, debugon, currentskin, tperpage, mperpage) VALUES ('$regemail','#FE8000','#BDFB04','70','70','{$_POST['timediff']}','$useragreement','0','two','30','15')") OR DIE("Gravity Board X was unable to set the default settings: " . mysql_error());

########################
##Create default ranks##
########################
mysql_query("INSERT INTO " . $prefix . "ranks (rank, postsneeded, color) VALUES ('n00b','0','#555555')") OR DIE("Gravity board X was unable to create rank id1: " . mysql_error());
mysql_query("INSERT INTO " . $prefix . "ranks (rank, postsneeded, color) VALUES ('Amateur n00b','25','#AAAAAA')") OR DIE("Gravity board X was unable to create rank id1: " . mysql_error());
mysql_query("INSERT INTO " . $prefix . "ranks (rank, postsneeded, color) VALUES ('Professional n00b','50','#FFFFFF')") OR DIE("Gravity board X was unable to create rank id1: " . mysql_error());
mysql_query("INSERT INTO " . $prefix . "ranks (rank, postsneeded, color) VALUES ('Minty Fresh','75','#C4FF60')") OR DIE("Gravity board X was unable to create rank id1: " . mysql_error());
mysql_query("INSERT INTO " . $prefix . "ranks (rank, postsneeded, color) VALUES ('Triple Digits','100','#0000C8')") OR DIE("Gravity board X was unable to create rank id1: " . mysql_error());
mysql_query("INSERT INTO " . $prefix . "ranks (rank, postsneeded, color) VALUES ('Midget','166','#FFFF7D')") OR DIE("Gravity board X was unable to create rank id1: " . mysql_error());
mysql_query("INSERT INTO " . $prefix . "ranks (rank, postsneeded, color) VALUES ('Error 401 - Unauthorized','401','#00FF00')") OR DIE("Gravity board X was unable to create rank id1: " . mysql_error());
mysql_query("INSERT INTO " . $prefix . "ranks (rank, postsneeded, color) VALUES ('Error 403 - Forbidden','403','#00FF00')") OR DIE("Gravity board X was unable to create rank id1: " . mysql_error());
mysql_query("INSERT INTO " . $prefix . "ranks (rank, postsneeded, color) VALUES ('Error 404 - Page cannot be found','404','#00FF00')") OR DIE("Gravity board X was unable to create rank id1: " . mysql_error());
mysql_query("INSERT INTO " . $prefix . "ranks (rank, postsneeded, color) VALUES ('Error 500 - Internal Error','500','#00FF00')") OR DIE("Gravity board X was unable to create rank id1: " . mysql_error());
mysql_query("INSERT INTO " . $prefix . "ranks (rank, postsneeded, color) VALUES ('Error 501 - Not Implemented','501','#FF00FF')") OR DIE("Gravity board X was unable to create rank id1: " . mysql_error());
mysql_query("INSERT INTO " . $prefix . "ranks (rank, postsneeded, color) VALUES ('Error 503 - Service Unavailable','502','#00FF00')") OR DIE("Gravity board X was unable to create rank id1: " . mysql_error());
mysql_query("INSERT INTO " . $prefix . "ranks (rank, postsneeded, color) VALUES ('Bulls Eye','504','#C80000')") OR DIE("Gravity board X was unable to create rank id1: " . mysql_error());
mysql_query("INSERT INTO " . $prefix . "ranks (rank, postsneeded, color) VALUES ('Emergency!','911','#FF0000')") OR DIE("Gravity board X was unable to create rank id1: " . mysql_error());
mysql_query("INSERT INTO " . $prefix . "ranks (rank, postsneeded, color) VALUES ('Burning Up!','912','#AA0000')") OR DIE("Gravity board X was unable to create rank id1: " . mysql_error());
mysql_query("INSERT INTO " . $prefix . "ranks (rank, postsneeded, color) VALUES ('Quadruple Digits','1000','#960000')") OR DIE("Gravity board X was unable to create rank id1: " . mysql_error());
mysql_query("INSERT INTO " . $prefix . "ranks (rank, postsneeded, color) VALUES ('1337','1337','#00FF00')") OR DIE("Gravity board X was unable to create rank id1: " . mysql_error());
mysql_query("INSERT INTO " . $prefix . "ranks (rank, postsneeded, color) VALUES ('Circus Ringmaster','1338','#006400')") OR DIE("Gravity board X was unable to create rank id1: " . mysql_error());
mysql_query("INSERT INTO " . $prefix . "ranks (rank, postsneeded, color) VALUES ('Diamond In The Rough','1999','#00FFFF')") OR DIE("Gravity board X was unable to create rank id1: " . mysql_error());
mysql_query("INSERT INTO " . $prefix . "ranks (rank, postsneeded, color) VALUES ('Drunk Driver','3333','#FF9664')") OR DIE("Gravity board X was unable to create rank id1: " . mysql_error());
mysql_query("INSERT INTO " . $prefix . "ranks (rank, postsneeded, color) VALUES ('Con Artist','5000','#404040')") OR DIE("Gravity board X was unable to create rank id1: " . mysql_error());
mysql_query("INSERT INTO " . $prefix . "ranks (rank, postsneeded, color) VALUES ('Gangsta','6101','#C8AF00')") OR DIE("Gravity board X was unable to create rank id1: " . mysql_error());
mysql_query("INSERT INTO " . $prefix . "ranks (rank, postsneeded, color) VALUES ('Pirate','7500','#BE0000')") OR DIE("Gravity board X was unable to create rank id1: " . mysql_error());
mysql_query("INSERT INTO " . $prefix . "ranks (rank, postsneeded, color) VALUES ('Rookie Of The Year','8206','#C8C800')") OR DIE("Gravity board X was unable to create rank id1: " . mysql_error());
mysql_query("INSERT INTO " . $prefix . "ranks (rank, postsneeded, color) VALUES ('Genius','10000','#960096')") OR DIE("Gravity board X was unable to create rank id1: " . mysql_error());
mysql_query("INSERT INTO " . $prefix . "ranks (rank, postsneeded, color) VALUES ('Moderator Candidate','15000','#00C800')") OR DIE("Gravity board X was unable to create rank id1: " . mysql_error());
mysql_query("INSERT INTO " . $prefix . "ranks (rank, postsneeded, color) VALUES ('Bow To Your Royal Highness!','30000','#FFC800')") OR DIE("Gravity board X was unable to create rank id1: " . mysql_error());
mysql_query("INSERT INTO " . $prefix . "ranks (rank, postsneeded, color) VALUES ('Make Me An Administrator!','50000','#FFFF00')") OR DIE("Gravity board X was unable to create rank id1: " . mysql_error());
#####################
##End default ranks##
#####################

//Close MySQL connection
mysql_close();

//Create the board SQL configuration file
$filepointer = fopen("config.php", "w");
fputs($filepointer, "<?\r\n\$hostname=\"" . $_POST['hostname'] . "\";\r\n");
fputs($filepointer, "\$prefix=\"" . $_POST['prefix'] . "\";\r\n");
fputs($filepointer, "\$username=\"" . $_POST['username'] . "\";\r\n");
fputs($filepointer, "\$password=\"" . $_POST['password'] . "\";\r\n");
fputs($filepointer, "\$dbname=\"" . $_POST['dbname'] . "\";\r\n");
fputs($filepointer, "\$boardname=\"" . $_POST['boardname'] . "\";\r\n");
fputs($filepointer, "function dbconnect(){\r\nglobal \$hostname;\r\nglobal \$username;\r\nglobal \$password;\r\nglobal \$dbname;\r\n\$connection = MYSQL_CONNECT(\$hostname,\$username,\$password) OR DIE(\"Gravity Board X was unable to connect to the specified database. Please go back and double-check the database you supplied: \" . mysql_error());\r\n @mysql_select_db(\"\$dbname\") OR DIE(\"Gravity Board X was able to connect to your SQL host, but had difficulties selecting the specified database.  Please go back and double-check the database you supplied.\");\r\n return \$connection;\r\n}\r\n?>");
fclose($filepointer);

//TEST INSTALLATION STATUS
//CONNECT TO DATABASE
mysql_connect($_POST['hostname'], $_POST['username'], $_POST['password']) OR DIE("<b><font color=\"#FF0000\">ERROR: </font>Cannot connect to mysql host: </b><br/>" . mysql_error());
mysql_select_db($_POST['dbname']) OR DIE("<b><font color=\"#FF0000\">ERROR: </font>Cannot connect to mysql database: </b><br/>" . mysql_error());

$q1 = mysql_query("SELECT * FROM " . $prefix . "announcements") OR DIE("<b><font color=\"#FF0000\">ERROR: </font>Announcements table not found.</b><br/>");
if($q1){ echo '<b><span style="color: #2EB800">Announcements table created successfully.</span></b><br/>'; }else{ $install_error = 1; }
$q1 = mysql_query("SELECT * FROM " . $prefix . "banned") OR DIE("<b><font color=\"#FF0000\">ERROR: </font>Banned table not found.</b><br/>");
if($q1){ echo '<b><span style="color: #2EB800">Banned table created successfully.</span></b><br/>'; }else{ $install_error = 1; }
$q1 = mysql_query("SELECT * FROM " . $prefix . "boards") OR DIE("<b><font color=\"#FF0000\">ERROR: </font>Boards table not found.</b><br/>");
if($q1){ echo '<b><span style="color: #2EB800">Boards table created successfully.</span></b><br/>'; }else{ $install_error = 1; }
$q1 = mysql_query("SELECT * FROM " . $prefix . "categories") OR DIE("<b><font color=\"#FF0000\">ERROR: </font>Categories table not found.</b><br/>");
if($q1){ echo '<b><span style="color: #2EB800">Categories table created successfully.</span></b><br/>'; }else{ $install_error = 1; }
$q1 = mysql_query("SELECT * FROM " . $prefix . "censor") OR DIE("<b><font color=\"#FF0000\">ERROR: </font>Censor table not found.</b><br/>");
if($q1){ echo '<b><span style="color: #2EB800">Censor table created successfully.</span></b><br/>'; }else{ $install_error = 1; }
$q1 = mysql_query("SELECT * FROM " . $prefix . "ims") OR DIE("<b><font color=\"#FF0000\">ERROR: </font>IMs table not found.</b><br/>");
if($q1){ echo '<b><span style="color: #2EB800">IMs table created successfully.</span></b><br/>'; }else{ $install_error = 1; }
$q1 = mysql_query("SELECT * FROM " . $prefix . "membergroups") OR DIE("<b><font color=\"#FF0000\">ERROR: </font>Membergroups table not found.</b><br/>");
if($q1){ echo '<b><span style="color: #2EB800">MemberGroups table created successfully.</span></b><br/>'; }else{ $install_error = 1; }
$q1 = mysql_query("SELECT * FROM " . $prefix . "members") OR DIE("<b><font color=\"#FF0000\">ERROR: </font>Members table not found.</b><br/>");
if($q1){ echo '<b><span style="color: #2EB800">Members table created successfully.</span></b><br/>'; }else{ $install_error = 1; }
$q1 = mysql_query("SELECT * FROM " . $prefix . "memberstats") OR DIE("<b><font color=\"#FF0000\">ERROR: </font>Member stats table not found.</b><br/>");
if($q1){ echo '<b><span style="color: #2EB800">Memberstats table created successfully.</span></b><br/>'; }else{ $install_error = 1; }
$q1 = mysql_query("SELECT * FROM " . $prefix . "message_logs") OR DIE("<b><font color=\"#FF0000\">ERROR: </font>Message log table not found.</b><br/>");
if($q1){ echo '<b><span style="color: #2EB800">Message_logs table created successfully.</span></b><br/>'; }else{ $install_error = 1; }
$q1 = mysql_query("SELECT * FROM " . $prefix . "online") OR DIE("<b><font color=\"#FF0000\">ERROR: </font>Online table not found.</b><br/>");
if($q1){ echo '<b><span style="color: #2EB800">Online table created successfully.</span></b><br/>'; }else{ $install_error = 1; }
$q1 = mysql_query("SELECT * FROM " . $prefix . "posts") OR DIE("<b><font color=\"#FF0000\">ERROR: </font>Posts table not found.</b><br/>");
if($q1){ echo '<b><span style="color: #2EB800">Posts table created successfully.</span></b><br/>'; }else{ $install_error = 1; }
$q1 = mysql_query("SELECT * FROM " . $prefix . "pwreset") OR DIE("<b><font color=\"#FF0000\">ERROR: </font>PW Reset table not found.</b><br/>");
if($q1){ echo '<b><span style="color: #2EB800">PWReset table created successfully.</span></b><br/>'; }else{ $install_error = 1; }
$q1 = mysql_query("SELECT * FROM " . $prefix . "ranks") OR DIE("<b><font color=\"#FF0000\">ERROR: </font>Ranks table not found.</b><br/>");
if($q1){ echo '<b><span style="color: #2EB800">Ranks table created successfully.</span></b><br/>'; }else{ $install_error = 1; }
$q1 = mysql_query("SELECT * FROM " . $prefix . "settings") OR DIE("<b><font color=\"#FF0000\">ERROR: </font>Settings table not found.</b><br/>");
if($q1){ echo '<b><span style="color: #2EB800">Settings table created successfully.</span></b><br/>'; }else{ $install_error = 1; }
$q1 = mysql_query("SELECT * FROM " . $prefix . "stats") OR DIE("<b><font color=\"#FF0000\">ERROR: </font>Stats table not found.</b><br/>");
if($q1){ echo '<b><span style="color: #2EB800">Stats table created successfully.</span></b><br/>'; }else{ $install_error = 1; }
$q1 = mysql_query("SELECT * FROM " . $prefix . "threads") OR DIE("<b><font color=\"#FF0000\">ERROR: </font>Threads table not found.</b><br/>");
if($q1){ echo '<b><span style="color: #2EB800">Threads table created successfully.</span></b><br/>'; }else{ $install_error = 1; }
$q1 = mysql_query("SELECT * FROM " . $prefix . "tracking") OR DIE("<b><font color=\"#FF0000\">ERROR: </font>Tracking table not found.</b><br/>");
if($q1){ echo '<b><span style="color: #2EB800">Tracking table created successfully.</span></b><br/>'; }else{ $install_error = 1; }

if($install_error)
{
	echo '<b><span style="color: #FF0000;">One or more errors occurred when installing Gravity Board X.  Please see above for more information.</span></b>';
}else
{
	echo '<b>Congratulations, your Gravity Board X software has been successfully installed!  You may now login with the email "admin" and the password "admin".  It is highly reccommended that you change the password to this account.  To get started, enter your admin control panel and create a board.  Please also do not forget to delete install.php in your board directory.  We hope you enjoy using Gravity Board X!</b>';
}

} else {

//THE TWO PASSWORDS SUPPLIED DID NOT MATCH
echo "<b>Your passwords supplied did not match. Please go back and re-enter your data.</b>";

}


//////////////////////
//END SUBMITTED FORM//
//////////////////////

}else
{

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
    <title>Gravity Board X Installation</title>

    <link rel="stylesheet" type="text/css" href="skins/slate/skin.css"/>
    <link rel="GBX Icon" href="favicon.ico">

    <script type="text/javascript" src="validate/yav.js"></script>
    <script type="text/javascript" src="validate/yav-config.js"></script>
</head>

<body bgcolor=#303030>

<script type="text/javascript" language="JavaScript">
var rules=new Array();
rules[0]='boardname|required|Please enter a Forum Name.';
rules[1]='hostname|required|Please enter a MySQL Hostname.';
rules[2]='username|required|Please enter a MySQL Username.';
rules[3]='password|required|Please enter a MySQL Password.';
rules[4]='passwordconfirm|required|Please confirm your MySQL Password.';
rules[5]='prefix|required|Please enter a Database Prefix.';
rules[6]='dbname|required|Please enter a Database Name.';
rules[7]='password|equal|$passwordconfirm|MySQL Passwords must match.';
</script>

<form method="POST" name="installform" action="<?php echo $PHP_SELF; ?>" onSubmit="return performCheck('installform', rules, 'innerHtml');">
<table class="station" width="100%" cellspacing="0">
  <tr>
    <td width="100%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium" bgcolor="#C0C0C0" colspan="5">
    <p align="center"><b><font face="Verdana" color="#003366">Gravity Board X
    Installation</font></b></td>
  </tr>
  <tr>
    <td width="100%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium" colspan="5" align="center">&nbsp;</td>
    </tr>
  <tr>
    <td width="100%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium" colspan="5" align="center">
    <b><font face="Verdana" size="2" color="#FFFFFF">Installing Gravity Board X
    on your site is easy.&nbsp; Simply fill out the form below with the
    information requested, click Install, and in a matter of <i>seconds</i>, you'll
    have GBX up and running on your site, ready for use!</font></b></td>
    </tr>
  <tr>
    <td width="100%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium" colspan="5" align="center">&nbsp;</td>
    </tr>
  <tr>
    <td width="100%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium" colspan="5" align="center">
    <b><font size="2" face="Verdana" color="#FF0000">All fields are required to
    successfully install your forum.</font></b></td>
    </tr>
  <tr>
    <td width="100%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium" colspan="5" align="center">&nbsp;</td>
    </tr>
  <tr>
    <td width="45%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium" align="right">
    <font face="Verdana" color="#FFFFFF" size="2">Forum Name</font></td>
    <td width="1%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">&nbsp;</td>
    <td width="15%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">
    <input type="text" class="textbox" name="boardname" size="20" value="Gravity Board X"></td>
    <td width="1%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">
    &nbsp;</td>
    <td width="38%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium" align="left">
    <p align="left"><font face="Verdana" size="2" color="#FFFFFF">The name of
    your forum (default Gravity Board X)</font></td>
    </tr>
  <tr>
    <td width="45%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium" align="right">
    <font face="Verdana" size="2" color="#FFFFFF">MySQL Hostname</font></td>
    <td width="1%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">&nbsp;</td>
    <td width="15%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">
    <input type="text" class="textbox" name="hostname" size="20" value="localhost"></td>
    <td width="1%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">
    &nbsp;</td>
    <td width="38%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium" align="left">
    <font face="Verdana" size="2" color="#FFFFFF">Your MySQL hostname (usually
    localhost)</font></td>
    </tr>
  <tr>
    <td width="45%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium" align="right">
    <font face="Verdana" color="#FFFFFF" size="2">MySQL Username</font></td>
    <td width="1%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium" >&nbsp;</td>
    <td width="15%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">
    <input type="text" class="textbox" name="username" size="20"></td>
    <td width="1%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">
    &nbsp;</td>
    <td width="38%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium" align="left">
    <font face="Verdana" size="2" color="#FFFFFF">Your MySQL username</font></td>
    </tr>
  <tr>
    <td width="45%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium" align="right">
    <font face="Verdana" color="#FFFFFF" size="2">MySQL Password</font></td>
    <td width="1%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">&nbsp;</td>
    <td width="15%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">
    <input type="password" class="textbox" name="password" size="20"></td>
    <td width="1%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">
    &nbsp;</td>
    <td width="38%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium" align="left">
    <font face="Verdana" size="2" color="#FFFFFF">Your MySQL password</font></td>
    </tr>
  <tr>
    <td width="45%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium" align="right">
    <font face="Verdana" size="2" color="#FFFFFF">Confirm MySQL Password</font></td>
    <td width="1%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">&nbsp;</td>
    <td width="15%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">
    <input type="password" class="textbox" name="passwordconfirm" size="20"></td>
    <td width="1%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">
    &nbsp;</td>
    <td width="38%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium" align="left">
    <font face="Verdana" size="2" color="#FFFFFF">Re-Enter your MySQL password</font></td>
    </tr>
  <tr>
    <td width="45%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium" align="right">
    <font face="Verdana" color="#FFFFFF" size="2">MySQL Database Name</font></td>
    <td width="1%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">&nbsp;</td>
    <td width="15%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">
    <input type="text" class="textbox" name="dbname" size="20"></td>
    <td width="1%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">
    &nbsp;</td>
    <td width="38%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium" align="left">
    <font face="Verdana" size="2" color="#FFFFFF">Your MySQL database name</font></td>
    </tr>
  <tr>
    <td width="45%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium" align="right">
    <font face="Verdana" size="2" color="#FFFFFF">Database Prefix</font></td>
    <td width="1%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">&nbsp;</td>
    <td width="15%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">
    <input type="text" class="textbox" name="prefix" size="20" value="gbx_"></td>
    <td width="1%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">
    &nbsp;</td>
    <td width="38%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium" align="left">
    <font face="Verdana" size="2" color="#FFFFFF">The MySQL database prefix
    (default is gbx)</font></td>
    </tr>
  <tr>
    <td width="45%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium" align="right">
    <font face="Verdana" size="2" color="#FFFFFF">Default Time Zone</font></td>
    <td width="1%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">&nbsp;</td>
    <td width="15%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">

    <select name="timediff" class="textbox">
      <option value="-12">(GMT -12:00)</option>
      <option value="-11">(GMT -11:00)</option>
      <option value="-10">(GMT -10:00)</option>
      <option value="-9">(GMT -9:00)</option>
      <option value="-8">(GMT -8:00)</option>
      <option value="-7">(GMT -7:00)</option>
      <option value="-6">(GMT -6:00)</option>
      <option value="-5">(GMT -5:00)</option>
      <option value="-4">(GMT -4:00)</option>
      <option value="-3">(GMT -3:00)</option>
      <option value="-2">(GMT -2:00)</option>
      <option value="-1">(GMT -1:00)</option>
      <option value="0" selected>(GMT)</option>
      <option value="1">(GMT +1:00)</option>
      <option value="2">(GMT +2:00)</option>
      <option value="3">(GMT +3:00)</option>
      <option value="4">(GMT +4:00)</option>
      <option value="5">(GMT +5:00)</option>
      <option value="6">(GMT +6:00)</option>
      <option value="7">(GMT +7:00)</option>
      <option value="8">(GMT +8:00)</option>
      <option value="9">(GMT +9:00)</option>
      <option value="10">(GMT +10:00)</option>
      <option value="11">(GMT +11:00)</option>
      <option value="12">(GMT +12:00)</option>
      <option value="13">(GMT +13:00)</option>
    </select>

</td>
    <td width="1%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">
    &nbsp;</td>
    <td width="38%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium" align="left">
    <font face="Verdana" size="2" color="#FFFFFF">Default Time Zone for this board</font></td>
    </tr>
  <tr>
    <td width="45%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">&nbsp;</td>
    <td width="1%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">&nbsp;</td>
    <td width="15%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">&nbsp;</td>
    <td width="1%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">&nbsp;</td>
    <td width="38%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium" align="left">&nbsp;</td>
    </tr>
  <tr>
    <td width="45%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">&nbsp;</td>
    <td width="1%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">&nbsp;</td>
    <td width="15%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">&nbsp;</td>
    <td width="1%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">&nbsp;</td>
    <td width="38%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium" align="left">&nbsp;</td>
    </tr>
  <tr>
    <td width="100%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium" colspan="5">
    <p align="center"><font color="#FF0000"><b>Before proceeding, make sure your install directory (where this install.php file is residing) has its permissions set to 777!  If this is not the case, installation will produce many errors and not be able to create the config file.</b></p>
    <p align="center"><font face="Verdana" size="2" color="#FFFFFF">Please
    double-check the variables you entered above.&nbsp; <b>By clicking I AGREE -
    Install below, you hereby agree to having read readme.html
    included with this GBX download, and are bound to the terms of the GNU
    General Public License Version 2 or (at your option) any later version FOR NON-COMMERCIAL USE.</b></font></td>
    </tr>
  <tr>
    <td>
<div id="errorsDiv" style="color: #FF0000;"></div>
    </td>
  </tr>
  <tr>
    <td width="45%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">&nbsp;</td>
    <td width="1%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">&nbsp;</td>
    <td width="15%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">
    <p align="center">
    <input type="submit" class="button" value="I AGREE - Install" name="install_submit">
    <input type="reset" class="button" value="Clear" name="B2"></td>
    <td width="1%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">
    &nbsp;</td>
    <td width="38%" style="border-left: medium none #111111; border-right-style: none; border-right-width: medium; border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium" align="left">
    &nbsp;</td>
    </tr>
  </table>
</form>
</body>

</html>

<?php

}

?>