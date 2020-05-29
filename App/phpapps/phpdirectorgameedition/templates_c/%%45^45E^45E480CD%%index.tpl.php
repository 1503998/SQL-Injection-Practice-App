<?php /* Smarty version 2.6.18, created on 2010-07-27 16:01:46
         compiled from index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'paginate_prev', 'index.tpl', 26, false),array('function', 'paginate_next', 'index.tpl', 26, false),array('function', 'paginate_middle', 'index.tpl', 124, false),array('modifier', 'truncate', 'index.tpl', 94, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<!---->

<div id="right"> <br />
	<div class="boxtop"></div>
	<div class="box">
	
		<p align="center">
		<b><?php echo $this->_tpl_vars['LAN_7']; ?>
</b>
		<br /><br />
			&nbsp;<?php echo $this->_tpl_vars['LAN_31']; ?>

			<!--Sort By Rating-->
			<a href="?sort=rating&amp;order=up&amp;next=<?php echo $this->_tpl_vars['next']; ?>
&amp;pt=<?php echo $this->_tpl_vars['pagetype']; ?>
"><img src="templates/Photine/images/arrowup.gif" alt="error" border="0"/></a> <a href="?sort=rating&amp;order=down&amp;next=<?php echo $this->_tpl_vars['next']; ?>
&amp;pt=<?php echo $this->_tpl_vars['pagetype']; ?>
" ><img src="templates/Photine/images/arrowdown.gif" border="0" alt="error"/></a>&nbsp;&nbsp;	&nbsp;	&nbsp;		<?php echo $this->_tpl_vars['LAN_32']; ?>

			<!--Sort By Views-->
			<a href="?sort=views&amp;order=up&amp;next=<?php echo $this->_tpl_vars['next']; ?>
&amp;pt=<?php echo $this->_tpl_vars['pagetype']; ?>
"><img src="templates/Photine/images/arrowup.gif" border="0" alt="error"/></a> <a href="?sort=views&amp;order=down&amp;next=<?php echo $this->_tpl_vars['next']; ?>
&amp;pt=<?php echo $this->_tpl_vars['pagetype']; ?>
"><img src="templates/Photine/images/arrowdown.gif" border="0" alt="error"/></a><br />
			&nbsp;<?php echo $this->_tpl_vars['LAN_33']; ?>

			<!--Sort By Name-->
			<a href="?sort=name&amp;order=up&amp;next=<?php echo $this->_tpl_vars['next']; ?>
&amp;pt=<?php echo $this->_tpl_vars['pagetype']; ?>
"><img src="templates/Photine/images/arrowup.gif" border="0" alt="error"/></a> <a href="?sort=name&amp;order=down&amp;next=<?php echo $this->_tpl_vars['next']; ?>
&amp;pt=<?php echo $this->_tpl_vars['pagetype']; ?>
"><img src="templates/Photine/images/arrowdown.gif" border="0" alt="error"/></a>&nbsp;	&nbsp;	&nbsp;	&nbsp;	<?php echo $this->_tpl_vars['LAN_34']; ?>

			<!--Sort By Date-->
			<a href="?sort=date&amp;order=up&amp;next=<?php echo $this->_tpl_vars['next']; ?>
&amp;pt=<?php echo $this->_tpl_vars['pagetype']; ?>
"> <img src="templates/Photine/images/arrowup.gif" border="0" alt="error"/></a> <a href="?sort=date&amp;order=down&amp;next=<?php echo $this->_tpl_vars['next']; ?>
&amp;pt=<?php echo $this->_tpl_vars['pagetype']; ?>
"><img src="templates/Photine/images/arrowdown.gif" border="0" alt="error"/></a><br />
			<?php echo smarty_function_paginate_prev(array(), $this);?>
&nbsp;&nbsp;<?php echo smarty_function_paginate_next(array(), $this);?>
</p>
	</div>
	<div class="boxtop"></div>
	<div class="box">
	<p align="center">
		<b><?php echo $this->_tpl_vars['LAN_76']; ?>
</b></p>
		<br /><br />
						<?php unset($this->_sections['lastcomment']);
$this->_sections['lastcomment']['name'] = 'lastcomment';
$this->_sections['lastcomment']['loop'] = is_array($_loop=$this->_tpl_vars['lastcomment']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['lastcomment']['show'] = true;
$this->_sections['lastcomment']['max'] = $this->_sections['lastcomment']['loop'];
$this->_sections['lastcomment']['step'] = 1;
$this->_sections['lastcomment']['start'] = $this->_sections['lastcomment']['step'] > 0 ? 0 : $this->_sections['lastcomment']['loop']-1;
if ($this->_sections['lastcomment']['show']) {
    $this->_sections['lastcomment']['total'] = $this->_sections['lastcomment']['loop'];
    if ($this->_sections['lastcomment']['total'] == 0)
        $this->_sections['lastcomment']['show'] = false;
} else
    $this->_sections['lastcomment']['total'] = 0;
if ($this->_sections['lastcomment']['show']):

            for ($this->_sections['lastcomment']['index'] = $this->_sections['lastcomment']['start'], $this->_sections['lastcomment']['iteration'] = 1;
                 $this->_sections['lastcomment']['iteration'] <= $this->_sections['lastcomment']['total'];
                 $this->_sections['lastcomment']['index'] += $this->_sections['lastcomment']['step'], $this->_sections['lastcomment']['iteration']++):
$this->_sections['lastcomment']['rownum'] = $this->_sections['lastcomment']['iteration'];
$this->_sections['lastcomment']['index_prev'] = $this->_sections['lastcomment']['index'] - $this->_sections['lastcomment']['step'];
$this->_sections['lastcomment']['index_next'] = $this->_sections['lastcomment']['index'] + $this->_sections['lastcomment']['step'];
$this->_sections['lastcomment']['first']      = ($this->_sections['lastcomment']['iteration'] == 1);
$this->_sections['lastcomment']['last']       = ($this->_sections['lastcomment']['iteration'] == $this->_sections['lastcomment']['total']);
?>
      <p><a href="games.php?id=<?php echo $this->_tpl_vars['lastcomment'][$this->_sections['lastcomment']['index']]['file_id']; ?>
" alt="Play this game" title="Play this game" >
	  <?php echo $this->_tpl_vars['lastcomment'][$this->_sections['lastcomment']['index']]['comment']; ?>
</a>&nbsp <br /><?php echo $this->_tpl_vars['LAN_16']; ?>
 <b><?php echo $this->_tpl_vars['lastcomment'][$this->_sections['lastcomment']['index']]['nom']; ?>
</b><p><br />
	 <?php endfor; endif; ?>	
	 </div>
	 <div class="boxtop"></div>
	<div class="box">
	<p align="center">
		<b>Welcome to</b>
		<br /><br />

   <table border="0" cellspacing="20" >      
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['lastuser']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['step'] = ((int)3) == 0 ? 1 : (int)3;
$this->_sections['i']['max'] = (int)9;
$this->_sections['i']['show'] = true;
if ($this->_sections['i']['max'] < 0)
    $this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = min(ceil(($this->_sections['i']['step'] > 0 ? $this->_sections['i']['loop'] - $this->_sections['i']['start'] : $this->_sections['i']['start']+1)/abs($this->_sections['i']['step'])), $this->_sections['i']['max']);
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>

<tr valign="top" >
<?php unset($this->_sections['last']);
$this->_sections['last']['name'] = 'last';
$this->_sections['last']['loop'] = is_array($_loop=$this->_tpl_vars['lastuser']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['last']['start'] = (int)$this->_sections['i']['index'];
$this->_sections['last']['max'] = (int)3;
$this->_sections['last']['show'] = true;
if ($this->_sections['last']['max'] < 0)
    $this->_sections['last']['max'] = $this->_sections['last']['loop'];
$this->_sections['last']['step'] = 1;
if ($this->_sections['last']['start'] < 0)
    $this->_sections['last']['start'] = max($this->_sections['last']['step'] > 0 ? 0 : -1, $this->_sections['last']['loop'] + $this->_sections['last']['start']);
else
    $this->_sections['last']['start'] = min($this->_sections['last']['start'], $this->_sections['last']['step'] > 0 ? $this->_sections['last']['loop'] : $this->_sections['last']['loop']-1);
if ($this->_sections['last']['show']) {
    $this->_sections['last']['total'] = min(ceil(($this->_sections['last']['step'] > 0 ? $this->_sections['last']['loop'] - $this->_sections['last']['start'] : $this->_sections['last']['start']+1)/abs($this->_sections['last']['step'])), $this->_sections['last']['max']);
    if ($this->_sections['last']['total'] == 0)
        $this->_sections['last']['show'] = false;
} else
    $this->_sections['last']['total'] = 0;
if ($this->_sections['last']['show']):

            for ($this->_sections['last']['index'] = $this->_sections['last']['start'], $this->_sections['last']['iteration'] = 1;
                 $this->_sections['last']['iteration'] <= $this->_sections['last']['total'];
                 $this->_sections['last']['index'] += $this->_sections['last']['step'], $this->_sections['last']['iteration']++):
$this->_sections['last']['rownum'] = $this->_sections['last']['iteration'];
$this->_sections['last']['index_prev'] = $this->_sections['last']['index'] - $this->_sections['last']['step'];
$this->_sections['last']['index_next'] = $this->_sections['last']['index'] + $this->_sections['last']['step'];
$this->_sections['last']['first']      = ($this->_sections['last']['iteration'] == 1);
$this->_sections['last']['last']       = ($this->_sections['last']['iteration'] == $this->_sections['last']['total']);
?>
<td width="55px">
<a href="user.php?u=<?php echo $this->_tpl_vars['lastuser'][$this->_sections['last']['index']]['user']; ?>
"><img height='50' width='50' border='0' src="avatar/<?php echo $this->_tpl_vars['lastuser'][$this->_sections['last']['index']]['avatar']; ?>
" class="thumbnail" title="since : <?php echo $this->_tpl_vars['lastuser'][$this->_sections['last']['index']]['register']; ?>
" alt="since : <?php echo $this->_tpl_vars['lastuser'][$this->_sections['last']['index']]['register']; ?>
" />
<br><br><h4><?php echo $this->_tpl_vars['lastuser'][$this->_sections['last']['index']]['user']; ?>
</a></h4>






</td>
      

      
   <?php endfor; endif; ?>
        </tr>

   <?php endfor; endif; ?>
  </table>   	
	 </p>
	 </div>
	
</div>

<div align="left">

<object class="flash" type="application/x-shockwave-flash" data="recentplayer.swf?x=recent.php&t=Games actually played Right Now..." width="550" height="110" align="middle">
       <param name="allowScriptAccess" value="sameDomain">

       <param name="movie" value="recentplayer.swf?x=recent.php&t=Games actually played Right Now...">
       <param name="quality" value="high">
       <param name="bgcolor" value="#FFFFFF">
     </object>

	<div class="left">
	<?php if (isset ( $_GET['act'] ) && $_GET['act'] == 'Arcade'): ?>
<?php echo $this->_tpl_vars['msg']; ?>

<?php else: ?>
	<?php unset($this->_sections['mysec']);
$this->_sections['mysec']['name'] = 'mysec';
$this->_sections['mysec']['loop'] = is_array($_loop=$this->_tpl_vars['videos']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['mysec']['show'] = true;
$this->_sections['mysec']['max'] = $this->_sections['mysec']['loop'];
$this->_sections['mysec']['step'] = 1;
$this->_sections['mysec']['start'] = $this->_sections['mysec']['step'] > 0 ? 0 : $this->_sections['mysec']['loop']-1;
if ($this->_sections['mysec']['show']) {
    $this->_sections['mysec']['total'] = $this->_sections['mysec']['loop'];
    if ($this->_sections['mysec']['total'] == 0)
        $this->_sections['mysec']['show'] = false;
} else
    $this->_sections['mysec']['total'] = 0;
if ($this->_sections['mysec']['show']):

            for ($this->_sections['mysec']['index'] = $this->_sections['mysec']['start'], $this->_sections['mysec']['iteration'] = 1;
                 $this->_sections['mysec']['iteration'] <= $this->_sections['mysec']['total'];
                 $this->_sections['mysec']['index'] += $this->_sections['mysec']['step'], $this->_sections['mysec']['iteration']++):
$this->_sections['mysec']['rownum'] = $this->_sections['mysec']['iteration'];
$this->_sections['mysec']['index_prev'] = $this->_sections['mysec']['index'] - $this->_sections['mysec']['step'];
$this->_sections['mysec']['index_next'] = $this->_sections['mysec']['index'] + $this->_sections['mysec']['step'];
$this->_sections['mysec']['first']      = ($this->_sections['mysec']['iteration'] == 1);
$this->_sections['mysec']['last']       = ($this->_sections['mysec']['iteration'] == $this->_sections['mysec']['total']);
?>

   <table border="0" cellspacing="20" >      
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['videos']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['step'] = ((int)3) == 0 ? 1 : (int)3;
$this->_sections['i']['max'] = (int)15;
$this->_sections['i']['show'] = true;
if ($this->_sections['i']['max'] < 0)
    $this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = min(ceil(($this->_sections['i']['step'] > 0 ? $this->_sections['i']['loop'] - $this->_sections['i']['start'] : $this->_sections['i']['start']+1)/abs($this->_sections['i']['step'])), $this->_sections['i']['max']);
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>

<tr valign="top" >
<?php unset($this->_sections['mysec']);
$this->_sections['mysec']['name'] = 'mysec';
$this->_sections['mysec']['loop'] = is_array($_loop=$this->_tpl_vars['videos']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['mysec']['start'] = (int)$this->_sections['i']['index'];
$this->_sections['mysec']['max'] = (int)3;
$this->_sections['mysec']['show'] = true;
if ($this->_sections['mysec']['max'] < 0)
    $this->_sections['mysec']['max'] = $this->_sections['mysec']['loop'];
$this->_sections['mysec']['step'] = 1;
if ($this->_sections['mysec']['start'] < 0)
    $this->_sections['mysec']['start'] = max($this->_sections['mysec']['step'] > 0 ? 0 : -1, $this->_sections['mysec']['loop'] + $this->_sections['mysec']['start']);
else
    $this->_sections['mysec']['start'] = min($this->_sections['mysec']['start'], $this->_sections['mysec']['step'] > 0 ? $this->_sections['mysec']['loop'] : $this->_sections['mysec']['loop']-1);
if ($this->_sections['mysec']['show']) {
    $this->_sections['mysec']['total'] = min(ceil(($this->_sections['mysec']['step'] > 0 ? $this->_sections['mysec']['loop'] - $this->_sections['mysec']['start'] : $this->_sections['mysec']['start']+1)/abs($this->_sections['mysec']['step'])), $this->_sections['mysec']['max']);
    if ($this->_sections['mysec']['total'] == 0)
        $this->_sections['mysec']['show'] = false;
} else
    $this->_sections['mysec']['total'] = 0;
if ($this->_sections['mysec']['show']):

            for ($this->_sections['mysec']['index'] = $this->_sections['mysec']['start'], $this->_sections['mysec']['iteration'] = 1;
                 $this->_sections['mysec']['iteration'] <= $this->_sections['mysec']['total'];
                 $this->_sections['mysec']['index'] += $this->_sections['mysec']['step'], $this->_sections['mysec']['iteration']++):
$this->_sections['mysec']['rownum'] = $this->_sections['mysec']['iteration'];
$this->_sections['mysec']['index_prev'] = $this->_sections['mysec']['index'] - $this->_sections['mysec']['step'];
$this->_sections['mysec']['index_next'] = $this->_sections['mysec']['index'] + $this->_sections['mysec']['step'];
$this->_sections['mysec']['first']      = ($this->_sections['mysec']['iteration'] == 1);
$this->_sections['mysec']['last']       = ($this->_sections['mysec']['iteration'] == $this->_sections['mysec']['total']);
?>
<td width="160px">
<a href="games.php?id=<?php echo $this->_tpl_vars['videos'][$this->_sections['mysec']['index']]['id']; ?>
"><img height='120' width='160' src="<?php echo $this->_tpl_vars['videos'][$this->_sections['mysec']['index']]['picture']; ?>
" class="thumbnail" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['videos'][$this->_sections['mysec']['index']]['description'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 132, '...') : smarty_modifier_truncate($_tmp, 132, '...')); ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['videos'][$this->_sections['mysec']['index']]['description'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 132, '...') : smarty_modifier_truncate($_tmp, 132, '...')); ?>
" /></a>
<br><br><br><br><br><br><br><h4><a href="games.php?id=<?php echo $this->_tpl_vars['videos'][$this->_sections['mysec']['index']]['id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['videos'][$this->_sections['mysec']['index']]['name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 32, '...') : smarty_modifier_truncate($_tmp, 32, '...')); ?>
</a></h4>






</td>
      

      
   <?php endfor; endif; ?>
        </tr>

   <?php endfor; endif; ?>
  </table>   
      
</div>
   
   <?php endfor; else: ?>
   
   No Results
   
   <?php endif; ?>	

</div>
	
<div class="left"> 
	<p align="center">&nbsp;&nbsp;<?php echo smarty_function_paginate_prev(array(), $this);?>
&nbsp;&nbsp;<?php echo smarty_function_paginate_next(array(), $this);?>
 <br /><?php echo smarty_function_paginate_middle(array('page_limit' => '20'), $this);?>
 </p>
	
</div>
 <?php endif; ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>