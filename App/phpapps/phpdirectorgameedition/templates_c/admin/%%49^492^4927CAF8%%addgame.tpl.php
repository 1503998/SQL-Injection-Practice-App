<?php /* Smarty version 2.6.18, created on 2010-07-27 08:35:53
         compiled from addgame.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<center>
<?php echo $this->_tpl_vars['message']; ?>

<h2 align="center"><?php echo $this->_tpl_vars['LAN_126']; ?>
</h2>
<form method="post" action="addgame.php">
<input type="hidden" value="add">
<?php echo $this->_tpl_vars['LAN_33']; ?>
 :<br />
<input type="text" name="name"><br />
<?php echo $this->_tpl_vars['LAN_118']; ?>
 :<br />
<input type="text" name="authorlink"><br />
<?php echo $this->_tpl_vars['LAN_34']; ?>
 :<br />
<input type="text" name="author"><br />
<?php echo $this->_tpl_vars['LAN_35']; ?>
 :<br />
<textarea name="description"></textarea><br />
<?php echo $this->_tpl_vars['LAN_127']; ?>
 :<br />
<input type="text" name="gameurl"><br />
<?php echo $this->_tpl_vars['LAN_128']; ?>
 :<br />
<input type="text" name="thumburl"><br />
<?php echo $this->_tpl_vars['LAN_131']; ?>
 :<br />
<select name="leaderboard">
<option value="0"><?php echo $this->_tpl_vars['LAN_132']; ?>
</option>
<option value="1"><?php echo $this->_tpl_vars['LAN_133']; ?>
</option>
</select><br />
<?php echo $this->_tpl_vars['LAN_129']; ?>
 :<br />
<input type="text" name="width"><br />
<?php echo $this->_tpl_vars['LAN_130']; ?>
 :<br />
<input type="text" name="height"><br />
<input type="submit" value="<?php echo $this->_tpl_vars['LAN_126']; ?>
">
</form>
</center>
</body>
</html>