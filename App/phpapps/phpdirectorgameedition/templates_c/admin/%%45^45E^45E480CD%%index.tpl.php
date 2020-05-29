<?php /* Smarty version 2.6.18, created on 2009-12-07 10:18:35
         compiled from index.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<!---->
<h1 align="center"><?php echo $this->_tpl_vars['LAN_61']; ?>
</h1>
<p align="center">&nbsp;</p>

<h3 align="center"><?php if ($this->_tpl_vars['approves'] == 0): ?>No Items To Approve<?php else: ?><a href="admin_videos.php?pt=easyapprove&amp;pag=vid">You Have <?php echo $this->_tpl_vars['approves']; ?>
 Items to Approve </a><?php endif; ?></h3>
<p align="center"><strong>Version</strong> = <?php echo $this->_tpl_vars['version']; ?>
</p>
<p align="center"> <?php if ($this->_tpl_vars['up2date'] <= $this->_tpl_vars['version']): ?> <b>Your version is recent. Good Job!</b> <?php else: ?>Time to upgrade!
<a href='http://phpdirector.co.uk'>Upgrade!</a> <?php endif; ?> </p>
</body>
</html>