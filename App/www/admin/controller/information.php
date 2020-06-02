<?php
class ControllerInformation extends Controller {
	var $error = array();

	function index() {
		$response =& $this->locator->get('response');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$module   =& $this->locator->get('module');
 
		$language->load('controller/information.php');

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
						
		$language->load('controller/information.php');

		$template->set('title', $language->get('heading_title'));
				
		if ($request->isPost() && $request->has('language', 'post') && $this->validateForm()) {
			$sql = "insert into information set sort_order = '?'";
			$database->query($database->parse($sql, $request->gethtml('sort_order', 'post')));

			$insert_id = $database->getLastId(); 
			
			foreach ($request->get('language', 'post') as $key => $value) {
				$sql = "insert into information_description set information_id = '?', language_id = '?', title = '?', description = '?'";
				$database->query($database->parse($sql, $insert_id, $key, $value['title'], $value['description']));
			}

			$cache->delete('information');
			
			$session->set('message', $language->get('text_message'));

			$response->redirect($url->ssl('information'));
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
				
		$language->load('controller/information.php');

		$template->set('title', $language->get('heading_title'));

		if ($request->isPost() && $request->has('language', 'post') && $this->validateForm()) {
			$sql = "update information set sort_order = '?' where information_id = '?'";
			$database->query($database->parse($sql, $request->gethtml('sort_order', 'post'), $request->gethtml('information_id')));

			$database->query("delete from information_description where information_id = '" . (int)$request->gethtml('information_id') . "'");
						
			foreach ($request->get('language', 'post') as $key => $value) {
				$sql = "insert into information_description set information_id = '?', language_id = '?', title = '?', description = '?'";
				$database->query($database->parse($sql, $request->gethtml('information_id'), $key, $value['title'], $value['description']));
			}

			$cache->delete('information');
			
			$session->set('message', $language->get('text_message'));

			$response->redirect($url->ssl('information'));
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

		$language->load('controller/information.php');

		$template->set('title', $language->get('heading_title'));

		if (($request->gethtml('information_id')) && ($this->validateDelete())) {
			$database->query("delete from information where information_id = '" . (int)$request->gethtml('information_id') . "'");
			$database->query("delete from information_description where information_id = '" . (int)$request->gethtml('information_id') . "'");

			$cache->delete('information');
			
			$session->set('message', $language->get('text_message'));

			$response->redirect($url->ssl('information'));
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
			'name'  => $language->get('column_title'),
			'sort'  => 'id.title',
			'align' => 'left'
		);

		$cols[] = array(
			'name'  => $language->get('column_sort_order'),
			'sort'  => 'i.sort_order',
			'align' => 'right'
		);

    	$cols[] = array(
      		'name'  => $language->get('column_action'),
      		'align' => 'right'
    	);
		
		if (!$session->get('information.search')) {
			$sql = "select i.information_id, id.title, i.sort_order from information i left join information_description id on i.information_id = id.information_id where id.language_id = '" . (int)$language->getId() . "'";
		} else {
			$sql = "select i.information_id, id.title, i.sort_order from information i left join information_description id on i.information_id = id.information_id where id.language_id = '" . (int)$language->getId() . "' and id.title like '?'";
		}

		$sort = array(
			'id.title',
			'i.sort_order'
		);

		if (in_array($session->get('information.sort'), $sort)) {
			$sql .= " order by " . $session->get('information.sort') . " " . (($session->get('information.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by id.title asc";
		}

		$results = $database->getRows($database->splitQuery($database->parse($sql, '%' . $session->get('information.search') . '%'), $session->get('information.page'), $config->get('config_max_rows')));

		$rows = array();

		foreach ($results as $result) {
			$cell = array();

			$cell[] = array(
				'value' => $result['title'],
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
				'href' => $url->ssl('information', 'update', array('information_id' => $result['information_id']))
      		);
						
			$action[] = array(
        		'icon' => 'delete.png',
				'text' => $language->get('button_delete'),
				'href' => $url->ssl('information', 'delete', array('information_id' => $result['information_id']))
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
		 
		$view->set('action', $url->ssl('information', 'page'));

		$view->set('search', $session->get('information.search'));
		$view->set('sort', $session->get('information.sort'));
		$view->set('order', $session->get('information.order'));
		$view->set('page', $session->get('information.page'));

		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('list', $url->ssl('information'));

		$view->set('insert', $url->ssl('information', 'insert'));

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
		$view->set('entry_sort_order', $language->get('entry_sort_order'));

		$view->set('button_list', $language->get('button_list'));
		$view->set('button_insert', $language->get('button_insert'));
		$view->set('button_update', $language->get('button_update'));
		$view->set('button_delete', $language->get('button_delete'));
		$view->set('button_save', $language->get('button_save'));
		$view->set('button_cancel', $language->get('button_cancel'));

		$view->set('tab_general', $language->get('tab_general'));
		$view->set('tab_data', $language->get('tab_data'));

		$view->set('error', @$this->error['message']);
		$view->set('error_title', @$this->error['title']);
		$view->set('error_description', @$this->error['description']);

		$view->set('action', $url->ssl('information', $request->gethtml('action'), array('information_id' => $request->gethtml('information_id'))));

		$view->set('list', $url->ssl('information'));

		$view->set('insert', $url->ssl('information', 'insert'));

		if ($request->gethtml('information_id')) {
			$view->set('update', $url->ssl('information', 'update', array('information_id' => $request->gethtml('information_id'))));
			$view->set('delete', $url->ssl('information', 'delete', array('information_id' => $request->gethtml('information_id'))));
		}

		$view->set('cancel', $url->ssl('information'));
				
		$information_data = array();

		$results = $database->cache('language', "select * from language order by sort_order");
		
		foreach ($results as $result) {
			if (($request->gethtml('information_id')) && (!$request->isPost())) {		
				$information_description_info = $database->getRow("select title, description from information_description where information_id = '" . (int)$request->gethtml('information_id') . "' and language_id = '" . (int)$result['language_id'] . "'");				
			} else {
				$information_description_info = $request->gethtml('language', 'post');
			}
			
			$information_data[] = array(
				'language_id' => $result['language_id'],
				'language'    => $result['name'],
				'title'       => (isset($information_description_info[$result['language_id']]) ? $information_description_info[$result['language_id']]['title'] : @$information_description_info['title']),
	    		'description' => (isset($information_description_info[$result['language_id']]) ? $information_description_info[$result['language_id']]['description'] : @$information_description_info['description'])
			);
		}

		$view->set('informations', $information_data);

		if (($request->gethtml('information_id')) && (!$request->isPost())) {
			$information_info = $database->getRow("select distinct * from information where information_id = '" . (int)$request->gethtml('information_id') . "'");
		}

		if ($request->has('sort_order', 'post')) {
			$view->set('sort_order', $request->gethtml('sort_order', 'post'));
		} else {
			$view->set('sort_order', @$information_info['sort_order']);
		}

		return $view->fetch('content/information.tpl');
	}

	function validateForm() {
		$request  =& $this->locator->get('request');
		$user     =& $this->locator->get('user');
		$language =& $this->locator->get('language');
        $validate =& $this->locator->get('validate');

		if (!$user->hasPermission('modify', 'information')) {
			$this->error['message'] = $language->get('error_permission');
		}

		foreach ($request->gethtml('language', 'post') as $value) {
            if (!$validate->strlen($value['title'],1,32)) {
				$this->error['title'] = $language->get('error_title');
			}
            if (!$validate->strlen($value['description'],1)) {
                $this->error['description'] = $language->get('error_description');
            }
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

		if (!$user->hasPermission('modify', 'information')) {
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
			$session->set('information.search', $request->gethtml('search', 'post'));
		}

		if (($request->has('page', 'post')) || ($request->has('search', 'post'))) {
			$session->set('information.page', $request->gethtml('page', 'post'));
		}

		if ($request->has('sort', 'post')) {
			$session->set('information.order', (($session->get('information.sort') == $request->gethtml('sort', 'post')) && ($session->get('information.order') == 'asc')) ? 'desc' : 'asc');
		}

		if ($request->has('sort', 'post')) {
			$session->set('information.sort', $request->gethtml('sort', 'post'));
		}

		$response->redirect($url->ssl('information'));
	}
}
?>
