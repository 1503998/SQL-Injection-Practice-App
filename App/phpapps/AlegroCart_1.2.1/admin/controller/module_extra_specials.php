<?php
class ControllerModuleExtraSpecials extends Controller {
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
		
		$language->load('controller/module_extra_specials.php');

		$template->set('title', $language->get('heading_title'));

		if (($request->isPost()) && ($this->validate())) {
			$database->query("delete from setting where type = 'catalog' and `group` = 'specials'");
			
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'specials', `key` = 'specials_status', `value` = '?'", $request->get('catalog_specials_status', 'post')));
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'specials', `key` = 'specials_limit', `value` = '?'", $request->get('catalog_specials_limit', 'post')));
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'specials', `key` = 'specials_image_width', `value` = '?'", $request->get('catalog_specials_image_width', 'post')));//New
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'specials', `key` = 'specials_image_height', `value` = '?'", $request->get('catalog_specials_image_height', 'post')));//New
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'specials', `key` = 'specials_addtocart', `value` = '?'", $request->get('catalog_specials_addtocart', 'post')));//New			
			
			
			
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

		$view->set('action', $url->ssl('module_extra_specials'));

		$view->set('list', $url->ssl('extension', FALSE, array('type' => 'module')));

		$view->set('cancel', $url->ssl('extension', FALSE, array('type' => 'module')));	

		if (!$request->isPost()) {
			$results = $database->getRows("select * from setting where type = 'catalog' and `group` = 'specials'");
			
			foreach ($results as $result) {
				$setting_info[$result['type']][$result['key']] = $result['value'];
			}
		}

		if ($request->has('catalog_specials_status', 'post')) {
			$view->set('catalog_specials_status', $request->get('catalog_specials_status', 'post'));
		} else {
			$view->set('catalog_specials_status', @$setting_info['catalog']['specials_status']);
		}
		if ($request->has('catalog_specials_limit', 'post')) {
			$view->set('catalog_specials_limit', $request->get('catalog_specials_limit', 'post'));
		} else {
			$view->set('catalog_specials_limit', @$setting_info['catalog']['specials_limit']);
		}
		//New Block
		if ($request->has('catalog_specials_image_width', 'post')) {
			$view->set('catalog_specials_image_width', $request->get('catalog_specials_image_width', 'post'));
		} else {
			$view->set('catalog_specials_image_width', @$setting_info['catalog']['specials_image_width']);
		}
		if ($request->has('catalog_specials_image_height', 'post')) {
			$view->set('catalog_specials_image_height', $request->get('catalog_specials_image_height', 'post'));
		} else {
			$view->set('catalog_specials_image_height', @$setting_info['catalog']['specials_image_height']);
		}
		if ($request->has('catalog_specials_addtocart', 'post')) {
			$view->set('catalog_specials_addtocart', $request->get('catalog_specials_addtocart', 'post'));
		} else {
			$view->set('catalog_specials_addtocart', @$setting_info['catalog']['specials_addtocart']);
		}		
		// End of New Block
		$template->set('content', $view->fetch('content/module_extra_specials.tpl'));

		$template->set($module->fetch());

		$response->set($template->fetch('layout.tpl'));
	}

	function validate() {
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');

		if (!$user->hasPermission('modify', 'module_extra_specials')) {
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

		$language->load('controller/module_extra_specials.php');
		
		if ($user->hasPermission('modify', 'module_extra_specials')) {
			$database->query("delete from setting where type = 'catalog' and `group` = 'specials'");
			
			$database->query("insert into setting set type = 'catalog', `group` = 'specials', `key` = 'specials_status', value = '1'");
			$database->query("insert into setting set type = 'catalog', `group` = 'specials', `key` = 'specials_limit', value = '3'");
			$database->query("insert into setting set type = 'catalog', `group` = 'specials', `key` = 'specials_image_width', value = '175'");//New			
			$database->query("insert into setting set type = 'catalog', `group` = 'specials', `key` = 'specials_image_height', value = '175'");//New			
			$database->query("insert into setting set type = 'catalog', `group` = 'specials', `key` = 'specials_addtocart', value = '1'");//New			
			
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
		
		$language->load('controller/module_extra_specials.php');

		if ($user->hasPermission('modify', 'module_extra_specials')) {
			$database->query("delete from setting where type = 'catalog' and `group` = 'specials'");
			
			$session->set('message', $language->get('text_message'));
		} else {
			$session->set('error', $language->get('error_permission'));
		}	

		$response->redirect($url->ssl('extension', FALSE, array('type' => 'module')));
	}
}
?>