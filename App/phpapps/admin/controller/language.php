<?php
class ControllerLanguage extends Controller {
	var $error = array();
 
	function index() {
		$response =& $this->locator->get('response');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$module   =& $this->locator->get('module');

		$language->load('controller/language.php');

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

		$language->load('controller/language.php');

		$template->set('title', $language->get('heading_title'));

		if ($request->isPost() && $request->has('name', 'post') && $this->validateForm()) {
			$sql = "insert into language set name = '?', code = '?', directory = '?', filename= '?', image = '?', sort_order = '?'";
			$database->query($database->parse($sql, $request->gethtml('name', 'post'), $request->gethtml('code', 'post'), $request->gethtml('directory', 'post'), $request->gethtml('filename', 'post'), $request->gethtml('image', 'post'), $request->gethtml('sort_order', 'post')));

			$cache->delete('language');
			
			$session->set('message', $language->get('text_message'));

			$response->redirect($url->ssl('language'));
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

		$language->load('controller/language.php');

		$template->set('title', $language->get('heading_title'));

		if ($request->isPost() && $request->has('name', 'post') && $this->validateForm()) {
			$sql = "update language set name = '?', code = '?', directory = '?', filename= '?', image = '?', sort_order = '?' where language_id = '?'";
			$database->query($database->parse($sql, $request->gethtml('name', 'post'), $request->gethtml('code', 'post'), $request->gethtml('directory', 'post'), $request->gethtml('filename', 'post'), $request->gethtml('image', 'post'), $request->gethtml('sort_order', 'post'), $request->gethtml('language_id')));

			$cache->delete('language');
			
			$session->set('message', $language->get('text_message'));

			$response->redirect($url->ssl('language'));
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

		$language->load('controller/language.php');

		$template->set('title', $language->get('heading_title'));

		if (($request->gethtml('language_id')) && ($this->validateDelete())) {
			$database->query("delete from language where language_id = '" . (int)$request->gethtml('language_id') . "'");

			$cache->delete('language');

			$response->redirect($url->ssl('language'));
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
			'sort'  => 'name',
			'align' => 'left'
		);

		$cols[] = array(
			'name'  => $language->get('column_code'),
			'sort'  => 'code',
			'align' => 'left'
		);

		$cols[] = array(
			'name'  => $language->get('column_sort_order'),
			'sort'  => 'sort_order',
			'align' => 'right'
		);

    	$cols[] = array(
      		'name'  => $language->get('column_action'),
      		'align' => 'right'
    	);
		
		if (!$session->get('language.search')) {
			$sql = "select language_id, name, code, sort_order from language";
		} else {
			$sql = "select language_id, name, code, sort_order from language where name like '?'";
		}

		$sort = array(
			'name',
			'code',
			'sort_order'
		);

		if (in_array($session->get('language.sort'), $sort)) {
			$sql .= " order by " . $session->get('language.sort') . " " . (($session->get('language.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by name asc";
		}

		$results = $database->getRows($database->splitQuery($database->parse($sql, '%' . $session->get('language.search') . '%'), $session->get('language.page'), $config->get('config_max_rows')));

		$rows = array();

		foreach ($results as $result) {
			$cell = array();

			$cell[] = array(
				'value'   => $result['name'],
				'align'   => 'left',
				'default' => ($result['code'] == $config->get('config_language'))
			);

			$cell[] = array(
				'value' => $result['code'],
				'align' => 'left'
			);

			$cell[] = array(
				'value' => $result['sort_order'],
				'align' => 'right'
			);
			
			$action = array();
      		
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $language->get('button_update'),
				'href' => $url->ssl('language', 'update', array('language_id' => $result['language_id']))
      		);
						
			$action[] = array(
        		'icon' => 'delete.png',
				'text' => $language->get('button_delete'),
				'href' => $url->ssl('language', 'delete', array('language_id' => $result['language_id']))
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

		$view->set('text_default', $language->get('text_default'));
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
		
		$view->set('action', $url->ssl('language', 'page'));

		$view->set('search', $session->get('language.search'));
		$view->set('sort', $session->get('language.sort'));
		$view->set('order', $session->get('language.order'));
		$view->set('page', $session->get('language.page'));

		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('list', $url->ssl('language'));

		$view->set('insert', $url->ssl('language', 'insert'));

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

		$view->set('entry_name', $language->get('entry_name'));
		$view->set('entry_code', $language->get('entry_code'));
		$view->set('entry_image', $language->get('entry_image'));
		$view->set('entry_directory', $language->get('entry_directory'));
		$view->set('entry_filename', $language->get('entry_filename'));
		$view->set('entry_sort_order', $language->get('entry_sort_order'));

		$view->set('button_list', $language->get('button_list'));
		$view->set('button_insert', $language->get('button_insert'));
		$view->set('button_update', $language->get('button_update'));
		$view->set('button_delete', $language->get('button_delete'));
		$view->set('button_save', $language->get('button_save'));
		$view->set('button_cancel', $language->get('button_cancel'));

		$view->set('tab_general', $language->get('tab_general'));

		$view->set('error', @$this->error['message']);
		$view->set('error_name', @$this->error['name']);
		$view->set('error_code', @$this->error['code']);
		$view->set('error_image', @$this->error['image']);
		$view->set('error_directory', @$this->error['directory']);
		$view->set('error_filename', @$this->error['filename']);

		$view->set('action', $url->ssl('language', $request->gethtml('action'), array('language_id' => $request->gethtml('language_id'))));

		$view->set('list', $url->ssl('language'));

		$view->set('insert', $url->ssl('language', 'insert'));

		if ($request->gethtml('language_id')) {
			$view->set('update', $url->ssl('language', 'update', array('language_id' => $request->gethtml('language_id'))));
			$view->set('delete', $url->ssl('language', 'delete', array('language_id' => $request->gethtml('language_id'))));
		}

		$view->set('cancel', $url->ssl('language'));

		if (($request->gethtml('language_id')) && (! $request->isPost())) {
			$language_info = $database->getRow("select distinct * from language where language_id = '" . (int)$request->gethtml('language_id') . "'");
		}

		if ($request->has('name', 'post')) {
			$view->set('name', $request->gethtml('name', 'post'));
		} else {
			$view->set('name', @$language_info['name']);
		}

		if ($request->has('code', 'post')) {
			$view->set('code', $request->gethtml('code', 'post'));
		} else {
			$view->set('code', @$language_info['code']);
		}

		if ($request->has('image', 'post')) {
			$view->set('image', $request->gethtml('image', 'post'));
		} else {
			$view->set('image', @$language_info['image']);
		}

		if ($request->has('directory', 'post')) {
			$view->set('directory', $request->gethtml('directory', 'post'));
		} else {
			$view->set('directory', @$language_info['directory']);
		}

		if ($request->has('filename', 'post')) {
			$view->set('filename', $request->gethtml('filename', 'post'));
		} else {
			$view->set('filename', @$language_info['filename']);
		}

		if ($request->has('sort_order', 'post')) {
			$view->set('sort_order', $request->gethtml('sort_order', 'post'));
		} else {
			$view->set('sort_order', @$language_info['sort_order']);
		}

		return $view->fetch('content/language.tpl');
	}
	
	function validateForm() {
		$request  =& $this->locator->get('request');
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');
        $validate =& $this->locator->get('validate');

		if (!$user->hasPermission('modify', 'language')) {
			$this->error['message'] = $language->get('error_permission');
		}
        if (!$validate->strlen($request->gethtml('name', 'post'),1,32)) {
			$this->error['name'] = $language->get('error_name');
		}

		if (!$validate->strlen($request->gethtml('code', 'post'),2,5)) {
			$this->error['code'] = $language->get('error_code');
		}

		if (!$request->gethtml('directory', 'post')) {
			$this->error['directory'] = $language->get('error_directory');
		}

		if (!$request->gethtml('image', 'post')) {
			$this->error['image'] = $language->get('error_image');
		}
        
        if (!$request->gethtml('filename', 'post')) {
            $this->error['filename'] = $language->get('error_filename');
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
		$config   =& $this->locator->get('config');
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');

		if (!$user->hasPermission('modify', 'language')) {
			$this->error['message'] = $language->get('error_permission');
		}

		$language_info = $database->getRow("select distinct code from language where language_id = '" . (int)$request->gethtml('language_id') . "'");

		if ($config->get('config_language') == $language_info['code'] || $language_info['code'] == "en") {
			$this->error['message'] = $language->get('error_default');
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
			$session->set('language.search', $request->gethtml('search', 'post'));
		}

		if (($request->has('page', 'post')) || ($request->has('search', 'post'))) {
			$session->set('language.page', $request->gethtml('page', 'post'));
		}

		if ($request->has('sort', 'post')) {
			$session->set('language.order', (($session->get('language.sort') == $request->gethtml('sort', 'post')) && ($session->get('language.order') == 'asc') ? 'desc' : 'asc'));
		}
		
		if ($request->has('sort', 'post')) {
			$session->set('language.sort', $request->gethtml('sort', 'post'));
		}
		
		$response->redirect($url->ssl('language'));
	}
}
?>