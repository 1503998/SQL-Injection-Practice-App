<?php 
  $head_def->setcss( $this->style . "/css/module_column.css");
  $head_def->setcss($this->style . "/css/thickbox.css");  
  $head_def->set_javascript("ajax/jquery.js");
  $head_def->set_javascript("ajax/jqueryadd2cart.js");
  $head_def->set_javascript("thickbox/thickbox-compressed.js");
  $shared_path = 'catalog/template/' . $this->directory . '/shared/';
  $columns = 0;
?>
<div class="headingcolumn"><h1><?php echo $heading_title; ?></h1></div>
<div class="module_column">
<?php if (isset($products)) { ?>
 <?php foreach ($products as $key =>$product) { ?>
  <div class="img">
   <?php $image_link = 1;
	  if(isset($image_link)){
	    include $shared_path . 'product_image_link.tpl';
	  } else {
		include $shared_path . 'product_image.tpl';
   }?>
  </div>
  <b><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a><br></b>
  <?php echo $product['description']; ?><br>	
  <div class="onhand"><?php echo $onhand.$product['stock_level']; ?></div>
  <?php include $shared_path . 'product_price.tpl';?>
  <?php if ($add_enable && $addtocart) { ?>
   <?php $option = $product['options'];				
   If ($option == TRUE) {?>
	<div class="options"><a href="<?php echo $product['href']; ?>">
	<?php echo $options_text; ?></a></div>
   <?php } else { ?>
	<?php include $shared_path . 'add_to_cart.tpl';?>
   <?php }?>
  <?php }?>
  <?php if(($key + 1) < count($products)){
   echo "<div class=\"divider\"></div>";} ?>
  <?php } ?>
 <?php } else { ?>
  <p><?php echo $text_notfound; ?></p>
<?php } ?>
</div>
<div class="columnBottom"></div>