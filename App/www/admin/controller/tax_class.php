<?php
class ControllerTaxClass extends Controller {
	var $error = array();

	function index() {
		$response =& $this->locator->get('response');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template'); 
		$module   =& $this->locator->get('module');

		$language->load('controller/tax_class.php');

		$template->set('title', $language->get('heading_title'));

		$template->set('content', $this->getList());

		$template->set($module->fetch());

		$response->set($template->fetch('layout.tpl')); 
	}

	function insert() {
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$database =& $this->locator->get('database');
		$url      =& $this->locator->get('url');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$session  =& $this->locator->get('session');
		$module   =& $this->locator->get('module');
		$cache    =& $this->locator->get('cache');

		$language->load('controller/tax_class.php');

		$template->set('title', $language->get('heading_title'));

		if ($request->isPost() && $request->has('title', 'post') && $this->validateForm()) {
			$sql = "insert into tax_class set title = '?', description = '?', date_added = now()";
			$database->query($database->parse($sql, $request->gethtml('title', 'post'), $request->gethtml('description', 'post')));

			$cache->delete('tax_class');

			$session->set('message', $language->get('text_message'));
			
			$response->redirect($url->ssl('tax_class'));
		}

		$template->set('content', $this->getForm());

		$template->set($module->fetch());

		$response->set($template->fetch('layout.tpl'));
	}

	function update() {
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$database =& $this->locator->get('database');
		$url      =& $this->locator->get('url');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$session  =& $this->locator->get('session');
		$module   =& $this->locator->get('module');
		$cache    =& $this->locator->get('cache');

		$language->load('controller/tax_class.php');

		$template->set('title', $language->get('heading_title'));

		if ($request->isPost() && $request->has('title', 'post') && $this->validateForm()) {
			$sql = "update tax_class set title = '?', description = '?', date_modified = now() where tax_class_id = '?'";
			$database->query($database->parse($sql, $request->gethtml('title', 'post'), $request->gethtml('description', 'post'), $request->gethtml('tax_class_id')));

			$cache->delete('tax_class');
			
			$session->set('message', $language->get('text_message'));
			
			$response->redirect($url->ssl('tax_class'));
		}

		$template->set('content', $this->getForm());

		$template->set($module->fetch());

		$response->set($template->fetch('layout.tpl'));
	}

	function delete() {
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$database =& $this->locator->get('database');
		$url      =& $this->locator->get('url');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$session  =& $this->locator->get('session');
		$module   =& $this->locator->get('module');
		$cache    =& $this->locator->get('cache');

		$language->load('controller/tax_class.php');

		$template->set('title', $language->get('heading_title'));
 
		if (($request->gethtml('tax_class_id')) && ($this->validateDelete())) {
			$database->query("delete from tax_class where tax_class_id = '" . (int)$request->gethtml('tax_class_id') . "'");
			$database->query("delete from tax_rate where tax_class_id = '" . (int)$request->gethtml('tax_class_id') . "'");

			$cache->delete('tax_class');
			
			$session->set('message', $language->get('text_message'));
			
			$response->redirect($url->ssl('tax_class'));
		}

		$template->set('content', $this->getList());

		$template->set($module->fetch());

		$response->set($template->fetch('layout.tpl'));
	}

