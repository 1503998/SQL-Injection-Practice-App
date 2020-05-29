<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $this->_tpl_vars['title']; ?></title>
<meta name="description" content="<?php echo $this->_tpl_vars['description']; ?>">
<meta name="Keywords" content="<?php echo $this->_tpl_vars['keywords']; ?>">

<link rel="stylesheet" href="<?php echo $this->_tpl_vars['t_url']; ?>css/styles.css" type="text/css">
<link rel="stylesheet" href="<?php echo $this->_tpl_vars['t_url']; ?>css/menu.css" type="text/css">
<?php if(!IS_CHINESE){; ?>
<link rel="stylesheet" href="<?php echo $this->_tpl_vars['t_url']; ?>css/english.css" type="text/css"><!-- 英文页面兼容CSS -->
<?php }; ?>
<script type="text/javascript">
siteConfig={
	siteurl:"<?php echo $this->_tpl_vars['baseurl']; ?>",
	sitename:"<?php echo $this->_tpl_vars['sitename']; ?>",
	scrolltop:"<?php echo $this->_tpl_vars['langs']['backtotop']; ?>"
};
</script>

<script type="text/javascript" src="<?php echo $this->_tpl_vars['public']; ?>js/jquery-1.2.6.min.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['public']; ?>js/jquery.addon.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['public']; ?>js/common.js"></script>
</head>
<body>

<!-- 顶部导航栏 start -->
<div id="shortcut">
	<div class="w">
		<ul class="fl lh">
			<?php if(IS_CHINESE){; ?>
				<li class="fore1"><div class="cn_on" title="<?php echo $this->_tpl_vars['langs']['chinese']; ?>"></div></li>
				<li><div class="en" title="<?php echo $this->_tpl_vars['langs']['change_lan']; ?>"></div></li>
			<?php }else{; ?>
				<li class="fore1"><div class="cn" title="<?php echo $this->_tpl_vars['langs']['change_lan']; ?>"></div></li>
				<li><div class="en_on" title="<?php echo $this->_tpl_vars['langs']['english']; ?>"></div></li>
			<?php }; ?>
		</ul>
		<ul class="fr lh">
			<li class="fore1 ld"><b></b><a href="javascript:addToFavorite()"><?php echo $this->_tpl_vars['langs']['addfavorite']; ?></a></li>
			<li><a href="<?php PURL('about'); ?>"><?php echo $this->_tpl_vars['langs']['aboutus']; ?></a></li>
			<li><a href="<?php PURL('about?id=2'); ?>"><?php echo $this->_tpl_vars['langs']['contactus']; ?></a></li>
			<li class="menu">
				<dl>
					<dt class="ld"><?php echo $this->_tpl_vars['langs']['services']; ?><b></b></dt>
					<dd>
						<div><a href="<?php PURL('about?id=14'); ?>"><?php echo $this->_tpl_vars['langs']['culture']; ?></a></div>
						<div><a href="<?php PURL('about?id=15'); ?>"><?php echo $this->_tpl_vars['langs']['organization']; ?></a></div>
					</dd>
				</dl>
			</li>
			<li class="menu w1">
				<dl class="w2">
					<dt class="ld"><?php echo $this->_tpl_vars['langs']['companys']; ?><b></b></a></dt>
					<dd>
						<div><a href="<?php PURL('about?id=11'); ?>"><?php echo $this->_tpl_vars['langs']['company1']; ?></a></div>
						<div><a href="<?php PURL('about?id=12'); ?>"><?php echo $this->_tpl_vars['langs']['company2']; ?></a></div>
						<div><a href="<?php PURL('about?id=13'); ?>"><?php echo $this->_tpl_vars['langs']['company3']; ?></a></div>
					</dd>
				</dl>
			</li>
		</ul>
		<span class="clr"></span>
	</div>
</div>
<!-- 顶部导航栏 end -->

<!-- Logo栏 start -->
<div id="header" class="w">
	<div id="logo"><a href="<?php PURL(); ?>" hidefocus="true" title="<?php echo $this->_tpl_vars['sitename']; ?>"><img src="<?php echo $this->_tpl_vars['t_url']; ?>images/logo.png" width="260" height="80" alt="<?php echo $this->_tpl_vars['sitename']; ?>"></a></div>
	<div id="top_adv"><img src="<?php echo $this->_tpl_vars['t_url']; ?>images/top_adv<?php echo rand(1, 6); ?>.png"></div>
</div>
<!-- Logo栏 end -->

