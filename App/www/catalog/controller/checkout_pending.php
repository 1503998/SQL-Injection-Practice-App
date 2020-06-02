<?php //Checkout Pending AlegroCart
class ControllerCheckoutPending extends Controller {
	function initialize() {//$this->initialize();  Required in each function 
		if(!isset($this->dataLink)){
			$this->dataLink = TRUE;
			$this->config   =& $this->locator->get('config');
			$this->customer =& $this->locator->get('customer');
			$this->head_def =& $this->locator->get('HeaderDefinition');  // New Header
			$this->language =& $this->locator->get('language');
			$this->module   =& $this->locator->get('module');
			$this->order    =& $this->locator->get('order');
			$this->payment  =& $this->locator->get('payment');
			$this->response =& $this->locator->get('response');
			$this->request  =& $this->locator->get('request');
			$this->session  =& $this->locator->get('session');
			$this->template =& $this->locator->get('template');
			$this->url      =& $this->locator->get('url');
			$this->modelCheckout =& $this->model->get('model_checkout');
		}
	}	
	function index() {
		$this->initialize(); // Required 

    	if (!$this->customer->isLogged()) {
      		$this->session->set('redirect',  $this->url->ssl('checkout_pending'));

	  		$this->response->redirect($this->url->ssl('account_login'));
    	}
		
   		$this->language->load('controller/checkout_pending.php');

    	$this->template->set('title', $this->language->get('heading_title')); 
		
    	$view = $this->locator->create('template');

    	$view->set('heading_title', $this->language->get('heading_title'));
//
    	$view->set('text_pending', $this->language->get('text_pending', $this->url->href('contact')));

    	$view->set('button_click_to_complete', $this->language->get('button_click_to_complete'));

    	$view->set('continue', $this->url->href('home'));
        
        $view->set('text_redirect', $this->language->get('text_redirect'));

        $view->set('error', @$this->error['message']);
        
        $view->set('message', $this->session->get('message'));
    
        $this->session->delete('message');
        
        $this->session->delete('payment_form_enctype');
        
        $this->session->delete('confirm_id');
                            
        $view->set('payment_url', $this->session->get('payment_offsite_url'));
              
        if ($this->session->get('payment_form_enctype')) {
            $view->set('payment_form_enctype', $this->session->get('payment_form_enctype'));
        }
        
        $this->order->set('payment_method', $this->payment->getTitle($this->session->get('payment_method')));
        
        $view->set('fields', $this->session->get('fields'));
		$view->set('head_def',$this->head_def);    // New Header
		$this->template->set('head_def',$this->head_def);    // New Header	        
    	$this->template->set('content', $view->fetch('content/checkout_pending.tpl'));
	
		$this->template->set($this->module->fetch());
		
		$this->response->set($this->template->fetch('layout.tpl'));
  	}
}
?>
