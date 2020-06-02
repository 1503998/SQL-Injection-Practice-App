<?php
class ControllerModuleExtraManufacturer extends Controller {
	var $error = array();
	// All References change to module_extra_ due to new module loader  
	function index() { 
		$database =& $this->locator->get('database');
		$language =& $this->locator->get('language');
		$module   =& $this->locator->get('module');
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$template =& $this->locator->get('template');
		$session  =& $this->locator->get('session');
		$url      =& $this->locator->get('url');
		
		$language->load('controller/module_extra_manufacturer.php');

		$template->set('title', $language->get('heading_title'));
		
		if (($request->isPost()) && ($this->validate())) {
			$database->query("delete from setting where type = 'catalog' and `group` = 'manufacturer'");
			
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'manufacturer', `key` = 'manufacturer_status', `value` = '?'", $request->get('catalog_manufacturer_status', 'post')));
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'manufacturer', `key` = 'manufacturer_addtocart', `value` = '?'", $request->get('catalog_manufacturer_addtocart', 'post')));//New
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'manufacturer', `key` = 'manufacturer_image_width', `value` = '?'", $request->get('catalog_manufacturer_image_width', 'post')));//New
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'manufacturer', `key` = 'manufacturer_image_height', `value` = '?'", $request->get('catalog_manufacturer_image_height', 'post')));//New			
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'manufacturer', `key` = 'manufacturer_columns', `value` = '?'", $request->get('catalog_manufacturer_columns', 'post')));//New
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'manufacturer', `key` = 'manufacturer_display_lock', `value` = '?'", $request->get('catalog_manufacturer_display_lock', 'post')));//New
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'manufacturer', `key` = 'manufacturer_options_select', `value` = '?'", $request->gethtml('catalog_manufacturer_options_select', 'post'))); //New
			
			$session->set('message', $language->get('text_message'));
			$response->redirect($url->ssl('extension', FALSE, array('type' => 'module')));
		}
		$view = $this->locator->create('template');
		$view->set('heading_title', $language->get('heading_title'));
		$view->set('heading_description', $language->get('heading_description'));

		$view->set('text_enabled', $language->get('text_enabled'));
		$view->set('text_disabled', $language->get('text_disabled'));
		$view->set('text_select', $language->get('text_select'));
		$view->set('text_radio', $language->get('text_radio'));
		
		$view->set('entry_status', $language->get('entry_status'));
		$view->set('entry_addtocart', $language->get('entry_addtocart')); //New
		$view->set('text_image', $language->get('text_image'));
		$view->set('entry_height', $language->get('entry_height'));
		$view->set('entry_width', $language->get('entry_width'));
		$view->set('entry_columns', $language->get('entry_columns'));
		$view->set('entry_display_lock', $language->get('entry_display_lock'));
		$view->set('entry_options_select',$language->get('entry_options_select')); //New
		$view->set('button_list', $language->get('button_list'));
		$view->set('button_insert', $language->get('button_insert'));
		$view->set('button_update', $language->get('button_update'));
		$view->set('button_delete', $language->get('button_delete'));
		$view->set('button_save', $language->get('button_save'));
		$view->set('button_cancel', $language->get('button_cancel'));

		$view->set('tab_general', $language->get('tab_general'));

		$view->set('error', @$this->error['message']);
		$view->set('action', $url->ssl('module_extra_manufacturer'));
		$view->set('list', $url->ssl('extension', FALSE, array('type' => 'module')));

		$view->set('cancel', $url->ssl('extension', FALSE, array('type' => 'module')));
		if (!$request->isPost()) {
			$results = $database->getRows("select * from setting where type = 'catalog' and `group` = 'manufacturer'");
			foreach ($results as $result) {
				$setting_info[$result['type']][$result['key']] = $result['value'];
			}
		}			
		if ($request->has('catalog_manufacturer_status', 'post')) {
			$view->set('catalog_manufacturer_status', $request->get('catalog_manufacturer_status', 'post'));
		} else {
			$view->set('catalog_manufacturer_status', @$setting_info['catalog']['manufacturer_status']);
		}
		if ($request->has('catalog_manufacturer_addtocart', 'post')) {
			$view->set('catalog_manufacturer_addtocart', $request->get('catalog_manufacturer_addtocart', 'post'));
		} else {
			$view->set('catalog_manufacturer_addtocart', @$setting_info['catalog']['manufacturer_addtocart']);
		}
		if ($request->has('catalog_manufacturer_image_width', 'post')) {
			$view->set('catalog_manufacturer_image_width', $request->get('catalog_manufacturer_image_width', 'post'));
		} else {
			$view->set('catalog_manufacturer_image_width', @$setting_info['catalog']['manufacturer_image_width']);
		}
		if ($request->has('catalog_manufacturer_image_height', 'post')) {
			$view->set('catalog_manufacturer_image_height', $request->get('catalog_manufacturer_image_height', 'post'));
		} else {
			$view->set('catalog_manufacturer_image_height', @$setting_info['catalog']['manufacturer_image_height']);
		}
		if ($request->has('catalog_manufacturer_columns', 'post')) {
			$view->set('catalog_manufacturer_columns', $request->get('catalog_manufacturer_columns', 'post'));
		} else {
			$view->set('catalog_manufacturer_columns', @$setting_info['catalog']['manufacturer_columns']);
		}
		if ($request->has('catalog_manufacturer_display_lock', 'post')) {
			$view->set('catalog_manufacturer_display_lock', $request->get('catalog_manufacturer_display_lock', 'post'));
		} else {
			$view->set('catalog_manufacturer_display_lock', @$setting_info['catalog']['manufacturer_display_lock']);
		}
		if ($request->has('catalog_manufacturer_options_select', 'post')) {
			$view->set('catalog_manufacturer_options_select', $request->get('catalog_manufacturer_options_select', 'post'));
		} else {
			$view->set('catalog_manufacturer_options_select', @$setting_info['catalog']['manufacturer_options_select']);
		}
		$columns = array(1, 3, 4);
		$view->set('columns', $columns);
		
		$template->set('content', $view->fetch('content/module_extra_manufacturer.tpl'));

		$template->set($module->fetch());

		$response->set($template->fetch('layout.tpl'));
	}			
			
