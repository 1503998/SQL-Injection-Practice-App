<?php 
  $head_def->setcss($this->style . "/css/product.css");
  $head_def->setcss($this->style . "/css/tab.css");
  $head_def->setcss($this->style . "/css/thickbox.css");  
  $head_def->set_javascript("ajax/jquery.js");
  $head_def->set_javascript("ajax/jqueryadd2cart.js");
  $head_def->set_javascript("thickbox/thickbox-compressed.js");
  $head_def->set_javascript("tab/tab.js");  
  if ($meta_title){
    $head_def->set_MetaTitle($meta_title);
  }
  if ($meta_description){
    $head_def->set_MetaDescription($meta_description);
  }
  if ($meta_keywords){
    $head_def->set_MetaKeywords($meta_keywords);
  }
  $shared_path = 'catalog/template/' . $this->directory . '/shared/';
?>
<div class="headingbody"><?php echo "Product Detail : ".$heading_title; ?></div>
<div class="contentBody">
 <div class="product" id="product">
  <div class="a" >
   <?php include $shared_path . 'product_image.tpl';?>
  </a>
 </div>
 <div class="pbox">
  <div class="pheading">
   <div class="left"><?php echo $product_number.$heading_title; ?></div>
  </div>
  <div>
   <?php if(isset($alt_description)){ echo $alt_description;} ?>
  </div>
  <div class="ponhand"><?php echo $quantity_available.$stock_level; ?></div>	
   <?php include $shared_path . 'product_price.tpl' ;?>
   
  <?php if ($product['options']){
   if(isset($product_options_select) && $product_options_select == 'radio'){
     include $shared_path . 'product_options_radio.tpl';
   } else {
	 include $shared_path . 'product_options.tpl';
  }} ?>
   <?php if ($product_addtocart) { ?>	
     <table>
      <tr>
	   <td align="left">		  
		<?php include $shared_path . 'add_to_cart.tpl';?>		  
	   </td>
      </tr>
     </table>
   <?php } ?>
  </div>
 </div>
 <div class="clearfix"></div>
<div class="box">
  <?php if($review_status){
    $Review_tabs = "<a>".$tab_reviews."(".$maxrow.")"."</a><a>".$tab_write."</a>";}
	if($technical){
	$Technical_tabs = "<a>" . $tab_technical . "</a>";}
  ?>
<div class="tab" id="tab">
  <div class="tabs"><a><?php echo $tab_description; ?></a>
    <?php if (isset($Technical_tabs)){ echo $Technical_tabs;} ?>
    <a><?php echo $tab_images."(".count($images).")"; ?></a>
	<a><?php echo $tab_information; ?></a>
	<?php if (isset($Review_tabs)){ echo $Review_tabs;} ?></div>
  <div class="pages">
    <div class="page">
	  <div class="pad">
	    <Div>
		  <?php echo $description; ?>
		</div>
	  </div>
	</div>
	<?php if (isset($Technical_tabs)) { ?>
	<div class="page">
      <div class="pad">  	
	    <Div>
		  <?php echo $technical; ?>
		</div>
	  </div>
	</div>	
	<?php } ?>
	<div class="page">
      <div class="pad">  
        <?php if ($images) { ?>
          <?php foreach ($images as $image) { ?>
            <div class="images"><a href="<?php echo $image['popup']; ?>" title="<?php echo $image['title']; ?>" class="thickbox"><img src="<?php echo $image['thumb']; ?>" title="<?php echo $image['title']; ?>" alt="<?php echo $image['title']; ?>"></a>
            </div>
          <?php } ?>
        <?php } ?>
       </div>
    </div>
	<div class="page">
	  <div class="pad">
	    <?php if ($product['min_qty'] >= '1') { ?>
          <?php echo $text_min_qty; ?><?php echo $product['min_qty']; ?><br>
        <?php } ?>
		<?php if (isset($product_discounts)){ ?><br>
		  <?php echo "<b>".$text_quantity_discount."</b><br>"; ?>
		  <?php foreach ($product_discounts as $product_discount){ ?>
		    <?php echo "&nbsp;&nbsp;".$text_qty_discount.$product_discount['discount_quantity']."&nbsp;&nbsp;".$text_discount.$product_discount['discount_amount']."&nbsp;&nbsp; (".$product_discount['discount_percent']."%)"; ?><br>
		  <?php } ?>
		<?php } ?>
		<?php if (($product['special_price'] > '$0.00' ) && date('Y-m-d') >= $product['sale_start_date'] && date('Y-m-d') <= $product['sale_end_date']) { ?><br>	
		  <?php echo "<b>".$text_date."</b><br>"; ?>
		  <?php echo "&nbsp;&nbsp;".$text_sale_start.date("F-d-Y",strtotime($product['sale_start_date'])); ?><br>
		  <?php echo "&nbsp;&nbsp;".$text_sale_end.date("F-d-Y",strtotime($product['sale_end_date'])); ?><br>
		<?php } ?>
		<?php if (isset($manufacturer)) { ?><br>
		  <?php echo $text_manufacturer.$manufacturer; ?><br>
		<?php } ?>
		<?php if ($weight) { ?><br>
		  <?php echo $text_weight . $weight; ?><br>
		 <?php } ?>
		<?php if ($shipping) { ?>
		  <?php echo $text_shipping_yes; } else { echo $text_shipping_no; ?> <br>
		<?php  } ?>
	  </div>
	</div>
	<div class="page">
      <div class="pad">
	    <?php if(!$maxrow){ echo $text_error; } ?>
		<div class= "rtab" id="rtab">
		  <div class="rtabs">
		  <?php $i=1; while($i <= $maxrow){ echo "<a> ".$i." </a>";$i++;} ?>
		  </div>		  
		  <div class="rpages">
	        <?php foreach ($review_data as $review) { ?>		  
		    <div class="rpage">
			  <div class="rpad">
				<div class="review">		  
				  <div class="a">
			        <div class="b"><a href="<?php echo $review['href']; ?>" ><?php //echo $review['name']; ?></a><br>
			          <b><?php echo $text_review_by; ?></b><?php echo $review['author']; ?></div><br>
			        <div class="c"><b><?php echo $text_date_added; ?></b> <?php echo $review['date_added']; ?></div><br>
			        <table>
				      <tr>				  
				        <td><?php echo $review['text']; ?></td>
				      </tr>
				      <tr>
				        <td><br><b><?php echo $text_rating; ?></b> <img src="catalog/styles/<?php echo $this->style?>/image/stars_<?php echo $review['rating'] . '.png'; ?>" alt="<?php echo $review['out_of']; ?>" class="png"><br>
					    (<?php echo $review['out_of']; ?>)</td>
				      </tr>
			        </table>
			      </div>
		        </div>
		      </div>	  
	        </div>		
			<?php } ?>
		  </div>	
	    </div>
	  </div>
    </div>
	<div class="page">
      <div class="pad">
        <div class="review_write"><br><a href="<?php echo $write; ?>"><img src="catalog/styles/<?php echo $this->style?>/image/write.png" alt="Write"></a><br><br>
        <a href="<?php echo $write; ?>"><?php echo $text_write; ?></a></div>
	  </div>
    </div>	  
  </div>
</div>
</div></div>
<div class="contentBodyBottom"></div>
<div>
<div class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
      <?php } ?>
</div>

</div>
	<script type="text/javascript"><!--
      tabview_initialize('tab')
    //--></script>
	  <script type="text/javascript"><!--
	  rtabview_initialize('rtab');
    //--></script>