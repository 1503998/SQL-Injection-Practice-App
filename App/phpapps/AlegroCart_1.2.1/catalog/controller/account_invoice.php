<?php //Account Invoice AlegroCart
class ControllerAccountInvoice extends Controller {
	function initialize() {//$this->initialize();  Required in each function 
		if(!isset($this->dataLink)){
			$this->dataLink = TRUE;
			$this->address  =& $this->locator->get('address');
			$this->config   =& $this->locator->get('config');
			$this->currency =& $this->locator->get('currency');
			$this->customer =& $this->locator->get('customer');
			$this->head_def =& $this->locator->get('HeaderDefinition');  // New Header
			$this->language =& $this->locator->get('language');
			$this->module   =& $this->locator->get('module');
			$this->response =& $this->locator->get('response');
			$this->request  =& $this->locator->get('request');
			$this->session  =& $this->locator->get('session');
			$this->template =& $this->locator->get('template');
			$this->url      =& $this->locator->get('url');
			$this->modelAccountInvoice =& $this->model->get('model_accountinvoice');
		}
	}

	function index() { 
		$this->initialize(); // Required 
    	if (!$this->customer->isLogged()) {
			if ($this->request->has('order_id')) {
				$this->session->set('redirect', $this->url->ssl('account_invoice', FALSE, array('order_id' => $this->request->gethtml('order_id'))));
			} else {
				$this->session->set('redirect', $this->url->ssl('account_invoice', FALSE, array('reference' => $this->request->gethtml('reference'))));
			}
      		
			$this->response->redirect($this->url->ssl('account_login'));
    	}
	
    	$this->language->load('controller/account_invoice.php');
    	$this->template->set('title', $this->language->get('heading_title'));
		$view = $this->locator->create('template');
		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('head_def',$this->head_def);    // New Header
		$this->template->set('head_def',$this->head_def);    // New Header	
		$view->set('button_continue', $this->language->get('button_continue'));
		$view->set('continue', $this->url->ssl('account_history'));
		
		if ($this->request->has('order_id')) {
			$order_info = $this->modelAccountInvoice->get_order($this->request->gethtml('order_id'));
    	} else {
			$order_info = $this->modelAccountInvoice->get_order_ref($this->request->gethtml('reference'));
		}
		
		if ($order_info) {
    		$view->set('text_order', $this->language->get('text_order'));
			$view->set('text_email', $this->language->get('text_email'));
			$view->set('text_telephone', $this->language->get('text_telephone'));
			$view->set('text_fax', $this->language->get('text_fax'));
      		$view->set('text_shipping_address', $this->language->get('text_shipping_address'));
      		$view->set('text_shipping_method', $this->language->get('text_shipping_method'));
      		$view->set('text_payment_address', $this->language->get('text_payment_address'));
      		$view->set('text_payment_method', $this->language->get('text_payment_method'));
      		$view->set('text_order_history', $this->language->get('text_order_history'));
      		$view->set('text_product', $this->language->get('text_product'));
      		$view->set('text_model_number', $this->language->get('text_model_number'));
      		$view->set('text_quantity', $this->language->get('text_quantity'));
      		$view->set('text_price', $this->language->get('text_price'));
      		$view->set('text_total', $this->language->get('text_total'));
      		$view->set('column_date_added', $this->language->get('column_date_added'));
      		$view->set('column_status', $this->language->get('column_status'));
      		$view->set('column_comment', $this->language->get('column_comment'));
			$view->set('reference', $order_info['reference']);
			$view->set('email', $order_info['email']);
			$view->set('telephone', $order_info['telephone']);
			$view->set('fax', $order_info['fax']);
		
      		$shipping_address = array(
        		'firstname' => $order_info['shipping_firstname'],
        		'lastname'  => $order_info['shipping_lastname'],
        		'company'   => $order_info['shipping_company'],
        		'address_1' => $order_info['shipping_address_1'],
        		'address_2' => $order_info['shipping_address_2'],
        		'city'      => $order_info['shipping_city'],
        		'postcode'  => $order_info['shipping_postcode'],
        		'zone'      => $order_info['shipping_zone'],
        		'country'   => $order_info['shipping_country']
      		);

      		$view->set('shipping_address', $this->address->format($shipping_address, $order_info['shipping_address_format'], '<br />'));
			$view->set('shipping_method', $order_info['shipping_method']);

      		$payment_address = array(
        		'firstname' => $order_info['payment_firstname'],
        		'lastname'  => $order_info['payment_lastname'],
        		'company'   => $order_info['payment_company'],
        		'address_1' => $order_info['payment_address_1'],
        		'address_2' => $order_info['payment_address_2'],
        		'city'      => $order_info['payment_city'],
        		'postcode'  => $order_info['payment_postcode'],
        		'zone'      => $order_info['payment_zone'],
        		'country'   => $order_info['payment_country']
      		);

      		$view->set('payment_address', $this->address->format($payment_address, $order_info['payment_address_format'], '<br />'));
      		$view->set('payment_method', $order_info['payment_method']);
			
			$products = $this->modelAccountInvoice->get_order_products($order_info['order_id']);
      		$product_data = array();
      		foreach ($products as $product) {
				$options = $this->modelAccountInvoice->get_options($order_info['order_id'],$product['order_product_id']);
        		$option_data = array();
        		foreach ($options as $option) {
          			$option_data[] = array(
            			'name'  => $option['name'],
            			'value' => $option['value'],
          			);
        		}

				if ($this->config->get('config_tax')) {
					$product_data[] = array(
						'name'     		=> $product['name'],
						'model_number'  => $product['model_number'],
						'option'   		=> $option_data,
						'quantity' 		=> $product['quantity'],
						'price'   		=> $this->currency->format(($product['price'] + ($product['price'] * $product['tax'] / 100)), $order_info['currency'], $order_info['value']),
						'discount' 		=> (ceil($product['discount']) ? $this->currency->format(($product['price'] - $product['discount']) + (($product['price'] - $product['discount']) * $product['tax'] / 100), $order_info['currency'], $order_info['value']) : NULL),
						'total'    		=> $this->currency->format($product['total'] + ($product['total'] * $product['tax'] / 100), $order_info['currency'], $order_info['value'])
					);
				}
				else {
					$product_data[] = array(
						'name'     		=> $product['name'],
						'model_number'	=> $product['model_number'],
						'option'   		=> $option_data,
						'quantity' 		=> $product['quantity'],
						'price'    		=> $this->currency->format($product['price'], $order_info['currency'], $order_info['value']),
						'discount' 		=> (ceil($product['discount']) ? $this->currency->format(($product['price'] - $product['discount']), $order_info['currency'], $order_info['value']) : NULL),
						'total'   		=> $this->currency->format($product['total'], $order_info['currency'], $order_info['value'])
					);
				}
      		}

      		$view->set('products', $product_data);
			$view->set('totals',$this->modelAccountInvoice->get_totals($order_info['order_id']));
      		$history_data = array();
			$results = $this->modelAccountInvoice->get_order_history($order_info['order_id']);
      		foreach ($results as $result) {
        		$history_data[] = array(
          			'date_added' => $this->language->formatDate($this->language->get('date_format_short'), strtotime($result['date_added'])),
          			'status'     => $result['status'],
          			'comment'    => $result['comment']
        		);
      		}
      		$view->set('historys', $history_data);

	  		$this->template->set('content', $view->fetch('content/account_invoice.tpl'));
    	} else {
      		$view->set('text_error', $this->language->get('text_error'));
	  		$this->template->set('content', $view->fetch('content/error.tpl'));
    	}
		$this->template->set($this->module->fetch());
		$this->response->set($this->template->fetch('layout.tpl'));
  	}
}
?>
