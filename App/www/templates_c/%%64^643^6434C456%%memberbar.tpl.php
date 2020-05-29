<?php /* Smarty version 2.6.18, created on 2010-07-27 15:58:03
         compiled from memberbar.tpl */ ?>
<?php if (isset ( $_COOKIE['id'] )): ?>
<img src="avatar/<?php echo $this->_tpl_vars['avatar']; ?>
" width="50" haigh="50" align="left">&nbsp;&nbsp;Hi <strong><?php echo $this->_tpl_vars['user']; ?>
</strong><br />
	&nbsp;&nbsp;<a href="avatar.php"><?php echo $this->_tpl_vars['LAN_97']; ?>
</a> - <a href="member.php"><?php echo $this->_tpl_vars['LAN_116']; ?>
</a> - <a href="favorit.php"><?php echo $this->_tpl_vars['LAN_96']; ?>
</a> - <a href="message.php"><?php echo $this->_tpl_vars['LAN_110']; ?>
<?php if ($this->_tpl_vars['nbmess'] >= 1): ?>(<font color="red"><?php echo $this->_tpl_vars['nbmess']; ?>
</font>)<?php else: ?>(<?php echo $this->_tpl_vars['nbmess']; ?>
)<?php endif; ?></a> - <a href="logout.php"><?php echo $this->_tpl_vars['LAN_92']; ?>
</a>
<?php else: ?>
<form action="login.php" method="post">
          <?php echo $this->_tpl_vars['LAN_89']; ?>
 : <input type="text" name="TB_Nom_Utilisateur" />
          <?php echo $this->_tpl_vars['LAN_90']; ?>
 : <input type="password" name="TB_Mot_de_Passe" />
          <input type="submit" name="BT_Envoyer" value="<?php echo $this->_tpl_vars['LAN_91']; ?>
" />
	 <p><a href="register.php"><?php echo $this->_tpl_vars['LAN_93']; ?>
</a></p>
</form>
<?php endif; ?>
<div id="search">
<form method="post" action="index.php" name="search1">
<p><input type="text" name="searching" class="search"/><input type="submit" value="Search"  class="button"/></p>
</form>
</div>