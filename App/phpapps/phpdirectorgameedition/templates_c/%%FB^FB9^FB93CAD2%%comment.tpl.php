<?php /* Smarty version 2.6.18, created on 2010-07-27 15:58:18
         compiled from comment.tpl */ ?>
<div id="comment">
<h1><?php echo $this->_tpl_vars['LAN_70']; ?>
</h1>
<?php if (isset ( $_COOKIE['id'] )): ?>
<?php echo $this->_tpl_vars['message']; ?>

<form method="post" action="games.php?id=<?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['id']; ?>
">
<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['id']; ?>
">
 <b><?php echo $this->_tpl_vars['LAN_71']; ?>
</b><br /><input type="text" name="nom" value="<?php echo $this->_tpl_vars['user']; ?>
"><br />
<b><?php echo $this->_tpl_vars['LAN_72']; ?>
</b><br /><textarea name="comment" row="10" cols="60"></textarea><br />
<input type="submit" name="go" value="<?php echo $this->_tpl_vars['LAN_73']; ?>
">
</form>
<br />
<?php else: ?>
<?php echo $this->_tpl_vars['LAN_94']; ?>

<?php endif; ?>
<table width="100%">
<?php unset($this->_sections['game_co']);
$this->_sections['game_co']['name'] = 'game_co';
$this->_sections['game_co']['loop'] = is_array($_loop=$this->_tpl_vars['game_co']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['game_co']['show'] = true;
$this->_sections['game_co']['max'] = $this->_sections['game_co']['loop'];
$this->_sections['game_co']['step'] = 1;
$this->_sections['game_co']['start'] = $this->_sections['game_co']['step'] > 0 ? 0 : $this->_sections['game_co']['loop']-1;
if ($this->_sections['game_co']['show']) {
    $this->_sections['game_co']['total'] = $this->_sections['game_co']['loop'];
    if ($this->_sections['game_co']['total'] == 0)
        $this->_sections['game_co']['show'] = false;
} else
    $this->_sections['game_co']['total'] = 0;
if ($this->_sections['game_co']['show']):

            for ($this->_sections['game_co']['index'] = $this->_sections['game_co']['start'], $this->_sections['game_co']['iteration'] = 1;
                 $this->_sections['game_co']['iteration'] <= $this->_sections['game_co']['total'];
                 $this->_sections['game_co']['index'] += $this->_sections['game_co']['step'], $this->_sections['game_co']['iteration']++):
$this->_sections['game_co']['rownum'] = $this->_sections['game_co']['iteration'];
$this->_sections['game_co']['index_prev'] = $this->_sections['game_co']['index'] - $this->_sections['game_co']['step'];
$this->_sections['game_co']['index_next'] = $this->_sections['game_co']['index'] + $this->_sections['game_co']['step'];
$this->_sections['game_co']['first']      = ($this->_sections['game_co']['iteration'] == 1);
$this->_sections['game_co']['last']       = ($this->_sections['game_co']['iteration'] == $this->_sections['game_co']['total']);
?>
     <tr>
	     <td width="60" align="left">
		 <img src="avatar/<?php echo $this->_tpl_vars['game_co'][$this->_sections['game_co']['index']]['comment']; ?>
" width="50" height="50" align="left">
		 </td>
		 <td width="400" valign="top"><?php echo $this->_tpl_vars['LAN_16']; ?>
 <b><?php echo $this->_tpl_vars['game_co'][$this->_sections['game_co']['index']]['nom']; ?>
</b><br />
	  <?php echo $this->_tpl_vars['game_co'][$this->_sections['game_co']['index']]['comment']; ?>
</td><tr>
	 <br />
	 <?php endfor; endif; ?>
	 </table>
	 </div>