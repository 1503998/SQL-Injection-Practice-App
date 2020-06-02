<?php
class ControllerModuleExtraRelated extends Controller {
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
		
		$language->load('controller/module_extra_related.php');

		$template->set('title', $language->get('heading_title'));

		if (($request->isPost()) && ($this->validate())) {
			$database->query("delete from setting where type = 'catalog' and `group` = 'related'");
			
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'related', `key` = 'related_status', `value` = '?'", $request->get('catalog_related_status', 'post')));
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'related', `key` = 'related_limit', `value` = '?'", $request->get('catalog_related_limit', 'post')));
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'related', `key` = 'related_image_width', `value` = '?'", $request->get('catalog_related_image_width', 'post')));//New
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'related', `key` = 'related_image_height', `value` = '?'", $request->get('catalog_related_image_height', 'post')));//New
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'related', `key` = 'related_addtocart', `value` = '?'", $request->get('catalog_related_addtocart', 'post')));//New				
			
			$session->set('message', $language->get('text_message'));
			
			$response->redirect($url->ssl('extension', FALSE, array('type' => 'module')));
		}
		
		$view = $this->locator->create('template');
		
		$view->set('heading_title', $language->get('heading_title'));
		$view->set('heading_description', $language->get('heading_description'));

		$view->set('text_enabled', $language->get('text_enabled'));
		$view->set('text_disabled', $language->get('text_disabled'));
		
		$view->set('entry_status', $language->get('entry_status'));
		$view->set('entry_limit', $language->get('entry_limit'));
		$view->set('entry_height', $language->get('entry_height'));	//New	
		$view->set('entry_width', $language->get('entry_width')); //New
		$view->set('entry_addtocart', $language->get('entry_addtocart')); //New
		
		$view->set('button_list', $language->get('button_list'));
		$view->set('button_insert', $language->get('button_insert'));
		$view->set('button_update', $language->get('button_update'));
		$view->set('button_delete', $language->get('button_delete'));
		$view->set('button_save', $language->get('button_save'));
		$view->set('button_cancel', $language->get('button_cancel'));

		$view->set('tab_general', $language->get('tab_general'));

		$view->set('error', @$this->error['message']);

		$view->set('action', $url->ssl('module_extra_related'));

		$view->set('list', $url->ssl('extension', FALSE, array('type' => 'module')));

		$view->set('cancel', $url->ssl('extension', FALSE, array('type' => 'module')));	

		if (!$request->isPost()) {
			$results = $database->getRows("select * from setting where type = 'catalog' and `group` = 'related'");
			
			foreach ($results as $result) {
				$setting_info[$result['type']][$result['key']] = $result['value'];
			}
		}

		if ($request->has('catalog_related_status', 'post')) {
			$view->set('catalog_related_status', $request->get('catalog_related_status', 'post'));
		} else {
			$view->set('catalog_related_status', @$setting_info['catalog']['related_status']);
		}
		if ($request->has('catalog_related_limit', 'post')) {
			$view->set('catalog_related_limit', $request->get('catalog_related_limit', 'post'));
		} else {
			$view->set('catalog_related_limit', @$setting_info['catalog']['related_limit']);
		}
		//New Block
		if ($request->has('catalog_related_image_width', 'post')) {
			$view->set('catalog_related_image_width', $request->get('catalog_related_image_width', 'post'));
		} else {
			$view->set('catalog_related_image_width', @$setting_info['catalog']['related_image_width']);
		}
		if ($request->has('catalog_related_image_height', 'post')) {
			$view->set('catalog_related_image_height', $request->get('catalog_related_image_height', 'post'));
		} else {
			$view->set('catalog_related_image_height', @$setting_info['catalog']['related_image_height']);
		}
		if ($request->has('catalog_related_addtocart', 'post')) {
			$view->set('catalog_related_addtocart', $request->get('catalog_related_addtocart', 'post'));
		} else {
			$view->set('catalog_related_addtocart', @$setting_info['catalog']['related_addtocart']);
		}		
		// End of New Block
		$template->set('content', $view->fetch('content/module_extra_related.tpl'));

		$template->set($module->fetch());

		$response->set($template->fetch('layout.tpl'));
	}

	function validate() {
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');

		if (!$user->hasPermission('modify', 'module_extra_related')) {
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

		$language->load('controller/module_extra_related.php');
		
		if ($user->hasPermission('modify', 'module_extra_related')) {
			$database->query("delete from setting where type = 'catalog' and `group` = 'related'");
			
			$database->query("insert into setting set type = 'catalog', `group` = 'related', `key` = 'related_status', value = '1'");
			$database->query("insert into setting set type = 'catalog', `group` = 'related', `key` = 'related_limit', value = '3'");
			$database->query("insert into setting set type = 'catalog', `group` = 'related', `key` = 'related_image_width', value = '175'");//New			
			$database->query("insert into setting set type = 'catalog', `group` = 'related', `key` = 'related_image_height', value = '175'");//New			
			$database->query("insert into setting set type = 'catalog', `group` = 'related', `key` = 'related_addtocart', value = '1'");//New
			
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
		
		$language->load('controller/module_extra_related.php');

		if ($user->hasPermission('modify', 'module_extra_related')) {
			$database->query("delete from setting where type = 'catalog' and `group` = 'related'");
			
			$session->set('message', $language->get('text_message'));
		} else {
			$session->set('error', $language->get('error_permission'));
		}	

		$response->redirect($url->ssl('extension', FALSE, array('type' => 'module')));
	}
}
?>