	function validate() {
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');

		if (!$user->hasPermission('modify', 'module_extra_manufacturer')) {
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

		$language->load('controller/module_extra_manufacturer.php');
		
		if ($user->hasPermission('modify', 'module_extra_manufacturer')) {
			$database->query("delete from setting where type = 'catalog' and `group` = 'manufacturer'");
			
			$database->query("insert into setting set type = 'catalog', `group` = 'manufacturer', `key` = 'manufacturer_status', value = '1'");
			$database->query("insert into setting set type = 'catalog', `group` = 'manufacturer', `key` = 'manufacturer_addtocart', value = '1'");//New			
			$database->query("insert into setting set type = 'catalog', `group` = 'manufacturer', `key` = 'manufacturer_columns', value = '1'");//New	
			$database->query("insert into setting set type = 'catalog', `group` = 'manufacturer', `key` = 'manufacturer_display_lock', value = '0'");//New	
			$database->query("insert into setting set type = 'catalog', `group` = 'manufacturer', `key` = 'manufacturer_image_width', value = '175'");//New			
			$database->query("insert into setting set type = 'catalog', `group` = 'manufacturer', `key` = 'manufacturer_image_height', value = '175'");//New
			$database->query("insert into setting set type = 'catalog', `group` = 'manufacturer', `key` = 'manufacturer_options_select', value = 'select'");//New
			
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
		
		$language->load('controller/module_extra_manufacturer.php');

		if ($user->hasPermission('modify', 'module_extra_manufacturer')) {
			$database->query("delete from setting where type = 'catalog' and `group` = 'manufacturer'");
			
			$session->set('message', $language->get('text_message'));
		} else {
			$session->set('error', $language->get('error_permission'));
		}	

		$response->redirect($url->ssl('extension', FALSE, array('type' => 'module')));
	}
}	
?>