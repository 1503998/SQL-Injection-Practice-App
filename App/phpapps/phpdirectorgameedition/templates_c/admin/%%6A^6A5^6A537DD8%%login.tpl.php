<?php /* Smarty version 2.6.18, created on 2009-12-01 16:01:08
         compiled from login.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<font color="#990000"><?php echo $this->_tpl_vars['error']; ?>
&nbsp;</font>
<form action="login.php?do=login" method="post" onsubmit="return aValidator();" id="frmLogin"> 
  <div align="center">
			<?php echo $this->_tpl_vars['LAN_62']; ?>
:
			<input type="text" name="username" id="txtUserId" />
	 		<?php echo $this->_tpl_vars['LAN_63']; ?>
:
			<input type="password" name="password" id="txtPassword" />

	<div style="visibility:hidden">	<input name="remme" type="checkbox" checked="checked" /></div>
			<input type="submit" name="submit" value="Login"  id="btnLogin" />
			</div>
        </form>
		
</body>
</html>