	function getList() {
		$request  =& $this->locator->get('request');
		$database =& $this->locator->get('database');
		$config   =& $this->locator->get('config');
		$url      =& $this->locator->get('url');
		$language =& $this->locator->get('language');
		$session  =& $this->locator->get('session');

		$cols = array();
    	
		$cols[] = array('align' => 'center');
		
		$cols[] = array(
			'name'  => $language->get('column_title'),
			'sort'  => 'title',
			'align' => 'left'
		);

    	$cols[] = array(
      		'name'  => $language->get('column_action'),
      		'align' => 'right'
    	);
		
		if (!$session->get('tax_class.search')) {
			$sql = "select tax_class_id, title from tax_class";
		} else {
			$sql = "select tax_class_id, title from tax_class where title like '?'";
		}

		if (in_array($session->get('tax_class.sort'), array('title'))) {
			$sql .= " order by " . $session->get('tax_class.sort') . " " . (($session->get('tax_class.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by title asc";
		}

		$results = $database->getRows($database->splitQuery($database->parse($sql, '%' . $session->get('tax_class.search') . '%'), $session->get('tax_class.page'), $config->get('config_max_rows')));

		$rows = array();

		foreach ($results as $result) {
			$cell = array();

      		$cell[] = array(
        		'icon'  => 'folder.png',
        		'align' => 'center',
				'path'  => $url->ssl('tax_rate', FALSE, array('tax_class_id' => $result['tax_class_id']))
		  	);
			
			$cell[] = array(
				'value' => $result['title'],
				'align' => 'left'
			);
			
			$action = array();
      		
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $language->get('button_update'),
				'href' => $url->ssl('tax_class', 'update', array('tax_class_id' => $result['tax_class_id']))
      		);
						
			$action[] = array(
        		'icon' => 'delete.png',
				'text' => $language->get('button_delete'),
				'href' => $url->ssl('tax_class', 'delete', array('tax_class_id' => $result['tax_class_id']))
      		);

      		$cell[] = array(
        		'action' => $action,
        		'align'  => 'right'
      		);
			
			$rows[] = array('cell' => $cell);
		}

		$view = $this->locator->create('template');

		$view->set('heading_title', $language->get('heading_title'));
		$view->set('heading_description', $language->get('heading_description'));

		$view->set('text_results', $language->get('text_results', $database->getFrom(), $database->getTo(), $database->getTotal()));

		$view->set('entry_page', $language->get('entry_page'));
		$view->set('entry_search', $language->get('entry_search'));

		$view->set('button_list', $language->get('button_list'));
		$view->set('button_insert', $language->get('button_insert'));
		$view->set('button_update', $language->get('button_update'));
		$view->set('button_delete', $language->get('button_delete'));
		$view->set('button_save', $language->get('button_save'));
		$view->set('button_cancel', $language->get('button_cancel'));
 
		$view->set('error', @$this->error['message']);

		$view->set('message', $session->get('message'));
		
		$session->delete('message');
		
		$view->set('action', $url->ssl('tax_class', 'page'));

		$view->set('search', $session->get('tax_class.search'));
		$view->set('sort', $session->get('tax_class.sort'));
		$view->set('order', $session->get('tax_class.order'));
		$view->set('page', $session->get('tax_class.page'));

		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('list', $url->ssl('tax_class'));

		$view->set('insert', $url->ssl('tax_class', 'insert'));

		$page_data = array();

		for ($i = 1; $i <= $database->getPages(); $i++) {
			$page_data[] = array(
				'text'  => $language->get('text_pages', $i, $database->getPages()),
				'value' => $i
			);
		}

		$view->set('pages', $page_data);

		return $view->fetch('content/list.tpl');
	}

	function getForm() {
		$request  =& $this->locator->get('request');
		$database =& $this->locator->get('database');
		$url      =& $this->locator->get('url');
		$language =& $this->locator->get('language');

		$view = $this->locator->create('template');

		$view->set('heading_title', $language->get('heading_title'));
		$view->set('heading_description', $language->get('heading_description'));

		$view->set('entry_title', $language->get('entry_title'));
		$view->set('entry_description', $language->get('entry_description'));

		$view->set('button_list', $language->get('button_list'));
		$view->set('button_insert', $language->get('button_insert'));
		$view->set('button_update', $language->get('button_update'));
		$view->set('button_delete', $language->get('button_delete'));
		$view->set('button_save', $language->get('button_save'));
		$view->set('button_cancel', $language->get('button_cancel'));

		$view->set('tab_general', $language->get('tab_general'));

		$view->set('error', @$this->error['message']);
		$view->set('error_title', @$this->error['title']);
		$view->set('error_description', @$this->error['description']);

		$view->set('action', $url->ssl('tax_class', $request->gethtml('action'), array('tax_class_id' => $request->gethtml('tax_class_id'))));

		$view->set('list', $url->ssl('tax_class'));

		$view->set('insert', $url->ssl('tax_class', 'insert'));

		if ($request->gethtml('tax_class_id')) {
			$view->set('update', $url->ssl('tax_class', 'update', array('tax_class_id' => $request->gethtml('tax_class_id'))));
			$view->set('delete', $url->ssl('tax_class', 'delete', array('tax_class_id' => $request->gethtml('tax_class_id'))));
		}

		$view->set('cancel', $url->ssl('tax_class'));

		if (($request->gethtml('tax_class_id')) && (!$request->isPost())) {
			$tax_class_info = $database->getRow("select * from tax_class where tax_class_id = '" . (int)$request->gethtml('tax_class_id') . "'");
		}

		if ($request->has('title', 'post')) {
			$view->set('title', $request->gethtml('title', 'post'));
		} else {
			$view->set('title', @$tax_class_info['title']);
		}

		if ($request->has('description', 'post')) {
			$view->set('description', $request->gethtml('description', 'post'));
		} else {
			$view->set('description', @$tax_class_info['description']);
		}

		return $view->fetch('content/tax_class.tpl');
	}

	function validateForm() {
		$request  =& $this->locator->get('request');
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');
        $validate =& $this->locator->get('validate');

		if (!$user->hasPermission('modify', 'tax_class')) {
			$this->error['message'] = $language->get('error_permission');
		}
    
        if (!$validate->strlen($request->gethtml('title', 'post'),1,32)) {
			$this->error['title'] = $language->get('error_title');
		}

        if (!$validate->strlen($request->gethtml('description', 'post'),1,255)) {
			$this->error['description'] = $language->get('error_description');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function validateDelete() {
		$request  =& $this->locator->get('request');
		$database =& $this->locator->get('database');
		$user     =& $this->locator->get('user');
		$language =& $this->locator->get('language');

		if (!$user->hasPermission('modify', 'tax_class')) {
			$this->error['message'] = $language->get('error_permission');
		}

		$product_info = $database->getRow("select count(*) as total from product where tax_class_id = '" . (int)$request->gethtml('tax_class_id') . "'");

		if ($product_info['total']) {
			$this->error['message'] = $language->get('error_product', $product_info['total']);
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function page() {
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$url      =& $this->locator->get('url');
		$session  =& $this->locator->get('session');

		if ($request->has('search', 'post')) {
			$session->set('tax_class.search', $request->gethtml('search', 'post'));
		}

		if (($request->has('page', 'post')) || ($request->has('search', 'post'))) {
			$session->set('tax_class.page', $request->gethtml('page', 'post'));
		}

		if ($request->has('sort', 'post')) {
			$session->set('tax_class.order', (($session->get('tax_class.sort') == $request->gethtml('sort', 'post')) && ($session->get('tax_class.order') == 'asc')) ? 'desc' : 'asc');
		}

		if ($request->has('sort', 'post')) {
			$session->set('tax_class.sort', $request->gethtml('sort', 'post'));
		}

		$response->redirect($url->ssl('tax_class'));
	}	
}
?>