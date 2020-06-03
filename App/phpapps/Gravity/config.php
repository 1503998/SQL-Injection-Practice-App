<?
$hostname="localhost";
$prefix="gbx_";
$username="root";
$password="hacklab2019";
$dbname="gravity";
$boardname="Gravity Board X";
function dbconnect(){
global $hostname;
global $username;
global $password;
global $dbname;
$connection = MYSQL_CONNECT($hostname,$username,$password) OR DIE("Gravity Board X was unable to connect to the specified database. Please go back and double-check the database you supplied: " . mysql_error());
 @mysql_select_db("$dbname") OR DIE("Gravity Board X was able to connect to your SQL host, but had difficulties selecting the specified database.  Please go back and double-check the database you supplied.");
 return $connection;
}
?>