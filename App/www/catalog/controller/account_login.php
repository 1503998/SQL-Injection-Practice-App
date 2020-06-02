<?php //Account Login AlegroCart
class ControllerAccountLogin extends Controller {
	var $error = array();
	function initialize() {//$this->initialize();  Required in each function 
		if(!isset($this->dataLink)){
			$this->dataLink = TRUE;
			$this->config   =& $this->locator->get('config');
			$this->customer =& $this->locator->get('customer');
			$this->head_def =& $this->locator->get('HeaderDefinition');  // New Header
			$this->language =& $this->locator->get('language');
			$this->module   =& $this->locator->get('module');
			$this->response =& $this->locator->get('response');
			$this->request  =& $this->locator->get('request');
			$this->session  =& $this->locator->get('session');
			$this->template =& $this->locator->get('template');
			$this->url      =& $this->locator->get('url');
			$this->validate =& $this->locator->get('validate');
		}	
	}
  	function index() {
		$this->initialize(); // Required 
		
		
		if ($this->customer->isLogged()) {  
      		$this->response->redirect($this->url->ssl('account'));
    	}
	
    	$this->language->load('controller/account_login.php');
    	$this->template->set('title', $this->language->get('heading_title'));
			
		if ($this->request->isPost() && $this->request->has('password', 'post') && $this->validate()) {			
      		if ($this->request->has('redirect', 'post')) {
				$this->response->redirect($this->request->gethtml('redirect', 'post'));
      		} else {
				$this->response->redirect($this->url->ssl('account'));
      		} 
    	}  
		
    	$view = $this->locator->create('template');

    	$view->set('heading_title', $this->language->get('heading_title'));

    	$view->set('text_new_customer', $this->language->get('text_new_customer'));
    	$view->set('text_i_am_new_customer', $this->language->get('text_i_am_new_customer'));
    	$view->set('text_returning_customer', $this->language->get('text_returning_customer'));
    	$view->set('text_i_am_returning_customer', $this->language->get('text_i_am_returning_customer'));
    	$view->set('text_create_account', $this->language->get('text_create_account'));
    	$view->set('text_forgotten_password', $this->language->get('text_forgotten_password'));
    	$view->set('text_forgotten_password', $this->language->get('text_forgotten_password'));
    	$view->set('entry_email', $this->language->get('entry_email_address'));
    	$view->set('entry_password', $this->language->get('entry_password'));

    	$view->set('button_continue', $this->language->get('button_continue'));
    	$view->set('button_login', $this->language->get('button_login'));

		$view->set('error', @$this->error['message']);
		$view->set('action', $this->url->ssl('account_login'));
		$view->set('email', $this->request->gethtml('email', 'post'));

    	if ($this->request->has('redirect', 'post')) {
			$view->set('redirect', $this->request->gethtml('redirect', 'post'));
		} else {
      		$view->set('redirect', $this->session->get('redirect'));
	  		$this->session->delete('redirect');		  	
    	}

    	$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		$this->session->set('account_validation', md5(time()));
		$view->set('account_validation', $this->session->get('account_validation'));

    	$view->set('continue', $this->url->ssl('account_create'));
    	$view->set('forgotten', $this->url->ssl('account_forgotten'));
		$view->set('head_def',$this->head_def);    // New Header
		$this->template->set('head_def',$this->head_def);    // New Header

		$this->template->set('content', $view->fetch('content/account_login.tpl'));
		$this->template->set($this->module->fetch());
	
		$this->response->set($this->template->fetch('layout.tpl'));
  	}
  
  	function validate() {
		$this->initialize(); // Required 
		
		if(($this->session->get('account_validation') == $this->request->gethtml('account_validation','post')) && (strlen($this->session->get('account_validation')) > 10)){
			if (!$this->customer->login($this->request->sanitize('email', 'post'), $this->request->sanitize('password', 'post'))) {
				$this->error['message'] = $this->language->get('error_login');
			}
		} else {
				$this->session->set('message',$this->language->get('error_referer'));
		}
		$this->session->delete('account_validation');
		
    	if (!$this->error) {
      		return TRUE;
    	} else {
      		return FALSE;
    	}  	
  	}
}
?>