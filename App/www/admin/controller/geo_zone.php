<?php
class ControllerGeoZone extends Controller {
	var $error = array();

	function index() {
		$response =& $this->locator->get('response');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$module   =& $this->locator->get('module');

		$language->load('controller/geo_zone.php');

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

		$language->load('controller/geo_zone.php');

		$template->set('title', $language->get('heading_title'));

		if ($request->isPost() && $request->has('name', 'post') && $this->validateForm()) {
			$sql = "insert into geo_zone set name = '?', description = '?', date_added = now()";
			$database->query($database->parse($sql, $request->gethtml('name', 'post'), $request->gethtml('description', 'post')));

			$cache->delete('geo_zone');
			
			$session->set('message', $language->get('text_message'));

			$response->redirect($url->ssl('geo_zone'));
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

		$language->load('controller/geo_zone.php');

		$template->set('title', $language->get('heading_title'));

		if ($request->isPost() && $request->has('name', 'post') && $this->validateForm()) {
			$sql = "update geo_zone set name = '?', description = '?', date_modified = now() where geo_zone_id = '?'";
			$database->query($database->parse($sql, $request->gethtml('name', 'post'), $request->gethtml('description', 'post'), $request->gethtml('geo_zone_id')));

			$cache->delete('geo_zone');
			
			$session->set('message', $language->get('text_message'));

			$response->redirect($url->ssl('geo_zone'));
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

		$language->load('controller/geo_zone.php');

		$template->set('title', $language->get('heading_title'));

		if (($request->gethtml('geo_zone_id')) && ($this->validateDelete())) {
			$database->query("delete from geo_zone where geo_zone_id = '" . (int)$request->gethtml('geo_zone_id') . "'");
			$database->query("delete from zone_to_geo_zone where zone_id = '" . (int)$request->gethtml('geo_zone_id') . "'");

			$cache->delete('geo_zone');
			
			$session->set('message', $language->get('text_message'));
 
			$response->redirect($url->ssl('geo_zone'));
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
			'name'  => $language->get('column_name'),
			'sort'  => 'name',
			'align' => 'left'
		);

		$cols[] = array(
			'name'  => $language->get('column_description'),
			'sort'  => 'description',
			'align' => 'left'
		);

    	$cols[] = array(
      		'name'  => $language->get('column_action'),
      		'align' => 'right'
    	);
				
		if (!$session->get('geo_zone.search')) {
			$sql = "select geo_zone_id, name, description from geo_zone";
		} else {
			$sql = "select geo_zone_id, name, description from geo_zone where name like '?'";
		}

		$sort = array(
			'name',
			'description'
		);

		if (in_array($session->get('geo_zone.sort'), $sort)) {
			$sql .= " order by " . $session->get('geo_zone.sort') . " " . (($session->get('geo_zone.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by name asc";
		}

		$results = $database->getRows($database->splitQuery($database->parse($sql, '%' . $session->get('geo_zone.search') . '%'), $session->get('geo_zone.page'), $config->get('config_max_rows')));

		$rows = array();

		foreach ($results as $result) {
			$cell = array();

      		$cell[] = array(
        		'icon'  => 'folder.png',
        		'align' => 'center',
				'path'  => $url->ssl('zone_to_geo_zone', FALSE, array('geo_zone_id' => $result['geo_zone_id']))
		  	);
			
			$cell[] = array(
				'value' => $result['name'],
				'align' => 'left'
			);

			$cell[] = array(
				'value' => $result['description'],
				'align' => 'left'
			);

			$action = array();
      		
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $language->get('button_update'),
				'href' => $url->ssl('geo_zone', 'update', array('geo_zone_id' => $result['geo_zone_id']))
      		);
						
			$action[] = array(
        		'icon' => 'delete.png',
				'text' => $language->get('button_delete'),
				'href' => $url->ssl('geo_zone', 'delete', array('geo_zone_id' => $result['geo_zone_id']))
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
		 
		$view->set('action', $url->ssl('geo_zone', 'page'));

		$view->set('search', $session->get('geo_zone.search'));
		$view->set('sort', $session->get('geo_zone.sort'));
		$view->set('order', $session->get('geo_zone.order'));
		$view->set('page', $session->get('geo_zone.page'));

		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('list', $url->ssl('geo_zone'));

		$view->set('insert', $url->ssl('geo_zone', 'insert'));

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
		$view->set('entry_description', $language->get('entry_description'));

		$view->set('button_list', $language->get('button_list'));
		$view->set('button_insert', $language->get('button_insert'));
		$view->set('button_update', $language->get('button_update'));
		$view->set('button_delete', $language->get('button_delete'));
		$view->set('button_save', $language->get('button_save'));
		$view->set('button_cancel', $language->get('button_cancel'));

		$view->set('tab_general', $language->get('tab_general'));

		$view->set('error', @$this->error['message']);
		$view->set('error_name', @$this->error['name']);
		$view->set('error_description', @$this->error['description']);

		$view->set('action', $url->ssl('geo_zone', $request->gethtml('action'), array('geo_zone_id' => $request->gethtml('geo_zone_id'))));

		$view->set('list', $url->ssl('geo_zone'));

		$view->set('insert', $url->ssl('geo_zone', 'insert'));

		if ($request->gethtml('geo_zone_id')) {
			$view->set('update', $url->ssl('geo_zone', 'update', array('geo_zone_id' => $request->gethtml('geo_zone_id'))));
			$view->set('delete', $url->ssl('geo_zone', 'delete', array('geo_zone_id' => $request->gethtml('geo_zone_id'))));
		}

		$view->set('cancel', $url->ssl('geo_zone'));

		if (($request->gethtml('geo_zone_id')) && (!$request->isPost())) {
			$geo_zone_info = $database->getRow("select distinct * from geo_zone where geo_zone_id = '" . (int)$request->gethtml('geo_zone_id') . "'");
		}

		if ($request->has('name', 'post')) {
			$view->set('name', $request->gethtml('name', 'post'));
		} else {
			$view->set('name', @$geo_zone_info['name']);
		}

		if ($request->has('description', 'post')) {
			$view->set('description', $request->gethtml('description', 'post'));
		} else {
			$view->set('description', @$geo_zone_info['description']);
		}

		return $view->fetch('content/geo_zone.tpl');
	}
	
	function validateForm() {
		$request  =& $this->locator->get('request');
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');
        $validate =& $this->locator->get('validate');
        
		if (!$user->hasPermission('modify', 'geo_zone')) {
			$this->error['message'] = $language->get('error_permission');
		}
        
        if (!$validate->strlen($request->gethtml('name', 'post'),1,32)) {
			$this->error['name'] = $language->get('error_name');
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
		$config   =& $this->locator->get('config');
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');

		if (!$user->hasPermission('modify', 'geo_zone')) {
			$this->error['message'] = $language->get('error_permission');
		}

		$tax_rate_info = $database->getRow("select count(*) as total from tax_rate where geo_zone_id = '" . (int)$request->gethtml('geo_zone_id') . "'");

		if ($tax_rate_info['total']) {
			$this->error['message'] = $language->get('error_tax_rate', $tax_rate_info['total']);
		}

		$zone_to_geo_zone_info = $database->getRow("select count(*) as total from zone_to_geo_zone where geo_zone_id = '" . (int)$request->gethtml('geo_zone_id') . "'");

		if ($zone_to_geo_zone_info['total']) {
			$this->error['message'] = $language->get('error_zone_to_geo_zone', $zone_to_geo_zone_info['total']);
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
			$session->set('geo_zone.search', $request->gethtml('search', 'post'));
		}

		if (($request->has('page', 'post')) || ($request->has('search', 'post'))) {
			$session->set('geo_zone.page', $request->gethtml('page', 'post'));
		}

		if ($request->has('sort', 'post')) {
			$session->set('geo_zone.order', (($session->get('geo_zone.sort') == $request->gethtml('sort', 'post')) && ($session->get('geo_zone.order') == 'asc')) ? 'desc' : 'asc');
		}

		if ($request->has('sort', 'post')) {
			$session->set('geo_zone.sort', $request->gethtml('sort', 'post'));
		}

		$response->redirect($url->ssl('geo_zone'));
	}	
}
?>