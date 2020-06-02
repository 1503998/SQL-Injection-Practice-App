<?php //Account Newsletter AlegroCart
class ControllerAccountNewsletter extends Controller {  
	function initialize() {//$this->initialize();  Required in each function 
		if(!isset($this->dataLink)){
			$this->dataLink = TRUE;
			$this->customer =& $this->locator->get('customer');
			$this->head_def =& $this->locator->get('HeaderDefinition');  // New Header
			$this->language =& $this->locator->get('language');
			$this->module   =& $this->locator->get('module');
			$this->response =& $this->locator->get('response');
			$this->request  =& $this->locator->get('request');
			$this->session  =& $this->locator->get('session');
			$this->template =& $this->locator->get('template');
			$this->url      =& $this->locator->get('url');
			$this->modelAccountCreate =& $this->model->get('model_accountcreate');
		}
	}
	function index() {
		$this->initialize(); // Required 

		if (!$this->customer->isLogged()) {
	  		$this->session->set('redirect', $this->url->ssl('account_newsletter'));
	  		$this->response->redirect($this->url->ssl('account_login'));
    	} 
		
		$this->language->load('controller/account_newsletter.php');
		$this->template->set('title', $this->language->get('heading_title'));
				
		if ($this->request->isPost() && $this->request->has('newsletter', 'post')) {
			$this->modelAccountCreate->update_newsletter($this->customer->getId());
			
			$this->session->set('message', $this->language->get('text_message'));
			$this->response->redirect($this->url->href('account'));
		}
		
    	$view = $this->locator->create('template');

    	$view->set('heading_title', $this->language->get('heading_title'));
    	$view->set('text_yes', $this->language->get('text_yes'));
		$view->set('text_no', $this->language->get('text_no'));
		$view->set('text_newsletter', $this->language->get('text_newsletter'));
		$view->set('entry_newsletter', $this->language->get('entry_newsletter'));
		$view->set('button_continue', $this->language->get('button_continue'));
		$view->set('button_back', $this->language->get('button_back'));
    	$view->set('action', $this->url->ssl('account_newsletter'));
		$view->set('newsletter', $this->customer->getNewsLetter());
		$view->set('back', $this->url->ssl('account'));
		$view->set('head_def',$this->head_def);    // New Header
		$this->template->set('head_def',$this->head_def);    // New Header				
		$this->template->set('content', $view->fetch('content/account_newsletter.tpl'));
	
		$this->template->set($this->module->fetch());
	
		$this->response->set($this->template->fetch('layout.tpl'));	
  	}
}
?>