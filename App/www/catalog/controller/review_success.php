<?php  
class ControllerReviewSuccess extends Controller {   
	function index() {
		$customer =& $this->locator->get('customer');
		$language =& $this->locator->get('language');
		$module   =& $this->locator->get('module');
		$response =& $this->locator->get('response');
		$template =& $this->locator->get('template');
		$url      =& $this->locator->get('url');
		$head_def =& $this->locator->get('HeaderDefinition');  // New Header
		     
    	if (!$customer->isLogged()) {
	  		$response->redirect($url->ssl('account_login'));  
    	}
		
		$language->load('controller/review_success.php');
	
		$template->set('title', $language->get('heading_title'));
	  
    	$view = $this->locator->create('template');

    	$view->set('heading_title', $language->get('heading_title'));

    	$view->set('text_success', $language->get('text_success'));

    	$view->set('button_continue', $language->get('button_continue'));

    	$view->set('continue', $url->href('home'));

		$template->set('content', $view->fetch('content/success.tpl'));
		$view->set('head_def',$head_def);    // New Header
		$template->set('head_def',$head_def);    // New Header	
		$template->set($module->fetch());
		
		$response->set($template->fetch('layout.tpl'));		
  	}
}
?>
