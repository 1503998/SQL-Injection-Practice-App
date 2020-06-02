<?php
class ControllerPaymentCod extends Controller {
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
		
		$language->load('controller/payment_cod.php');

		$template->set('title', $language->get('heading_title'));
				
		if ($request->isPost() && $request->has('global_cod_status', 'post') && $this->validate()) {
			$database->query("delete from setting where `group` = 'cod'");
			
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'cod', `key` = 'cod_status', `value` = '?'", $request->gethtml('global_cod_status', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'cod', `key` = 'cod_geo_zone_id', `value` = '?'", $request->gethtml('global_cod_geo_zone_id', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'cod', `key` = 'cod_sort_order', `value` = '?'", $request->gethtml('global_cod_sort_order', 'post')));
			
			$session->set('message', $language->get('text_message'));
			
			$response->redirect($url->ssl('extension', FALSE, array('type' => 'payment')));
		}
		
		$view = $this->locator->create('template');
		
		$view->set('heading_title', $language->get('heading_title'));
		$view->set('heading_description', $language->get('heading_description'));		

		$view->set('text_enabled', $language->get('text_enabled'));
		$view->set('text_disabled', $language->get('text_disabled'));
		$view->set('text_all_zones', $language->get('text_all_zones'));
		$view->set('text_none', $language->get('text_none'));
		$view->set('text_yes', $language->get('text_yes'));
		$view->set('text_no', $language->get('text_no'));
				
		$view->set('entry_status', $language->get('entry_status'));
		$view->set('entry_geo_zone', $language->get('entry_geo_zone'));
		$view->set('entry_email', $language->get('entry_email'));
		$view->set('entry_test', $language->get('entry_test'));
		$view->set('entry_currency', $language->get('entry_currency'));
		$view->set('entry_sort_order', $language->get('entry_sort_order'));
		
		$view->set('button_list', $language->get('button_list'));
		$view->set('button_insert', $language->get('button_insert'));
		$view->set('button_update', $language->get('button_update'));
		$view->set('button_delete', $language->get('button_delete'));
		$view->set('button_save', $language->get('button_save'));
		$view->set('button_cancel', $language->get('button_cancel'));

		$view->set('tab_general', $language->get('tab_general'));

		$view->set('error', @$this->error['message']);
		$view->set('error_email', @$this->error['email']);
		
		$view->set('action', $url->ssl('payment_cod'));
		
		$view->set('list', $url->ssl('extension', FALSE, array('type' => 'payment')));

		$view->set('cancel', $url->ssl('extension', FALSE, array('type' => 'payment')));	

		$results = $database->getRows("select * from setting where `group` = 'cod'");
			
		foreach ($results as $result) {
			$setting_info[$result['type']][$result['key']] = $result['value'];
		}
		
		if ($request->has('global_cod_status', 'post')) {
			$view->set('status', $request->gethtml('global_cod_status', 'post'));
		} else {
			$view->set('status', @$setting_info['global']['cod_status']);
		}
		
		if ($request->has('global_cod_geo_zone_id', 'post')) {
			$view->set('geo_zone_id', $request->gethtml('global_cod_geo_zone_id', 'post'));
		} else {
			$view->set('geo_zone_id', @$setting_info['global']['cod_geo_zone_id']); 
		} 
		
		if ($request->has('global_cod_sort_order', 'post')) {
			$view->set('global_cod_sort_order', $request->gethtml('global_cod_sort_order', 'post'));
		} else {
			$view->set('global_cod_sort_order', @$setting_info['global']['cod_sort_order']);
		}
										
		$view->set('geo_zones', $database->cache('geo_zone', "select * from geo_zone"));
								
		$template->set('content', $view->fetch('content/payment_cod.tpl'));

		$template->set($module->fetch());

		$response->set($template->fetch('layout.tpl'));
	}
	
	function validate() {
		$language =& $this->locator->get('language');
		$request  =& $this->locator->get('request');
		$user     =& $this->locator->get('user');

		if (!$user->hasPermission('modify', 'payment_cod')) {
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
		
		$language->load('controller/payment_cod.php');
		
		if ($user->hasPermission('modify', 'payment_cod')) {
			$database->query("delete from setting where `group` = 'cod'");
			
			$database->query("insert into setting set type = 'global', `group` = 'cod', `key` = 'cod_status', value = '0'");
			$database->query("insert into setting set type = 'global', `group` = 'cod', `key` = 'cod_geo_zone_id', value = '0'");
			$database->query("insert into setting set type = 'global', `group` = 'cod', `key` = 'cod_sort_order', value = '1'");
			
			$session->set('message', $language->get('text_message'));
		} else {
			$session->set('error', $language->get('error_permission'));
		}	
				
		$response->redirect($url->ssl('extension', FALSE, array('type' => 'payment')));
	}
	
	function uninstall() {
		$database =& $this->locator->get('database');
		$language =& $this->locator->get('language');
		$response =& $this->locator->get('response');
		$session  =& $this->locator->get('session');
		$url      =& $this->locator->get('url');
		$user     =& $this->locator->get('user');
		
		$language->load('controller/payment_cod.php');
		
		if ($user->hasPermission('modify', 'payment_cod')) {
			$database->query("delete from setting where `group` = 'cod'");
			
			$session->set('message', $language->get('text_message'));
		} else {
			$session->set('error', $language->get('error_permission'));
		}	
				
		$response->redirect($url->ssl('extension', FALSE, array('type' => 'payment')));
	}
}
?>