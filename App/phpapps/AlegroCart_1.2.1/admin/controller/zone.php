<?php
class ControllerZone extends Controller {
	var $error = array();

	function index() {
		$response =& $this->locator->get('response');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template'); 
		$module   =& $this->locator->get('module');

		$language->load('controller/zone.php');

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

		$language->load('controller/zone.php');

		$template->set('title', $language->get('heading_title'));
		
		// Add Zone Status
		if ($request->isPost() && $request->has('name', 'post') && $this->validateForm()) {
			$sql = "insert into zone set name = '?', zone_status = '?',code = '?', country_id = '?'";
			$database->query($database->parse($sql, $request->gethtml('name', 'post'), $request->gethtml('zone_status','post'), $request->gethtml('code', 'post'), $request->gethtml('country_id', 'post')));

			$cache->delete('zone');

			$session->set('message', $language->get('text_message'));
			
			$response->redirect($url->ssl('zone'));
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

		$language->load('controller/zone.php');

		$template->set('title', $language->get('heading_title'));

		// Add Zone Status
		if ($request->isPost() && $request->has('name', 'post') && $this->validateForm()) {
			$sql = "update zone set name = '?', zone_status = '?', code = '?', country_id = '?' where zone_id = '?'";
			$database->query($database->parse($sql, $request->gethtml('name', 'post'), $request->gethtml('zone_status','post'), $request->gethtml('code', 'post'), $request->gethtml('country_id', 'post'), $request->gethtml('zone_id')));

			$cache->delete('zone');
			
			$session->set('message', $language->get('text_message'));
			
			$response->redirect($url->ssl('zone'));
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
 
		$language->load('controller/zone.php');

		$template->set('title', $language->get('heading_title'));

		if (($request->gethtml('zone_id')) && ($this->validateDelete())) {
			$database->query("delete from zone where zone_id = '" . (int)$request->gethtml('zone_id') . "'");

			$cache->delete('zone');
			
			$session->set('message', $language->get('text_message'));
			
			$response->redirect($url->ssl('zone'));
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
			'sort'  => 'z.name',
			'align' => 'left'
		);

		$cols[] = array(    // Zone Status
			'name'  => $language->get('column_zone_status'),
			'sort'  => 'zone_status',
			'align' => 'left'
		);		
		
		$cols[] = array(
			'name'  => $language->get('column_code'),
			'sort'  => 'z.code',
			'align' => 'left'
		);

		$cols[] = array(
			'name'  => $language->get('column_country'),
			'sort'  => 'c.name',
			'align' => 'left'
		);

    	$cols[] = array(
      		'name'  => $language->get('column_action'),
      		'align' => 'right'
    	);
		
		if (!$session->get('zone.search')) {  // add Zone Status
			$sql = "select z.zone_id, z.name, z.zone_status, z.code, c.name as country from zone z left join country c on (z.country_id = c.country_id)";
		} else {
			$sql = "select z.zone_id, z.name, z.zone_status, z.code, c.name as country from zone z left join country c on (z.country_id = c.country_id) where z.name like '?'";
		}

		$sort = array(
			'z.name',
			'z.zone.status',  // New Zone Status
			'z.code',
			'c.name'
		);

		if (in_array($session->get('zone.sort'), $sort)) {
			$sql .= " order by " . $session->get('zone.sort') . " " . (($session->get('zone.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by z.name asc";
		}

		$results = $database->getRows($database->splitQuery($database->parse($sql, '%' . $session->get('zone.search') . '%'), $session->get('zone.page'), $config->get('config_max_rows')));

		$rows = array();

		foreach ($results as $result) {
			$cell = array();

			$cell[] = array(
				'value'   => $result['name'],
				'align'   => 'left',
				'default' => ($result['zone_id'] == $config->get('config_zone_id'))
			);

			$cell[] = array(  // New Zone Status
        		'icon'  => ($result['zone_status'] ? 'enabled.png' : 'disabled.png'),
        		'align' => 'left'
      		);			
			
			$cell[] = array(
				'value' => $result['code'],
				'align' => 'left'
			);

			$cell[] = array(
				'value' => $result['country'],
				'align' => 'left'
			);

			$action = array();
      		
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $language->get('button_update'),
				'href' => $url->ssl('zone', 'update', array('zone_id' => $result['zone_id']))
      		);
						
/*			$action[] = array(
        		'icon' => 'delete.png',
				'text' => $language->get('button_delete'),
				'href' => $url->ssl('zone', 'delete', array('zone_id' => $result['zone_id']))
      		);   // Should not be required
*/
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
		
		$view->set('action', $url->ssl('zone', 'page'));

		$view->set('search', $session->get('zone.search'));
		$view->set('sort', $session->get('zone.sort'));
		$view->set('order', $session->get('zone.order'));
		$view->set('page', $session->get('zone.page'));
 
		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('list', $url->ssl('zone'));

		$view->set('insert', $url->ssl('zone', 'insert'));

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
		$view->set('entry_zone_status', $language->get('entry_zone_status')); // New Zone Status
		$view->set('entry_code', $language->get('entry_code'));
		$view->set('entry_country', $language->get('entry_country'));

		$view->set('button_list', $language->get('button_list'));
		$view->set('button_insert', $language->get('button_insert'));
		$view->set('button_update', $language->get('button_update'));
		$view->set('button_delete', $language->get('button_delete'));
		$view->set('button_save', $language->get('button_save'));
		$view->set('button_cancel', $language->get('button_cancel'));

		$view->set('error', @$this->error['message']);
		$view->set('error_name', @$this->error['name']);

		$view->set('tab_general', $language->get('tab_general'));

		$view->set('action', $url->ssl('zone', $request->gethtml('action'), array('zone_id' => $request->gethtml('zone_id'))));
 
		$view->set('list', $url->ssl('zone'));

		$view->set('insert', $url->ssl('zone', 'insert'));

		if ($request->gethtml('zone_id')) {
			$view->set('update', $url->ssl('zone', 'update', array('zone_id' => $request->gethtml('zone_id'))));
			$view->set('delete', $url->ssl('zone', 'delete', array('zone_id' => $request->gethtml('zone_id'))));
		}

		$view->set('cancel', $url->ssl('zone'));

		if (($request->gethtml('zone_id')) && (!$request->isPost())) {
			$zone_info = $database->getRow("select distinct * from zone where zone_id = '" . (int)$request->gethtml('zone_id') . "'");
		}

		if ($request->has('name', 'post')) {
			$view->set('name', $request->gethtml('name', 'post'));
		} else {
			$view->set('name', @$zone_info['name']);
		}

		if ($request->has('zone_status', 'post')) {    // New Zone Status
      		$view->set('zone_status', $request->gethtml('zone_status', 'post'));
    	} else {
      		$view->set('zone_status', @$zone_info['zone_status']);
    	}				
		
		if ($request->has('code', 'post')) {
			$view->set('code', $request->gethtml('code', 'post'));
		} else {
			$view->set('code', @$zone_info['code']);
		}

		if ($request->has('country_id', 'post')) {
			$view->set('country_id', $request->gethtml('country_id', 'post'));
		} else {
			$view->set('country_id', @$zone_info['country_id']);
		}

		$view->set('countries', $database->cache('country', "select country_id, name,country_status from country where country_status = '1' order by name"));

		return $view->fetch('content/zone.tpl');
	}

	function validateForm() {
		$request  =& $this->locator->get('request');
		$user     =& $this->locator->get('user');
		$language =& $this->locator->get('language');
        $validate =& $this->locator->get('validate');

		if (!$user->hasPermission('modify', 'zone')) {
			$this->error['message'] = $language->get('error_permission');
		}

        if (!$validate->strlen($request->gethtml('name', 'post'),1,32)) {
			$this->error['name'] = $language->get('error_name');
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
		$user     =& $this->locator->get('user');
		$language =& $this->locator->get('language');

		if (!$user->hasPermission('modify', 'zone')) {
			$this->error['message'] = $language->get('error_permission');
		}

		if ($config->get('config_zone_id') == $request->gethtml('zone_id')) {
			$this->error['message'] = $language->get('error_default');
		}

		$address_info = $database->getRow("select count(*) as total from address where zone_id = '" . (int)$request->gethtml('zone_id') . "'");

		if ($address_info['total']) {
			$this->error['message'] = $language->get('error_address', $address_info['total']);
		}

		$zone_to_geo_zone_info = $database->getRow("select count(*) as total from zone_to_geo_zone where zone_id = '" . (int)$request->gethtml('zone_id') . "'");

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
			$session->set('zone.search', $request->gethtml('search', 'post'));
		}

		if (($request->has('page', 'post')) || ($request->has('search', 'post'))) {
			$session->set('zone.page', $request->gethtml('page', 'post'));
		}

		if ($request->has('sort', 'post')) {
			$session->set('zone.order', (($session->get('zone.sort') == $request->gethtml('sort', 'post')) && ($session->get('zone.order') == 'asc')) ? 'desc' : 'asc');
		}

		if ($request->has('sort', 'post')) {
			$session->set('zone.sort', $request->gethtml('sort', 'post'));
		}

		$response->redirect($url->ssl('zone'));
	}	
}
?>