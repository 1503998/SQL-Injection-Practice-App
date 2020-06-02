<?php //AlegroCart
class ControllerAccountLogout extends Controller {
	function initialize() {//$this->initialize();  Required in each function 
		if(!isset($this->dataLink)){
			$this->dataLink = TRUE;
			$this->cart     =& $this->locator->get('cart');
			$this->customer =& $this->locator->get('customer');
			$this->language =& $this->locator->get('language');
			$this->module   =& $this->locator->get('module');
			$this->response =& $this->locator->get('response');
			$this->shipping =& $this->locator->get('shipping');
			$this->template =& $this->locator->get('template');
			$this->url      =& $this->locator->get('url');
		}
	}
	function index() {
		$this->initialize(); // Required 

    	if ($this->customer->isLogged()) {
      		$this->customer->logout();
	  		$this->cart->clear();
      		$this->response->redirect($this->url->ssl('account_logout'));
    	}
 
    	$this->language->load('controller/account_logout.php');
    	$this->template->set('title', $this->language->get('heading_title'));
    	$view = $this->locator->create('template');
    	$view->set('heading_title', $this->language->get('heading_title'));
    	$view->set('text_success', $this->language->get('text_logout'));
    	$view->set('button_continue', $this->language->get('button_continue'));
    	$view->set('continue', $this->url->href('home'));
		$this->template->set('content', $view->fetch('content/success.tpl'));
		$this->template->set($this->module->fetch());
		$this->response->set($this->template->fetch('layout.tpl'));	
  	}
}
?>
