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



session_start();
include("qdblog.php");
if(user_allow("login.php", "admin", "mod")){
connect_db();
global $conn;
$database;
mysql_query( "DELETE FROM $prefix"."entries WHERE id = '" . (int)$_GET['id'] .  "';", $conn ) or die("Oh well, play again");
header("Location: index.php");
 }
 ?> 
