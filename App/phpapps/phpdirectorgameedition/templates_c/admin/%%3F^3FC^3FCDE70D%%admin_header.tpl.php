<?php /* Smarty version 2.6.18, created on 2010-07-25 21:32:12
         compiled from admin_header.tpl */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $this->_tpl_vars['config_name']; ?>
 - <?php echo $this->_tpl_vars['LAN_6']; ?>
</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type" />
<meta content="Copyright 2007 Cross Star Studios" name="copyright" />
<meta content="Ben Swanson and Dennis Berko" name="author" />
<link media="screen" type="text/css" href="../templates/Photine/admin/admin_main.css" rel="stylesheet"/>
<!--

<rdf:RDF xmlns="http://web.resource.org/cc/"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">
<Work rdf:about="">
   <license rdf:resource="http://creativecommons.org/licenses/GPL/2.0/" />
   <dc:type rdf:resource="http://purl.org/dc/dcmitype/Software" />
</Work>

<License rdf:about="http://creativecommons.org/licenses/GPL/2.0/">
<permits rdf:resource="http://web.resource.org/cc/Reproduction" />
   <permits rdf:resource="http://web.resource.org/cc/Distribution" />
   <requires rdf:resource="http://web.resource.org/cc/Notice" />
   <permits rdf:resource="http://web.resource.org/cc/DerivativeWorks" />
   <requires rdf:resource="http://web.resource.org/cc/ShareAlike" />
   <requires rdf:resource="http://web.resource.org/cc/SourceCode" />
</License>

</rdf:RDF>

-->
</head>
<body>
<div id="admin-header">
  <h1><?php echo $this->_tpl_vars['config_name']; ?>
 - <?php echo $this->_tpl_vars['LAN_6']; ?>
</h1>
</div>

<ul id="admin-menu">
<li><a href="../index.php"><?php echo $this->_tpl_vars['LAN_41']; ?>
</a></li><li <?php if ($this->_tpl_vars['pag'] == 'vid'): ?>class='selected'<?php endif; ?>> <a href="admin_manage.php?pag=vid"><?php echo $this->_tpl_vars['LAN_68']; ?>
</a> </li><li <?php if ($this->_tpl_vars['pag'] == 'options'): ?>class='selected'<?php endif; ?>><a href="options.php?pt=options&amp;pag=options"><?php echo $this->_tpl_vars['LAN_48']; ?>
</a> </li><li><a href="logout.php"><?php echo $this->_tpl_vars['LAN_42']; ?>
</a></li>
</ul>

<ul id="admin-submenu">
<?php if ($this->_tpl_vars['pag'] == 'vid'): ?>
<li <?php if ($this->_tpl_vars['pagetype'] == 'all'): ?>class='selected'<?php endif; ?>><a href="admin_manage.php?pt=all&amp;pag=vid"><?php echo $this->_tpl_vars['LAN_43']; ?>
</a></li>
<li <?php if ($this->_tpl_vars['pagetype'] == 'feature'): ?>class='selected'<?php endif; ?>><a href="admin_manage.php?pt=feature&amp;pag=vid&amp;next=1"><?php echo $this->_tpl_vars['LAN_45']; ?>
</a></li>
<li <?php if ($this->_tpl_vars['pagetype'] == 'approve'): ?>class='selected'<?php endif; ?>><a href="admin_manage.php?pt=approve&amp;pag=vid&amp;next=1"><?php echo $this->_tpl_vars['LAN_44']; ?>
</a></li>
<li <?php if ($this->_tpl_vars['pagetype'] == 'rejected'): ?>class='selected'<?php endif; ?>><a href="admin_manage.php?pt=rejected&amp;pag=vid&amp;next=1"><?php echo $this->_tpl_vars['LAN_46']; ?>
</a></li>
<li <?php if ($this->_tpl_vars['pagetype'] == 'easyapprove'): ?>class='selected'<?php endif; ?>><a href="admin_videos.php?pt=easyapprove&amp;pag=vid"><?php echo $this->_tpl_vars['LAN_47']; ?>
</a></li>
<li <?php if ($this->_tpl_vars['pagetype'] == 'edit'): ?>class='selected'<?php endif; ?>><a href="videos_manage.php?pt=edit"><?php echo $this->_tpl_vars['LAN_79']; ?>
</a></li>
<li><a href="addgame.php"><?php echo $this->_tpl_vars['LAN_126']; ?>
</a></li>
<?php endif; ?>
<?php if ($this->_tpl_vars['pag'] == 'options'): ?>
<li <?php if ($this->_tpl_vars['pagetype'] == 'options'): ?>class='selected'<?php endif; ?>><a href="options.php?pt=options&amp;pag=options"><?php echo $this->_tpl_vars['LAN_48']; ?>
</a></li>
<li <?php if ($this->_tpl_vars['pagetype'] == 'categories'): ?>class='selected'<?php endif; ?>><a href="categories.php?pt=categories&amp;pag=options"><?php echo $this->_tpl_vars['LAN_40']; ?>
</a></li>
<li <?php if ($this->_tpl_vars['pagetype'] == 'comment'): ?>class='selected'<?php endif; ?>><a href="comment.php?pt=comment&amp;pag=options"><?php echo $this->_tpl_vars['LAN_78']; ?>
</a></li>
<?php endif; ?>
</ul>
<?php if ($this->_tpl_vars['message'] == ""): ?><?php else: ?><p><h1 align="center"><?php echo $this->_tpl_vars['message1']; ?>
</h1></p><?php endif; ?>