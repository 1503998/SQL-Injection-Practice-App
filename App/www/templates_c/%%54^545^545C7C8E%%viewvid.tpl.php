<?php /* Smarty version 2.6.18, created on 2010-07-27 16:03:15
         compiled from viewvid.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'rating_bar', 'viewvid.tpl', 36, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div class="left">
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
<?php if ($this->_tpl_vars['reject'] == '1'): ?>
<?php echo $this->_tpl_vars['LAN_26']; ?>

<?php endif; ?><h2><?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['name']; ?>
&nbsp;<?php if (isset ( $_COOKIE['id'] )): ?>
<?php if (count ( $this->_tpl_vars['isfav'] ) == 0): ?>
<a href="addfavorite.php?g=<?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['id']; ?>
&u=<?php echo $this->_tpl_vars['user']; ?>
"><img src="templates/photine/images/favorites.png" border="0" title="<?php echo $this->_tpl_vars['LAN_95']; ?>
"></a>
<?php else: ?>
test
<?php endif; ?>
&nbsp;        <a target="_blank" href="mailto:?subject=<?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['name']; ?>
 - <?php echo $this->_tpl_vars['config_name']; ?>
&amp;body=http://www.play-online.bzh.be<?php echo $_SERVER['REQUEST_URI']; ?>
" title="E-mail this to a friend"><img alt="E-mail this to a friend" border="0" src="templates/photine/images/send.png" title="E-mail this to a friend" /></a>
<?php endif; ?></h2>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "players.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><br />
<div align="center">

<?php echo $this->_tpl_vars['leaderboard']; ?>

<div id="leaderboard_bridge"></div>
<?php echo $this->_tpl_vars['widget']; ?>

</div>
<br />
<table width="100%">
<h2>Classement</h2>
<?php unset($this->_sections['hscore']);
$this->_sections['hscore']['name'] = 'hscore';
$this->_sections['hscore']['loop'] = is_array($_loop=$this->_tpl_vars['hscore']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['hscore']['show'] = true;
$this->_sections['hscore']['max'] = $this->_sections['hscore']['loop'];
$this->_sections['hscore']['step'] = 1;
$this->_sections['hscore']['start'] = $this->_sections['hscore']['step'] > 0 ? 0 : $this->_sections['hscore']['loop']-1;
if ($this->_sections['hscore']['show']) {
    $this->_sections['hscore']['total'] = $this->_sections['hscore']['loop'];
    if ($this->_sections['hscore']['total'] == 0)
        $this->_sections['hscore']['show'] = false;
} else
    $this->_sections['hscore']['total'] = 0;
if ($this->_sections['hscore']['show']):

            for ($this->_sections['hscore']['index'] = $this->_sections['hscore']['start'], $this->_sections['hscore']['iteration'] = 1;
                 $this->_sections['hscore']['iteration'] <= $this->_sections['hscore']['total'];
                 $this->_sections['hscore']['index'] += $this->_sections['hscore']['step'], $this->_sections['hscore']['iteration']++):
$this->_sections['hscore']['rownum'] = $this->_sections['hscore']['iteration'];
$this->_sections['hscore']['index_prev'] = $this->_sections['hscore']['index'] - $this->_sections['hscore']['step'];
$this->_sections['hscore']['index_next'] = $this->_sections['hscore']['index'] + $this->_sections['hscore']['step'];
$this->_sections['hscore']['first']      = ($this->_sections['hscore']['iteration'] == 1);
$this->_sections['hscore']['last']       = ($this->_sections['hscore']['iteration'] == $this->_sections['hscore']['total']);
?>
     <tr>
	     <td  align="left">
		 <?php echo $this->_tpl_vars['hscore'][$this->_sections['hscore']['index']]['username']; ?>
 
	  : <?php echo $this->_tpl_vars['hscore'][$this->_sections['hscore']['index']]['thescore']; ?>
</td>
	  </tr>
	 <?php endfor; endif; ?>
	 </table>
<br /><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "comment.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>
<div id="right">
<h2><?php echo $this->_tpl_vars['LAN_74']; ?>
</h2><br />
<?php echo smarty_function_rating_bar(array('units' => '5','id' => $this->_tpl_vars['video'][$this->_sections['video']['index']]['id']), $this);?>

<b><?php echo $this->_tpl_vars['LAN_32']; ?>
: </b><?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['views']; ?>
<br />
<b><?php echo $this->_tpl_vars['LAN_36']; ?>
: </b><a href="<?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['url_creator']; ?>
" target="_blank"><?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['creator']; ?>
</a><br />
<strong><?php echo $this->_tpl_vars['LAN_35']; ?>
:</strong>
		<br />
<div id="description"><?php echo $this->_tpl_vars['video'][$this->_sections['video']['index']]['description']; ?>
</div>
<br />

<br />
<hr>
	<h2><?php echo $this->_tpl_vars['LAN_75']; ?>
</h2><br />
	<textarea id="ta1" name="ta1" rows="5" cols="32">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "players.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</textarea>
<?php endfor; endif; ?>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>