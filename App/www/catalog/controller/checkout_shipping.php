<?php //Checkout Shipping AlegroCart
class ControllerCheckoutShipping extends Controller {
	var $error = array(); 
	function initialize() {//$this->initialize();  Required in each function 
		if(!isset($this->dataLink)){
			$this->dataLink = TRUE;
			$this->address  =& $this->locator->get('address');
			$this->cart     =& $this->locator->get('cart');
			$this->config   =& $this->locator->get('config');
			$this->customer =& $this->locator->get('customer');
			$this->head_def =& $this->locator->get('HeaderDefinition');  // New Header
			$this->language =& $this->locator->get('language');
			$this->module   =& $this->locator->get('module');
			$this->response =& $this->locator->get('response');
			$this->request  =& $this->locator->get('request');
			$this->session  =& $this->locator->get('session');
			$this->shipping  =& $this->locator->get('shipping');
			$this->template =& $this->locator->get('template');
			$this->url      =& $this->locator->get('url');
			$this->validate =& $this->locator->get('validate');
			$this->modelCheckout = $this->model->get('model_checkout');
		}
	}			
  	function index() {
		$this->initialize(); // Required 

		if (!$this->customer->isLogged()) {
			$this->session->set('redirect', $this->url->ssl('checkout_shipping'));

	  		$this->response->redirect($this->url->ssl('account_login'));
    	} 

    	if ((!$this->cart->hasProducts()) || ((!$this->cart->hasStock()) && (!$this->config->get('config_stock_checkout')))) {
	  		$this->response->redirect($this->url->ssl('cart'));
    	}

    	if (!$this->cart->hasShipping()) {
			$this->session->delete('shipping_address_id');
			$this->session->delete('shipping_method');
			
			$this->response->redirect($this->url->ssl('checkout_payment'));
    	}

    	if (!$this->address->has($this->session->get('shipping_address_id'))) {
	  		$this->session->set('shipping_address_id', $this->customer->getAddressId());
    	}

    	if (!$this->address->has($this->session->get('shipping_address_id'))) {
	  		$this->response->redirect($this->url->ssl('checkout_address', 'shipping'));
		}

    	$this->language->load('controller/checkout_shipping.php');

		$this->template->set('title', $this->language->get('heading_title')); 

    	if ($this->request->isPost() && $this->request->has('shipping', 'post') && $this->validate()) {
			if(($this->session->get('account_validation') == $this->request->gethtml('account_validation', 'post')) && (strlen($this->session->get('account_validation')) > 10)){
				$this->session->set('shipping_method', $this->request->gethtml('shipping', 'post'));
				$this->session->set('comment', $this->request->sanitize('comment', 'post'));
				$this->session->delete('message');
				$this->session->delete('account_validation');
				$this->response->redirect($this->url->ssl('checkout_payment'));
			} else {
				$this->session->set('message',$this->language->get('error_referer'));
				$this->session->delete('account_validation');
				$this->response->redirect($this->url->ssl('checkout_shipping'));
			}	
    	}
		
    	$view = $this->locator->create('template');

    	$view->set('heading_title', $this->language->get('heading_title'));

    	$view->set('text_shipping', $this->language->get('text_shipping'));
    	$view->set('text_shipping_to', $this->language->get('text_shipping_to'));
    	$view->set('text_shipping_address', $this->language->get('text_shipping_address'));
    	$view->set('text_shipping_method', $this->language->get('text_shipping_method'));
    	$view->set('text_shipping_methods', $this->language->get('text_shipping_methods'));
    	$view->set('text_comments', $this->language->get('text_comments'));
    
		$view->set('button_change_address', $this->language->get('button_change_address'));
    	$view->set('button_back', $this->language->get('button_back'));
    	$view->set('button_continue', $this->language->get('button_continue'));
    
		$view->set('error', @$this->error['message']);
		$view->set('message', $this->session->get('message'));
		$view->set('action', $this->url->ssl('checkout_shipping'));
    	$this->session->set('account_validation', md5(time()));
		$view->set('account_validation', $this->session->get('account_validation'));

    	$view->set('address', $this->address->getFormatted($this->session->get('shipping_address_id'), '<br />'));
    
    	$view->set('change_address', $this->url->ssl('checkout_address', 'shipping'));

    	$view->set('methods', $this->shipping->getQuotes());

    	$view->set('default', $this->session->get('shipping_method'));

    	$view->set('comment', $this->session->get('comment'));

    	$view->set('back', $this->url->href('cart'));
		$view->set('head_def',$this->head_def);    // New Header
		$this->template->set('head_def',$this->head_def);    // New Header					
		$this->template->set('content', $view->fetch('content/checkout_shipping.tpl'));

		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  	}
  
  	function validate() {
		$this->initialize(); // Required 

    	if (!$this->request->gethtml('shipping', 'post')) {
	  		$this->error['message'] = $this->language->get('error_shipping');
		}

		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}
}
?>
