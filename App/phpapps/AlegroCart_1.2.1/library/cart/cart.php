<?php // AlegroCart
class Cart {
	var $data     = array();
	var $products = array();
	var $subtotal = 0;
	var $taxes    = array();
	var $total    = 0;
	var $stock    = TRUE;
  	var $shipping = FALSE;
   
  	function __construct(&$locator)
	{
		$this->locator  =& $locator;
		$this->session  =& $locator->get('session');
		if ($this->session->has('cart')) {
      		$this->data = $this->session->get('cart');
    	}
		$this->data_refresh();
    }	  
	function data_refresh()
	{		  
	$this->config   =& $this->locator->get('config');
		$this->database =& $this->locator->get('database');
		$this->language =& $this->locator->get('language');
		$this->tax      =& $this->locator->get('tax');
		$this->weight   =& $this->locator->get('weight');
		
    	foreach ($this->data as $key => $value)
		{
      		$array      = explode(':', $key);
      		$product_id = $array[0];
      		$quantity   = $value;

      		if (isset($array[1]))
			{
        		$options = explode('.', $array[1]);
      		} else {
        		$options = array();
      		} 
	  
      		$product = $this->database->getRow("select * from product p left join product_description pd on (p.product_id = pd.product_id) left join image i on (p.image_id = i.image_id) where p.product_id = '" . (int)$product_id . "' and pd.language_id = '" . (int)$this->language->getId() . "' and p.date_available < now() and p.status = '1'");
      	  	
			if ($product) {
      			$option_price = 0;

      			$option_data = array();
      
      			foreach ($options as $product_to_option_id) {
        			$option = $this->database->getRow("select o.name as name, ov.name as `value`, p2o.price, p2o.prefix from product_to_option p2o left join `option` o on p2o.option_id = o.option_id left join option_value ov on p2o.option_value_id = ov.option_value_id where p2o.product_to_option_id = '" . (int)$product_to_option_id . "' and product_id = '" . (int)$product_id . "' and o.language_id = '" . (int)$this->language->getId() . "' and ov.language_id = '" . (int)$this->language->getId() . "'");

        			if ($option['prefix'] == '+') {
          				$option_price = $option_price + $option['price'];
        			} elseif ($option['prefix'] == '-') {
          				$option_price = $option_price - $option['price'];
        			}
        
        			$option_data[] = array(
          				'product_to_option_id' => $product_to_option_id,
          				'name'                 => $option['name'],
          				'value'                => $option['value'],
          				'prefix'               => $option['prefix'],
          				'price'                => $option['price']
        			);
      			}

				$product_discount = $this->database->getRow("select * from product_discount where product_id = '" . (int)$product['product_id'] . "' and quantity <= '" . (int)$quantity . "' order by quantity desc limit 1");

				if (!$product_discount) {   // changed to percent
          			$discount_percent = 0;
				} else {
					$discount_percent = $product_discount['discount'];
				}
				      		
				$downloads = $this->database->getRows("select * from product_to_download p2d left join download d on (p2d.download_id = d.download_id) left join download_description dd on (d.download_id = dd.download_id) where p2d.product_id = '" . (int)$product_id . "' and dd.language_id = '" . (int)$this->language->getId() . "'");
			
				$download_data = array();
			
				foreach ($downloads as $download) {
        			$download_data[] = array(
          				'download_id' => $download['download_id'],
						'name'        => $download['name'],
						'filename'    => $download['filename'],
						'mask'        => $download['mask'],
						'remaining'   => $download['remaining']
        			);			
				}
				$price = $product['price'];
				$special_price = $product['special_price'];
				if ($special_price > 0 && date('Y-m-d') >= $product['sale_start_date'] && date('Y-m-d') <= $product['sale_end_date']) {
				  $extended_price = $special_price;
				  $discount = $discount_percent > 0 ? $discount = $special_price * ($discount_percent / 100) : '0';		  
				  $special_price = ($product['special_price'] + $option_price);
				} else{
				  $extended_price = $price;
				  $special_price = '0';
				  $discount = $discount_percent > 0 ? $price * ($discount_percent / 100) : '0';
				}
				
      			$this->products[$key] = array(
        			'key'             => $key,
        			'product_id'      => $product['product_id'],
        			'name'            => $product['name'],
        			'model_number'    => $product['model_number'],
					'shipping'        => $product['shipping'],
        			'image'           => $product['filename'],
        			'option'          => $option_data,
					'download'        => $download_data,
        			'quantity'        => $quantity,
                    'min_qty'         => $product['min_qty'],
					'stock'           => ($quantity <= $product['quantity']),
        			'price'           => ($product['price'] + $option_price),
					'special_price'   => $special_price,
					'discount'        => $discount,
					'discount_percent'=> $discount_percent,
        			'total'           => (($extended_price + $option_price) - $discount) * $quantity,
        			'tax_class_id'    => $product['tax_class_id'],
        			'weight'          => $product['weight'],
        			'weight_class_id' => $product['weight_class_id']
      			);

 	  			$this->subtotal += $this->tax->calculate((($extended_price + $option_price) - $discount) * $quantity, $product['tax_class_id'], $this->config->get('config_tax'));

				if (!isset($this->taxes[$product['tax_class_id']])) {
					$this->taxes[$product['tax_class_id']] = ((($extended_price + $option_price) - $discount) * $quantity) / 100 * $this->tax->getRate($product['tax_class_id']);
				} else {
					$this->taxes[$product['tax_class_id']] += ((($extended_price + $option_price) - $discount) * $quantity) / 100 * $this->tax->getRate($product['tax_class_id']);
				}
			
				$this->total += $this->tax->calculate((($extended_price + $option_price) - $discount) * $quantity, $product['tax_class_id']);
			
	  			if ($quantity > $product['quantity']) {
	    			$this->stock = FALSE;
	  			}	  
	  
	  			if ($product['shipping']) {
	    			$this->shipping = TRUE;
	  			}
			} else {
				$this->remove($key);
			}
    	}
	}
	  
