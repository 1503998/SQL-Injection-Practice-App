<?php //Account Password AlegroCart
class ControllerAccountPassword extends Controller {
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
			$this->modelAccountCreate =& $this->model->get('model_accountcreate');
		}	
	}	     
  	function index() {
		$this->initialize(); // Required 
		
    	if (!$this->customer->isLogged()) {
      		$this->session->set('redirect', $this->url->ssl('account_password'));

      		$this->response->redirect($this->url->ssl('account_login'));
    	}

		$this->language->load('controller/account_password.php');
    	$this->template->set('title', $this->language->get('heading_title'));
			  
    	if ($this->request->isPost() && $this->request->has('password', 'post') && $this->validate()) {
			if(($this->session->get('account_validation') == $this->request->gethtml('account_validation','post')) && (strlen($this->session->get('account_validation')) > 10)){
				$this->modelAccountCreate->update_password($this->customer->getId());
				$this->session->delete('account_validation');
				$this->session->set('message', $this->language->get('text_message'));
			} else {
				$this->session->set('message',$this->language->get('error_referer'));
			}
	  		$this->response->redirect($this->url->ssl('account'));
    	}
	
    	$view = $this->locator->create('template');
    	$view->set('heading_title', $this->language->get('heading_title'));
    	$view->set('text_password', $this->language->get('text_password'));
    	$view->set('entry_password', $this->language->get('entry_password'));
    	$view->set('entry_confirm', $this->language->get('entry_confirm'));
    	$view->set('button_continue', $this->language->get('button_continue'));
    	$view->set('button_back', $this->language->get('button_back'));
		$view->set('error_password', @$this->error['password']);
    	$view->set('error_confirm', @$this->error['confirm']);
    	$view->set('action', $this->url->ssl('account_password'));

		$this->session->set('account_validation', md5(time()));
		$view->set('account_validation', $this->session->get('account_validation'));
		
    	$view->set('password', $this->request->sanitize('password', 'post'));
    	$view->set('confirm', $this->request->sanitize('confirm', 'post'));

    	$view->set('back', $this->url->ssl('account'));
		$view->set('head_def',$this->head_def);    // New Header
		$this->template->set('head_def',$this->head_def);    // New Header	
		$this->template->set('content', $view->fetch('content/account_password.tpl'));
	
		$this->template->set($this->module->fetch());
    	$this->response->set($this->template->fetch('layout.tpl'));	
  	}
  
  	function validate() {
		$this->initialize(); // Required 	
    	if ((strlen($this->request->sanitize('password', 'post')) < 4) || (strlen($this->request->sanitize('password', 'post')) > 20)) {
      		$this->error['password'] = $this->language->get('error_password');
    	}

    	if ($this->request->sanitize('confirm', 'post') != $this->request->sanitize('password', 'post')) {
      		$this->error['confirm'] = $this->language->get('error_confirm');
    	}  
	
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}
}
?>
