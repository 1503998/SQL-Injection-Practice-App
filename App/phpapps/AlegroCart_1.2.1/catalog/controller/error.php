<?php  //Error  AlegroCart
class ControllerError extends Controller {   
	function index() {		
		$language =& $this->locator->get('language');
		$module   =& $this->locator->get('module');
		$response =& $this->locator->get('response');
		$template =& $this->locator->get('template');
		$url      =& $this->locator->get('url');	
			
		$language->load('controller/error.php');
 
    	$template->set('title', $language->get('heading_title')); 
		
    	$view = $this->locator->create('template');

    	$view->set('heading_title', $language->get('heading_title'));

    	$view->set('text_error', $language->get('text_error'));

    	$view->set('button_continue', $language->get('button_continue'));

    	$view->set('continue', $url->href('home'));
    
		$template->set('content', $view->fetch('content/error.tpl'));
	
		$template->set($module->fetch()); 
	
		$response->set($template->fetch('layout.tpl'));
  	}
}
?>