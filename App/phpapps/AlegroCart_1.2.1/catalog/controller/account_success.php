<?php  //Account Success AlegroCart
class ControllerAccountSuccess extends Controller {  
	function index() {
		$cart     =& $this->locator->get('cart');
		$customer =& $this->locator->get('customer');
		$language =& $this->locator->get('language');
		$module   =& $this->locator->get('module');
		$response =& $this->locator->get('response');
		$template =& $this->locator->get('template');
		$url      =& $this->locator->get('url');
			
		if (!$customer->isLogged()) {
      		$response->redirect($url->ssl('account_login'));
    	}
	
    	$language->load('controller/account_success.php');
    	$template->set('title', $language->get('heading_title'));	
    	$view = $this->locator->create('template');
    	$view->set('heading_title', $language->get('heading_title'));
    	$view->set('text_success', $language->get('text_success'));
    	$view->set('button_continue', $language->get('button_continue'));
		
		if ($cart->hasProducts()) {
			$view->set('continue', $url->href('cart'));
		} else {
			$view->set('continue', $url->href('account'));
		}
    	
		$template->set('content', $view->fetch('content/success.tpl'));
		$template->set($module->fetch());
    	$response->set($template->fetch('layout.tpl'));		
  	}
}
?>