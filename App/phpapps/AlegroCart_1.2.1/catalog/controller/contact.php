<?php //Contact AlegroCart
class ControllerContact extends Controller {
	var $error = array(); 
	function initialize() {//$this->initialize();  Required in each function 
		if(!isset($this->dataLink)){
			$this->dataLink = TRUE;
			$this->config   =& $this->locator->get('config');
			$this->customer =& $this->locator->get('customer');
			$this->head_def =& $this->locator->get('HeaderDefinition');  // New Header
			$this->language =& $this->locator->get('language');
			$this->mail           =& $this->locator->get('mail');
			$this->mail_check     =& $this->locator->get('mail_check_mx');
			$this->module   =& $this->locator->get('module');
			$this->response =& $this->locator->get('response');
			$this->request  =& $this->locator->get('request');
			$this->session  =& $this->locator->get('session');
			$this->template =& $this->locator->get('template');
			$this->url      =& $this->locator->get('url');
			$this->validate =& $this->locator->get('validate');
			require_once('library/application/string_modify.php');   //new
		}
	}    
  	function index() {
		$this->initialize(); // Required 
			
		$this->language->load('controller/contact.php');
    	$this->template->set('title', $this->language->get('heading_title'));  
	 
    	if ($this->request->isPost() && $this->request->has('email', 'post') && $this->validate()) {
	  		
			$this->mail->setTo($this->config->get('config_email'));
	  		$this->mail->setFrom($this->request->sanitize('email', 'post'));
	  		$this->mail->setSender($this->request->sanitize('name', 'post'));
	  		$this->mail->setSubject($this->language->get('email_subject', $this->request->sanitize('name', 'post')));
	  		$this->mail->setText($this->request->sanitize('enquiry', 'post'));
      		$this->mail->send();

	  		$this->response->redirect($this->url->ssl('contact', 'success'));
    	}

    	$view = $this->locator->create('template');
		$view->set('head_def',$this->head_def);    // New Header

    	$view->set('heading_title', $this->language->get('heading_title'));

    	$view->set('text_address', $this->language->get('text_address'));
    	$view->set('text_telephone', $this->language->get('text_telephone'));
    	$view->set('text_fax', $this->language->get('text_fax'));

    	$view->set('entry_name', $this->language->get('entry_name'));
    	$view->set('entry_email', $this->language->get('entry_email'));
    	$view->set('entry_enquiry', $this->language->get('entry_enquiry'));

    	$view->set('error_name', @$this->error['name']);
    	$view->set('error_email', @$this->error['email']);
    	$view->set('error_enquiry', @$this->error['enquiry']);

    	$view->set('button_continue', $this->language->get('button_continue'));
    
		$view->set('action', $this->url->href('contact'));
    
		$view->set('store', $this->config->get('config_store'));

    	$view->set('address', nl2br($this->config->get('config_address')));

    	$view->set('telephone', $this->config->get('config_telephone'));

    	$view->set('fax', $this->config->get('config_fax'));

    	$view->set('name', $this->request->sanitize('name', 'post'));

    	$view->set('email', $this->request->sanitize('email', 'post'));

    	$view->set('enquiry', $this->request->sanitize('enquiry', 'post'));
		
		$this->template->set('head_def',$this->head_def);    // New Header
		$this->template->set('content', $view->fetch('content/contact.tpl'));
		$this->template->set($this->module->load('popular')); // New Load Modules
		$this->template->set($this->module->load('specials'));	
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  	}

  	function success() {
		$this->initialize(); // Required 
		
		$this->language->load('controller/contact.php');

    	$this->template->set('title', $this->language->get('heading_title'));  
		
    	$view = $this->locator->create('template');
		$view->set('head_def',$this->head_def);    // New Header
    	$view->set('heading_title', $this->language->get('heading_title'));

    	$view->set('text_success', $this->language->get('text_success'));

    	$view->set('button_continue', $this->language->get('button_continue'));

    	$view->set('continue', $this->url->href('home'));
		$this->template->set('head_def',$this->head_def);    // New Header
		$this->template->set('content', $view->fetch('content/success.tpl'));
		$this->template->set($this->module->load('popular')); // New Load Modules
		$this->template->set($this->module->load('specials'));	
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  }
    
  	function validate() {
		$this->initialize(); // Required 
		
		if (!$this->validate->strlen($this->request->sanitize('name', 'post'),3,32)) {
      		$this->error['name'] = $this->language->get('error_name');
    	}

    	if ((!$this->validate->strlen($this->request->sanitize('email', 'post'), 6, 32)) || (!$this->validate->email($this->request->sanitize('email', 'post'))) || $this->mail_check->final_mail_check($this->request->sanitize('email', 'post')) == FALSE) {
      		$this->error['email'] = $this->language->get('error_email');
    	}

    	if (!$this->validate->strlen($this->request->sanitize('enquiry', 'post'),10,1000)) {
      		$this->error['enquiry'] = $this->language->get('error_enquiry');
    	}
	 
		if (!$this->error){
			return TRUE;
		} else {
      		return FALSE;
    	}
  	}
}
?>
