<?php // Maintenance
class ControllerMaintenance extends Controller{
	var $error = array();
	
	function index(){
		$database =& $this->locator->get('database');
		$language =& $this->locator->get('language');
		$module   =& $this->locator->get('module');
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$template =& $this->locator->get('template');
		$session  =& $this->locator->get('session');
		$url      =& $this->locator->get('url');

		$language->load('controller/maintenance.php');		
		$template->set('title', $language->get('heading_title'));

		if (($request->isPost()) && ($this->validate())) {
			$database->query("delete from setting where type = 'catalog' and `group` = 'maintenance'");
			
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'maintenance', `key` = 'maintenance_status', `value` = '?'", $request->get('catalog_maintenance_status', 'post')));		
		
			$session->set('message', $language->get('text_message'));
			$response->redirect($url->ssl('home'));
		}
	
		$view = $this->locator->create('template');
		$view->set('heading_title', $language->get('heading_title'));
		$view->set('heading_description', $language->get('heading_description'));
		$view->set('text_enabled', $language->get('text_enabled'));
		$view->set('text_disabled', $language->get('text_disabled'));
		$view->set('entry_status', $language->get('entry_status'));

		$view->set('button_list', $language->get('button_list'));
		$view->set('button_insert', $language->get('button_insert'));
		$view->set('button_update', $language->get('button_update'));
		$view->set('button_delete', $language->get('button_delete'));
		$view->set('button_save', $language->get('button_save'));
		$view->set('button_cancel', $language->get('button_cancel'));

		$view->set('tab_general', $language->get('tab_general'));

		$view->set('error', @$this->error['message']);
		$view->set('message', $session->get('message'));
		
		$session->delete('message');
		$view->set('action', $url->ssl('maintenance'));
		$view->set('cancel', $url->ssl('maintenance'));
		
		if (!$request->isPost()) {
			$results = $database->getRows("select * from setting where type = 'catalog' and `group` = 'maintenance'");		
			foreach ($results as $result) {
				$setting_info[$result['type']][$result['key']] = $result['value'];
			}
		}
		if ($request->has('catalog_maintenance_status', 'post')) {
			$view->set('catalog_maintenance_status', $request->get('catalog_maintenance_status', 'post'));
		} else {
			$view->set('catalog_maintenance_status', @$setting_info['catalog']['maintenance_status']);
		}	
		
		$template->set('content', $view->fetch('content/maintenance.tpl'));

		$template->set($module->fetch());

		$response->set($template->fetch('layout.tpl'));		
		
	}

  	function validate() {
    	$request  =& $this->locator->get('request');
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');
        $validate =& $this->locator->get('validate');

    	if (!$user->hasPermission('modify', 'maintenance')) {
      		$this->error['message'] = $language->get('error_permission');
    	}
        
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}    
}
?>