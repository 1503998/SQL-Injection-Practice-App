<?php // Cart AlegroCart
class ControllerCart extends Controller {
	function initialize() {//$this->initialize();  Required in each function 
		if(!isset($this->dataLink)){
			$this->dataLink = TRUE;
			$this->cart     =& $this->locator->get('cart');
			$this->config   =& $this->locator->get('config');
			$this->currency =& $this->locator->get('currency');
			$this->customer =& $this->locator->get('customer');
			$this->head_def =& $this->locator->get('HeaderDefinition');  // New Header
			$this->language =& $this->locator->get('language');
			$this->image    =& $this->locator->get('image');
			$this->module   =& $this->locator->get('module');
			$this->response =& $this->locator->get('response');
			$this->request  =& $this->locator->get('request');
			$this->shipping =& $this->locator->get('shipping');
			$this->session  =& $this->locator->get('session');
			$this->tax      =& $this->locator->get('tax');
			$this->template =& $this->locator->get('template');
			$this->url      =& $this->locator->get('url');
			$this->validate =& $this->locator->get('validate');
			require_once('library/application/string_modify.php');   //new
		}
	}
	function index() {
		$this->initialize(); // Required 
		    	
    	if ($this->request->isPost()) {
      		if ($this->request->gethtml('product_id', 'post')) {
        		$this->cart->add($this->request->gethtml('product_id', 'post'), '1', $this->request->gethtml('option', 'post'));
      		}
            
      		if ($this->request->gethtml('quantity', 'post') != null && $this->request->gethtml('quantity', 'post')) {
                foreach ($this->request->gethtml('quantity', 'post') as $key => $value) {
                    $this->session->set('min_qty_error['.$key.']', '0');
                    $this->session->set('line_min_error['.$key.']', '0');
                    if ($this->request->gethtml('min_qty', 'post') != null) {
                        foreach ($this->request->gethtml('min_qty', 'post') as $k => $v) {
                            if ($k == $key) {
                                if ($value != 0) {
                                    if ($value < $v) {
                                        $value = $v;
                                        $this->session->set('min_qty_error['.$key.']', '1');
                                        $this->session->set('line_min_error['.$key.']', '1');
                                    }
                                }
                                $this->cart->update($key, (int)$value);
                            }
                        }
                    } else {
                        $this->cart->update($key, (int)$value);
                    }
                }
            }

      		if ($this->request->gethtml('remove', 'post')) {
	    		foreach (array_keys($this->request->gethtml('remove', 'post')) as $key) {
          			$this->cart->remove($key);
				}
      		}
      
	  		$this->response->redirect($this->url->href('cart'));
    	}

		$this->language->load('controller/cart.php');
    	$this->template->set('title', $this->language->get('heading_title'));
		$view = $this->locator->create('template');
		$view->set('head_def',$this->head_def);    // New Header
      	$view->set('heading_title', $this->language->get('heading_title'));
		$this->template->set('head_def',$this->head_def);    // New Header	
		
    	if ($this->cart->hasProducts()) {

      		$view->set('text_subtotal', $this->language->get('text_subtotal'));
            $view->set('text_stock_ind', $this->language->get('text_stock_ind'));
            $view->set('text_min_qty_ind', $this->language->get('text_min_qty_ind'));
            
      		$view->set('column_remove', $this->language->get('column_remove'));
      		$view->set('column_image', $this->language->get('column_image'));
      		$view->set('column_name', $this->language->get('column_name'));
      		$view->set('column_quantity', $this->language->get('column_quantity'));
			$view->set('column_price', $this->language->get('column_price'));
      		$view->set('column_special', $this->language->get('column_special'));
      		$view->set('column_total', $this->language->get('column_total'));
            $view->set('column_min_qty', $this->language->get('column_min_qty'));

      		$view->set('button_update', $this->language->get('button_update'));
      		$view->set('button_shopping', $this->language->get('button_shopping'));
      		$view->set('button_checkout', $this->language->get('button_checkout'));

      		$view->set('error', ((!$this->cart->hasStock()) && ($this->config->get('config_stock_check')) ? $this->language->get('error_stock') : NULL));
      		$view->set('stock_check', $this->config->get('config_stock_check'));
      		$view->set('action', $this->url->href('cart'));

      		$product_data = array();

     		foreach ($this->cart->getProducts() as $result) {
        		$option_data = array();

        		foreach ($result['option'] as $option) {
          			$option_data[] = array(
            			'name'  => $option['name'],
            			'value' => $option['value'],
          			);
        		}
                
                // Minimum Order Verification
                $min_qty_error = '0';
                $line_min_error = '0';
                if ($result['quantity'] != 0) {
                    if ($result['quantity'] < $result['min_qty']) {
                        $result['quantity'] = $result['min_qty'];
                        $min_qty_error = '1';
                        $line_min_error = '1';
                    }
                }
                $special_price = $result['special_price'] - $result['discount'];
        		$product_data[] = array(
          			'key'           => $result['key'],
          			'name'          => $result['name'],
          			'model_number'  => $result['model_number'],
          			'thumb'         => $this->image->resize($result['image'], 50, 50),
          			'option'        => $option_data,
          			'quantity'      => $result['quantity'],
                    'min_qty'       => $result['min_qty'],
                    'min_qty_error' => ($line_min_error || $this->session->get('line_min_error['.$result['key'].']') ? '1' : '0'),
          			'stock'         => $result['stock'],
					'price'         => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
					'special_price' => $this->currency->format($this->tax->calculate($special_price, $result['tax_class_id'], $this->config->get('config_tax'))),
          			'discount'      => ($result['discount'] ? $this->currency->format($this->tax->calculate($result['price'] - $result['discount'], $result['tax_class_id'], $this->config->get('config_tax'))) : NULL),
					'total'         => $this->currency->format($this->tax->calculate($result['total'], $result['tax_class_id'], $this->config->get('config_tax'))),
					'href'          => $this->url->href('product', FALSE, array('product_id' => $result['product_id']))
        		);
                
                if ($min_qty_error == '1' || $this->session->get('min_qty_error['.$result['key'].']')) {
                    $view->set('error', $this->language->get('error_min_qty'));
                    $this->session->set('min_qty_error['.$result['key'].']', '0');
                    $this->session->set('line_min_error['.$result['key'].']', '0');
                }
                    
      		}

      		$view->set('products', $product_data);
     		$view->set('subtotal', $this->currency->format($this->cart->getSubtotal()));
            $view->set('weight', $this->cart->formatWeight($this->cart->getWeight()));
            $view->set('text_cart_weight', $this->language->get('text_cart_weight'));
      		$view->set('continue',"location='".$this->url->referer('home')."'"); // $url->href('home'));
      		$view->set('checkout', $this->url->ssl('checkout_shipping'));

	  		$this->template->set('content', $view->fetch('content/cart.tpl'));
    	} else {
      		$view->set('text_error', $this->language->get('text_error'));
      		$view->set('button_continue', $this->language->get('button_continue'));
      		$view->set('continue', $this->url->href('home'));
	  		$this->template->set('content', $view->fetch('content/error.tpl'));
    	}
		$this->template->set($this->module->load('popular'));
		$this->template->set($this->module->load('specials'));
	  	$this->template->set($this->module->fetch());
		$this->response->set($this->template->fetch('layout.tpl'));
  	}
}
?>
