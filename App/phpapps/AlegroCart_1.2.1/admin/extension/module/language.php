<?php  
class ModuleLanguage extends Controller {
	function fetch() {
		$config   =& $this->locator->get('config');
		$database =& $this->locator->get('database');
		$language =& $this->locator->get('language');	
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');	
		$url      =& $this->locator->get('url');
		
		if ($config->get('language_status')) {	
    		if (($request->isPost()) && ($request->has('module_language', 'post'))) {
	  			$language->set($request->gethtml('module_language', 'post'));
				$response->redirect($url->requested($url->ssl('home')));
    		}
		
			$language->load('extension/module/language.php');
		
			$view = $this->locator->create('template');
		 
    		$view->set('heading_title', $language->get('heading_title'));

    		$view->set('action', $url->requested($url->ssl('home')));

   			$view->set('text_language', $language->get('text_language'));

    		$view->set('entry_language', $language->get('entry_language'));

    		$view->set('default', $language->getCode());

    		$view->set('languages', $database->cache('language', "select * from language order by sort_order"));

   			return $view->fetch('module/language.tpl'); 
		}
	}
}
?>