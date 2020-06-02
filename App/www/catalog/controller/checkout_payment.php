<?php  //Checkout Payment AlegroCart
class ControllerCheckoutPayment extends Controller {
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
			$this->payment   =& $this->locator->get('payment');
			$this->response =& $this->locator->get('response');
			$this->request  =& $this->locator->get('request');
			$this->session  =& $this->locator->get('session');
			$this->shipping  =& $this->locator->get('shipping');
			$this->tax       =& $this->locator->get('tax');
			$this->template =& $this->locator->get('template');
			$this->url      =& $this->locator->get('url');
			$this->validate =& $this->locator->get('validate');
			$this->modelCheckout =& $this->model->get('model_checkout');
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

    	if ($this->cart->hasShipping()) {
			if (!$this->session->get('shipping_method')) {
	  			$this->response->redirect($this->url->ssl('checkout_shipping'));
			}

			if (!$this->shipping->getQuote($this->session->get('shipping_method'))) {
	  			$this->response->redirect($this->url->ssl('checkout_shipping'));
    		}

			if (!$this->address->has($this->session->get('shipping_address_id'))) {
	  			$this->response->redirect($this->url->ssl('checkout_address', 'shipping'));
    		}
		} else {
			$this->session->delete('shipping_address_id');
			$this->session->delete('shipping_method');
		}

    	if (!$this->address->has($this->session->get('payment_address_id'))) {
      		$this->session->set('payment_address_id', $this->session->get('shipping_address_id'));
    	}

    	if (!$this->address->has($this->session->get('payment_address_id'))) {
	  		$this->session->set('payment_address_id', $this->customer->getAddressId());
    	}

    	if (!$this->session->get('payment_address_id')) {
	  		$this->response->redirect($this->url->ssl('checkout_address', 'payment'));
    	}

    	$this->language->load('controller/checkout_payment.php');

    	$this->template->set('title', $this->language->get('heading_title')); 
		  
    	if ($this->request->isPost() && $this->request->has('payment', 'post') && $this->validate()) {
			if(($this->session->get('account_validation') == $this->request->gethtml('account_validation', 'post')) && (strlen($this->session->get('account_validation')) > 10)){
				$this->session->set('payment_method', $this->request->gethtml('payment', 'post'));
				$this->session->set('comment', $this->request->sanitize('comment', 'post'));
				$this->session->delete('message');
				$this->session->delete('account_validation');
				$this->response->redirect($this->url->ssl('checkout_confirm'));
			} else {
				$this->session->set('message',$this->language->get('error_referer'));
				$this->session->delete('account_validation');
				$this->response->redirect($this->url->ssl('checkout_payment'));
			}
    	}

    	$view = $this->locator->create('template');

    	$view->set('heading_title', $this->language->get('heading_title'));

    	$view->set('text_payment', $this->language->get('text_payment'));
    	$view->set('text_payment_to', $this->language->get('text_payment_to'));
    	$view->set('text_payment_address', $this->language->get('text_payment_address'));
    	$view->set('text_payment_method', $this->language->get('text_payment_method'));
    	$view->set('text_payment_methods', $this->language->get('text_payment_methods'));
    	$view->set('text_comments', $this->language->get('text_comments'));

    	$view->set('button_change_address', $this->language->get('button_change_address'));
    	$view->set('button_continue', $this->language->get('button_continue'));
    	$view->set('button_back', $this->language->get('button_back'));

    	$view->set('error', @$this->error['message']);
		$view->set('message', $this->session->get('message'));
    	$view->set('action', $this->url->ssl('checkout_payment'));
		$this->session->set('account_validation', md5(time()));
		$view->set('account_validation', $this->session->get('account_validation'));

    	$view->set('address', $this->address->getFormatted($this->session->get('payment_address_id'), '<br />'));
    
		$view->set('change_address', $this->url->ssl('checkout_address', 'payment'));

        $this->session->delete('payment_form_enctype');

		$view->set('methods', $this->payment->getMethods());

    	$view->set('default', $this->session->get('payment_method'));

    	$view->set('comment', $this->session->get('comment'));

    	if ($this->cart->hasShipping()) {
      		$view->set('back', $this->url->ssl('checkout_shipping'));
    	} else {
      		$view->set('back', $this->url->ssl('cart'));
    	}
		$view->set('head_def',$this->head_def);    // New Header
		$this->template->set('head_def',$this->head_def);    // New Header			
		$this->template->set('content', $view->fetch('content/checkout_payment.tpl'));

		$this->template->set($this->module->fetch());

    	$this->response->set($this->template->fetch('layout.tpl'));
  	}
  
  	function validate() {
		$this->initialize(); // Required 

    	if (!$this->request->has('payment', 'post')) {
	  		$this->error['message'] = $this->language->get('error_payment');
		}

		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}  
}
?>
