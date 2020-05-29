<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--
Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License

Name       : EarthlingTwo  
Description: A two-column, fixed-width design with dark color scheme.
Version    : 1.0
Released   : 20090918
-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>{$config_name|default:"PHP Director"} - {$title}</title>
<meta name="description" content="{$desc}" />
<link href="templates/earthlingtwo/style.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="js/behavior.js"></script>
<script type="text/javascript" src="js/rating.js"></script>
<script type="text/javascript" src="js/reflection.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/thickbox.js"></script>
<script type="text/javascript" src="js/show_hide.js"></script>
</head>
<body>
<div id="wrapper">
	<div id="header">
	
		<div id="logo">
			<h1><a href="#">{$config_name|default:"PHP Director"}</a></h1>
			<p></p>
		</div>
		<div id="search"> 
<form method="post" action="index.php" name="search1">
<p><input type="text" name="searching" class="search-text"/><input type="submit" value="Search"  class="search-submit"/></p>
</form>
<br /><a href="?lang=fr"><img src="fr.png" border="0"></a>&nbsp;<a href="?lang=en-gb"><img src="gb.png" border="0"></a>
</div>
	</div>
	<!-- end #header -->
	<div id="menu">
		<ul>
			<li {if $pagetype eq "feature"}class="current_page_item"{/if}><a href='index.php?pt=feature'>{$LAN_2|default:"Featured"}</a></li>
<li {if $pagetype eq "all"}class="current_page_item"{/if}><a href='index.php?pt=all'>{$LAN_3|default:"All"}</a></li>
<li {if $pagetype eq "categories"}class="current_page_item"{/if}><a href='categories.php?pt=categories'>{$LAN_40|default:"Categories"}</a></li>
<!--<li {if $pagetype eq "images"}class="current_page_item"{/if}><a href='images.php?pt=images'>{$LAN_4|default:"Images"}</a></li>-->
<li {if $pagetype eq "games"}class="current_page_item"{/if}><a href='games.php?pt=games'>{$LAN_39|default:"Games"}</a></li>
<li {if $pagetype eq "submit"}class="current_page_item"{/if}><a href='submit.php?pt=submit'>{$LAN_5|default:"Submit"}</a></li>
		</ul>
	</div>
	<!-- end #menu -->
	<div id="page">
		<div id="content">
