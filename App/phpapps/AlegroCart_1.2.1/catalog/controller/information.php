<?php // Information AlegroCart
class ControllerInformation extends Controller {
	function index() {  
		$language =& $this->locator->get('language');
		$module   =& $this->locator->get('module');
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$template =& $this->locator->get('template');
		$url      =& $this->locator->get('url');
		$this->modelCore =& $this->model->get('model_core');   // Model
		$head_def =& $this->locator->get('HeaderDefinition');  // New Header		
		
    	$language->load('controller/information.php');
		$information_info = $this->modelCore->getRow_information($request->gethtml('information_id'));
   		
		if ($information_info) {
	  		$template->set('title', $information_info['title']); 
      		$view = $this->locator->create('template');
      		$view->set('heading_title', $information_info['title']);
      		$view->set('description', $information_info['description']);
      		$view->set('button_continue', $language->get('button_continue'));
      		$view->set('continue', "location='".$url->referer('home')."'");
			$view->set('head_def',$head_def);    // New Header
			$template->set('head_def',$head_def);    // New Header			
      		$template->set('content', $view->fetch('content/information.tpl'));
    	} else {
	  		$template->set('title', $language->get('text_error'));
      		$view = $this->locator->create('template');
      		$view->set('heading_title', $language->get('text_error'));
      		$view->set('text_error', $language->get('text_error'));
      		$view->set('button_continue', $language->get('button_continue'));
      		$view->set('continue', $url->href('home'));
	  		$template->set('content', $view->fetch('content/error.tpl'));
    	}
		$template->set($module->load('popular')); // New Load Modules
		$template->set($module->load('specials'));
	  	$template->set($module->fetch());
	  	$response->set($template->fetch('layout.tpl'));
  	}
}
?>
