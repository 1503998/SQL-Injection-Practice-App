<?php /* Smarty version 2.6.18, created on 2010-07-27 15:58:03
         compiled from header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'header.tpl', 5, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title><?php echo ((is_array($_tmp=@$this->_tpl_vars['config_name'])) ? $this->_run_mod_handler('default', true, $_tmp, 'PHP Director') : smarty_modifier_default($_tmp, 'PHP Director')); ?>
 - <?php echo $this->_tpl_vars['title']; ?>
</title>
<meta name="description" content="<?php echo $this->_tpl_vars['desc']; ?>
" />
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
<?php echo '<script type="text/javascript">
var AdBrite_Title_Color = \'0000FF\';
var AdBrite_Text_Color = \'000000\';
var AdBrite_Background_Color = \'FFFFFF\';
var AdBrite_Border_Color = \'CCCCCC\';
var AdBrite_URL_Color = \'008000\';
try{var AdBrite_Iframe=window.top!=window.self?2:1;var AdBrite_Referrer=document.referrer==\'\'?document.location:document.referrer;AdBrite_Referrer=encodeURIComponent(AdBrite_Referrer);}catch(e){var AdBrite_Iframe=\'\';var AdBrite_Referrer=\'\';}
</script>
<span style="white-space:nowrap;"><script type="text/javascript">document.write(String.fromCharCode(60,83,67,82,73,80,84));document.write(\' src="http://ads.adbrite.com/mb/text_group.php?sid=1437777&zs=3436385f3630&ifr=\'+AdBrite_Iframe+\'&ref=\'+AdBrite_Referrer+\'" type="text/javascript">\');document.write(String.fromCharCode(60,47,83,67,82,73,80,84,62));</script>
<a target="_top" href="http://www.adbrite.com/mb/commerce/purchase_form.php?opid=1437777&afsid=1"><img src="http://files.adbrite.com/mb/images/adbrite-your-ad-here-banner.gif" style="background-color:#CCCCCC;border:none;padding:0;margin:0;" alt="Your Ad Here" width="11" height="60" border="0" /></a></span>
'; ?>

<!-- End: AdBrite -->
</p>
<div id="logo">
<h1><a href="index.php" title="home"><img src="templates/Photine/images/phpdirectorbeta.png" width="275" height="136" alt="PHP Director game edition" border="0" /></a></h1></div>
</div>
<div id='tabs'>
<ul>
<li><a <?php if ($this->_tpl_vars['pagetype'] == 'feature'): ?>class="current"<?php endif; ?> href='index.php?pt=feature' accesskey='f'><span class='key'><?php echo ((is_array($_tmp=@$this->_tpl_vars['LAN_2'])) ? $this->_run_mod_handler('default', true, $_tmp, 'Featured') : smarty_modifier_default($_tmp, 'Featured')); ?>
</span></a></li>
<li><a <?php if ($this->_tpl_vars['pagetype'] == 'all'): ?>class="current"<?php endif; ?> href='index.php?pt=all' accesskey='a'><span class='key'><?php echo ((is_array($_tmp=@$this->_tpl_vars['LAN_3'])) ? $this->_run_mod_handler('default', true, $_tmp, 'All') : smarty_modifier_default($_tmp, 'All')); ?>
</span></a></li>
<li><a <?php if ($this->_tpl_vars['pagetype'] == 'categories'): ?>class="current"<?php endif; ?> href='categories.php?pt=categories' accesskey='c'><span class='key'><?php echo ((is_array($_tmp=@$this->_tpl_vars['LAN_40'])) ? $this->_run_mod_handler('default', true, $_tmp, 'Categories') : smarty_modifier_default($_tmp, 'Categories')); ?>
</span></a></li>
<li><a <?php if ($this->_tpl_vars['pagetype'] == 'images'): ?>class="current"<?php endif; ?> href='images.php?pt=images' accesskey='i'><span class='key'><?php echo ((is_array($_tmp=@$this->_tpl_vars['LAN_4'])) ? $this->_run_mod_handler('default', true, $_tmp, 'Images') : smarty_modifier_default($_tmp, 'Images')); ?>
</span></a></li>
<li><a <?php if ($this->_tpl_vars['pagetype'] == 'games'): ?>class="current"<?php endif; ?> href='games.php?pt=games' accesskey='r'><span class='key'><?php echo ((is_array($_tmp=@$this->_tpl_vars['LAN_39'])) ? $this->_run_mod_handler('default', true, $_tmp, 'Games') : smarty_modifier_default($_tmp, 'Games')); ?>
</span></a></li>
<li><a <?php if ($this->_tpl_vars['pagetype'] == 'submit'): ?>class="current"<?php endif; ?> href='submit.php?pt=submit' accesskey='r'><span class='key'><?php echo ((is_array($_tmp=@$this->_tpl_vars['LAN_5'])) ? $this->_run_mod_handler('default', true, $_tmp, 'Submit') : smarty_modifier_default($_tmp, 'Submit')); ?>
</span></a></li>
</ul>

</div>
<?php if ($this->_tpl_vars['news'] == ""): ?>
<?php if ($this->_tpl_vars['firefox'] == '1'): ?><br />
<br />
<?php endif; ?>
<?php else: ?>
<div class="gboxtop"></div>
<div class="gbox">
	<p><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "memberbar.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></p>
</div>
<?php endif; ?>