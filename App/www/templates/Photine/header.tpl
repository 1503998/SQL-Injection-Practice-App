<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title>{$config_name|default:"PHP Director"} - {$title}</title>
<meta name="description" content="{$desc}" />
<meta http-equiv="content-type" content="text/html;charset=iso-8859-2" />
<meta name="author" content="Ben Swanson"/>
<meta name="theme" content="luka cvrk"/>
<link rel="stylesheet" href="templates/Photine/form.css" type="text/css" />
<link rel="stylesheet" href="templates/Photine/style.css" type="text/css" />
<script type="text/javascript" src="js/behavior.js"></script>
<script type="text/javascript" src="js/rating.js"></script>
<script type="text/javascript" src="js/reflection.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/thickbox.js"></script>
<script type="text/javascript" src="js/show_hide.js"></script>

</head>
<body>
<div id='content'>
<div id='header'><p id="top_info">
<!-- Begin: AdBrite, Generated: 2009-12-02 11:34:46  -->
{literal}<script type="text/javascript">
var AdBrite_Title_Color = '0000FF';
var AdBrite_Text_Color = '000000';
var AdBrite_Background_Color = 'FFFFFF';
var AdBrite_Border_Color = 'CCCCCC';
var AdBrite_URL_Color = '008000';
try{var AdBrite_Iframe=window.top!=window.self?2:1;var AdBrite_Referrer=document.referrer==''?document.location:document.referrer;AdBrite_Referrer=encodeURIComponent(AdBrite_Referrer);}catch(e){var AdBrite_Iframe='';var AdBrite_Referrer='';}
</script>
<span style="white-space:nowrap;"><script type="text/javascript">document.write(String.fromCharCode(60,83,67,82,73,80,84));document.write(' src="http://ads.adbrite.com/mb/text_group.php?sid=1437777&zs=3436385f3630&ifr='+AdBrite_Iframe+'&ref='+AdBrite_Referrer+'" type="text/javascript">');document.write(String.fromCharCode(60,47,83,67,82,73,80,84,62));</script>
<a target="_top" href="http://www.adbrite.com/mb/commerce/purchase_form.php?opid=1437777&afsid=1"><img src="http://files.adbrite.com/mb/images/adbrite-your-ad-here-banner.gif" style="background-color:#CCCCCC;border:none;padding:0;margin:0;" alt="Your Ad Here" width="11" height="60" border="0" /></a></span>
{/literal}
<!-- End: AdBrite -->
</p>
<div id="logo">
<h1><a href="index.php" title="home"><img src="templates/Photine/images/phpdirectorbeta.png" width="275" height="136" alt="PHP Director game edition" border="0" /></a></h1></div>
</div>
<div id='tabs'>
<ul>
<li><a {if $pagetype eq "feature"}class="current"{/if} href='index.php?pt=feature' accesskey='f'><span class='key'>{$LAN_2|default:"Featured"}</span></a></li>
<li><a {if $pagetype eq "all"}class="current"{/if} href='index.php?pt=all' accesskey='a'><span class='key'>{$LAN_3|default:"All"}</span></a></li>
<li><a {if $pagetype eq "categories"}class="current"{/if} href='categories.php?pt=categories' accesskey='c'><span class='key'>{$LAN_40|default:"Categories"}</span></a></li>
<li><a {if $pagetype eq "images"}class="current"{/if} href='images.php?pt=images' accesskey='i'><span class='key'>{$LAN_4|default:"Images"}</span></a></li>
<li><a {if $pagetype eq "games"}class="current"{/if} href='games.php?pt=games' accesskey='r'><span class='key'>{$LAN_39|default:"Games"}</span></a></li>
<li><a {if $pagetype eq "submit"}class="current"{/if} href='submit.php?pt=submit' accesskey='r'><span class='key'>{$LAN_5|default:"Submit"}</span></a></li>
</ul>

</div>
{if $news eq ""}
{if $firefox eq "1"}<br />
<br />
{/if}
{else}
<div class="gboxtop"></div>
<div class="gbox">
	<p>{include file="memberbar.tpl"}</p>
</div>
{/if}