  	function add($product_id, $qty = '1', $options = array()) {
    	if (!$options) {
      		$key = $product_id;
    	} else {
      		$key = $product_id . ':' . implode('.', $options);
    	}
    
    	if (!isset($this->data[$key])) {
      		$this->data[$key] = $qty;
    	} else {
      		$this->data[$key] += $qty;
    	}
		
		$this->session->set('cart', $this->data);
  	}

  	function update($key, $qty) {
    	if ($qty) {
      		$this->data[$key] = $qty;
    	} else {
	  		$this->remove($key);
		}
		
		$this->session->set('cart', $this->data);
  	}

  	function remove($key) {
		if (isset($this->data[$key])) {
     		unset($this->data[$key]);
  		}
		
		$this->session->set('cart', $this->data);
	}

  	function restore($product) {
    	foreach ($product as $key => $value) {
      		if (!isset($this->data[$key])) {
        		$this->data[$key] = $value;
      		} else {
        		$this->data[$key] += $value;
      		}
    	}
		
		$this->session->set('cart', $this->data);
  	}
  
  	function clear() {
		$this->data = array();
			
		$this->session->set('cart', $this->data);
  	}

  	function getData() {
    	return $this->data;
  	}
	      
  	function getProducts() {
    	return $this->products;
  	}
  	
  	function getWeight() {
		$total = 0;
	
    	foreach ($this->products as $product) {
      		$total += $this->weight->convert($product['weight'] * $product['quantity'], $product['weight_class_id'], $this->config->get('config_weight_class_id'));
    	}
	
		return $total;
	}

	function formatWeight($total) {
		$total = $this->weight->format($total, $this->config->get('config_weight_class_id'));
		return $total;
	}
  
  	function getSubtotal() {  
		return $this->subtotal;   
  	} 
  	
	function getTaxes() {
		return $this->taxes;
  	}
	
  	function getTotal() {
		return $this->total;
  	}
	
	function increaseTotal($value) {
		$this->total += $value;
	}
	
	function decreaseTotal($value) {
		$this->total -= $value;
	}
		
	function addTax($tax_class_id, $value) {
		if (!isset($this->taxes[$tax_class_id])) {
			$this->taxes[$tax_class_id] = $value;
		} else {
			$this->taxes[$tax_class_id] += $value;
		}
	}
  	
  	function countProducts() {
		$total = 0;
		
		foreach ($this->data as $key => $value) {
			$total += $value;
		}
		
    	return $total;
  	}
	  
  	function hasProducts() {
    	return count($this->data);
  	}
  
  	function hasStock() {
    	return $this->stock;
  	}
  
  	function hasShipping() {
    	return $this->shipping;
  	}  
}
?>
