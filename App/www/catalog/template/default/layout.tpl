<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html dir="<?php echo $direction; ?>" lang="<?php echo $code; ?>">
<head> 
<title><?php if ($head_def->meta_title){echo $head_def->get_MetaTitle(); } else if  ($meta_title){echo $meta_title;} else {echo $title;} ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset; ?>">
<?php if ($head_def->meta_description){ echo $head_def->get_MetaDescription()."\n"; } else if  ($meta_description){echo $meta_description."\n";}else { ?>
<meta name="description" content="* Meta Description Goes here *">
<?php } ?>
<?php if ($head_def->meta_keywords){ echo $head_def->get_MetaKeywords() ."\n"; } else if ($meta_keywords){echo $meta_keywords. "\n";} else { ?>
<meta name="keywords" content=" * Meta Keywords go here *">
<?php } ?>
<meta name="robots" content="INDEX, FOLLOW">
<base href="<?php echo $base; ?>">
<link rel="stylesheet" type="text/css" href="catalog/styles/<?php echo $this->style; ?>/css/default.css">
<?php                     // New CSS Generator
	if ($head_def->CssDef){
		foreach ($head_def->CssDef as $pagecss){
			echo $pagecss."\n";
		}
    }
?>
<?php $page_color = isset($template_color) ? $template_color : $this->color;?>
<link rel="stylesheet" type="text/css" href="catalog/styles/<?php echo $this->style; ?>/colors/<?php echo $page_color;?>">
<?php
	if($head_def->java_script){
		foreach ($head_def->java_script as $pagejs){
			echo $pagejs."\n";
		}
	}
?>
<link rel="alternate" type="application/rss+xml" title="<?php echo $title; ?>" href="rss.php">
<!--[if lte IE 6]>
  <link rel="stylesheet" type="text/css" href="catalog/styles/<?php echo $this->style; ?>/css/ie6fix.css">
<![endif]-->
</head>
<body>
<noscript><div class="noscript"><?php echo $noscript; ?></div></noscript>
<div id="container">
  <div id="header">
	<?php if (isset($language)) { echo $language; }?>
    <?php if (isset($currency)) { echo $currency; }?>
	<?php if (isset($header)) { echo $header; } ?>
	<?php if (isset($search)) { echo $search; }?>
  <div id="bar">
	<?php if (isset($navigation)) { echo $navigation;}?>
  </div></div>
  <div id="column">
    <?php if (isset($cart)) {echo $cart;} ?>
    <?php if (isset($category)) {echo $category;} ?>
	<?php if (isset($manufacturer)) { echo $manufacturer;} ?>
    <?php if (isset($popular)) {echo $popular;} ?>	
	<?php if (isset($review)) {echo $review;} ?>
	<?php if (isset($information)) { echo $information;}?>
  </div>
  <div id="content">
    <?php 
        if (isset($content)) {echo $content;}
		if (isset($homepage)) {echo $homepage;}
        if (isset($featured)) {echo $featured;}
        if (isset($latest)) {echo $latest;}        
    ?>  
  </div>
  <div id="columnright">
    <?php
		if (isset($searchoptions)){ echo $searchoptions;}
		if (isset($categoryoptions)){ echo $categoryoptions;}
	    if (isset($manufactureroptions)){ echo $manufactureroptions;}
        if (isset($specials)) {echo $specials;}
        if (isset($related)) {echo $related;}		
    ?>
  </div>

  <?php if (isset($footer)) { ?>
  <div id="footer"><?php echo $footer; ?></div>
  <?php } ?>
</div>
<?php if (isset($time)) { ?>
<div id="time"><?php echo $time; ?></div>
<?php } ?>
</body>
</html>
