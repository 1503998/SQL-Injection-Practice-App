<?php  // AlegroCart
class ControllerCountry extends Controller {
	var $error = array();

	function index() {
		$response =& $this->locator->get('response');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$module   =& $this->locator->get('module');

		$language->load('controller/country.php');

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

		$language->load('controller/country.php');

		$template->set('title', $language->get('heading_title'));

		// Add Country Status
		if ($request->isPost() && $request->has('name', 'post') && $this->validateForm()) {
			$sql = "insert into country set name = '?', country_status = '?', iso_code_2 = '?', iso_code_3 = '?', address_format = '?'";
			$database->query($database->parse($sql, $request->gethtml('name', 'post'), $request->gethtml('country_status', 'post'), $request->gethtml('iso_code_2', 'post'), $request->gethtml('iso_code_3', 'post'), $request->gethtml('address_format', 'post')));
			
			$cache->delete('country');
			
			$session->set('message', $language->get('text_message'));

			$response->redirect($url->ssl('country'));
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

		$language->load('controller/country.php');

		$template->set('title', $language->get('heading_title'));

		// Add Country Status
		if ($request->isPost() && $request->has('name', 'post') && $this->validateForm()) {
			$sql = "update country set name = '?', country_status = '?', iso_code_2 = '?', iso_code_3 = '?', address_format = '?' where country_id = '?'";
			$database->query($database->parse($sql, $request->gethtml('name', 'post'), $request->gethtml('country_status', 'post'), $request->gethtml('iso_code_2', 'post'), $request->gethtml('iso_code_3', 'post'), $request->gethtml('address_format', 'post'), $request->gethtml('country_id')));

			$cache->delete('country');
			
			$session->set('message', $language->get('text_message'));

			$response->redirect($url->ssl('country'));
		}

		$template->set('content', $this->getForm());

		$template->set($module->fetch());

		$response->set($template->fetch('layout.tpl'));
	}
	
	function enableDisable(){
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$database =& $this->locator->get('database');
		$url      =& $this->locator->get('url');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$session  =& $this->locator->get('session');
		$module   =& $this->locator->get('module');
		$cache    =& $this->locator->get('cache');

		$language->load('controller/country.php');

		$template->set('title', $language->get('heading_title'));
		
		if($this->validateEnable()){
			if ($database->getRows("select country_status from country where country_status = '1'")) {
				$status = 0;
			} else {
				$status = 1;
			}
			$database->query("update country set country_status = '" . $status . "'");
			$cache->delete('country');	
			$session->set('message', $language->get('text_message'));
			$response->redirect($url->ssl('country'));
		} else {
			$session->set('message', @$this->error['message']);
		}
		$response->redirect($url->ssl('country'));
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

		$language->load('controller/country.php');

		$template->set('title', $language->get('heading_title'));

		if (($request->gethtml('country_id')) && ($this->validateDelete())) {
			$database->query("delete from country where country_id = '" . (int)$request->gethtml('country_id') . "'");

			$cache->delete('country');
			
			$session->set('message', $language->get('text_message'));

			$response->redirect($url->ssl('country'));
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
		
		$cols[] = array(    // Country Status
			'name'  => $language->get('column_country_status'),
			'sort'  => 'country_status',
			'align' => 'left'
		);

		$cols[] = array(
			'name'  => $language->get('column_iso_code_2'),
			'sort'  => 'iso_code_2',
			'align' => 'left'
		);

		$cols[] = array(
			'name'  => $language->get('column_iso_code_3'),
			'sort'  => 'iso_code_3',
			'align' => 'left'
		);

    	$cols[] = array(
      		'name'  => $language->get('column_action'),
      		'align' => 'right'
    	);
				
		if (!$session->get('country.search')) {  // add Country Status
			$sql = "select country_id, name, country_status, iso_code_2, iso_code_3 from country";
		} else {
			$sql = "select country_id, name, country_status, iso_code_2, iso_code_3 from country where name like '?' or iso_code_2 like '?' or iso_code_3 like '?'";
		}

		$sort = array(
			'name',
			'country_status',  // New Country Status
			'iso_code_2',
			'iso_code_3'
		);

		if (in_array($session->get('country.sort'), $sort)) {
			$sql .= " order by " . $session->get('country.sort') . " " . (($session->get('country.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by name asc";
		}

		$results = $database->getRows($database->splitQuery($database->parse($sql, '%' . $session->get('country.search') . '%', $session->get('country.search'), $session->get('country.search')), $session->get('country.page'), $config->get('config_max_rows')));

		$rows = array();

		foreach ($results as $result) {
			$cell = array();

			$cell[] = array(
				'value'   => $result['name'],
				'align'   => 'left',
				'default' => ($result['country_id'] == $config->get('config_country_id'))
			);
			
			$cell[] = array(  // New Country Status
        		'icon'  => ($result['country_status'] ? 'enabled.png' : 'disabled.png'),
        		'align' => 'left'
      		);

			$cell[] = array(
				'value' => $result['iso_code_2'],
				'align' => 'left'
			);

			$cell[] = array(
				'value' => $result['iso_code_3'],
				'align' => 'left'
			);

			$action = array();
      		
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $language->get('button_update'),
				'href' => $url->ssl('country', 'update', array('country_id' => $result['country_id']))
      		);
						
/*			$action[] = array(
        		'icon' => 'delete.png',
				'text' => $language->get('button_delete'),
				'href' => $url->ssl('country', 'delete', array('country_id' => $result['country_id']))
      		); // Should not be required
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
 		$view->set('button_refresh', $language->get('button_enable_disable')); // Button Text
		
		$view->set('error', @$this->error['message']);

		$view->set('message', $session->get('message'));
		
		$session->delete('message');
		
		$view->set('action', $url->ssl('country', 'page'));
		$view->set('action_refresh', $url->ssl('country', 'enableDisable'));   // Enable or Disable all countries

		$view->set('search', $session->get('country.search'));
		$view->set('sort', $session->get('country.sort'));
		$view->set('order', $session->get('country.order'));
		$view->set('page', $session->get('country.page'));

		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('list', $url->ssl('country'));

		$view->set('insert', $url->ssl('country', 'insert'));

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
		$view->set('entry_country_status', $language->get('entry_country_status')); // New Country Status
		$view->set('entry_iso_code_2', $language->get('entry_iso_code_2'));
		$view->set('entry_iso_code_3', $language->get('entry_iso_code_3'));
		$view->set('entry_address_format', $language->get('entry_address_format'));

		$view->set('button_list', $language->get('button_list'));
		$view->set('button_insert', $language->get('button_insert'));
		$view->set('button_update', $language->get('button_update'));
		$view->set('button_delete', $language->get('button_delete'));
		$view->set('button_save', $language->get('button_save'));
		$view->set('button_cancel', $language->get('button_cancel'));

		$view->set('tab_general', $language->get('tab_general'));

		$view->set('error', @$this->error['message']);
		$view->set('error_name', @$this->error['name']);

		$view->set('action', $url->ssl('country', $request->gethtml('action'), array('country_id' => $request->gethtml('country_id'))));

		$view->set('list', $url->ssl('country'));

		$view->set('insert', $url->ssl('country', 'insert'));

		if ($request->gethtml('country_id')) {
			$view->set('update', $url->ssl('country', 'update', array('country_id' => $request->gethtml('country_id'))));
			$view->set('delete', $url->ssl('country', 'delete', array('country_id' => $request->gethtml('country_id'))));
		}

		$view->set('cancel', $url->ssl('country'));

		if (($request->gethtml('country_id')) && (!$request->isPost())) {
			$country_info = $database->getRow("select distinct * from country where country_id = '" . (int)$request->gethtml('country_id') . "'");
		}

		if ($request->has('name', 'post')) {
			$view->set('name', $request->gethtml('name', 'post'));
		} else {
			$view->set('name', @$country_info['name']);
		}
		
		if ($request->has('country_status', 'post')) {    // New Country Status
      		$view->set('country_status', $request->gethtml('country_status', 'post'));
    	} else {
      		$view->set('country_status', @$country_info['country_status']);
    	}		

		if ($request->has('iso_code_2', 'post')) {
			$view->set('iso_code_2', $request->gethtml('iso_code_2', 'post'));
		} else {
			$view->set('iso_code_2', @$country_info['iso_code_2']);
		}

		if ($request->has('iso_code_3', 'post')) {
			$view->set('iso_code_3', $request->gethtml('iso_code_3', 'post'));
		} else {
			$view->set('iso_code_3', @$country_info['iso_code_3']);
		}

		if ($request->has('address_format', 'post')) {
			$view->set('address_format', $request->gethtml('address_format', 'post'));
		} else {
			$view->set('address_format', @$country_info['address_format']);
		}

		return $view->fetch('content/country.tpl');
	}

	function validateForm() {
		$request  =& $this->locator->get('request');
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');
        $validate =& $this->locator->get('validate');

		if (!$user->hasPermission('modify', 'country')) {
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
	function validateEnable() {
		$request  =& $this->locator->get('request');
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');
        $validate =& $this->locator->get('validate');

		if (!$user->hasPermission('modify', 'country')) {
			$this->error['message'] = $language->get('error_permission');
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

		if (!$user->hasPermission('modify', 'country')) {
			$this->error['message'] = $language->get('error_permission');
		}

		if ($config->get('config_country_id') == $request->gethtml('country_id')) {
			$this->error['message'] = $language->get('error_default');
		}

		$address_info = $database->getRow("select count(*) as total from address where country_id = '" . (int)$request->gethtml('country_id') . "'");

		if ($address_info['total']) {
			$this->error['message'] = $language->get('error_address', $address_info['total']);
		}

		$address_info = $database->getRow("select count(*) as total from address where country_id = '" . (int)$request->gethtml('country_id') . "'");

		if ($address_info['total']) {
			$this->error['message'] = $language->get('error_address', $address_info['total']);
		}

		$zone_info = $database->getRow("select count(*) as total from zone where country_id = '" . (int)$request->gethtml('country_id') . "'");

		if ($zone_info['total']) {
			$this->error['message'] = $language->get('error_zone', $zone_info['total']);
		}

		$zone_to_geo_zone_info = $database->getRow("select count(*) as total from zone_to_geo_zone where country_id = '" . (int)$request->gethtml('country_id') . "'");

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
			$session->set('country.search', $request->gethtml('search', 'post'));
		}

		if (($request->has('page', 'post')) || ($request->has('search', 'post'))) {
			$session->set('country.page', $request->gethtml('page', 'post'));
		}

		if ($request->has('sort', 'post')) {
			$session->set('country.order', (($session->get('country.sort') == $request->gethtml('sort', 'post')) && ($session->get('country.order') == 'asc')) ? 'desc' : 'asc');
		}

		if ($request->has('sort', 'post')) {
			$session->set('country.sort', $request->gethtml('sort', 'post'));
		}

		$response->redirect($url->ssl('country'));
	}	
}
?>