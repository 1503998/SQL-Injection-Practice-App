<?php // Maintenance AlegroCart
class ControllerMaintenance extends Controller {
	function index() {
		$config   =& $this->locator->get('config');
		$language =& $this->locator->get('language');
		$module   =& $this->locator->get('module');
		$response =& $this->locator->get('response');
		$template =& $this->locator->get('template');
		$url      =& $this->locator->get('url');
		$session  =& $this->locator->get('session');
		$head_def =& $this->locator->get('HeaderDefinition');  // New Header			
		$language->load('controller/maintenance.php');

		$template->set('title', $language->get('heading_title'));
		
		$view = $this->locator->create('template');
		
		$view->set('heading_title', $language->get('heading_title'));
		$session->set('maintenance', 'enabled');
		$view->set('text_maintenance', $language->get('text_maintenance'));
		$view->set('head_def',$head_def);    // New header
		$template->set('head_def',$head_def);    // New Header  
		$template->set($module->fetch());
 		$template->set('content', $view->fetch('content/maintenance.tpl'));

		$response->set($template->fetch('layout_maintenance.tpl'));
	}
	
	function CheckMaintenance() {
		$user 	=& $this->locator->get('user');
		$config	=& $this->locator->get('config');

		if ($config->get('maintenance_status')) {
			return $this->forward('maintenance', 'index');
		}
	}
	
}
?>