<!-- 菜单栏 start -->
<div class="w topmenu">
	<div id="menu">
		<ul class="sf-menu">
			<li><a href="<?php PURL(); ?>" hidefocus="true" <?php if($this->_tpl_vars['menu']=='home'){; ?>class="on"<?php }; ?>><?php echo $this->_tpl_vars['langs']['home']; ?></a></li>
			<li><a href="<?php PURL('news'); ?>" hidefocus="true" <?php if($this->_tpl_vars['menu']=='news'){; ?>class="on"<?php }; ?>><?php echo $this->_tpl_vars['langs']['news']; ?></a></li>
			<li>
				<a href="<?php PURL('products'); ?>" hidefocus="true" <?php if($this->_tpl_vars['menu']=='products'){; ?>class="on"<?php }; ?>><?php echo $this->_tpl_vars['langs']['products']; ?></a>
				<?php echo $this->_tpl_vars['pcategories']; ?>
			</li>
			<li><a href="<?php PURL('about'); ?>" hidefocus="true" <?php if($this->_tpl_vars['menu']=='about'){; ?>class="on"<?php }; ?>><?php echo $this->_tpl_vars['langs']['aboutus']; ?></a>
				<ul>
					<li><a href="<?php PURL('about?id=14'); ?>"><?php echo $this->_tpl_vars['langs']['culture']; ?></a></li>
					<li><a href="<?php PURL('about?id=15'); ?>"><?php echo $this->_tpl_vars['langs']['organization']; ?></a></li>
					<li><a href="<?php PURL('about?id=11'); ?>"><?php echo $this->_tpl_vars['langs']['company1']; ?></a></li>
					<li><a href="<?php PURL('about?id=12'); ?>"><?php echo $this->_tpl_vars['langs']['company2']; ?></a></li>
					<li><a href="<?php PURL('about?id=13'); ?>"><?php echo $this->_tpl_vars['langs']['company3']; ?></a></li>
				</ul>
			</li>
			<li><a href="<?php PURL('about?id=2'); ?>" hidefocus="true" <?php if($this->_tpl_vars['menu']=='contact'){; ?>class="on"<?php }; ?>><?php echo $this->_tpl_vars['langs']['contactus']; ?></a></li>
		</ul>


	</div>
	<div id="searchform">
		<form action="<?php PURL('products'); ?>" method="post" id="search_form" onSubmit="return CheckSpace('searchkey', '<?php echo $this->_tpl_vars['langs']['search_err']; ?>');">
			<span class=searchicon></span>
			<input type="text" name="s" id="searchkey" value="<?php echo $this->_tpl_vars['keyword']; ?>">&nbsp;
			<input type="submit" class="submit" id="search_submit" name="searchbtn" value="<?php echo $this->_tpl_vars['langs']['search']; ?>" hidefocus="true">
		</form>
	</div>
</div>
<!-- 菜单栏 end -->

<!-- 当前位置导航栏 start -->
<?php if($this->_tpl_vars['pagenav']){; ?>
<div class="w">
	<div class="nav">
		<div id="pagenav"><span class=navicon></span><?php echo $this->_tpl_vars['langs']['yourarehere']; ?>&nbsp;&nbsp;<?php echo $this->_tpl_vars['pagenav']; ?></div>
	</div>
</div>
<?php }; ?>
<!-- 当前位置导航栏 end -->

<script type="text/javascript">
(function() {
	//固定顶部Div不随页面滚动
	js_scrolly({
		id:'shortcut', l:0, t:0, f:1
	});

	$("#shortcut .menu").Jdropdown({
		delay: 50
	});

	//加载scrolltop
	scrolltotop.init();

	//切换语言动作
	$("#shortcut .cn").click(function(){
		setCookie('<?php echo COOKIE_KEY; ?>lang', 'Chinese', 30);
		document.location=window.location.href.replace(/#[\w]*/ig, '');
	});
	$("#shortcut .en").click(function(){
		setCookie('<?php echo COOKIE_KEY; ?>lang', 'English', 30);
		document.location=window.location.href.replace(/#[\w]*/ig, '');
	});

	//JQuery多级菜单
	$("ul.sf-menu").superfish();

	//搜索关键词变化
	var searchkey_obj =$("#searchkey");
	var keyword = searchkey_obj.val();
	searchkey_obj.bind("focus",function(){
		if (this.value==keyword){
			this.value="";
			this.style.color="#333";
			this.style.background="#FFF";
			this.style.borderColor="#CC3300";
		}
	}).bind("blur",function(){
		if (!this.value){
			this.value=keyword;
			this.style.color="#999";
			this.style.background="#d8d8d8";
			this.style.borderColor="#3C3C3C";
		}
	});
})();
</script>