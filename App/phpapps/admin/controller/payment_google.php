<?php
class ControllerPaymentGoogle extends Controller {
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
		
		$language->load('controller/payment_google.php');

		$template->set('title', $language->get('heading_title'));
				
		if (($request->isPost()) && ($this->validate())) {
			$database->query("delete from setting where `group` = 'google'");
			
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'google', `key` = 'google_status', `value` = '?'", $request->get('global_google_status', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'google', `key` = 'google_geo_zone_id', `value` = '?'", $request->get('global_google_geo_zone_id', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'google', `key` = 'google_merchantid', `value` = '?'", $request->get('global_google_merchantid', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'google', `key` = 'google_merchantkey', `value` = '?'", $request->get('global_google_merchantkey', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'google', `key` = 'google_test', `value` = '?'", $request->get('global_google_test', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'google', `key` = 'google_currency', `value` = '?'", $request->get('global_google_currency', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'google', `key` = 'google_sort_order', `value` = '?'", $request->get('global_google_sort_order', 'post')));
			
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
		$view->set('entry_merchantid', $language->get('entry_merchantid'));
		$view->set('entry_merchantkey', $language->get('entry_merchantkey'));
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
		$view->set('error_merchantid', @$this->error['merchantid']);
		$view->set('error_merchantkey', @$this->error['merchantkey']);
		
		$view->set('action', $url->ssl('payment_google'));
		
		$view->set('list', $url->ssl('extension', FALSE, array('type' => 'payment')));

		$view->set('cancel', $url->ssl('extension', FALSE, array('type' => 'payment')));	

		$results = $database->getRows("select * from setting where `group` = 'google'");
			
		foreach ($results as $result) {
			$setting_info[$result['type']][$result['key']] = $result['value'];
		}
		
		if ($request->has('global_google_status', 'post')) {
			$view->set('global_google_status', $request->get('global_google_status', 'post'));
		} else {
			$view->set('global_google_status', @$setting_info['global']['google_status']);
		}
		
		if ($request->has('global_google_geo_zone_id', 'post')) {
			$view->set('geo_zone_id', $request->get('global_google_geo_zone_id', 'post'));
		} else {
			$view->set('geo_zone_id', @$setting_info['global']['google_geo_zone_id']); 
		} 

		if ($request->has('global_google_merchantid', 'post')) {
			$view->set('global_google_merchantid', $request->get('global_google_merchantid', 'post'));
		} else {
			$view->set('global_google_merchantid', @$setting_info['global']['google_merchantid']);
		}
		
		if ($request->has('global_google_merchantkey', 'post')) {
			$view->set('global_google_merchantkey', $request->get('global_google_merchantkey', 'post'));
		} else {
			$view->set('global_google_merchantkey', @$setting_info['global']['google_merchantkey']);
		}
		
		if ($request->has('global_google_test', 'post')) {
			$view->set('global_google_test', $request->get('global_google_test', 'post'));
		} else {
			$view->set('global_google_test', @$setting_info['global']['google_test']);
		}
		
		if ($request->has('global_google_currency', 'post')) {
			$payment_info = $request->get('global_google_currency', 'post');
		} else {
			$payment_info = $setting_info['global']['google_currency'];
		}

		$currency_data = array();
		
		$currency_data[] = array(
			'text'     => $language->get('text_gbp'),
			'value'    => 'GBP',
			'selected' => ('GBP'==$payment_info)
		);

		$currency_data[] = array(
			'text'     => $language->get('text_usd'),
			'value'    => 'USD',
			'selected' => ('USD'==$payment_info)
		);

		$view->set('currencies', $currency_data);
		
		if ($request->has('global_google_sort_order', 'post')) {
			$view->set('global_google_sort_order', $request->get('global_google_sort_order', 'post'));
		} else {
			$view->set('global_google_sort_order', @$setting_info['global']['google_sort_order']);
		}
										
		$view->set('geo_zones', $database->cache('geo_zone', "select * from geo_zone"));
								
		$template->set('content', $view->fetch('content/payment_google.tpl'));

		$template->set($module->fetch());

		$response->set($template->fetch('layout.tpl'));
	}
	
	function validate() {
		$language =& $this->locator->get('language');
		$request  =& $this->locator->get('request');
		$user     =& $this->locator->get('user');

		if (!$user->hasPermission('modify', 'payment_google')) {
			$this->error['message'] = $language->get('error_permission');
		}
		
		if (!$request->get('global_google_merchantid', 'post')) {
			$this->error['merchantid'] = $language->get('error_merchantid');
		}
				
		if (!$request->get('global_google_merchantkey', 'post')) {
			$this->error['merchantkey'] = $language->get('error_merchantkey');
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
		
		$language->load('controller/payment_google.php');
				
		if ($user->hasPermission('modify', 'payment_google')) {
			$database->query("delete from setting where `group` = 'google'");
			
			$database->query("insert into setting set type = 'global', `group` = 'google', `key` = 'google_status', value = '0'");
			$database->query("insert into setting set type = 'global', `group` = 'google', `key` = 'google_geo_zone_id', value = '0'");
			$database->query("insert into setting set type = 'global', `group` = 'google', `key` = 'google_merchantid', value = ''");
			$database->query("insert into setting set type = 'global', `group` = 'google', `key` = 'google_merchantkey', value = ''");
			$database->query("insert into setting set type = 'global', `group` = 'google', `key` = 'google_test', value = '0'");
			$database->query("insert into setting set type = 'global', `group` = 'google', `key` = 'google_currency', value = 'USD'");
			$database->query("insert into setting set type = 'global', `group` = 'google', `key` = 'google_sort_order', value = '1'");
		
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

		$language->load('controller/payment_google.php');
				
		if ($user->hasPermission('modify', 'payment_google')) {
			$database->query("delete from setting where `group` = 'google'");
			
			$session->set('message', $language->get('text_message'));
		} else {
			$session->set('error', $language->get('error_permission'));
		}	
				
		$response->redirect($url->ssl('extension', FALSE, array('type' => 'payment')));
	}
}
?>