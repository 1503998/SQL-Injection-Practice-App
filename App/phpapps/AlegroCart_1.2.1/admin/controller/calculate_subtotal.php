<?php 
class ControllerCalculateSubtotal extends Controller {
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
		
		$language->load('controller/calculate_subtotal.php');

		$template->set('title', $language->get('heading_title'));
				
		if ($request->isPost() && $request->has('global_subtotal_status', 'post') && $this->validate()) {
			$database->query("delete from setting where `group` = 'subtotal'");
						
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'subtotal', `key` = 'subtotal_status', `value` = '?'", $request->gethtml('global_subtotal_status', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'subtotal', `key` = 'subtotal_sort_order', `value` = '?'", $request->gethtml('global_subtotal_sort_order', 'post')));
			
			$session->set('message', $language->get('text_message'));
			
			$response->redirect($url->ssl('extension', FALSE, array('type' => 'calculate')));
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

		$view->set('action', $url->ssl('calculate_subtotal'));
		
		$view->set('list', $url->ssl('extension', FALSE, array('type' => 'calculate')));

		$view->set('cancel', $url->ssl('extension', FALSE, array('type' => 'calculate')));	

		if (!$request->isPost()) {
			$results = $database->getRows("select * from setting where `group` = 'subtotal'");
			
			foreach ($results as $result) {
				$setting_info[$result['type']][$result['key']] = $result['value'];
			}
		}
		
		if ($request->has('global_subtotal_status', 'post')) {
			$view->set('global_subtotal_status', $request->gethtml('global_subtotal_status', 'post'));
		} else {
			$view->set('global_subtotal_status', @$setting_info['global']['subtotal_status']);
		}

		if ($request->has('global_subtotal_sort_order', 'post')) {
			$view->set('global_subtotal_sort_order', $request->gethtml('global_subtotal_sort_order', 'post'));
		} else {
			$view->set('global_subtotal_sort_order', @$setting_info['global']['subtotal_sort_order']);
		}
																
		$template->set('content', $view->fetch('content/calculate_subtotal.tpl'));

		$template->set($module->fetch());

		$response->set($template->fetch('layout.tpl'));
	}
	
	function validate() {
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');

		if (!$user->hasPermission('modify', 'calculate_subtotal')) {
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
		
		$language->load('controller/calculate_subtotal.php');
		
		if ($user->hasPermission('modify', 'calculate_subtotal')) {
			$database->query("delete from setting where `group` = 'subtotal'");
			
			$database->query("insert into setting set type = 'global', `group` = 'subtotal', `key` = 'subtotal_status', value = '1'");
			$database->query("insert into setting set type = 'global', `group` = 'subtotal', `key` = 'subtotal_sort_order', value = '1'");
		} else {
			$session->set('error', $language->get('error_permission'));
		}
		
		$response->redirect($url->ssl('extension', FALSE, array('type' => 'calculate')));			
	}
	
	function uninstall() {
		$database =& $this->locator->get('database');
		$language =& $this->locator->get('language');
		$response =& $this->locator->get('response');
		$session  =& $this->locator->get('session');
		$url      =& $this->locator->get('url');
		$user     =& $this->locator->get('user');
		
		$language->load('controller/calculate_subtotal.php');
		
		if ($user->hasPermission('modify', 'calculate_subtotal')) {
			$database->query("delete from setting where `group` = 'subtotal'");
		} else {
			$session->set('error', $language->get('error_permission'));
		}
		
		$response->redirect($url->ssl('extension', FALSE, array('type' => 'calculate')));
	}
}
?>