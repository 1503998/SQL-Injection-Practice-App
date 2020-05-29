<?php $this->display('header.tpl'); ?>

<!-- 首页顶部 start -->
<div class="w index_top">
	<!-- 顶部左侧slide图片广告 start -->
	<div id="slide_box" class="slide_box" style="width:690px;height:280px;margin:0;padding:0;">
		<div class="slide_img">
			<a href="" target="_blank"><img src="" style="border:0;"></a>
		</div>
		<div class="slide-controls">
			<span href="<?php PURL(); ?>" pic="<?php echo $this->_tpl_vars['t_url']; ?>images/adv_1.jpg">1</span>
			<span href="<?php PURL(); ?>" pic="<?php echo $this->_tpl_vars['t_url']; ?>images/adv_2.jpg">2</span>
			<span href="<?php PURL(); ?>" pic="<?php echo $this->_tpl_vars['t_url']; ?>images/adv_3.jpg">3</span>
		</div>
	</div>
	<script type='text/javascript'>
		$(function(){
			var slideObj=new slide_im("#slide_box", 5000);
			slideObj.init();
		});
	</script>
	<!-- 顶部左侧slide图片广告 end -->

	<!-- 顶部右侧新闻公告 start -->
	<div id="index_news">
		<div class="divtop"><span class="span_r"><a href="<?php PURL('news'); ?>"><?php echo $this->_tpl_vars['langs']['more']; ?></a></span><span class=icon></span><a href="<?php PURL('news'); ?>"><?php echo $this->_tpl_vars['langs']['latestnews']; ?></a></div>
		<div class="news">
			<ul>
			<?php foreach($this->_tpl_vars['news'] AS $this->_tpl_vars['new']){; ?>
				<li><a href="<?php if($this->_tpl_vars['new']['linkurl']){; ?><?php echo $this->_tpl_vars['new']['linkurl']; ?><?php }else{; ?><?php PURL('news?id=' . $this->_tpl_vars['new']['n_id']); ?><?php }; ?>" target="_blank"><?php echo $this->_tpl_vars['new']['title']; ?></a> <span class=grey>(<?php echo DisplayDate($this->_tpl_vars['new']['created']); ?>)</span></li>
			<?php }; ?>
			</ul>
		</div>
	</div>
	<!-- 顶部右侧新闻公告 end -->
</div>
<!-- 首页顶部 end -->

<!-- 首页中部 start -->
<div class="w index_main">
	<!-- 中部左侧企业介绍 start -->

	<!-- 获取首页加载的常态内容, 并分配给变量 -->
	<?php $this->_tpl_vars['homecontent'] = GetContent(3); ?>
	<div class="m_left"><?php echo $this->_tpl_vars['homecontent']['content']; ?></div>
	<!-- 中部左侧企业介绍 end -->

	<!-- 中部右侧最新产品 start -->
	<div id="new_pros">
		<div class="divtop"><span class="span_r"><a href="<?php PURL('products'); ?>"><?php echo $this->_tpl_vars['langs']['more']; ?></a></span><span class=icon></span><a href="<?php PURL('products'); ?>"><?php echo $this->_tpl_vars['langs']['latestpros']; ?></a></div>
		<div class="pros">
		<?php foreach($this->_tpl_vars['newproducts'] AS $this->_tpl_vars['product']){; ?>
			<div class="thumb-sml">
			<table>
			<thead class="thumbnail_hover">
			<tr>
			<th><a href="<?php PURL('products?id=' . $this->_tpl_vars['product']['pro_id']); ?>" title="<?php echo $this->_tpl_vars['product']['title']; ?>" target="_blank"><img original="<?php PrintImageURL($this->_tpl_vars['product']['path'], $this->_tpl_vars['product']['filename']); ?>" width="80" class="thumbnail" alt="<?php echo $this->_tpl_vars['product']['title']; ?>" onMouseMove="ShowBigImage();"></a></th>
			</tr>
			</thead>
			</table>
			</div>
		<?php }; ?>
		</div>
	</div>
	<!-- 中部右侧最新产品 end -->
</div>
<!-- 首页中部 end -->

<!-- 底部推荐产品 start -->
<div class="w index_bottom">
	<div class="bottom">
		<div class="title"><span class="span_r"><a href="<?php PURL('products'); ?>"><?php echo $this->_tpl_vars['langs']['more']; ?></a></span><span class=icon></span><a href="<?php PURL('products'); ?>"><?php echo $this->_tpl_vars['langs']['reproducts']; ?></a></div>
		<div class="repros">
		<?php foreach($this->_tpl_vars['recommends'] AS $this->_tpl_vars['product']){; ?>
			<div class="thumb-lrg">
				<table>
				<thead class="thumbnail_hover">
				<tr>
					<th><a href="<?php PURL('products?id=' . $this->_tpl_vars['product']['pro_id']); ?>" title="<?php echo $this->_tpl_vars['product']['title']; ?>" target="_blank"><img original="<?php PrintImageURL($this->_tpl_vars['product']['path'], $this->_tpl_vars['product']['filename'], 2); ?>" width="160" class="thumbnail" alt="<?php echo $this->_tpl_vars['product']['title']; ?>"></a></th>
				</tr>
				</thead>
				<tr>
					<td>
						<div><a href="<?php PURL('products?id=' . $this->_tpl_vars['product']['pro_id']); ?>" title="<?php echo $this->_tpl_vars['product']['title']; ?>" target="_blank"><?php echo $this->_tpl_vars['product']['title']; ?></a></div>
					</td>
				</tr>
				</table>
			</div>
		<?php }; ?>
		</div>
	</div>
</div>
<!-- 底部推荐产品 end -->

<?php $this->display('footer.tpl'); ?>
