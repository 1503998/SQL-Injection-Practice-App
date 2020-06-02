<div id="mini_cart" class="mini_cart">
  <div class="headingcolumn"><h1><?php echo $heading_title; ?></h1></div>

  <div  class="cart">
    <div id="cart_content" class="cart_content">
    <?php if ($products) { ?>
    <div id="cart_products">
	<table>
      <?php foreach ($products as $product) { ?>
      <tr>
        <td><?php echo $product['quantity']; ?>&nbsp;x&nbsp;</td>
        <td style="width: 100px;"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></td>
		<td><?php echo ' '.$product['total']; ?></td>
      </tr>
      <?php } ?>
    </table>
    <div class="a"><?php echo $text_subtotal; ?><?php echo $subtotal; ?></div>
	</div>
	<div class="c"><?php echo $text_products.$product_total;?><div class="d"><?php echo $text_items.$item_total;?></div></div>
	
    <div class="b"><a href="<?php echo $view_cart; ?>"><?php echo $text_view_cart; ?></a></div>
    <?php } else { ?>
    <div class="b"><?php echo $text_empty; ?></div>
    <?php } ?>
  </div></div>
  <!--<div class="clearfix"></div>-->
  <div class="bottom"></div>
</div>
  <script type="text/javascript"><!--
$(document).ready(function(){
	$('#cart_products').hide(2500);
	$('#mini_cart').hover(function(){
		$('#cart_products').show(400);
	}, function() {
		$('#cart_products').hide(800);
	});
});
  //--></script>