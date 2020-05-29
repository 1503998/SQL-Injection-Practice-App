<?php /* Smarty version 2.6.18, created on 2010-07-25 21:24:42
         compiled from admin_manage.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'paginate_prev', 'admin_manage.tpl', 16, false),array('function', 'paginate_next', 'admin_manage.tpl', 16, false),array('function', 'paginate_middle', 'admin_manage.tpl', 74, false),array('modifier', 'truncate', 'admin_manage.tpl', 35, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			<?php if ($this->_tpl_vars['pagevalue'] == 'all'): ?>
				<h2><?php echo $this->_tpl_vars['LAN_68']; ?>
s</h2>   
			<?php elseif ($this->_tpl_vars['pagevalue'] == 'feature'): ?>
				<h2><?php echo $this->_tpl_vars['LAN_53']; ?>
</h2>
			<?php elseif ($this->_tpl_vars['pagevalue'] == 'approve'): ?>
				<h2><?php echo $this->_tpl_vars['LAN_52']; ?>
</h2>
			<?php elseif ($this->_tpl_vars['pagevalue'] == 'rejected'): ?>
				<h2><?php echo $this->_tpl_vars['LAN_54']; ?>
</h2>
			<?php endif; ?>

			<?php if (paginate_middle == ""): ?>
			no vids<?php else: ?>

<table cellspacing="0" cellpadding="0" border="1" id="categorias"><tbody>
<div align="center"><?php echo smarty_function_paginate_prev(array(), $this);?>
&nbsp;<?php echo smarty_function_paginate_next(array(), $this);?>
</div>
	<tr class="categoria_h">
		<th class="s1">ID</th>
		<th class="s2"><?php echo $this->_tpl_vars['LAN_33']; ?>
</th>
		<th class="s3"><?php echo $this->_tpl_vars['LAN_35']; ?>
</th>
		<th class="s4"><?php echo $this->_tpl_vars['LAN_34']; ?>
</th>
		<th class="s5"><?php echo $this->_tpl_vars['LAN_36']; ?>
</th>
		<th class="s6"><?php echo $this->_tpl_vars['LAN_4']; ?>
</th>
		<th class="s7"><?php echo $this->_tpl_vars['LAN_51']; ?>
</th>
	</tr>
<?php unset($this->_sections['video']);
$this->_sections['video']['name'] = 'video';
$this->_sections['video']['loop'] = is_array($_loop=$this->_tpl_vars['video']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['video']['show'] = true;
$this->_sections['video']['max'] = $this->_sections['video']['loop'];
$this->_sections['video']['step'] = 1;
$this->_sections['video']['start'] = $this->_sections['video']['step'] > 0 ? 0 : $this->_sections['video']['loop']-1;
if ($this->_sections['video']['show']) {
    $this->_sections['video']['total'] = $this->_sections['video']['loop'];
    if ($this->_sections['video']['total'] == 0)
        $this->_sections['video']['show'] = false;
} else
    $this->_sections['video']['total'] = 0;
if ($this->_sections['video']['show']):

            for ($this->_sections['video']['index'] = $this->_sections['video']['start'], $this->_sections['video']['iteration'] = 1;
                 $this->_sections['video']['iteration'] <= $this->_sections['video']['total'];
                 $this->_sections['video']['index'] += $this->_sections['video']['step'], $this->_sections['video']['iteration']++):
$this->_sections['video']['rownum'] = $this->_sections['video']['iteration'];
$this->_sections['video']['index_prev'] = $this->_sections['video']['index'] - $this->_sections['video']['step'];
$this->_sections['video']['index_next'] = $this->_sections['video']['index'] + $this->_sections['video']['step'];
$this->_sections['video']['first']      = ($this->_sections['video']['iteration'] == 1);
$this->_sections['video']['last']       = ($this->_sections['video']['iteration'] == $this->_sections['video']['total']);
?>	
<tr class="subcategoria">
	<td class="s1">	
		<a href="admin_videos.php?id=<?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['id']; ?>
&pag=vid" target="_blank">
		<?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['id']; ?>
</a>
	</td>
	
	<td class="s2">
		<a href="admin_videos.php?id=<?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['id']; ?>
&pag=vid" target="_blank">
		<?php echo ((is_array($_tmp=$this->_tpl_vars['video'][$this->_sections['video']['index']]['name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 80, '...') : smarty_modifier_truncate($_tmp, 80, '...')); ?>
</a>
	</td>
	
	<td class="s3">	
		<?php echo ((is_array($_tmp=$this->_tpl_vars['video'][$this->_sections['video']['index']]['description'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 250, '...') : smarty_modifier_truncate($_tmp, 250, '...')); ?>

	
	</td>
	
	<td class="s4">
		<?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['date']; ?>

	</td>
	<td class="s5">	
		<?php echo ((is_array($_tmp=$this->_tpl_vars['video'][$this->_sections['video']['index']]['creator'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 40, '...') : smarty_modifier_truncate($_tmp, 40, '...')); ?>

	</td>
	<td class="s6" align="center">
<a href='admin_videos.php?id=<?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['id']; ?>
&pag=vid'>
<img border='0' src='<?php if ($this->_tpl_vars['video'][$this->_sections['video']['index']]['video_type'] == 'YouTube'): ?><?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['ytpic']; ?>
1.jpg' height='64'><img border='0' src='<?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['ytpic']; ?>
2.jpg' height='64'><img border='0' src='<?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['ytpic']; ?>
3.jpg' height='64'>
<?php else: ?><?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['picture']; ?>
' height='64'><?php endif; ?></a>
	</td>

	<td class="s7">
<?php if ($this->_tpl_vars['video'][$this->_sections['video']['index']]['approved'] == '0'): ?>
<a href='?id=<?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['id']; ?>
&what=approve&pt=<?php echo $this->_tpl_vars['pagevalue']; ?>
&page=<?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['page']; ?>
&pag=vid'><?php echo $this->_tpl_vars['LAN_52']; ?>
</a>
<?php endif; ?>
<?php if ($this->_tpl_vars['video'][$this->_sections['video']['index']]['featured'] == '0'): ?>
<p><a href='?id=<?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['id']; ?>
&what=feature&pt=<?php echo $this->_tpl_vars['pagevalue']; ?>
&page=<?php echo $this->_tpl_vars['page']; ?>
&pag=vid'><?php echo $this->_tpl_vars['LAN_53']; ?>
</a></p>
<?php else: ?>
<p><a href='?id=<?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['id']; ?>
&what=unfeature&pt=<?php echo $this->_tpl_vars['pagevalue']; ?>
&page=<?php echo $this->_tpl_vars['page']; ?>
&pag=vid'>Un<?php echo $this->_tpl_vars['LAN_53']; ?>
</a></p>
<?php endif; ?>
<?php if ($this->_tpl_vars['video'][$this->_sections['video']['index']]['rejected'] == '0'): ?><p><a href='?id=<?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['id']; ?>
&what=reject&pt=<?php echo $this->_tpl_vars['pagevalue']; ?>
&page=<?php echo $this->_tpl_vars['page']; ?>
&pag=vid'><?php echo $this->_tpl_vars['LAN_54']; ?>
</a>
<?php echo $this->_tpl_vars['LAN_55']; ?>
<?php else: ?><p><?php endif; ?>
<a href='?id=<?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['id']; ?>
&what=delete&pt=<?php echo $this->_tpl_vars['pagevalue']; ?>
&page=<?php echo $this->_tpl_vars['page']; ?>
&pag=vid'><?php echo $this->_tpl_vars['LAN_56']; ?>
</a></p>
	</td>
	</tr>
</tbody>
<?php endfor; endif; ?>
	</table>
	</font></p>
	<br />    <p align="center">&nbsp;&nbsp;<?php echo smarty_function_paginate_prev(array(), $this);?>
&nbsp;&nbsp;<?php echo smarty_function_paginate_next(array(), $this);?>
 <br /><?php echo smarty_function_paginate_middle(array('page_limit' => '20'), $this);?>
 </p>
	<?php endif; ?>
</body>
</html>