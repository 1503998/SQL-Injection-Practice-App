<?php 
class ControllerCheckoutSuccess extends Controller {
	function index() { 
		$customer =& $this->locator->get('customer');
		$language =& $this->locator->get('language');
		$module   =& $this->locator->get('module');
		$response =& $this->locator->get('response');
		$session  =& $this->locator->get('session');
		$template =& $this->locator->get('template');
		$url      =& $this->locator->get('url');
		 
    	if (!$customer->isLogged()) {
      		$session->set('redirect',  $url->ssl('checkout_success'));

	  		$response->redirect($url->ssl('account_login'));
    	}
		
   		$language->load('controller/checkout_success.php');

    	$template->set('title', $language->get('heading_title')); 
		
    	$view = $this->locator->create('template');

    	$view->set('heading_title', $language->get('heading_title'));

    	$view->set('text_success', $language->get('text_success', $url->ssl('account'), $url->ssl('account_history'), $url->href('contact')));

    	$view->set('button_continue', $language->get('button_continue'));

    	$view->set('continue', $url->href('home'));

    	$template->set('content', $view->fetch('content/success.tpl'));
	
		$template->set($module->fetch());
		
		$response->set($template->fetch('layout.tpl'));
  	}
}
?>
