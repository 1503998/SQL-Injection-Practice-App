<?php
/*
QDBlog (Quick and Dirty Blog) is a simple minimalistic tool for blogging
Copyright © 2004 Ben "SchleyFox" Hughes
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; version 2
of the License.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/

include("config.php");

function connect_db()
{
  global $server_name;
  global $sql_username;
  global $sql_password;
  global $db_name;
  global $conn;
  $conn = @mysql_connect( $server_name, $sql_username, $sql_password ) or die( "Error: Could not connect to database" );
  global $database;
  $database = @mysql_select_db( $db_name, $conn ) or die( "Error: Database does not appear to exist" );
}
function cat_theme($input)
{
	global $value1;
	$input = str_replace("[CATEGORY]", $value1['name'], $input);
	return($input);
}
function theme() {
	global $theme;
	connect_db();
	global $conn;
	
	global $prefix;
	if($_SESSION['theme'] and !$_GET['theme']) {
		$theme = $_SESSION['theme'];
	} else {
		if($_GET['theme']) {
			$theme = $_GET['theme'];
		} else {
			if($_COOKIE['theme']) {
				$theme = $_COOKIE['theme'];
			}
		}
		#if(!$theme) { $theme = "test"; }
		if(mysql_num_rows(mysql_query("SELECT name FROM " . $prefix . "themes WHERE name = '$theme';", $conn)) == 1) {
			$_SESSION['theme'] = $theme;
			} else {
			$rs = mysql_query("SELECT theme_default FROM $prefix"."config LIMIT 1;", $conn);
			while( $row = mysql_fetch_array($rs)  ) { $theme = $row['theme_default']; }
			$_SESSION['theme'] = $theme;
		}
	}
}

function filter($input) {
	//basically strip html, translate BBCode, have a very merry Christmas etc
	$input = str_replace('<br/>',"", $input);
	//reverse bbcode stuff
	$input = str_replace('<b>', '[b]', $input);
	$input = str_replace('</b>', '[/b]', $input);
	$input = str_replace('<', '&lt;', $input);
	$input = str_replace('>', '&gt;', $input);
	$input = str_replace("\n", '<br/>', $input);
	$input = str_replace('[b]', '<b>', $input);
	$input = str_replace('[/b]', '</b>', $input);
	//add more as I see fit
	
	//sign as Washington Irving and its ready to go
	return $input;
}

//Now lets do the permission thing
function user_deny($page) {
	//$page is the page to send them if they are denied
	//function accepts infinite args as to groups
	//specify which groups to check for via args
	//its an OR type deal if they are members of any of the specified groups they fail
	$num = func_num_args();
	$failcounter = 0;
	if($num <= 1) {
		die("insufficient argumentage");
	}
	if($_SESSION['perm'] == NULL) { $_SESSION['perm'] = 'anon'; }
	$perm = explode(",", $_SESSION['perm']);
	for($i = 1; $i < $num; $i++) {
		$group = func_get_arg($i);
		for($x = 0; $x < count($perm); $x++) {
		if($perm["$x"] == $group){
			$failcounter++;
		}
		}
	}
	if($failcounter >= 1) {
		header("Location: $page?" . $_SERVER['QUERY_STRING']);
		return false;
	} else { return true; }
}
function user_allow($page) {
	//same deal as above, just inverse
	$passcounter = 0;
	$num = func_num_args();
	if($num <= 1) {
		die("Insufficient argumentage");
	}
	if($_SESSION['perm'] == NULL) { $_SESSION['perm'] = 'anon'; }
	$perm = explode(",", $_SESSION['perm']);
	for($i = 1; $i < $num; $i++) {
		$group = func_get_arg($i);
		for($x = 0; $x < count($perm); $x++) {
		if($perm["$x"] == $group) {
			$passcounter++;
		}
		}
	}
	if($passcounter <= 0) {
		header("Location: $page?" . $_SERVER['QUERY_STRING']);
		return false;
	} else { return true; }
}
	
				
	
?>
