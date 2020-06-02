<?php 
class ControllerMail extends Controller {
	var $error = array();
	 
	function index() {
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$database =& $this->locator->get('database');
		$config   =& $this->locator->get('config');
		$url      =& $this->locator->get('url');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$module   =& $this->locator->get('module');
		$session  =& $this->locator->get('session');
		$mail     =& $this->locator->get('mail');

		$language->load('controller/mail.php');

		$template->set('title', $language->get('heading_title'));
		
		if ($request->isPost() && $request->has('to', 'post') && $this->validate()) {
			$email = array();
			
			switch ($request->gethtml('to', 'post')) {
				case 'newsletter':
					$results = $database->getRows("select email from customer where newsletter = '1'");
			
					foreach ($results as $result) {
						$email[] = $result['email'];
					}				
					break;
				case 'customer':
					$results = $database->getRows("select email from customer");
			
					foreach ($results as $result) {
						$email[] = $result['email'];
					}						
					break;
				default: 
					$result = $database->getRow("select email from customer where customer_id = '" . (int)$request->gethtml('to', 'post') . "'");

					$email = $result['email'];
					break;
			}
			
			if ($email) {		
				$mail->setTo($email);
				$mail->setFrom($config->get('config_email'));
	    		$mail->setSender($config->get('config_store'));
	    		$mail->setSubject($request->get('subject', 'post'));
				$mail->setHtml($request->get('content', 'post'));
	    		$mail->send();
			}
			
			$session->set('message', $language->get('text_message'));
					 
			$response->redirect($url->href('mail'));
		}

		$view = $this->locator->create('template');

		$view->set('heading_title', $language->get('heading_title'));
		$view->set('heading_description', $language->get('heading_description'));

		$view->set('text_newsletter', $language->get('text_newsletter'));
		$view->set('text_customer', $language->get('text_customer'));
		
		$view->set('entry_to', $language->get('entry_to'));
		$view->set('entry_subject', $language->get('entry_subject'));
		$view->set('entry_content', $language->get('entry_content'));
		
		$view->set('button_send', $language->get('button_send'));
		
		$view->set('error', @$this->error['message']);
		$view->set('error_to', @$this->error['to']);
		$view->set('error_subject', @$this->error['subject']);
		$view->set('error_content', @$this->error['content']);
		
		$view->set('message', $session->get('message'));
		
		$session->delete('message');
				
		$view->set('action', $url->ssl('mail'));
		
		$customer_data = array();
		
		$results = $database->getRows("select * from customer order by firstname, lastname, email");
		
		foreach ($results as $result) {
			$customer_data[] = array(
				'customer_id' => $result['customer_id'],
				'name'        => $result['firstname'] . ' ' . $result['lastname'] . ' (' . $result['email'] . ')'
			);
		}	
		
		$view->set('customers', $customer_data);
		
		$view->set('to', $request->gethtml('to', 'post'));
		$view->set('subject', $request->gethtml('subject', 'post'));
		$view->set('content', $request->gethtml('content', 'post'));
								
		$template->set('content', $view->fetch('content/mail.tpl'));

		$template->set($module->fetch());

		$response->set($template->fetch('layout.tpl'));
	}
	
	function validate() {
		$request  =& $this->locator->get('request');
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');	
		
		if (!$user->hasPermission('modify', 'mail')) {
			$this->error['message'] = $language->get('error_permission');
		}
				
		if (!$request->gethtml('subject', 'post')) {
			$this->error['subject'] = $language->get('error_subject');
		}

		if (!$request->gethtml('content', 'post')) {
			$this->error['content'] = $language->get('error_content');
		}
						
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}	
}
?>