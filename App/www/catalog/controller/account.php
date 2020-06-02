<?php //Account AlegroCart
class ControllerAccount extends Controller { 
	function index() {
		$customer =& $this->locator->get('customer');
		$language =& $this->locator->get('language');
		$module   =& $this->locator->get('module');
		$response =& $this->locator->get('response');
		$session  =& $this->locator->get('session');
		$template =& $this->locator->get('template');
		$url      =& $this->locator->get('url');
		$head_def =& $this->locator->get('HeaderDefinition');  // New Header
		
		if (!$customer->isLogged()) {
	  		$session->set('redirect', $url->ssl('account'));
	  
	  		$response->redirect($url->ssl('account_login'));
    	} 
	
		$language->load('controller/account.php');
    
		$template->set('title', $language->get('heading_title'));
			
    	$view = $this->locator->create('template');

    	$view->set('heading_title', $language->get('heading_title'));

    	$view->set('text_my_account', $language->get('text_my_account'));
		$view->set('text_my_orders', $language->get('text_my_orders'));
		$view->set('text_my_newsletter', $language->get('text_my_newsletter'));
    	$view->set('text_information', $language->get('text_information'));
    	$view->set('text_password', $language->get('text_password'));
    	$view->set('text_address', $language->get('text_address'));
    	$view->set('text_history', $language->get('text_history'));
    	$view->set('text_download', $language->get('text_download'));
		$view->set('text_newsletter', $language->get('text_newsletter'));

    	$view->set('message', $session->get('message'));
    
		$session->delete('message');

    	$view->set('information', $url->ssl('account_edit'));

    	$view->set('password', $url->ssl('account_password'));

		$view->set('address', $url->ssl('account_address'));

    	$view->set('history', $url->ssl('account_history'));

    	$view->set('download', $url->ssl('account_download'));
		
		$view->set('newsletter', $url->ssl('account_newsletter'));
		
		$view->set('head_def',$head_def);    // New Header
		$template->set('head_def',$head_def);    // New Header			
		$template->set('content', $view->fetch('content/account.tpl'));
	
		$template->set($module->fetch());
	
		$response->set($template->fetch('layout.tpl'));	
  	}
}
?>
