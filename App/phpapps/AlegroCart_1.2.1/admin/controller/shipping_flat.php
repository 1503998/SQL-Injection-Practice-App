<?php
class ControllerShippingFlat extends Controller {
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
		
		$language->load('controller/shipping_flat.php');

		$template->set('title', $language->get('heading_title'));
				
		if ($request->isPost() && $request->has('global_flat_status', 'post') && $this->validate()) {
			$database->query("delete from setting where `group` = 'flat'");
			
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'flat', `key` = 'flat_status', `value` = '?'", $request->gethtml('global_flat_status', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'flat', `key` = 'flat_geo_zone_id', `value` = '?'", $request->gethtml('global_flat_geo_zone_id', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'flat', `key` = 'flat_cost', `value` = '?'", $request->gethtml('global_flat_cost', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'flat', `key` = 'flat_tax_class_id', `value` = '?'", $request->gethtml('global_flat_tax_class_id', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'flat', `key` = 'flat_sort_order', `value` = '?'", $request->gethtml('global_flat_sort_order', 'post')));
			
			$session->set('message', $language->get('text_message'));
						
			$response->redirect($url->ssl('extension', FALSE, array('type' => 'shipping')));
		}
		
		$view = $this->locator->create('template');
		
		$view->set('heading_title', $language->get('heading_title'));
		$view->set('heading_description', $language->get('heading_description'));		

		$view->set('text_enabled', $language->get('text_enabled'));
		$view->set('text_disabled', $language->get('text_disabled'));
		$view->set('text_all_zones', $language->get('text_all_zones'));
		$view->set('text_none', $language->get('text_none'));
		
		$view->set('entry_status', $language->get('entry_status'));
		$view->set('entry_geo_zone', $language->get('entry_geo_zone'));
		$view->set('entry_cost', $language->get('entry_cost'));
		$view->set('entry_tax', $language->get('entry_tax'));
		$view->set('entry_sort_order', $language->get('entry_sort_order'));
		
		$view->set('button_list', $language->get('button_list'));
		$view->set('button_insert', $language->get('button_insert'));
		$view->set('button_update', $language->get('button_update'));
		$view->set('button_delete', $language->get('button_delete'));
		$view->set('button_save', $language->get('button_save'));
		$view->set('button_cancel', $language->get('button_cancel'));

		$view->set('tab_general', $language->get('tab_general'));

		$view->set('error', @$this->error['message']);

		$view->set('action', $url->ssl('shipping_flat'));
		
		$view->set('list', $url->ssl('extension', FALSE, array('type' => 'shipping')));
 
		$view->set('cancel', $url->ssl('extension', FALSE, array('type' => 'shipping')));	

		$results = $database->getRows("select * from setting where `group` = 'flat'");
			
		foreach ($results as $result) {
			$setting_info[$result['type']][$result['key']] = $result['value'];
		}
		
		if ($request->has('global_flat_status', 'post')) {
			$view->set('global_flat_status', $request->gethtml('global_flat_status', 'post'));
		} else {
			$view->set('global_flat_status', @$setting_info['global']['flat_status']);
		}

		if ($request->has('global_flat_geo_zone_id', 'post')) {
			$view->set('global_flat_geo_zone_id', $request->gethtml('global_flat_geo_zone_id', 'post'));
		} else {
			$view->set('global_flat_geo_zone_id', @$setting_info['global']['flat_geo_zone_id']);
		}
		
		if ($request->has('global_flat_cost', 'post')) {
			$view->set('global_flat_cost', $request->gethtml('global_flat_cost', 'post'));
		} else {
			$view->set('global_flat_cost', @$setting_info['global']['flat_cost']);
		}

		if ($request->has('global_flat_tax_class_id', 'post')) {
			$view->set('global_flat_tax_class_id', $request->gethtml('global_flat_tax_class_id', 'post'));
		} else {
			$view->set('global_flat_tax_class_id', @$setting_info['global']['flat_tax_class_id']);
		}
		
		if ($request->has('global_flat_sort_order', 'post')) {
			$view->set('global_flat_sort_order', $request->gethtml('global_flat_sort_order', 'post'));
		} else {
			$view->set('global_flat_sort_order', @$setting_info['global']['flat_sort_order']);
		}				

		$view->set('tax_classes', $database->cache('tax_class', "select * from tax_class"));
		$view->set('geo_zones', $database->cache('geo_zone', "select * from geo_zone"));
								
		$template->set('content', $view->fetch('content/shipping_flat.tpl'));

		$template->set($module->fetch());

		$response->set($template->fetch('layout.tpl'));
	}
	
	function install() {
		$database =& $this->locator->get('database');
		$language =& $this->locator->get('language');
		$response =& $this->locator->get('response');
		$session  =& $this->locator->get('session');
		$url      =& $this->locator->get('url');
		$user     =& $this->locator->get('user');
		
		$language->load('controller/shipping_flat.php');
		
		if ($user->hasPermission('modify', 'shipping_flat')) {		
			$database->query("delete from setting where `group` = 'flat'");
		
			$database->query("insert into setting set type = 'global', `group` = 'flat', `key` = 'flat_status', value = '0'");
			$database->query("insert into setting set type = 'global', `group` = 'flat', `key` = 'flat_geo_zone_id', value = '0'");
			$database->query("insert into setting set type = 'global', `group` = 'flat', `key` = 'flat_cost', value = '0.00'");
			$database->query("insert into setting set type = 'global', `group` = 'flat', `key` = 'flat_tax_class_id', value = '0'");
			$database->query("insert into setting set type = 'global', `group` = 'flat', `key` = 'flat_sort_order', value = '0'");
		
			$session->set('message', $language->get('text_message'));
		} else {
			$session->set('error', $language->get('error_permission'));
		}	
				
		$response->redirect($url->ssl('extension', FALSE, array('type' => 'shipping')));		
	}
	
	function uninstall() {
		$database =& $this->locator->get('database');
		$language =& $this->locator->get('language');
		$response =& $this->locator->get('response');
		$session  =& $this->locator->get('session');
		$url      =& $this->locator->get('url');
		$user     =& $this->locator->get('user');
		
		$language->load('controller/shipping_flat.php');
		
		if ($user->hasPermission('modify', 'shipping_flat')) {
			$database->query("delete from setting where `group` = 'flat'");
			
			$session->set('message', $language->get('text_message'));
		} else {
			$session->set('error', $language->get('error_permission'));
		}	
				
		$response->redirect($url->ssl('extension', FALSE, array('type' => 'shipping')));
	}
	
	function validate() {
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');

		if (!$user->hasPermission('modify', 'shipping_flat')) {
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