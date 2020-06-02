	<?php if (($product['special_price'] > '$0.00' ) && date('Y-m-d') >= $product['sale_start_date'] && date('Y-m-d') <= $product['sale_end_date']) { ?>
	  <div><?php echo $regular_price; ?>
	  <span class="price_old"><?php echo $product['price']; ?></span></div>
	  <div class="price_new"><?php echo $sale_price . $product['special_price']; ?></div>
	  <?php if(isset($product['options']) && $product['options'] && $columns == 1){?>
	    <?php $product_total = (float)str_replace($symbols,'',str_replace(',','',$product['special_price'])); ?>
	    <input name="base_price" id="base_price_<?php echo $product['product_id']; ?>" value="<?php echo number_format((float)str_replace($symbols,'',str_replace(',','',$product['special_price'])),$decimal_place,'.','');?>" type="hidden">
	  <?php } ?>
	<?php } else { ?>
	  <br>
	  <div class="price_new"><?php echo $product['price']; ?></div>
	  <?php if(isset($product['options']) && $product['options'] && $columns == 1){?>
	  <?php $product_total = (float)str_replace($symbols,'',str_replace(',','',$product['price'])); ?>
	  <input name="base_price" id="base_price_<?php echo $product['product_id']; ?>" value="<?php echo number_format((float)str_replace($symbols,'',str_replace(',','',$product['price'])),$decimal_place,'.','');?>" type="hidden">
	  <?php } ?>	  
	<?php } ?>