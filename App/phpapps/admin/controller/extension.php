<?php
class ControllerExtension extends Controller {
	var $error = array();
	
	function index() {
		$response =& $this->locator->get('response');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$module   =& $this->locator->get('module');
 
		$language->load('controller/extension.php');
		
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
						
		$language->load('controller/extension.php');

		$template->set('title', $language->get('heading_title'));
				
		if ($request->isPost() && $request->has('code', 'post') && $this->validateForm()) {
			$sql = "insert into extension set code = '?', type = '?', directory = '?', filename = '?', controller = '?'";
			$database->query($database->parse($sql, $request->gethtml('code', 'post'), $request->gethtml('type'), $request->gethtml('directory', 'post'), $request->gethtml('filename', 'post'), $request->gethtml('controller', 'post')));

			$insert_id = $database->getLastId(); 
						
			foreach ($request->gethtml('extension_language', 'post') as $key => $value) {
				$sql = "insert into extension_description set extension_id = '?', language_id = '?', name = '?', description = '?'";
				$database->query($database->parse($sql, $insert_id, $key, $value['name'], $value['description']));
			}
			
			$session->set('message', $language->get('text_message'));

			$response->redirect($url->ssl('extension', FALSE, array('type' => $request->gethtml('type'))));
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
				
		$language->load('controller/extension.php');

		$template->set('title', $language->get('heading_title'));

		if ($request->isPost() && $request->has('code', 'post') && $this->validateForm()) {
			$sql = "update extension set code = '?', type = '?', directory = '?', filename = '?', controller = '?' where extension_id = '?'";
			$database->query($database->parse($sql, $request->gethtml('code', 'post'), $request->gethtml('type'), $request->gethtml('directory', 'post'), $request->gethtml('filename', 'post'), $request->gethtml('controller', 'post'), $request->gethtml('extension_id')));

			$database->query("delete from extension_description where extension_id = '" . (int)$request->gethtml('extension_id') . "'");
					
			foreach ($request->gethtml('extension_language', 'post') as $key => $value) {
				$sql = "insert into extension_description set extension_id = '?', language_id = '?', name = '?', description = '?'";
				$database->query($database->parse($sql, $request->gethtml('extension_id'), $key, $value['name'], $value['description']));
			}
			
			$session->set('message', $language->get('text_message'));

			$response->redirect($url->ssl('extension', FALSE, array('type' => $request->gethtml('type'))));
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
 
		$language->load('controller/extension.php');

		$template->set('title', $language->get('heading_title'));

		if (($request->gethtml('extension_id')) && ($this->validateDelete())) {
			$extension_info = $database->getRow("select distinct * from extension where extension_id = '" . (int)$request->gethtml('extension_id') . "'");
			
			$database->query($database->parse("delete from setting where `group` = '?'", $extension_info['code']));
			
			$database->query("delete from extension where extension_id = '" . (int)$request->gethtml('extension_id') . "'");
			$database->query("delete from extension_description where extension_id = '" . (int)$request->gethtml('extension_id') . "'");
			
			$session->set('message', $language->get('text_message'));

			$response->redirect($url->ssl('extension', FALSE, array('type' => $request->gethtml('type'))));
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

		$cols[] = array(
			'name'  => $language->get('column_name'),
			'sort'  => 'ed.name',
			'align' => 'left'
		);

		$cols[] = array(
			'name'  => $language->get('column_description'),
			'sort'  => 'ed.description',
			'align' => 'left'
		);

		$cols[] = array(
			'name'  => $language->get('column_code'),
			'sort'  => 'e.code',
			'align' => 'left'
		);

    	$cols[] = array(
      		'name'  => $language->get('column_action'),
      		'align' => 'right'
    	);
		
		if (!$session->get('extension.search')) {
			$sql = "select e.extension_id, ed.name, ed.description, e.code, e.type, e.controller from extension e left join extension_description ed on e.extension_id = ed.extension_id where e.type = '?' and ed.language_id = '" . (int)$language->getId() . "'";
		} else {
			$sql = "select e.extension_id, ed.name, ed.description, e.code, e.type, e.controller from extension e left join extension_description ed on e.extension_id = ed.extension_id where e.type = '?' and ed.language_id = '" . (int)$language->getId() . "' and ed.name like '?'";
		}

		$sort = array(
			'ed.name',
			'ed.description', 
			'e.code'
		);

		if (in_array($session->get('extension.sort'), $sort)) {
			$sql .= " order by " . $session->get('extension.sort') . " " . (($session->get('extension.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by ed.name asc";
		}

		$results = $database->getRows($database->splitQuery($database->parse($sql, $request->gethtml('type'), '%' . $session->get('extension.search') . '%'), $session->get('extension.page'), $config->get('config_max_rows')));

		$rows = array();

		foreach ($results as $result) {
			$cell = array();

			$cell[] = array(
				'value' => $result['name'],
				'align' => 'left'
			);

			$cell[] = array(
				'value' => $result['description'],
				'align' => 'left'
			);
			
			$cell[] = array(
				'value' => $result['code'],
				'align' => 'left'
			);

			$query = array(
				'type'         => $request->gethtml('type'),
				'extension_id' => $result['extension_id']
			);

			$action = array();
      		
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $language->get('button_update'),
				'href' => $url->ssl('extension', 'update', $query)
      		);
						
			$action[] = array(
        		'icon' => 'delete.png',
				'text' => $language->get('button_delete'),
				'href' => $url->ssl('extension', 'delete', $query)
      		);
			
			$array = explode('_', $result['controller']);
			
			if (count($array) >= 3 && in_array($array[1], array('admin','catalog'))) {
				$setting_info = $database->countRows($database->parse("select * from setting where type = '?' and `group` = '?'", $array[1], $result['code']));
			}
			else {
				$setting_info = $database->countRows($database->parse("select * from setting where `group` = '?'", $result['code']));
			}
			
			if ($setting_info) {	
				$action[] = array(
        			'icon' => 'uninstall.png',
					'text' => $language->get('button_uninstall'),
					'href' => $url->ssl($result['controller'], 'uninstall')
      			);
	
				$action[] = array(
        			'icon' => 'configure.png',
					'text' => $language->get('button_configure'),
					'href' => $url->ssl($result['controller'])
      			);	
			} else {
				$action[] = array(
        			'icon' => 'install.png',
					'text' => $language->get('button_install'),
					'href' => $url->ssl($result['controller'], 'install')
      			);			
			}
												
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
		
		if ($session->has('error')) {
			$view->set('error', $session->get('error'));
			
			$session->delete('error');
 		} else {
			$view->set('error', @$this->error['message']);
		}

		$view->set('message', $session->get('message'));
		
		$session->delete('message');
				
		$view->set('action', $url->ssl('extension', 'page', array('type' => $request->gethtml('type'))));

		$view->set('search', $session->get('extension.search'));
		$view->set('sort', $session->get('extension.sort'));
		$view->set('order', $session->get('extension.order'));
		$view->set('page', $session->get('extension.page'));

		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('list', $url->ssl('extension', FALSE, array('type' => $request->gethtml('type'))));

		$view->set('insert', $url->ssl('extension', 'insert', array('type' => $request->gethtml('type'))));

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

    	$view->set('text_enabled', $language->get('text_enabled'));
    	$view->set('text_disabled', $language->get('text_disabled'));
		
		$view->set('entry_name', $language->get('entry_name'));
		$view->set('entry_description', $language->get('entry_description'));
		$view->set('entry_code', $language->get('entry_code'));
		$view->set('entry_directory', $language->get('entry_directory'));
		$view->set('entry_filename', $language->get('entry_filename'));
		$view->set('entry_controller', $language->get('entry_controller'));

		$view->set('button_list', $language->get('button_list'));
		$view->set('button_insert', $language->get('button_insert'));
		$view->set('button_update', $language->get('button_update'));
		$view->set('button_delete', $language->get('button_delete'));
		$view->set('button_save', $language->get('button_save'));
		$view->set('button_cancel', $language->get('button_cancel'));

		$view->set('tab_general', $language->get('tab_general'));
		$view->set('tab_data', $language->get('tab_data'));
		
  		$view->set('error', @$this->error['message']);	
		$view->set('error_name', @$this->error['name']);
		$view->set('error_description', @$this->error['description']);
		$view->set('error_code', @$this->error['code']);
		$view->set('error_directory', @$this->error['directory']);
		$view->set('error_filename', @$this->error['filename']);
		$view->set('error_controller', @$this->error['controller']);
		
		$query = array(
			'type'         => $request->gethtml('type'),
			'extension_id' => $request->gethtml('extension_id')
		);
			
		$view->set('action', $url->ssl('extension', $request->gethtml('action'), $query));

		$view->set('list', $url->ssl('extension', FALSE, array('type' => $request->gethtml('type'))));

		$view->set('insert', $url->ssl('extension', 'insert'));

		if ($request->gethtml('extension_id')) {
			$query = array(
				'type'         => $request->gethtml('type'),
				'extension_id' => $request->gethtml('extension_id')
			);	
		
			$view->set('update', $url->ssl('extension', 'update', $query));
			$view->set('delete', $url->ssl('extension', 'delete', $query));
		}

		$view->set('cancel', $url->ssl('extension', FALSE, array('type' => $request->gethtml('type'))));
				
		$extension_data = array();

		$results = $database->cache('language', "select * from language order by sort_order");
		
		foreach ($results as $result) {
			if (($request->gethtml('extension_id')) && (!$request->isPost())) {		
				$extension_description_info = $database->getRow("select name, description from extension_description where extension_id = '" . (int)$request->gethtml('extension_id') . "' and language_id = '" . (int)$result['language_id'] . "'");				
			} else {
				$extension_description_info = $request->gethtml('extension_language', 'post');
			}
			
			$extension_data[] = array(
				'language_id' => $result['language_id'],
				'language'    => $result['name'],
	    		'name'        => (isset($extension_description_info[$result['language_id']]) ? $extension_description_info[$result['language_id']]['name'] : @$extension_description_info['name']),
				'description' => (isset($extension_description_info[$result['language_id']]) ? $extension_description_info[$result['language_id']]['description'] : @$extension_description_info['description'])
			);
		}

		$view->set('extensions', $extension_data);

		if (($request->gethtml('extension_id')) && (!$request->isPost())) {
			$extension_info = $database->getRow("select distinct * from extension where extension_id = '" . (int)$request->gethtml('extension_id') . "'");
		}

		if ($request->has('code', 'post')) {
			$view->set('code', $request->gethtml('code', 'post'));
		} else {
			$view->set('code', @$extension_info['code']);
		}

		if ($request->has('directory', 'post')) {
			$view->set('directory', $request->gethtml('directory', 'post'));
		} else {
			$view->set('directory', @$extension_info['directory']);
		}

		if ($request->has('filename', 'post')) {
			$view->set('filename', $request->gethtml('filename', 'post'));
		} else {
			$view->set('filename', @$extension_info['filename']);
		}

		if ($request->has('controller', 'post')) {
			$view->set('controller', $request->gethtml('controller', 'post'));
		} else {
			$view->set('controller', @$extension_info['controller']);
		}

		return $view->fetch('content/extension.tpl');
	}

	function validateForm() {
		$request  =& $this->locator->get('request');
		$user     =& $this->locator->get('user');
		$language =& $this->locator->get('language');
        $validate =& $this->locator->get('validate');
        
		if (!$user->hasPermission('modify', 'extension')) {
			$this->error['message'] = $language->get('error_permission');
		}
				
		foreach ($request->gethtml('extension_language', 'post') as $value) {
			if (!$validate->strlen($value['name'],1,128)) {
				$this->error['name'] = $language->get('error_name');
			}
		}

		foreach ($request->gethtml('extension_language', 'post') as $value) {
			if (!$validate->strlen($value['description'],1,255)) {
				$this->error['description'] = $language->get('error_description');
			}
		}

    	if (!$validate->strlen($request->gethtml('code', 'post'),1,32)) {
      		$this->error['code'] = $language->get('error_code');
    	}
        
        if (!$validate->strlen($request->gethtml('directory', 'post'),1,32)) {
      		$this->error['directory'] = $language->get('error_directory');
    	}
		
        if (!$validate->strlen($request->gethtml('filename', 'post'),1,128)) {
      		$this->error['filename'] = $language->get('error_filename');
    	}

        if (!$validate->strlen($request->gethtml('controller', 'post'),1,128)) {
      		$this->error['controller'] = $language->get('error_controller');
    	}
						
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function validateDelete() {
		$user     =& $this->locator->get('user');
		$language =& $this->locator->get('language');

		if (!$user->hasPermission('modify', 'extension')) {
			$this->error['message'] = $language->get('error_permission');
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
			$session->set('extension.search', $request->gethtml('search', 'post'));
		}

		if (($request->has('page', 'post')) || ($request->has('search', 'post'))) {
			$session->set('extension.page', $request->gethtml('page', 'post'));
		}

		if ($request->has('sort', 'post')) {
			$session->set('extension.order', (($session->get('extension.sort') == $request->gethtml('sort', 'post')) && ($session->get('extension.order') == 'asc')) ? 'desc' : 'asc');
		}

		if ($request->has('sort', 'post')) {
			$session->set('extension.sort', $request->gethtml('sort', 'post'));
		}

		$response->redirect($url->ssl('extension', FALSE, array('type' => $request->gethtml('type'))));
	}
}
?>
