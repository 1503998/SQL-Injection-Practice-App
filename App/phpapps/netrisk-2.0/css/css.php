<?php

//Set Default CSS Files
$default = array('base.css', 'browser.css', 'tooltips.css', 'style.css');
	
$page = $_GET['p'];

if($page == "game"){
	$maptype = 'maps/board_'.strtolower($_SESSION['maptype']).'_'.$_SESSION['css_style'].'.css'; 
	$flagtype = 'flags/flags_'.strtolower($_SESSION['maptype']).'_'.$_SESSION['css_style'].'.css'; 
	$unittype = 'units/units_'.$_SESSION['unit_type'].'.css'; 
   	$optional = array('game.css', $maptype, $unittype, $flagtype); 
} else {
	$optional = array(); 
}


$css = array_merge($default, $optional);

//Combine Default and CSS Arrays
//Out all the CSS

foreach($css as $value){
	echo ("<link rel=\"stylesheet\" type=\"text/css\" href=\"./css/{$value}\" /> \n");
}

//Add Javascript Includes as well as needed
echo ("<script type=\"text/javascript\" src=\"./jscripts/register.js\"></script> \n");
	

//echo '<pre>';
//print_r($css);
//echo '</pre>';

?>