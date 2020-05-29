<?php /* Smarty version 2.6.18, created on 2009-12-07 10:24:17
         compiled from options.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<h2 align="center"><?php echo $this->_tpl_vars['LAN_48']; ?>
</h2>
<form action="options.php?pt=options&pag=options" method="POST">
	<p>
		<?php unset($this->_sections['options']);
$this->_sections['options']['name'] = 'options';
$this->_sections['options']['loop'] = is_array($_loop=$this->_tpl_vars['options']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['options']['show'] = true;
$this->_sections['options']['max'] = $this->_sections['options']['loop'];
$this->_sections['options']['step'] = 1;
$this->_sections['options']['start'] = $this->_sections['options']['step'] > 0 ? 0 : $this->_sections['options']['loop']-1;
if ($this->_sections['options']['show']) {
    $this->_sections['options']['total'] = $this->_sections['options']['loop'];
    if ($this->_sections['options']['total'] == 0)
        $this->_sections['options']['show'] = false;
} else
    $this->_sections['options']['total'] = 0;
if ($this->_sections['options']['show']):

            for ($this->_sections['options']['index'] = $this->_sections['options']['start'], $this->_sections['options']['iteration'] = 1;
                 $this->_sections['options']['iteration'] <= $this->_sections['options']['total'];
                 $this->_sections['options']['index'] += $this->_sections['options']['step'], $this->_sections['options']['iteration']++):
$this->_sections['options']['rownum'] = $this->_sections['options']['iteration'];
$this->_sections['options']['index_prev'] = $this->_sections['options']['index'] - $this->_sections['options']['step'];
$this->_sections['options']['index_next'] = $this->_sections['options']['index'] + $this->_sections['options']['step'];
$this->_sections['options']['first']      = ($this->_sections['options']['iteration'] == 1);
$this->_sections['options']['last']       = ($this->_sections['options']['iteration'] == $this->_sections['options']['total']);
?></p>
	<p align="center"> <?php echo $this->_tpl_vars['LAN_33']; ?>
<br />
		<input name="name" type="text" value="<?php echo $this->_tpl_vars['options'][$this->_sections['options']['index']]['name']; ?>
" size="30" maxlength="200" />
		<br />
		<br />
		<?php echo $this->_tpl_vars['LAN_67']; ?>
<br />
		<textarea name="news" cols="30" rows="2"><?php echo $this->_tpl_vars['options'][$this->_sections['options']['index']]['news']; ?>
</textarea>
		<br />
		<br />
		<?php echo $this->_tpl_vars['LAN_57']; ?>
<br />
		<input name="vids_per_page" type="text" value="<?php echo $this->_tpl_vars['options'][$this->_sections['options']['index']]['vids_per_page']; ?>
" size="2" maxlength="2" />
		<br />
		<br />
		<?php echo $this->_tpl_vars['LAN_121']; ?>
<br />
		<input name="mochi" type="text" value="<?php echo $this->_tpl_vars['options'][$this->_sections['options']['index']]['mochi_id']; ?>
" size="30" maxlength="200" />
		<br />
		<br />
		<?php echo $this->_tpl_vars['LAN_122']; ?>
<br />
		<input name="url" type="text" value="<?php echo $this->_tpl_vars['options'][$this->_sections['options']['index']]['site_url']; ?>
" size="30" maxlength="200" />
		<br />
	</p>
	<p align="center"><?php echo $this->_tpl_vars['LAN_69']; ?>
<br />
		<input name="template" type="text" value="<?php echo $this->_tpl_vars['options'][$this->_sections['options']['index']]['template']; ?>
" size="30" maxlength="200" />
	</p>
	<p align="center"><br />
		<?php echo $this->_tpl_vars['LAN_58']; ?>
<br />
		<label><input name="lang" type="radio" value="en-gb.inc.php" <?php if ($this->_tpl_vars['options'][$this->_sections['options']['index']]['lang'] == "en-gb.inc.php"): ?>checked="checked"<?php endif; ?> />English</label>
		<br />
		<label><input name="lang" type="radio" value="fr.inc.php" <?php if ($this->_tpl_vars['options'][$this->_sections['options']['index']]['lang'] == "fr.inc.php"): ?>checked="checked"<?php endif; ?> />French</label>
		<br />
		<?php endfor; endif; ?><br />
		<input type="hidden" name="options" />
		<input name="submit" type="submit" value="Edit" />
		<br />
		<span class="redsmall"><?php echo $this->_tpl_vars['LAN_66']; ?>
</span></p>
</form>
</pre>
</body>
</html>