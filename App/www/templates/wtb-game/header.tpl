<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title>{$config_name|default:"PHP Director"} - {$title}</title>
<meta name="description" content="{$desc}" />
<meta http-equiv="content-type" content="text/html;charset=iso-8859-2" />
<meta name="author" content="Ben Swanson"/>
<meta name="theme" content="luka cvrk"/>
<link rel="stylesheet" href="templates/wtb-game/style.css" type="text/css" />
<script type="text/javascript" src="js/behavior.js"></script>
<script type="text/javascript" src="js/rating.js"></script>
<script type="text/javascript" src="js/reflection.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/thickbox.js"></script>
<script type="text/javascript" src="js/show_hide.js"></script>
<!--[if IE 8]>
<link rel="stylesheet" href="templates/wtb-game/style-ie8.css" type="text/css" media="screen" />
<![endif]-->
<!--[if IE 7]>
<link rel="stylesheet" href="templates/wtb-game/style-ie7.css" type="text/css" media="screen" />
<![endif]-->
<!--[if IE 6]>
<link rel="stylesheet" href="templates/wtb-game/style-ie6.css" type="text/css" media="screen" />
<![endif]-->
</head>
<body>
<div id="main">
<div id="header">
<div class="logo">
                <h1><a href="index.php" title="{$config_name|default:"PHP Director"}">{$config_name|default:"PHP Director"}</a></h1>
</div>
<div class="searchpart">
<div class="search">
               {include file="searchform.tpl"}
</div>
</div>
</div>

<div id="content">

<div id="topmenu">
	<div id="topcatmenu">         
		   <ul>
             <li class="home"><a href="index.php">Home</a></li>
<li><a {if $pagetype eq "feature"}class="current"{/if} href='index.php?pt=feature' accesskey='f'><span class='key'>{$LAN_2|default:"Featured"}</span></a></li>
<li><a {if $pagetype eq "all"}class="current"{/if} href='index.php?pt=all' accesskey='a'><span class='key'>{$LAN_3|default:"All"}</span></a></li>
<li><a {if $pagetype eq "categories"}class="current"{/if} href='categories.php?pt=categories' accesskey='c'><span class='key'>{$LAN_40|default:"Categories"}</span></a></li>
<li><a {if $pagetype eq "images"}class="current"{/if} href='images.php?pt=images' accesskey='i'><span class='key'>{$LAN_4|default:"Images"}</span></a></li>
<li><a {if $pagetype eq "games"}class="current"{/if} href='games.php?pt=games' accesskey='r'><span class='key'>{$LAN_39|default:"Games"}</span></a></li>
<li><a {if $pagetype eq "submit"}class="current"{/if} href='submit.php?pt=submit' accesskey='r'><span class='key'>{$LAN_5|default:"Submit"}</span></a></li>
                        </ul>
	</div>
</div>
	{include file="memberbar.tpl"}