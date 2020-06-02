<?php
class ControllerTaxRate extends Controller {
	var $error = array();

	function index() {
		$response =& $this->locator->get('response');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template'); 
		$module   =& $this->locator->get('module');
 
		$language->load('controller/tax_rate.php');

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

		$language->load('controller/tax_rate.php');

		$template->set('title', $language->get('heading_title'));

		if ($request->isPost() && $request->has('geo_zone_id', 'post') && $this->validateForm()) {
			$sql = "insert into tax_rate set geo_zone_id = '?', tax_class_id = '?', priority = '?', rate = '?', description = '?', date_added = now()";
			$database->query($database->parse($sql, $request->gethtml('geo_zone_id', 'post'), $request->gethtml('tax_class_id'), $request->gethtml('priority', 'post'), $request->gethtml('rate', 'post'), $request->gethtml('description', 'post')));
			
			$session->set('message', $language->get('text_message'));

			$response->redirect($url->ssl('tax_rate', FALSE, array('tax_class_id' => $request->gethtml('tax_class_id'))));
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

		$language->load('controller/tax_rate.php');

		$template->set('title', $language->get('heading_title'));

		if ($request->isPost() && $request->has('geo_zone_id', 'post') && $this->validateForm()) {
			$sql = "update tax_rate set geo_zone_id = '?', tax_class_id = '?', priority = '?', rate = '?', description = '?', date_modified = now() where tax_rate_id = '?'";
			$database->query($database->parse($sql, $request->gethtml('geo_zone_id', 'post'), $request->gethtml('tax_class_id'), $request->gethtml('priority', 'post'), $request->gethtml('rate', 'post'), $request->gethtml('description', 'post'), $request->gethtml('tax_rate_id')));

			$session->set('message', $language->get('text_message'));
			
			$response->redirect($url->ssl('tax_rate', FALSE, array('tax_class_id' => $request->gethtml('tax_class_id'))));
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
 
		$language->load('controller/tax_rate.php');

		$template->set('title', $language->get('heading_title'));

		if (($request->gethtml('tax_rate_id')) && ($this->validateDelete())) {
			$database->query("delete from tax_rate where tax_rate_id = '" . (int)$request->gethtml('tax_rate_id') . "' and tax_class_id = '" . (int)$request->gethtml('tax_class_id') . "'");

			$session->set('message', $language->get('text_message'));
			
			$response->redirect($url->ssl('tax_rate', FALSE, array('tax_class_id' => $request->gethtml('tax_class_id'))));
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
			'name'  => $language->get('column_priority'),
			'sort'  => 'tr.priority',
			'align' => 'left'
		);

		$cols[] = array(
			'name'  => $language->get('column_geo_zone'),
			'sort'  => 'gz.name',
			'align' => 'left'
		);

		$cols[] = array(
			'name'  => $language->get('column_rate'),
			'sort'  => 'tr.rate',
			'align' => 'left'
		);

    	$cols[] = array(
      		'name'  => $language->get('column_action'),
      		'align' => 'right'
    	);
		
		if (!$session->get('tax_rate.search')) {
			$sql = "select tr.tax_rate_id, tr.priority, gz.name, tr.rate from tax_class tc, tax_rate tr left join geo_zone gz on (tr.geo_zone_id = gz.geo_zone_id) where tr.tax_class_id = tc.tax_class_id and tc.tax_class_id = '" . (int)$request->gethtml('tax_class_id') . "'";
		} else {
			$sql = "select tr.tax_rate_id, tr.priority, gz.name, tr.rate from tax_class tc, tax_rate tr left join geo_zone gz on (tr.geo_zone_id = gz.geo_zone_id) where tr.tax_class_id = tc.tax_class_id and tc.tax_class_id = '" . (int)$request->gethtml('tax_class_id') . "' and gz.name like '?'";
		}

		$sort = array(
			'tr.priority',
			'gz.name',
			'tr.rate'
		);

		if (in_array($session->get('tax_rate.sort'), $sort)) {
			$sql .= " order by " . $session->get('tax_rate.sort') . " " . (($session->get('tax_rate.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by tr.priority, tc.title asc";
		}

		$results = $database->getRows($database->splitQuery($database->parse($sql, '%' . $session->get('tax_rate.search') . '%'), $session->get('tax_rate.' . $request->gethtml('tax_class_id') . '.page'), $config->get('config_max_rows')));

		$rows = array();

		foreach ($results as $result) {
			$cell = array();

			$cell[] = array(
				'value' => $result['priority'],
				'align' => 'left'
			);

			$cell[] = array(
				'value' => $result['name'],
				'align' => 'left'
			);

			$cell[] = array(
				'value' => round($result['rate'], 2) . '%',
				'align' => 'left'
			);

			$query = array(
				'tax_rate_id'  => $result['tax_rate_id'],
				'tax_class_id' => $request->gethtml('tax_class_id')
			);
			
			$action = array();
      		
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $language->get('button_update'),
				'href' => $url->ssl('tax_rate', 'update', $query)
      		);
						
			$action[] = array(
        		'icon' => 'delete.png',
				'text' => $language->get('button_delete'),
				'href' => $url->ssl('tax_rate', 'delete', $query)
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

		$view->set('text_previous', $language->get('text_previous'));
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
		
		$view->set('action', $url->ssl('tax_rate', 'page', array('tax_class_id' => $request->gethtml('tax_class_id'))));
 
		$view->set('previous', $url->ssl('tax_class', FALSE, array('tax_class_id' => $request->gethtml('tax_class_id'))));

		$view->set('search', $session->get('tax_rate.search')); 
		$view->set('sort', $session->get('tax_rate.sort'));
		$view->set('order', $session->get('tax_rate.order'));
		$view->set('page', $session->get('tax_rate.' . $request->gethtml('tax_class_id') . '.page'));
		
		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('list', $url->ssl('tax_rate', FALSE, array('tax_class_id' => $request->gethtml('tax_class_id'))));

		$view->set('insert', $url->ssl('tax_rate', 'insert', array('tax_class_id' => $request->gethtml('tax_class_id'))));

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

		$view->set('entry_priority', $language->get('entry_priority'));
		$view->set('entry_geo_zone', $language->get('entry_geo_zone'));
		$view->set('entry_rate', $language->get('entry_rate'));
		$view->set('entry_description', $language->get('entry_description'));

		$view->set('button_list', $language->get('button_list'));
		$view->set('button_insert', $language->get('button_insert'));
		$view->set('button_update', $language->get('button_update'));
		$view->set('button_delete', $language->get('button_delete'));
		$view->set('button_save', $language->get('button_save'));
		$view->set('button_cancel', $language->get('button_cancel'));

		$view->set('tab_general', $language->get('tab_general'));

		$view->set('error', @$this->error['message']);
		$view->set('error_priority', @$this->error['priority']);
		$view->set('error_rate', @$this->error['rate']);
		$view->set('error_description', @$this->error['description']);

		$query = array(
			'tax_rate_id'  => $request->gethtml('tax_rate_id'),
			'tax_class_id' => $request->gethtml('tax_class_id')
		);

		$view->set('action', $url->ssl('tax_rate', $request->gethtml('action'), $query));

		$view->set('list', $url->ssl('tax_rate', FALSE, array('tax_class_id' => $request->gethtml('tax_class_id'))));

		$view->set('insert', $url->ssl('tax_rate', 'insert', array('tax_class_id' => $request->gethtml('tax_class_id'))));

		if ($request->gethtml('tax_rate_id')) {
			$query = array(
				'tax_rate_id'  => $request->gethtml('tax_rate_id'),
				'tax_class_id' => $request->gethtml('tax_class_id')
			);

			$view->set('update', $url->ssl('tax_rate', 'update', $query));
			$view->set('delete', $url->ssl('tax_rate', 'delete', $query));
		}

		$view->set('cancel', $url->ssl('tax_rate', FALSE, array('tax_class_id' => $request->gethtml('tax_class_id'))));

		if (($request->gethtml('tax_rate_id')) && (!$request->isPost())) {
			$tax_rate_info = $database->getRow("select distinct * from tax_rate where tax_rate_id = '" . (int)$request->gethtml('tax_rate_id') . "'");
		}

		if ($request->has('geo_zone_id', 'post')) {
			$view->set('geo_zone_id', $request->gethtml('geo_zone_id', 'post'));
		} else {
			$view->set('geo_zone_id', @$tax_rate_info['geo_zone_id']);
		}

		$view->set('geo_zones', $database->getRows("select geo_zone_id, name from geo_zone order by name"));

		if ($request->has('priority', 'post')) {
			$view->set('priority', $request->gethtml('priority', 'post'));
		} else {
			$view->set('priority', @$tax_rate_info['priority']);
		}

		if ($request->has('rate', 'post')) {
			$view->set('rate', $request->gethtml('rate', 'post'));
		} else {
			$view->set('rate', @$tax_rate_info['rate']);
		}

		if ($request->has('description', 'post')) {
			$view->set('description', $request->gethtml('description', 'post'));
		} else {
			$view->set('description', @$tax_rate_info['description']);
		}

		return $view->fetch('content/tax_rate.tpl');
	}

	function validateForm() {
		$request  =& $this->locator->get('request');
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');
        $validate =& $this->locator->get('validate');

		if (!$user->hasPermission('modify', 'tax_rate')) {
			$this->error['message'] = $language->get('error_permission');
		}

		if (!$request->gethtml('priority', 'post')) {
			$this->error['priority'] = $language->get('error_priority');
		}

		if (!$request->gethtml('rate', 'post')) {
			$this->error['rate'] = $language->get('error_rate');
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
		$user     =& $this->locator->get('user');
		$language =& $this->locator->get('language');

		if (!$user->hasPermission('modify', 'tax_rate')) {
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
			$session->set('tax_rate.search', $request->gethtml('search', 'post'));
		}

		if (($request->has('page', 'post')) || ($request->has('search', 'post'))) {
			$session->set('tax_rate.' . $request->gethtml('tax_class_id') . '.page', $request->gethtml('page', 'post'));			
		}

		if ($request->has('sort', 'post')) {
			$session->set('tax_rate.order', (($session->get('tax_rate.sort') == $request->gethtml('sort', 'post')) && ($session->get('tax_rate.order') == 'asc')) ? 'desc' : 'asc');
		}

		if ($request->has('sort', 'post')) {
			$session->set('tax_rate.sort', $request->gethtml('sort', 'post'));
		}

		$response->redirect($url->ssl('tax_rate', FALSE, array('tax_class_id' => $request->gethtml('tax_class_id'))));
	}		
}
?>