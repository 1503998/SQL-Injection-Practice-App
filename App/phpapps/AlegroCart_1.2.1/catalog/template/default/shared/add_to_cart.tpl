<div id="<?php echo $this_controller.'_'.$product['product_id']; ?>" class="add" <?php if(isset($button_font)){echo 'style="font-size: '.$button_font.'px"'; }?>>
<?php $loadpath = "$('#mini_cart').load('index.php?controller=addtocart&amp;action=add&amp;";?>
 <input class="button" <?php if(isset($button_font)){echo 'style="font-size: '.$button_font.'px"'; }?> type="button" id="<?php echo $this_controller.'_add_'.$product['product_id']; ?>" value="<?php echo $Add_to_Cart;?>" onclick="$.add2cart('<?php echo $this_controller.'_image'.$product['product_id']; ?>','cart_content'); <?php echo $loadpath; ?>'+GetData(<?php echo "'".$product['product_id']."','".$this_controller."'"; ?>)),document.getElementById('<?php echo $this_controller.'_add_'.$product['product_id']; ?>').value='<?php echo $Added_to_Cart; ?>';">&nbsp;
 <?php if($product['min_qty'] > '1'){
  $i = $product['min_qty'];
 } else {
  $i = 1;
 }?>
 <select name="<?php echo $this_controller;?>_quantity" id="<?php echo $this_controller.'_quantity_'.$product['product_id']; ?>">
  <?php while ($i <= '20'){ ?>
   <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
   <?php $i ++;
  } ?>				
 </select>
</div>