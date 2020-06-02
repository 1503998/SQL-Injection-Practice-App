<?php
class CalculateCoupon extends Calculate {
	function __construct(&$locator) {		
		$this->cart     =& $locator->get('cart');
		$this->coupon   =& $locator->get('coupon');
		$this->config   =& $locator->get('config');
		$this->currency =& $locator->get('currency');
		$this->language =& $locator->get('language');
		$this->session  =& $locator->get('session');
		$this->shipping =& $locator->get('shipping');
		$this->tax      =& $locator->get('tax');
	
		$this->language->load('extension/calculate/coupon.php');
  	}

    // Modified for proper coupon usage
	function calculate() {
		$total_data = array();
		$rawcouponvalue = 0; // raw based on pre-tax price
        $freeshipavail = 0; // for per-item allowance of free shipping
        
        // Check if coupons are enabled and that there is a valid coupon id
		if (($this->config->get('coupon_status')) && ($this->coupon->getId())) {
            
            // Check the coupon->product array. This array holds all products that are valid for this coupon id.
            // If there is at least 1 product in the allowed products, then use per-item.
            // If there are no individual products selected, then the array is empty and we assume the coupon is "per-cart"
            // Need to add that info to the language file: ("De-select all allowable products to set as a per-cart coupon")
            if (!empty($this->coupon->product)) { // peritem code
                
                // get the list of allowed products
                foreach ($this->coupon->product as $result) {
                    $data[] = $result['product_id'];
                }
                
                // Check each item in the cart against the allowed products. If found, then apply the coupon for that item only.
                // Freeshipping is a cart-wide coupon, but can still be disallowed if none of the products in the cart are eligible for freeshipping.
                // So check that at least one item has freeshipping allowed and set a flag.
                foreach ($this->cart->getProducts() as $product) {
                    if (in_array($product['product_id'], $data)) {
                        $rawcouponvalue += ($this->coupon->getDiscount($product['price']) *  $product['quantity']);
                        $freeshipavail++;
                    }
                }
                
                // If there is a discount, add it to the array
                // Take the sum of all the per-item coupon discounts and set it as a single discount coupon (instead of having 20 items and 20 separate deductions.
			    if ($rawcouponvalue > 0) {
                    $total_data[] = array(
                        'title' => $this->language->get('text_coupon_title', $this->coupon->getName()),
                        'text'  => '-' . $this->currency->format($rawcouponvalue),
                        'value' => $rawcouponvalue
                    );
                }
                
                // Subtract that from the total.
                $this->cart->decreaseTotal($rawcouponvalue);
                
                // If at least one of the products was freeshipping eligible, then  run the free shipping check
                if ($freeshipavail) {
                    if (($this->coupon->getShipping()) && ($this->cart->hasShipping())) {
                        $total_data[] = array(
                            'title' => $this->language->get('text_coupon_shipping'),
                            'text'  => '-' . $this->currency->format($this->tax->calculate($this->shipping->getCost($this->session->get('shipping_method')), $this->shipping->getTaxClassId($this->session->get('shipping_method')))),
                            'value' => $this->tax->calculate($this->shipping->getCost($this->session->get('shipping_method')), $this->shipping->getTaxClassId($this->session->get('shipping_method')))
                        );
                        // Subtract that from the total.
                        $this->cart->decreaseTotal($this->tax->calculate($this->shipping->getCost($this->session->get('shipping_method')), $this->shipping->getTaxClassId($this->session->get('shipping_method'))));
                    }
                }
            } else { // per cart code
                
                // Get the discount using the pre-tax subtotal by getting the price from the sum of all products based on product price instead of the cart price.
                foreach ($this->cart->getProducts() as $result) {
                    $rawcouponvalue += $this->coupon->getDiscount($result['price']);
                }
                
                // If there is a discount, add it to the array
			    if ($rawcouponvalue > 0) {
      			    $total_data[] = array(
        			    'title' => $this->language->get('text_coupon_title', $this->coupon->getName()),
	    			    'text'  => '-' . $this->currency->format($rawcouponvalue),
        			    'value' => $rawcouponvalue
      			    );
				    // Subtract that from the total.
				    $this->cart->decreaseTotal($rawcouponvalue);
			    }
                
                // Apply shipping per-cart code  if applicable.
			    if (($this->coupon->getShipping()) && ($this->cart->hasShipping())) {
      			    $total_data[] = array(
        			    'title' => $this->language->get('text_coupon_shipping'),
	    			    'text'  => '-' . $this->currency->format($this->tax->calculate($this->shipping->getCost($this->session->get('shipping_method')), $this->shipping->getTaxClassId($this->session->get('shipping_method')))),
        			    'value' => $this->tax->calculate($this->shipping->getCost($this->session->get('shipping_method')), $this->shipping->getTaxClassId($this->session->get('shipping_method')))
      			    );
							    
				    $this->cart->decreaseTotal($this->tax->calculate($this->shipping->getCost($this->session->get('shipping_method')), $this->shipping->getTaxClassId($this->session->get('shipping_method'))));
			    }
            }
    	}
	
    	return $total_data;
	}
    
	
	function getSortOrder() {
		return $this->config->get('coupon_sort_order');
	}
}
?>
