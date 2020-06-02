<?php // .79
class ControllerCurrency extends Controller {
	var $error = array();

	function index() {
		$response =& $this->locator->get('response');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template'); 
		$module   =& $this->locator->get('module');

		$language->load('controller/currency.php');

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

		$language->load('controller/currency.php');

		$template->set('title', $language->get('heading_title'));

		if ($request->isPost() && $request->has('title', 'post') && $this->validateForm()) {
			$sql = "insert into currency set title = '?', code = '?', symbol_left = '?', symbol_right = '?', decimal_place = '?', value = '?', date_modified = now()";
			$database->query($database->parse($sql, $request->gethtml('title', 'post'), $request->gethtml('code', 'post'), $request->gethtml('symbol_left', 'post'), $request->gethtml('symbol_right', 'post'), $request->gethtml('decimal_place', 'post'), number_format($request->gethtml('value', 'post'), 8)));

			$cache->delete('currency');
			
			$session->set('message', $language->get('text_message'));

			$response->redirect($url->ssl('currency'));
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

		$language->load('controller/currency.php');

		$template->set('title', $language->get('heading_title'));

		if ($request->isPost() && $request->has('title', 'post') && $this->validateForm()) {
			$sql = "update currency set title = '?', code = '?', symbol_left = '?', symbol_right = '?', decimal_place = '?', value = '?', date_modified = now() where currency_id = '?'";
			$database->query($database->parse($sql, $request->gethtml('title', 'post'), $request->gethtml('code', 'post'), $request->gethtml('symbol_left', 'post'), $request->gethtml('symbol_right', 'post'), $request->gethtml('decimal_place', 'post'), number_format($request->gethtml('value', 'post'), 8), $request->gethtml('currency_id')));

			$cache->delete('currency');
			
			$session->set('message', $language->get('text_message'));

			$response->redirect($url->ssl('currency'));
		}

		$template->set('content', $this->getForm());

		$template->set($module->fetch());

		$response->set($template->fetch('layout.tpl'));
	}
	
		function updateRates() {
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$database =& $this->locator->get('database');
		$url      =& $this->locator->get('url');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$session  =& $this->locator->get('session');
		$module   =& $this->locator->get('module');
		$cache    =& $this->locator->get('cache');
		$config   =& $this->locator->get('config');
		$currency =& $this->locator->get('currency');
		
		$language->load('controller/currency.php');
	
		if($this->validateUpdate()){
			$from = $config->get('config_currency');
			$results = $database->getRows("select code from currency");
			foreach ($results as $to) {		
				$rate = $currency->currency_converter('1.00', $from, $to['code']);
				if ($rate > 0){
					$database->query("update `currency` set value ='".$rate ."', date_modified = now() where code = '".$to['code']."'"); 
				}
			}		
			$cache->delete('currency');		
			$session->set('message', $language->get('text_message'));
		} else {
			$session->set('message', @$this->error['message']);
		}
		$response->redirect($url->ssl('currency'));
		
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

		$language->load('controller/currency.php');

		$template->set('title', $language->get('heading_title'));

		if (($request->gethtml('currency_id')) && ($this->validateDelete())) {
			$database->query("delete from currency where currency_id = '" . (int)$request->gethtml('currency_id') . "'");

			$cache->delete('currency');
			
			$session->set('message', $language->get('text_message'));

			$response->redirect($url->ssl('currency'));
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
			'name'  => $language->get('column_code'),
			'sort'  => 'code',
			'align' => 'left'
		);

		$cols[] = array(
			'name'  => $language->get('column_value'),
			'sort'  => 'value',
			'align' => 'right'
		);

		$cols[] = array(
			'name'  => $language->get('column_date_modified'),
			'sort'  => 'date_modified',
			'align' => 'right'
		);
    	
		$cols[] = array(
      		'name'  => $language->get('column_action'),
      		'align' => 'right'
    	);
		
		if (!$session->get('currency.search')) {
			$sql = "select currency_id, title, code, value, date_modified from currency";
		} else {
			$sql = "select currency_id, title, code, value, date_modified from currency where title like '?'";
		}

		$sort = array(
			'title',
			'code',
			'value',
			'date_modified'
		);

		if (in_array($session->get('currency.sort'), $sort)) {
			$sql .= " order by " . $session->get('currency.sort') . " " . (($session->get('currency.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by title asc";
		}

		$results = $database->getRows($database->splitQuery($database->parse($sql, '%' . $session->get('currency.search') . '%'), $session->get('currency.page'), $config->get('config_max_rows')));

		$rows = array();

		foreach ($results as $result) {
			$cell = array();

			$cell[] = array(
				'value'   => $result['title'],
				'align'   => 'left',
				'default' => ($result['code'] == $config->get('config_currency'))
			);

			$cell[] = array(
				'value' => $result['code'],
				'align' => 'left'
			);

			$cell[] = array(
				'value' => $result['value'],
				'align' => 'right'
			);

			$cell[] = array(
				'value' => $language->formatDate($language->get('date_format_short'), strtotime($result['date_modified'])),
				'align' => 'right'
			);
			
			$action = array();
      		
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $language->get('button_update'),
				'href' => $url->ssl('currency', 'update', array('currency_id' => $result['currency_id']))
      		);
						
			$action[] = array(
        		'icon' => 'delete.png',
				'text' => $language->get('button_delete'),
				'href' => $url->ssl('currency', 'delete', array('currency_id' => $result['currency_id']))
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
		$view->set('button_refresh', $language->get('button_rate'));
		
		$view->set('error', @$this->error['message']);

		$view->set('message', $session->get('message'));
		
		$session->delete('message');
		
		$view->set('action', $url->ssl('currency', 'page'));
		$view->set('action_refresh', $url->ssl('currency', 'updateRates'));
		$view->set('search', $session->get('currency.search'));
		$view->set('sort', $session->get('currency.sort'));
		$view->set('order', $session->get('currency.order'));
		$view->set('page', $session->get('currency.page'));
 
		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('list', $url->ssl('currency'));

		$view->set('insert', $url->ssl('currency', 'insert'));

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
		$view->set('entry_code', $language->get('entry_code'));
		$view->set('entry_value', $language->get('entry_value'));
		$view->set('entry_symbol_left', $language->get('entry_symbol_left'));
		$view->set('entry_symbol_right', $language->get('entry_symbol_right'));
		$view->set('entry_decimal_place', $language->get('entry_decimal_place'));

		$view->set('button_list', $language->get('button_list'));
		$view->set('button_insert', $language->get('button_insert'));
		$view->set('button_update', $language->get('button_update'));
		$view->set('button_delete', $language->get('button_delete'));
		$view->set('button_save', $language->get('button_save'));
		$view->set('button_cancel', $language->get('button_cancel'));

		$view->set('tab_general', $language->get('tab_general'));

		$view->set('error', @$this->error['message']);
		$view->set('error_title', @$this->error['title']);
		$view->set('error_code', @$this->error['code']);

		$view->set('action', $url->ssl('currency', $request->gethtml('action'), array('currency_id' => $request->gethtml('currency_id'))));

		$view->set('list', $url->ssl('currency'));

		$view->set('insert', $url->ssl('currency', 'insert'));

		if ($request->gethtml('currency_id')) {
			$view->set('update', $url->ssl('currency', 'update', array('currency_id' => $request->gethtml('currency_id'))));
			$view->set('delete', $url->ssl('currency', 'delete', array('currency_id' => $request->gethtml('currency_id'))));
		}

		$view->set('cancel', $url->ssl('currency'));

		if (($request->gethtml('currency_id')) && (!$request->isPost())) {
			$currency_info = $database->getRow("select distinct * from currency where currency_id = '" . (int)$request->gethtml('currency_id') . "'");
		}

		if ($request->has('title', 'post')) {
			$view->set('title', $request->gethtml('title', 'post'));
		} else {
			$view->set('title', @$currency_info['title']);
		}

		if ($request->has('code', 'post')) {
			$view->set('code', $request->gethtml('code', 'post'));
		} else {
			$view->set('code', @$currency_info['code']);
		}

		if ($request->has('symbol_left', 'post')) {
			$view->set('symbol_left', $request->gethtml('symbol_left', 'post'));
		} else {
			$view->set('symbol_left', @$currency_info['symbol_left']);
		}

		if ($request->has('symbol_right', 'post')) {
			$view->set('symbol_right', $request->gethtml('symbol_right', 'post'));
		} else {
			$view->set('symbol_right', @$currency_info['symbol_right']);
		}

		if ($request->has('decimal_place', 'post')) {
			$view->set('decimal_place', $request->gethtml('decimal_place', 'post'));
		} else {
			$view->set('decimal_place', @$currency_info['decimal_place']);
		}

		if ($request->has('value', 'post')) {
			$view->set('value', $request->gethtml('value', 'post'));
		} else {
			$view->set('value', @$currency_info['value']);
		}

		return $view->fetch('content/currency.tpl');
	}
	function validateUpdate(){
		$request  =& $this->locator->get('request');
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');
        $validate =& $this->locator->get('validate');
		
		if (!$user->hasPermission('modify', 'currency')) {
			$this->error['message'] = $language->get('error_permission');
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}		
	}
	
	function validateForm() {
		$request  =& $this->locator->get('request');
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');
        $validate =& $this->locator->get('validate');

		if (!$user->hasPermission('modify', 'currency')) {
			$this->error['message'] = $language->get('error_permission');
		}

		if (!$validate->strlen($request->gethtml('title', 'post'),1,32)) {
			$this->error['title'] = $language->get('error_title');
		}

        if (!$validate->strlen($request->gethtml('code', 'post'),3,3)) {
			$this->error['code'] = $language->get('error_code');
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

		if (!$user->hasPermission('modify', 'currency')) {
			$this->error['message'] = $language->get('error_permission');
		}

		$result = $database->getRow("select * from currency where currency_id = '" . (int)$request->gethtml('currency_id') . "'");

		if ($config->get('config_currency') == $result['code']) {
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
			$session->set('currency.search', $request->gethtml('search', 'post'));
		}

		if (($request->has('page', 'post')) || ($request->has('search', 'post'))) {
			$session->set('currency.page', $request->gethtml('page', 'post'));
		}

		if ($request->has('sort', 'post')) {
			$session->set('currency.order', (($session->get('currency.sort') == $request->gethtml('sort', 'post')) && ($session->get('currency.order') == 'asc')) ? 'desc' : 'asc');
		}

		if ($request->has('sort', 'post')) {
			$session->set('currency.sort', $request->gethtml('sort', 'post'));
		}

		$response->redirect($url->ssl('currency'));
	}		
}
?>