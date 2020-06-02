<?php // Account Edit AlegroCart
class ControllerAccountEdit extends Controller {
	var $error = array();
	function initialize() {//$this->initialize();  Required in each function 
		if(!isset($this->dataLink)){
			$this->dataLink = TRUE;
			$this->config   =& $this->locator->get('config');
			$this->customer =& $this->locator->get('customer');
			$this->head_def =& $this->locator->get('HeaderDefinition');  // New Header
			$this->language =& $this->locator->get('language');
			$this->mail     =& $this->locator->get('mail');
			$this->mail_check  =& $this->locator->get('mail_check_mx');
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
			$this->session->set('redirect', $this->url->ssl('account_edit'));

			$this->response->redirect($this->url->ssl('account_login'));
		}

		$this->language->load('controller/account_edit.php');

		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('firstname', 'post') && $this->validate()) {
			if(($this->session->get('account_validation') == $this->request->gethtml('account_validation','post')) && (strlen($this->session->get('account_validation')) > 10)){
				$this->modelAccountCreate->update_customer($this->customer->getId());
				$this->session->set('message', $this->language->get('text_message'));
			} else {
				$this->session->set('message',$this->language->get('error_referer'));
			}
			$this->session->delete('account_validation');
			$this->response->redirect($this->url->ssl('account'));
		}

		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('text_your_details', $this->language->get('text_your_details'));
		$view->set('text_your_password', $this->language->get('text_your_password'));

		$view->set('entry_firstname', $this->language->get('entry_firstname'));
		$view->set('entry_lastname', $this->language->get('entry_lastname'));
		$view->set('entry_date_of_birth', $this->language->get('entry_date_of_birth'));
		$view->set('entry_email', $this->language->get('entry_email'));
		$view->set('entry_telephone', $this->language->get('entry_telephone'));
		$view->set('entry_fax', $this->language->get('entry_fax'));
		$view->set('entry_password', $this->language->get('entry_password'));
		$view->set('entry_confirm', $this->language->get('entry_confirm'));

		$view->set('button_continue', $this->language->get('button_continue'));
		$view->set('button_back', $this->language->get('button_back'));

		$view->set('error', @$this->error['message']);
		$view->set('error_firstname', @$this->error['firstname']);
		$view->set('error_lastname', @$this->error['lastname']);
		$view->set('error_email', @$this->error['email']);
		$view->set('error_telephone', @$this->error['telephone']);
		$view->set('error_password', @$this->error['password']);
		$view->set('error_confirm', @$this->error['confirm']);

		$view->set('action', $this->url->ssl('account_edit'));
		
		if($this->session->get('message')){
			$view->set('message', $this->session->get('message'));
			$this->session->delete('message');
		}
		
		$this->session->set('account_validation', md5(time()));
		$view->set('account_validation', $this->session->get('account_validation'));
		
		if (($this->customer->getId()) && (!$this->request->isPost())) {
			$customer_info = $this->modelAccountCreate->get_customer($this->customer->getId());
		}

		if ($this->request->has('firstname', 'post')) {
			$view->set('firstname', $this->request->sanitize('firstname', 'post'));
		} else {
			$view->set('firstname', @$customer_info['firstname']);
		}

		if ($this->request->has('lastname', 'post')) {
			$view->set('lastname', $this->request->sanitize('lastname', 'post'));
		} else {
			$view->set('lastname', @$customer_info['lastname']);
		}

		if ($this->request->has('email', 'post')) {
			$view->set('email', $this->request->gethtml('email', 'post'));
		} else {
			$view->set('email',@$customer_info['email']);
		}

		if ($this->request->has('telephone', 'post')) {
			$view->set('telephone', $this->request->sanitize('telephone', 'post'));
		} else {
			$view->set('telephone',@$customer_info['telephone']);
		}

		if ($this->request->has('fax', 'post')) {
			$view->set('fax', $this->request->sanitize('fax', 'post'));
		} else {
			$view->set('fax',@$customer_info['fax']);
		}

		$view->set('password', $this->request->gethtml('password', 'post'));
		$view->set('confirm', $this->request->gethtml('confirm', 'post'));

		$view->set('back', $this->url->ssl('account'));
		$view->set('head_def',$this->head_def);    // New Header		
		$this->template->set('head_def',$this->head_def);    // New Header						
		$this->template->set('content', $view->fetch('content/account_edit.tpl'));

		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function validate() {
		$this->initialize(); // Required 
			
		if ((strlen($this->request->sanitize('firstname', 'post')) < 3) || (strlen($this->request->sanitize('firstname', 'post')) > 32)) {
			$this->error['firstname'] = $this->language->get('error_firstname');
		}

		if ((strlen($this->request->sanitize('lastname', 'post')) < 3) || (strlen($this->request->sanitize('lastname', 'post')) > 32)) {
			$this->error['lastname'] = $this->language->get('error_lastname');
		}
		if ((!$this->validate->strlen($this->request->gethtml('email', 'post'), 6, 32)) || (!$this->validate->email($this->request->gethtml('email', 'post'))) || $this->mail_check->final_mail_check($this->request->gethtml('email', 'post')) == FALSE) {
      		$this->error['email'] = $this->language->get('error_email');
    	}

		if ($this->modelAccountCreate->check_email($this->customer->getId())) {
			$this->error['message'] = $this->language->get('error_exists');
		}

		if ((strlen($this->request->sanitize('telephone', 'post')) < 3) || (strlen($this->request->sanitize('telephone', 'post')) > 32)) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>