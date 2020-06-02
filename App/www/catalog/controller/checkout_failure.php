<?php 
class ControllerCheckoutFailure extends Controller {	 
	function index() {
		$customer =& $this->locator->get('customer');
		$language =& $this->locator->get('language');
		$module   =& $this->locator->get('module');
		$response =& $this->locator->get('response');
		$session  =& $this->locator->get('session');
		$template =& $this->locator->get('template');
		$url      =& $this->locator->get('url');
		
    	if (!$customer->isLogged()) {
      		$session->set('redirect', $url->ssl('checkout_failure'));

			$response->redirect($url->ssl('account_login'));
    	}

    	$language->load('controller/checkout_failure.php');

    	$template->set('title', $language->get('heading_title')); 
		 
    	$view = $this->locator->create('template');

    	$view->set('heading_title', $language->get('heading_title'));

    	$view->set('text_failure', $language->get('text_failure', $url->href('contact')));

    	$view->set('button_continue', $language->get('button_continue'));

    	$view->set('continue', $url->ssl('checkout_shipping'));

    	$template->set('content', $view->fetch('content/failure.tpl'));
    
		$template->set($module->fetch());
    
		$response->set($template->fetch('layout.tpl'));
  	}
}
?>
