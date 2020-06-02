<?php
class ControllerModuleCatalogInformation extends Controller {
	var $error = array();
	 
	function index() { 
		$database =& $this->locator->get('database');
		$language =& $this->locator->get('language');
		$module   =& $this->locator->get('module');
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$template =& $this->locator->get('template');
		$session  =& $this->locator->get('session');
		$url      =& $this->locator->get('url');
		
		$language->load('controller/module_catalog_information.php');

		$template->set('title', $language->get('heading_title'));
				
		if ($request->isPost() && $request->has('catalog_information_status', 'post') && $this->validate()) {
			$database->query("delete from setting where type = 'catalog' and `group` = 'information'");
			
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'information', `key` = 'information_status', `value` = '?'", $request->gethtml('catalog_information_status', 'post')));
			
			$session->set('message', $language->get('text_message'));
			
			$response->redirect($url->ssl('extension', FALSE, array('type' => 'module')));
		}
		
		$view = $this->locator->create('template');
		
		$view->set('heading_title', $language->get('heading_title'));
		$view->set('heading_description', $language->get('heading_description'));		

		$view->set('text_enabled', $language->get('text_enabled'));
		$view->set('text_disabled', $language->get('text_disabled'));
		
		$view->set('entry_status', $language->get('entry_status'));
		$view->set('entry_sort_order', $language->get('entry_sort_order'));
		
		$view->set('button_list', $language->get('button_list'));
		$view->set('button_insert', $language->get('button_insert'));
		$view->set('button_update', $language->get('button_update'));
		$view->set('button_delete', $language->get('button_delete'));
		$view->set('button_save', $language->get('button_save'));
		$view->set('button_cancel', $language->get('button_cancel'));

		$view->set('tab_general', $language->get('tab_general'));

		$view->set('error', @$this->error['message']);

		$view->set('action', $url->ssl('module_catalog_information'));
		
		$view->set('list', $url->ssl('extension', FALSE, array('type' => 'module')));

		$view->set('cancel', $url->ssl('extension', FALSE, array('type' => 'module')));	

		if (!$request->isPost()) {
			$results = $database->getRows("select * from setting where type = 'catalog' and `group` = 'information'");
			
			foreach ($results as $result) {
				$setting_info[$result['type']][$result['key']] = $result['value'];
			}
		}
		
		if ($request->has('catalog_information_status', 'post')) {
			$view->set('catalog_information_status', $request->gethtml('catalog_information_status', 'post'));
		} else {
			$view->set('catalog_information_status', @$setting_info['catalog']['information_status']);
		}
																
		$template->set('content', $view->fetch('content/module_catalog_information.tpl'));

		$template->set($module->fetch());

		$response->set($template->fetch('layout.tpl'));
	}
	
	function validate() {
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');

		if (!$user->hasPermission('modify', 'module_catalog_information')) {
			$this->error['message'] = $language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
	
	function install() {
		$database =& $this->locator->get('database');
		$language =& $this->locator->get('language');
		$response =& $this->locator->get('response');
		$session  =& $this->locator->get('session');
		$url      =& $this->locator->get('url');
		$user     =& $this->locator->get('user');
		
		$language->load('controller/module_catalog_information.php');
		
		if ($user->hasPermission('modify', 'module_catalog_information')) {
			$database->query("delete from setting where type = 'catalog' and `group` = 'information'");
			
			$database->query("insert into setting set type = 'catalog', `group` = 'information', `key` = 'information_status', value = '1'");
			
			$session->set('message', $language->get('text_message'));
		} else {
			$session->set('error', $language->get('error_permission'));
		}	
				
		$response->redirect($url->ssl('extension', FALSE, array('type' => 'module')));	
	}
	
	function uninstall() {
		$database =& $this->locator->get('database');
		$language =& $this->locator->get('language');
		$response =& $this->locator->get('response');
		$session  =& $this->locator->get('session');
		$url      =& $this->locator->get('url');
		$user     =& $this->locator->get('user');
		
		$language->load('controller/module_catalog_information.php');
		
		if ($user->hasPermission('modify', 'module_catalog_information')) {
			$database->query("delete from setting where type = 'catalog' and `group` = 'information'");
			
			$session->set('message', $language->get('text_message'));
		} else {
			$session->set('error', $language->get('error_permission'));
		}	
				
		$response->redirect($url->ssl('extension', FALSE, array('type' => 'module')));
	}
}
?>