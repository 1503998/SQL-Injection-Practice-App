<?php
class ControllerWeightClass extends Controller {
	var $error = array();
 
	function index() {
		$response =& $this->locator->get('response');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template'); 
		$module   =& $this->locator->get('module');
 
		$language->load('controller/weight_class.php');

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

		$language->load('controller/weight_class.php');

		$template->set('title', $language->get('heading_title'));

		if ($request->isPost() && $request->has('language', 'post') && $this->validateForm()) {
			foreach ($request->gethtml('language', 'post') as $key => $value) {
				$sql = "insert into weight_class set weight_class_id = '?', language_id = '?', title = '?', unit = '?'";
				$database->query($database->parse($sql, @$insert_id, $key, $value['title'], $value['unit']));

				$insert_id = $database->getLastId();
			}

			foreach ($request->gethtml('rule', 'post', array()) as $key => $value) {
				$sql = "insert into weight_rule set from_id = '?', to_id = '?', rule = '?'";
				$database->query($database->parse($sql, $insert_id, $key, $value));
			}

			$cache->delete('weight_class');
			
			$session->set('message', $language->get('text_message'));

			$response->redirect($url->ssl('weight_class'));
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

		$language->load('controller/weight_class.php');

		$template->set('title', $language->get('heading_title'));

		if ($request->isPost() && $request->has('language', 'post') && $this->validateForm()) {
			$database->query("delete from weight_class where weight_class_id = '" . (int)$request->gethtml('weight_class_id') . "'");

			foreach ($request->gethtml('language', 'post') as $key => $value) {
				$sql = "insert into weight_class set weight_class_id = '?', language_id = '?', title = '?', unit = '?'";
				$database->query($database->parse($sql, $request->gethtml('weight_class_id'), $key, $value['title'],$value['unit']));
			}

			$database->query("delete from weight_rule where from_id = '" . (int)$request->gethtml('weight_class_id') . "'");

			foreach ($request->gethtml('rule', 'post', array()) as $key => $value) {
				$sql = "insert into weight_rule set from_id = '?', to_id = '?', rule = '?'";
				$database->query($database->parse($sql, $request->gethtml('weight_class_id'), $key, $value));
			}

			$cache->delete('weight_class');
			
			$session->set('message', $language->get('text_message'));

			$response->redirect($url->ssl('weight_class'));
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

		$language->load('controller/weight_class.php');

		$template->set('title', $language->get('heading_title'));
 
		if (($request->gethtml('weight_class_id')) && ($this->validateDelete())) {
			$database->query("delete from weight_class where weight_class_id = '" . (int)$request->gethtml('weight_class_id') . "'");
			$database->query("delete from weight_rule where from_id = '" . (int)$request->gethtml('weight_class_id') . "'");

			$cache->delete('weight_class');
			
			$session->set('message', $language->get('text_message'));
			
			$response->redirect($url->ssl('weight_class'));
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
			'sort'  => 'title',
			'align' => 'left'
		);

		$cols[] = array(
			'name'  => $language->get('column_unit'),
			'sort'  => 'unit',
			'align' => 'left'
		);

    	$cols[] = array(
      		'name'  => $language->get('column_action'),
      		'align' => 'right'
    	);
		
		if (!$session->get('weight_class.search')) {
			$sql = "select weight_class_id, title, unit from weight_class where language_id = '" . (int)$language->getId() . "'";
		} else {
			$sql = "select weight_class_id, title, unit from weight_class where language_id = '" . (int)$language->getId() . "' and title like '?'";
		}

		$sort = array(
			'title',
			'unit'
		);

		if (in_array($session->get('weight_class.sort'), $sort)) {
			$sql .= " order by " . $session->get('weight_class.sort') . " " . (($session->get('weight_class.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by title asc";
		}

		$results = $database->getRows($database->splitQuery($database->parse($sql, '%' . $session->get('weight_class.search') . '%'), $session->get('weight_class.page'), $config->get('config_max_rows')));

		$rows = array();

		foreach ($results as $result) {
			$cell = array();

			$cell[] = array(
				'value'   => $result['title'],
				'align'   => 'left',
				'default' => ($result['weight_class_id'] == $config->get('config_weight_class_id'))
			);

			$cell[] = array(
				'value' => $result['unit'],
				'align' => 'left'
			);

			$action = array();
      		
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $language->get('button_update'),
				'href' => $url->ssl('weight_class', 'update', array('weight_class_id' => $result['weight_class_id']))
      		);
						
			$action[] = array(
        		'icon' => 'delete.png',
				'text' => $language->get('button_delete'),
				'href' => $url->ssl('weight_class', 'delete', array('weight_class_id' => $result['weight_class_id']))
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
		
		$view->set('action', $url->ssl('weight_class', 'page'));

		$view->set('search', $session->get('weight_class.search'));
		$view->set('sort', $session->get('weight_class.sort'));
		$view->set('order', $session->get('weight_class.order'));
		$view->set('page', $session->get('weight_class.page'));

		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('list', $url->ssl('weight_class'));

		$view->set('insert', $url->ssl('weight_class', 'insert'));

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
		$view->set('entry_unit', $language->get('entry_unit'));

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
		$view->set('error_unit', @$this->error['unit']);

		$view->set('action', $url->ssl('weight_class', $request->gethtml('action'), array('weight_class_id' => $request->gethtml('weight_class_id'))));

		$view->set('list', $url->ssl('weight_class'));

		$view->set('insert', $url->ssl('weight_class', 'insert'));

		if ($request->gethtml('weight_class_id')) {
			$view->set('update', $url->ssl('weight_class', 'update', array('weight_class_id' => $request->gethtml('weight_class_id'))));
			$view->set('delete', $url->ssl('weight_class', 'delete', array('weight_class_id' => $request->gethtml('weight_class_id'))));
		}

		$view->set('cancel', $url->ssl('weight_class'));

		$weight_class_data = array();

		$results = $database->cache('language', "select * from language order by sort_order");

		foreach ($results as $result) {
			if (($request->gethtml('weight_class_id')) && (!$request->isPost())) {
				$weight_description_info = $database->getRow("select title, unit from weight_class where weight_class_id = '" . (int)$request->gethtml('weight_class_id') . "' and language_id = '" . (int)$result['language_id'] . "'");
			} else {
				$weight_description_info = $request->gethtml('language', 'post');
			}
						
			$weight_class_data[] = array(
				'language_id' => $result['language_id'],
				'language'    => $result['name'],
	    		'title'       => (isset($weight_description_info[$result['language_id']]) ? $weight_description_info[$result['language_id']]['title'] : @$weight_description_info['title']),
	    		'unit'        => (isset($weight_description_info[$result['language_id']]) ? $weight_description_info[$result['language_id']]['unit'] : @$weight_description_info['unit']),
			);
		}

		$view->set('weight_classes', $weight_class_data);

		$weight_rule_data = array();

		$results = $database->getRows("select * from weight_class where language_id = '" . (int)$language->getId() . "'");

		foreach ($results as $result) {
			if (($request->gethtml('weight_class_id')) && (!$request->isPost())) {
				$weight_rule_info = $database->getRow("select * from weight_rule where from_id = '" . (int)$request->gethtml('weight_class_id') . "' and to_id = '" . (int)$result['weight_class_id'] . "'");
			}
			
			if ($result['weight_class_id'] != $request->gethtml('weight_class_id')) {
				$weight_rule_data[] = array(
					'title' => $result['title'] . ':',
					'to_id' => $result['weight_class_id'],
					'rule'  => (isset($post_info['rule'][$result['weight_class_id']]) ? $post_info['rule'][$result['weight_class_id']] : @$weight_rule_info['rule'])
				);
			}
		}

		$view->set('weight_rules', $weight_rule_data);

		return $view->fetch('content/weight_class.tpl');
	}

	function validateForm() {
		$request  =& $this->locator->get('request');
		$user     =& $this->locator->get('user');
		$language =& $this->locator->get('language');
        $validate =& $this->locator->get('validate');

		if (!$user->hasPermission('modify', 'weight_class')) {
			$this->error['message'] = $language->get('error_permission');
		}

		foreach ($request->gethtml('language', 'post') as $value) {
			if (!$validate->strlen($value['title'],1,32)) {
				$this->error['title'] = $language->get('error_title');
			}
		} 

		foreach ($request->gethtml('language', 'post') as $value) {
			if ((!$value['unit']) || (strlen($value['unit']) > 4)) {
				$this->error['unit'] = $language->get('error_unit');
			}
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

		if (!$user->hasPermission('modify', 'weight_class')) {
			$this->error['message'] = $language->get('error_permission');
		}

		if ($config->get('config_weight_class_id') == $request->gethtml('weight_class_id')) {
			$this->error['message'] = $language->get('error_default');
		}

		$product_info = $database->getRow("select count(*) as total from product where weight_class_id = '" . (int)$request->gethtml('weight_class_id') . "'");

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
			$session->set('weight_class.search', $request->gethtml('search', 'post'));
		}

		if (($request->has('page', 'post')) || ($request->has('search', 'post'))) {
			$session->set('weight_class.page', $request->gethtml('page', 'post'));
		}

		if ($request->has('sort', 'post')) {
			$session->set('weight_class.order', (($session->get('weight_class.sort') == $request->gethtml('sort', 'post')) && ($session->get('weight_class.order') == 'asc')) ? 'desc' : 'asc');
		}

		if ($request->has('sort', 'post')) {
			$session->set('weight_class.sort', $request->gethtml('sort', 'post'));
		}

		$response->redirect($url->ssl('weight_class'));
	}	
}
?>