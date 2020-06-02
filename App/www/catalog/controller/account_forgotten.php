<?php //Account Forgotten AlegroCart
class ControllerAccountForgotten extends Controller {
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

			$this->modelAccountCreate =& $this->model->get('model_accountcreate');
		}
	}
	function index() {
		$this->initialize(); // Required 

		if ($this->customer->isLogged()) {
			$this->response->redirect($this->url->ssl('account'));
		}

		$this->language->load('controller/account_forgotten.php');

		$this->template->set('title', $this->language->get('heading_title'));

		if (($this->request->isPost()) && ($this->validate()) && $this->request->has('email', 'post')) {
			if(($this->session->get('account_validation') == $this->request->gethtml('account_validation','post')) && (strlen($this->session->get('account_validation')) > 10)){
				
				$password = substr(md5(rand()), 0, 7);
				$this->mail->setTo($this->request->gethtml('email', 'post'));
				$this->mail->setFrom($this->config->get('config_email'));
				$this->mail->setSender($this->config->get('config_store'));
				$this->mail->setSubject($this->language->get('email_subject', $this->config->get('config_store')));
				$this->mail->setText($this->language->get('email_message', $this->config->get('config_store'), $this->config->get('config_store'), $password));
				$this->mail->send();
				$this->modelAccountCreate->reset_password($password);
				$this->session->set('message', $this->language->get('text_message'));
			} else {
				$this->session->set('message',$this->language->get('error_referer'));
			}
			$this->session->delete('account_validation');
			$this->response->redirect($this->url->ssl('account_login'));
		}

		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_title'));

		$view->set('text_your_email', $this->language->get('text_your_email'));
		$view->set('text_email', $this->language->get('text_email'));
		$view->set('entry_email', $this->language->get('entry_email'));
		$view->set('button_continue', $this->language->get('button_continue'));
		$view->set('button_back', $this->language->get('button_back'));
		$view->set('error', @$this->error['message']);

		$view->set('action', $this->url->ssl('account_forgotten'));

		$this->session->set('account_validation', md5(time()));
		$view->set('account_validation', $this->session->get('account_validation'));
 
		$view->set('back', $this->url->ssl('account'));
		$view->set('head_def',$this->head_def);    // New Header
		$this->template->set('head_def',$this->head_def); // New Header	
		$this->template->set('content', $view->fetch('content/account_forgotten.tpl'));

		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function validate() {
		$this->initialize(); // Required 
		
		if (!$this->request->gethtml('email', 'post')) {
			$this->error['message'] = $this->language->get('error_email');
		}  elseif (!$this->modelAccountCreate->check_customer($this->request->gethtml('email', 'post'))){
			$this->error['message'] = $this->language->get('error_email');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>