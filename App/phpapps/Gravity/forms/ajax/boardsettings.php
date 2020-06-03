<?php

session_start();

if($_SESSION['perm'] == '1')
{

include("../../config.php");

dbconnect();

if($_POST['debugon'] == 'true'){ $debug = 1; }else{ $debug = 0; }
mysql_query("UPDATE " . $prefix . "settings SET regemail = '{$_POST['regemail']}', admincolor = '{$_POST['admincolorform']}', modcolor = '{$_POST['modcolorform']}', maxiconwidth = '{$_POST['maxiconwidth']}', maxiconheight = '{$_POST['maxiconheight']}', useragreement = '{$_POST['useragreement']}', debugon = '$debug'") OR DIE("Gravity Board X was unable to save the settings: " . mysql_error());

mysql_close();

echo '<span class="errortext">The board settings were saved successfully.</span>';

}else
{
    accessdenied();
}

?>