<?php
class ControllerProductOption extends Controller {
	var $error = array();

	function index() {
		$response =& $this->locator->get('response');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$module   =& $this->locator->get('module');
 
		$language->load('controller/product_option.php');

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

		$language->load('controller/product_option.php');

		$template->set('title', $language->get('heading_title'));

		if ($request->isPost() && $request->has('product_id') && $this->validate()) {
			$option = explode(':', $request->gethtml('option', 'post'));

			$sql ="insert into product_to_option set product_id = '?', option_id = '?', option_value_id = '?', prefix = '?', price = '?', sort_order = '?'";
			$database->query($database->parse($sql, $request->gethtml('product_id'), $option[0], $option[1], $request->gethtml('prefix', 'post'), $request->gethtml('price', 'post'), $request->gethtml('sort_order', 'post')));

			$cache->delete('product');
			
			$session->set('message', $language->get('text_message'));

			$response->redirect($url->ssl('product_option', FALSE, array('product_id' => $request->gethtml('product_id'))));
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

		$language->load('controller/product_option.php');

		$template->set('title', $language->get('heading_title'));

		if ($request->isPost() && $request->has('product_id') && $this->validate()) {
			$option = explode(':', $request->gethtml('option', 'post'));

			$sql ="update product_to_option set product_id = '?', option_id = '?', option_value_id = '?', prefix = '?', price = '?', sort_order = '?' where product_to_option_id = '?'";
			$database->query($database->parse($sql, $request->gethtml('product_id'), $option[0], $option[1], $request->gethtml('prefix', 'post'), $request->gethtml('price', 'post'), $request->gethtml('sort_order', 'post'), $request->gethtml('product_to_option_id')));

			$cache->delete('product');
			
			$session->set('message', $language->get('text_message'));

			$response->redirect($url->ssl('product_option', FALSE, array('product_id' => $request->gethtml('product_id'))));
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

		$language->load('controller/product_option.php');

		$template->set('title', $language->get('heading_title'));

		if (($request->gethtml('product_id')) && ($this->validate())) {
			$database->query("delete from product_to_option where product_to_option_id = '" . (int)$request->gethtml('product_to_option_id') . "' and product_id = '" . (int)$request->gethtml('product_id') . "'");

			$cache->delete('product');
			
			$session->set('message', $language->get('text_message'));

			$response->redirect($url->ssl('product_option', FALSE, array('product_id' => $request->gethtml('product_id'))));
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
			'name'  => $language->get('column_option'),
			'sort'  => 'o.name',
			'align' => 'left'
		);

		$cols[] = array(
			'name'  => $language->get('column_value'),
			'sort'  => 'ov.name',
			'align' => 'left'
		);

		$cols[] = array(
			'name'  => $language->get('column_prefix'),
			'sort'  => 'p2o.prefix',
			'align' => 'left'
		);

		$cols[] = array(
			'name'  => $language->get('column_price'),
			'sort'  => 'p2o.price',
			'align' => 'right'
		);

		$cols[] = array(
			'name'  => $language->get('column_sort_order'),
			'sort'  => 'p2o.sort_order',
			'align' => 'right'
		);

    	$cols[] = array(
      		'name'  => $language->get('column_action'),
      		'align' => 'right'
    	);
		
		if (!$session->get('product_option.search')) {
			$sql = "select p2o.product_to_option_id, o.name as `option`, ov.name as `option_value`, p2o.price, p2o.prefix, p2o.sort_order from product_to_option p2o left join `option` o on p2o.option_id = o.option_id left join option_value ov on p2o.option_value_id = ov.option_value_id where p2o.product_id = '" . (int)$request->gethtml('product_id') . "' and o.language_id = '" . (int)$language->getId() . "' and ov.language_id = '" . (int)$language->getId() . "'";
		} else {
			$sql = "select p2o.product_to_option_id, o.name as `option`, ov.name as `option_value`, p2o.price, p2o.prefix, p2o.sort_order from product_to_option p2o left join `option` o on p2o.option_id = o.option_id left join option_value ov on p2o.option_value_id = ov.option_value_id where p2o.product_id = '" . (int)$request->gethtml('product_id') . "' and o.language_id = '" . (int)$language->getId() . "' and ov.language_id = '" . (int)$language->getId() . "' and o.name like '?'";
		}

		$sort = array(
			'o.name',
			'ov.name',
			'p2o.price',
			'p2o.prefix',
			'p2o.sort_order'
		);

		if (in_array($session->get('product_option.sort'), $sort)) {
			$sql .= " order by " . $session->get('product_option.sort') . " " . (($session->get('product_option.order')== 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by p2o.sort_order, o.name asc";
		}

		$results = $database->getRows($database->splitQuery($database->parse($sql, '%' . $session->get('product_option.search') . '%'), $session->get('product_option.' . $request->gethtml('product_id') . '.page'), $config->get('config_max_rows')));

		$rows = array();

		foreach ($results as $result) {
			$cell = array();

			$cell[] = array(
				'value' => $result['option'],
				'align' => 'left'
			);

			$cell[] = array(
				'value' => $result['option_value'],
				'align' => 'left'
			);

			$cell[] = array(
				'value' => $result['prefix'],
				'align' => 'left'
			);

			$cell[] = array(
				'value' => $result['price'],
				'align' => 'right'
			);

			$cell[] = array(
				'value' => $result['sort_order'],
				'align' => 'right'
			);

			$query = array(
				'product_to_option_id' => $result['product_to_option_id'],
				'product_id'           => $request->gethtml('product_id')
			);
			
			$action = array();
      		
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $language->get('button_update'),
				'href' => $url->ssl('product_option', 'update', $query)
      		);
						
			$action[] = array(
        		'icon' => 'delete.png',
				'text' => $language->get('button_delete'),
				'href' => $url->ssl('product_option', 'delete', $query)
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
		
		$view->set('action', $url->ssl('product_option', 'page', array('product_id' => $request->gethtml('product_id'))));

		$view->set('previous', $url->ssl('product', FALSE, array('product_id' => $request->gethtml('product_id'))));
 
		$view->set('search', $session->get('product_option.search'));
		$view->set('sort', $session->get('product_option.sort'));
		$view->set('order', $session->get('product_option.order'));
		$view->set('page', $session->get('product_option.' . $request->gethtml('product_id') . '.page'));

		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('list', $url->ssl('product_option', FALSE, array('product_id' => $request->gethtml('product_id'))));

		$view->set('insert', $url->ssl('product_option', 'insert', array('product_id' => $request->gethtml('product_id'))));

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

		$view->set('text_plus', $language->get('text_plus'));
		$view->set('text_minus', $language->get('text_minus'));

		$view->set('entry_option', $language->get('entry_option'));
		$view->set('entry_prefix', $language->get('entry_prefix'));
		$view->set('entry_price', $language->get('entry_price'));
		$view->set('entry_sort_order', $language->get('entry_sort_order'));

		$view->set('button_list', $language->get('button_list'));
		$view->set('button_insert', $language->get('button_insert'));
		$view->set('button_update', $language->get('button_update'));
		$view->set('button_delete', $language->get('button_delete'));
		$view->set('button_save', $language->get('button_save'));
		$view->set('button_cancel', $language->get('button_cancel'));

		$view->set('tab_general', $language->get('tab_general'));

		$view->set('error', @$this->error['message']);

		$query = array(
			'product_to_option_id' => $request->gethtml('product_to_option_id'),
			'product_id'           => $request->gethtml('product_id')
		);

		$view->set('action', $url->ssl('product_option', $request->gethtml('action'), $query));

		$view->set('list', $url->ssl('product_option', FALSE, array('product_id' => $request->gethtml('product_id'))));

		$view->set('insert', $url->ssl('product_option', 'insert', array('product_id' => $request->gethtml('product_id'))));

		if ($request->gethtml('product_to_option_id')) {
			$query = array(
				'product_to_option_id' => $request->gethtml('product_to_option_id'),
				'product_id'           => $request->gethtml('product_id')
			);

			$view->set('update', $url->ssl('product_option', 'update', $query));
			$view->set('delete', $url->ssl('product_option', 'delete', $query));
		}

		$view->set('cancel', $url->ssl('product_option', FALSE, array('product_id' => $request->gethtml('product_id'))));

		$option_data = array();

		$options = $database->getRows("select option_id, name from `option` where language_id = '" . (int)$language->getId() . "'");

		foreach ($options as $option) {
			$option_value_data = array();

			$option_values = $database->getRows("select option_value_id, option_id, name from option_value where option_id = '" . (int)$option['option_id'] . "' and language_id = '" . (int)$language->getId() . "'");

			foreach ($option_values as $option_value) {
				$option_value_data[] = array(
					'option_value_id' => $option_value['option_id'] . ':' . $option_value['option_value_id'],
					'name'            => $option_value['name'],
				);
			}

			$option_data[] = array(
				'option_id' => $option['option_id'],
				'name'      => $option['name'],
				'value'     => $option_value_data
			);
		}

		$view->set('options', $option_data);

		if (($request->gethtml('product_to_option_id')) && (!$request->isPost())) {
			$product_option_info = $database->getRow("select distinct * from product_to_option where product_to_option_id = '" . (int)$request->gethtml('product_to_option_id') . "'");
		}

		if ($request->has('prefix', 'post')) {
			$view->set('prefix', $request->gethtml('prefix', 'post'));
		} else {
			$view->set('prefix', @$product_option_info['prefix']);
		}

		if ($request->has('price', 'post')) {
			$view->set('price', $request->gethtml('price', 'post'));
		} else {
			$view->set('price', @$product_option_info['price']);
		}

		if ($request->has('sort_order', 'post')) {
			$view->set('sort_order', $request->gethtml('sort_order', 'post'));
		} else {
			$view->set('sort_order', @$product_option_info['sort_order']);
		}

		if ($request->has('option', 'post')) {
			$view->set('option_value_id', $request->gethtml('option', 'post'));
		} else {
			$view->set('option_value_id', @$product_option_info['option_id'] . ':' . @$product_option_info['option_value_id']);
		}

		return $view->fetch('content/product_option.tpl');
	}

	function validate() {
		$user     =& $this->locator->get('user');
		$language =& $this->locator->get('language');

		if (!$user->hasPermission('modify', 'product_option')) {
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
			$session->set('product_option.search', $request->gethtml('search', 'post'));
		}

		if (($request->has('page', 'post')) || ($request->has('search', 'post'))) {
			$session->set('product_option.' . $request->gethtml('product_id') . '.page', $request->gethtml('page', 'post'));
		}

		if ($request->has('sort', 'post')) {
			$session->set('product_option.order', (($session->get('product_option.sort') == $request->gethtml('sort', 'post')) && ($session->get('product_option.order') == 'asc')) ? 'desc' : 'asc');
		}

		if ($request->has('sort', 'post')) {
			$session->set('product_option.sort', $request->gethtml('sort', 'post'));
		}

		$response->redirect($url->ssl('product_option', FALSE, array('product_id' => $request->gethtml('product_id'))));
	}	
}
?>