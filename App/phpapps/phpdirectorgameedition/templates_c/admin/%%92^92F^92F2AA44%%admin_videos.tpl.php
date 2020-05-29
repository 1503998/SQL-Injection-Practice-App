<?php /* Smarty version 2.6.18, created on 2009-12-07 10:48:54
         compiled from admin_videos.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php echo $this->_tpl_vars['message']; ?>

<?php unset($this->_sections['video']);
$this->_sections['video']['name'] = 'video';
$this->_sections['video']['loop'] = is_array($_loop=$this->_tpl_vars['video']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['video']['max'] = (int)1;
$this->_sections['video']['show'] = true;
if ($this->_sections['video']['max'] < 0)
    $this->_sections['video']['max'] = $this->_sections['video']['loop'];
$this->_sections['video']['step'] = 1;
$this->_sections['video']['start'] = $this->_sections['video']['step'] > 0 ? 0 : $this->_sections['video']['loop']-1;
if ($this->_sections['video']['show']) {
    $this->_sections['video']['total'] = min(ceil(($this->_sections['video']['step'] > 0 ? $this->_sections['video']['loop'] - $this->_sections['video']['start'] : $this->_sections['video']['start']+1)/abs($this->_sections['video']['step'])), $this->_sections['video']['max']);
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
<?php if ($this->_tpl_vars['video'][$this->_sections['video']['index']]['id'] == ""): ?>
<div align='center'> <font color='#FF0000' face='Arial Black' size='4'><?php echo $this->_tpl_vars['LAN_29']; ?>
</font></div>
<?php endif; ?>
<?php endfor; endif; ?>
<div align='center'> <?php unset($this->_sections['video']);
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
	<p>
	<h2><?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['name']; ?>
</h2>
	</p>
	<p> <?php if ($this->_tpl_vars['video'][$this->_sections['video']['index']]['approved'] == '1'): ?><font color='#00CC00' face='Arial Black' size='4'><?php echo $this->_tpl_vars['LAN_52']; ?>
d</font><?php else: ?><font color='#FF0000' face='Arial Black' size='4'>Un<?php echo $this->_tpl_vars['LAN_52']; ?>
d</font><?php endif; ?>
		<?php if ($this->_tpl_vars['video'][$this->_sections['video']['index']]['feature'] == '1'): ?><font color='#00CC00' face='Arial Black' size='4'>-<?php echo $this->_tpl_vars['LAN_53']; ?>
ed</font><?php endif; ?>
		<?php if ($this->_tpl_vars['video'][$this->_sections['video']['index']]['reject'] == '1'): ?><font color='#FF0000' face='Arial Black' size='4'>-<?php echo $this->_tpl_vars['LAN_54']; ?>
ed</font><?php endif; ?> <br />
	<form id="category" name="category" method="post" action="admin_videos.php?pt=<?php echo $this->_tpl_vars['pt']; ?>
&amp;page=<?php echo $this->_tpl_vars['page']; ?>
&amp;id=<?php echo $this->_tpl_vars['id']; ?>
&amp;pag=<?php echo $this->_tpl_vars['pag']; ?>
">
		<select name="category">
			
<?php unset($this->_sections['categories']);
$this->_sections['categories']['name'] = 'categories';
$this->_sections['categories']['loop'] = is_array($_loop=$this->_tpl_vars['categories']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['categories']['show'] = true;
$this->_sections['categories']['max'] = $this->_sections['categories']['loop'];
$this->_sections['categories']['step'] = 1;
$this->_sections['categories']['start'] = $this->_sections['categories']['step'] > 0 ? 0 : $this->_sections['categories']['loop']-1;
if ($this->_sections['categories']['show']) {
    $this->_sections['categories']['total'] = $this->_sections['categories']['loop'];
    if ($this->_sections['categories']['total'] == 0)
        $this->_sections['categories']['show'] = false;
} else
    $this->_sections['categories']['total'] = 0;
if ($this->_sections['categories']['show']):

            for ($this->_sections['categories']['index'] = $this->_sections['categories']['start'], $this->_sections['categories']['iteration'] = 1;
                 $this->_sections['categories']['iteration'] <= $this->_sections['categories']['total'];
                 $this->_sections['categories']['index'] += $this->_sections['categories']['step'], $this->_sections['categories']['iteration']++):
$this->_sections['categories']['rownum'] = $this->_sections['categories']['iteration'];
$this->_sections['categories']['index_prev'] = $this->_sections['categories']['index'] - $this->_sections['categories']['step'];
$this->_sections['categories']['index_next'] = $this->_sections['categories']['index'] + $this->_sections['categories']['step'];
$this->_sections['categories']['first']      = ($this->_sections['categories']['iteration'] == 1);
$this->_sections['categories']['last']       = ($this->_sections['categories']['iteration'] == $this->_sections['categories']['total']);
?>
	<option value="<?php echo $this->_tpl_vars['categories'][$this->_sections['categories']['index']]['id']; ?>
"<?php if ($this->_tpl_vars['categories_current'] == $this->_tpl_vars['categories'][$this->_sections['categories']['index']]['id']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['categories'][$this->_sections['categories']['index']]['name']; ?>
</option>
<?php endfor; endif; ?>
	
		</select>
		<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['id']; ?>
" />
		<input type="submit" name="Submit" value="Update Category" />
	</form>
	<img border='0' src='<?php if ($this->_tpl_vars['video'][$this->_sections['video']['index']]['video_type'] == 'YouTube'): ?>http://img.youtube.com/vi/<?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['file']; ?>
/1.jpg' height='100'> <img border='0' src='http://img.youtube.com/vi/<?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['file']; ?>
/2.jpg' height='100'> <img border='0' src='http://img.youtube.com/vi/<?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['file']; ?>
/3.jpg' height='100'> <?php else: ?>
	<?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['picture']; ?>
' height='100'><?php endif; ?>
	<div style='border:3px dashed #808080; position: absolute; z-index: 1; left: 200px; top: 350px; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px;' id='layer1'> <?php if ($this->_tpl_vars['video'][$this->_sections['video']['index']]['approved'] == '0'): ?> <a href='?<?php if ($this->_tpl_vars['pt'] == 'approve'): ?>id=<?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['id']; ?>
&amp;<?php endif; ?>what=approve&amp;pag=vid&amp;pt=<?php echo $this->_tpl_vars['pt']; ?>
&amp;id=<?php echo $this->_tpl_vars['id']; ?>
'><?php echo $this->_tpl_vars['LAN_52']; ?>
</a> <?php endif; ?>
		
		<?php if ($this->_tpl_vars['video'][$this->_sections['video']['index']]['feature'] == '0'): ?>
		<p><a href='?<?php if ($this->_tpl_vars['pt'] == 'approve'): ?>id=<?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['id']; ?>
&mp;<?php endif; ?>what=feature&amp;pag=vid&amp;pt=<?php echo $this->_tpl_vars['pt']; ?>
&amp;id=<?php echo $this->_tpl_vars['id']; ?>
'><?php echo $this->_tpl_vars['LAN_53']; ?>
</a></p>
		<?php else: ?>
		<p><a href='?<?php if ($this->_tpl_vars['pt'] == 'approve'): ?>id=<?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['id']; ?>
&amp;<?php endif; ?>what=unfeature&amp;pag=vid&amp;pt=<?php echo $this->_tpl_vars['pt']; ?>
&amp;id=<?php echo $this->_tpl_vars['id']; ?>
'>Un<?php echo $this->_tpl_vars['LAN_53']; ?>
</a></p>
		<?php endif; ?>
		
		<?php if ($this->_tpl_vars['video'][$this->_sections['video']['index']]['reject'] == '0'): ?> <a href='?<?php if ($this->_tpl_vars['pt'] == 'approve'): ?>id=<?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['id']; ?>
&amp;<?php endif; ?>what=reject&amp;pag=vid&amp;pt=<?php echo $this->_tpl_vars['pt']; ?>
&amp;id=<?php echo $this->_tpl_vars['id']; ?>
'><?php echo $this->_tpl_vars['LAN_54']; ?>
</a> or
		<?php endif; ?> <a href='?<?php if ($this->_tpl_vars['pt'] == 'approve'): ?>id=<?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['id']; ?>
&amp;<?php endif; ?>what=delete&amp;pag=vid&amp;pt=<?php echo $this->_tpl_vars['pt']; ?>
&amp;id=<?php echo $this->_tpl_vars['id']; ?>
'><?php echo $this->_tpl_vars['LAN_56']; ?>
</a></div>
	<br />
	<b><?php echo $this->_tpl_vars['LAN_36']; ?>
:</b><?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['creator']; ?>
 <br />
	<b><?php echo $this->_tpl_vars['LAN_35']; ?>
:</b><?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['description']; ?>
 <br />
	<br />
	<b>ID:</b><?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['id']; ?>

	<?php endfor; endif; ?> </div>
</html>