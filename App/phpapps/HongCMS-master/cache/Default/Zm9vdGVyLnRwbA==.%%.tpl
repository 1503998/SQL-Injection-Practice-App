<div class="w footer">
	<div id="foot">
	    <div>
		<p id="footeradv"><?php echo $this->_tpl_vars['langs']['footer_ad']; ?></p>
		<p id="message">
			<a href="<?php PURL(); ?>"><?php echo $this->_tpl_vars['langs']['home']; ?></a><em>|</em>
			<a href="<?php PURL('news'); ?>"><?php echo $this->_tpl_vars['langs']['news']; ?></a><em>|</em>
			<a href="<?php PURL('products'); ?>"><?php echo $this->_tpl_vars['langs']['products']; ?></a><em>|</em>
			<a href="<?php PURL('about'); ?>"><?php echo $this->_tpl_vars['langs']['aboutus']; ?></a><em>|</em>
			<a href="<?php PURL('about?id=2'); ?>"><?php echo $this->_tpl_vars['langs']['contactus']; ?></a>
			<?php if($this->_tpl_vars['sitebeian']){; ?>
				<em>|</em>
				<a href="http://www.miibeian.gov.cn/" target="_blank"><?php echo $this->_tpl_vars['sitebeian']; ?></a>
			<?php }; ?>
		</p>
		<p id="copy">&copy; <?php echo date("Y"); ?> <a href="<?php PURL(); ?>"><?php echo $this->_tpl_vars['sitename']; ?></a> <?php Debug(); ?></p>
	    </div>
	</div>
</div>
</body>
</html>
