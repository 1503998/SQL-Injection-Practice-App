<?php 
class ControllerCoupon extends Controller {
	var $error = array();
    
  	function index() {
		$response =& $this->locator->get('response');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$module   =& $this->locator->get('module');
			  
		$language->load('controller/coupon.php');
    	
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
        		 
    	$language->load('controller/coupon.php');

    	$template->set('title', $language->get('heading_title'));
		
    	if ($request->isPost() && $request->has('code', 'post') && $this->validateForm()) {
      		$sql = "insert into coupon set code = '?', discount = '?', prefix = '?', shipping = '?', date_start = '?', date_end = '?', uses_total = '?', uses_customer = '?', status = '?', date_added = now()";
      		$database->query($database->parse($sql, $request->gethtml('code', 'post'), $request->gethtml('discount', 'post'), $request->gethtml('prefix', 'post'), $request->gethtml('shipping', 'post'), date('Y-m-d', strtotime($request->gethtml('date_start_year', 'post') . '/' . $request->gethtml('date_start_month', 'post') . '/' . $request->gethtml('date_start_day', 'post'))), date('Y-m-d', strtotime($request->gethtml('date_end_year', 'post') . '/' . $request->gethtml('date_end_month', 'post') . '/' . $request->gethtml('date_end_day', 'post'))), $request->gethtml('uses_total', 'post'), $request->gethtml('uses_customer', 'post'), $request->gethtml('status', 'post')));

      		$insert_id = $database->getLastId();

      		foreach ($request->gethtml('language', 'post') as $key => $value) {
        		$sql = "insert into coupon_description set coupon_id = '?', language_id = '?', name = '?', description = '?'";
        		$database->query($database->parse($sql, $insert_id, $key, $value['name'], $value['description']));
      		}

      		foreach ($request->gethtml('product', 'post', array()) as $product_id) {
        		$sql = "insert into coupon_product set coupon_id = '?', product_id = '?'";
        		$database->query($database->parse($sql, $insert_id, $product_id));
      		}
			
			$session->set('message', $language->get('text_message'));

	  		$response->redirect($url->ssl('coupon'));
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

    	$language->load('controller/coupon.php');

    	$template->set('title', $language->get('heading_title'));
	
    	if ($request->isPost() && $request->has('code', 'post') && $this->validateForm()) {
			$sql = "update coupon set code = '?', discount = '?', prefix = '?', shipping = '?', date_start = '?', date_end = '?', uses_total = '?', uses_customer = '?', status = '?' where coupon_id = '?'";
			$database->query($database->parse($sql, $request->gethtml('code', 'post'), $request->gethtml('discount', 'post'), $request->gethtml('prefix', 'post'), $request->gethtml('shipping', 'post'), date('Y-m-d', strtotime($request->gethtml('date_start_year', 'post') . '/' . $request->gethtml('date_start_month', 'post') . '/' . $request->gethtml('date_start_day', 'post'))), date('Y-m-d', strtotime($request->gethtml('date_end_year', 'post') . '/' . $request->gethtml('date_end_month', 'post') . '/' . $request->gethtml('date_end_day', 'post'))), $request->gethtml('uses_total', 'post'), $request->gethtml('uses_customer', 'post'), $request->gethtml('status', 'post'), $request->gethtml('coupon_id')));

			$database->query("delete from coupon_description where coupon_id = '" . (int)$request->gethtml('coupon_id') . "'");

		  	foreach ($request->gethtml('language', 'post') as $key => $value) {
        		$sql = "insert into coupon_description set coupon_id = '?', language_id = '?', name = '?', description = '?'";
        		$database->query($database->parse($sql, $request->gethtml('coupon_id'), $key, $value['name'], $value['description']));
      		}

      		$database->query("delete from coupon_product where coupon_id = '" . (int)$request->gethtml('coupon_id') . "'");

      		foreach ($request->gethtml('product', 'post', array()) as $product_id) {
        		$sql = "insert into coupon_product set coupon_id = '?', product_id = '?'";
        		$database->query($database->parse($sql, $request->gethtml('coupon_id'), $product_id));
      		}
      		
			$session->set('message', $language->get('text_message'));
	  
	  		$response->redirect($url->ssl('coupon'));
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

    	$language->load('controller/coupon.php');

    	$template->set('title', $language->get('heading_title'));
	
    	if (($request->gethtml('coupon_id')) && ($this->validateDelete())) { 
      		$database->query("delete from coupon where coupon_id = '" . (int)$request->gethtml('coupon_id') . "'");
      		$database->query("delete from coupon_description where coupon_id = '" . (int)$request->gethtml('coupon_id') . "'");
      		$database->query("delete from coupon_product where coupon_id = '" . (int)$request->gethtml('coupon_id') . "'");
			$database->query("delete from coupon_redeem where coupon_id = '" . (int)$request->gethtml('coupon_id') . "'");
      		
			$session->set('message', $language->get('text_message'));
	  
	  		$response->redirect($url->ssl('coupon'));
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
      		'sort'  => 'cd.name',
      		'align' => 'left'
    	);
		
    	$cols[] = array(
      		'name'  => $language->get('column_code'),
      		'sort'  => 'c.code',
      		'align' => 'left'
    	);
    	
		$cols[] = array(
      		'name'  => $language->get('column_discount'),
      		'sort'  => 'c.discount',
      		'align' => 'right'
    	);
		
    	$cols[] = array(
      		'name'  => $language->get('column_prefix'),
      		'sort'  => 'c.prefix',
      		'align' => 'left'
    	);
		
		$cols[] = array(
      		'name'  => $language->get('column_date_start'),
      		'sort'  => 'c.date_start',
      		'align' => 'left'
    	);

		$cols[] = array(
      		'name'  => $language->get('column_date_end'),
      		'sort'  => 'c.date_end',
      		'align' => 'left'
    	);

		$cols[] = array(
      		'name'  => $language->get('column_status'),
      		'sort'  => 'c.status',
      		'align' => 'center'
    	);
						
    	$cols[] = array(
      		'name'  => $language->get('column_action'),
      		'align' => 'right'
    	);
								
    	if (!$session->get('coupon.search')) {
      		$sql = "select c.coupon_id, cd.name, c.code, c.discount, c.prefix, c.date_start, c.date_end, c.status from coupon c left join coupon_description cd on (c.coupon_id = cd.coupon_id) where cd.language_id = '" . (int)$language->getId() . "'";
    	} else {
      		$sql = "select c.coupon_id, cd.name, c.code, c.discount, c.prefix, c.date_start, c.date_end, c.status from coupon c left join coupon_description cd on (c.coupon_id = cd.coupon_id) where cd.language_id = '" . (int)$language->getId() . "' and (cd.name like '?' or c.code or like = '?')";
    	}
    
		$sort = array(
	  		'cd.name', 
			'c.code', 
	  		'c.discount', 
			'c.prefix', 
			'c.date_start', 
			'c.date_end', 
	  		'c.status'
		);
	
    	if (in_array($session->get('coupon.sort'), $sort)) {
      		$sql .= " order by " . $session->get('coupon.sort') . " " . (($session->get('coupon.order') == 'desc') ? 'desc' : 'asc');
    	} else {
      		$sql .= " order by cd.name asc";
    	}
    
    	$results = $database->getRows($database->splitQuery($database->parse($sql, '%' . $session->get('coupon.search') . '%', '%' . $session->get('coupon.search') . '%'), $session->get('coupon.page'), $config->get('config_max_rows')));

    	$rows = array();

    	foreach ($results as $result) {
      		$cell = array();

      		$cell[] = array(
        		'value' => $result['name'],
        		'align' => 'left'
		  	);

      		$cell[] = array(
        		'value' => $result['code'],
        		'align' => 'left'
      		);
			
      		$cell[] = array(
        		'value' => $result['discount'],
        		'align' => 'right'
      		);			
			
      		$cell[] = array(
        		'value' => $result['prefix'],
        		'align' => 'left'
      		);			

      		$cell[] = array(
        		'value' => $language->formatDate($language->get('date_format_short'), strtotime($result['date_start'])),
        		'align' => 'left'
      		);			

      		$cell[] = array(
        		'value' => $language->formatDate($language->get('date_format_short'), strtotime($result['date_end'])),
        		'align' => 'left'
      		);	
						
      		$cell[] = array(
        		'icon'  => ($result['status'] ? 'enabled.png' : 'disabled.png'),
        		'align' => 'center'
      		);
						
			$action = array();
      		
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $language->get('button_update'),
				'href' => $url->ssl('coupon', 'update', array('coupon_id' => $result['coupon_id']))
      		);
						
			$action[] = array(
        		'icon' => 'delete.png',
				'text' => $language->get('button_delete'),
				'href' => $url->ssl('coupon', 'delete', array('coupon_id' => $result['coupon_id']))
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
		  
    	$view->set('action', $url->ssl('coupon', 'page'));

    	$view->set('search', $session->get('coupon.search'));
    	$view->set('sort', $session->get('coupon.sort'));
    	$view->set('order', $session->get('coupon.order'));
    	$view->set('page', $session->get('coupon.page'));
 
    	$view->set('cols', $cols);
    	$view->set('rows', $rows);

    	$view->set('list', $url->ssl('coupon'));
    
    	$view->set('insert', $url->ssl('coupon', 'insert'));
  
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
		$config   =& $this->locator->get('config');
		$url      =& $this->locator->get('url');
		$language =& $this->locator->get('language');
   
    	$view = $this->locator->create('template');

    	$view->set('heading_title', $language->get('heading_title'));
    	$view->set('heading_description', $language->get('heading_description'));

    	$view->set('text_enabled', $language->get('text_enabled'));
    	$view->set('text_disabled', $language->get('text_disabled'));
    	$view->set('text_yes', $language->get('text_yes'));
    	$view->set('text_no', $language->get('text_no'));
    	$view->set('text_percent', $language->get('text_percent'));
    	$view->set('text_minus', $language->get('text_minus'));
		  
    	$view->set('entry_name', $language->get('entry_name'));
    	$view->set('entry_description', $language->get('entry_description'));
    	$view->set('entry_code', $language->get('entry_code'));
		$view->set('entry_discount', $language->get('entry_discount'));
    	$view->set('entry_prefix', $language->get('entry_prefix'));
    	$view->set('entry_date_start', $language->get('entry_date_start'));
    	$view->set('entry_date_end', $language->get('entry_date_end'));
    	$view->set('entry_shipping', $language->get('entry_shipping'));
    	$view->set('entry_uses_total', $language->get('entry_uses_total'));
		$view->set('entry_uses_customer', $language->get('entry_uses_customer'));
		$view->set('entry_product', $language->get('entry_product'));
		$view->set('entry_status', $language->get('entry_status'));

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
    	$view->set('error_date_start', @$this->error['date_start']);
		$view->set('error_date_end', @$this->error['date_end']);
		
    	$view->set('action', $url->ssl('coupon', $request->gethtml('action'), array('coupon_id' => $request->gethtml('coupon_id'))));
  
    	$view->set('list', $url->ssl('coupon'));
 
    	$view->set('insert', $url->ssl('coupon', 'insert'));
  
    	if ($request->gethtml('coupon_id')) {
     		$view->set('update', $url->ssl('coupon', 'update', array('coupon_id' => $request->gethtml('coupon_id'))));
      		$view->set('delete', $url->ssl('coupon', 'delete', array('coupon_id' => $request->gethtml('coupon_id'))));
    	}
  
    	$view->set('cancel', $url->ssl('coupon'));

    	$coupon_data = array();
     		 
    	$results = $database->cache('language', "select * from language order by sort_order");
    
    	foreach ($results as $result) {
			if (($request->gethtml('coupon_id')) && (!$request->isPost())) {
	  			$coupon_description_info = $database->getRow("select name, description from coupon_description where coupon_id = '" . (int)$request->gethtml('coupon_id') . "' and language_id = '" . (int)$result['language_id'] . "'");
			} else {
				$coupon_description_info = $request->gethtml('language', 'post');
			}
			
			$coupon_data[] = array(
	    		'language_id' => $result['language_id'],
	    		'language'    => $result['name'],
	    		'name'        => (isset($coupon_description_info[$result['language_id']]) ? $coupon_description_info[$result['language_id']]['name'] : @$coupon_description_info['name']),
	    		'description' => (isset($coupon_description_info[$result['language_id']]) ? $coupon_description_info[$result['language_id']]['description'] : @$coupon_description_info['description'])
	  		);
    	}

    	$view->set('coupons', $coupon_data);

    	if (($request->gethtml('coupon_id')) && (!$request->isPost())) {
      		$coupon_info = $database->getRow("select distinct * from coupon where coupon_id = '" . (int)$request->gethtml('coupon_id') . "'");
    	}

    	if ($request->has('code', 'post')) {
      		$view->set('code', $request->gethtml('code', 'post'));
    	} else {
      		$view->set('code', @$coupon_info['code']);
    	}

    	if ($request->has('discount', 'post')) {
      		$view->set('discount', $request->gethtml('discount', 'post'));
    	} else {
      		$view->set('discount', @$coupon_info['discount']);
    	}

    	if ($request->has('discount', 'post')) {
      		$view->set('discount', $request->gethtml('discount', 'post'));
    	} else {
      		$view->set('discount', @$coupon_info['discount']);
    	}
				
    	if ($request->has('prefix', 'post')) {
      		$view->set('prefix', $request->gethtml('prefix', 'post'));
    	} else {
      		$view->set('prefix', @$coupon_info['prefix']);
    	}

    	if ($request->has('shipping', 'post')) {
      		$view->set('shipping', $request->gethtml('shipping', 'post'));
    	} else {
      		$view->set('shipping', @$coupon_info['shipping']);
    	}
		
    	$month_data = array();

    	$month_data[] = array(
      		'value' => '01',
      		'text'  => $language->get('text_january')
    	);

    	$month_data[] = array(
      		'value' => '02',
      		'text'  => $language->get('text_february')
    	);

    	$month_data[] = array(
      		'value' => '03',
      		'text'  => $language->get('text_march')
    	);

    	$month_data[] = array(
      		'value' => '04',
      		'text'  => $language->get('text_april')
    	);

    	$month_data[] = array(
      		'value' => '05',
      		'text'  => $language->get('text_may')
    	);

    	$month_data[] = array(
      		'value' => '06',
      		'text'  => $language->get('text_june')
    	);

    	$month_data[] = array(
      		'value' => '07',
      		'text'  => $language->get('text_july')
    	);

    	$month_data[] = array(
      		'value' => '08',
      		'text'  => $language->get('text_august')
    	);

    	$month_data[] = array(
      		'value' => '09',
      		'text'  => $language->get('text_september')
    	);

    	$month_data[] = array(
      		'value' => '10',
      		'text'  => $language->get('text_october')
    	);

    	$month_data[] = array(
      		'value' => '11',
      		'text'  => $language->get('text_november')
    	);

    	$month_data[] = array(
      		'value' => '12',
      		'text'  => $language->get('text_december')
    	);

    	$view->set('months', $month_data);
      	
		if (isset($coupon_info['date_start'])) {
        	$date = explode('/', date('d/m/Y', strtotime($coupon_info['date_start'])));
      	} else {
        	$date = explode('/', date('d/m/Y', time()));
      	}
			
    	if ($request->has('date_start_day', 'post')) {
      		$view->set('date_start_day', $request->gethtml('date_start_day', 'post'));
    	} else {
      		$view->set('date_start_day', $date[0]);
    	}			
			
    	if ($request->has('date_start_month', 'post')) {
      		$view->set('date_start_month', $request->gethtml('date_start_month', 'post'));
    	} else {
      		$view->set('date_start_month', $date[1]);
    	}

    	if ($request->has('date_start_year', 'post')) {
      		$view->set('date_start_year', $request->gethtml('date_start_year', 'post'));
    	} else {
      		$view->set('date_start_year', $date[2]);
    	}					

		if (isset($coupon_info['date_end'])) {
        	$date = explode('/', date('d/m/Y', strtotime($coupon_info['date_end'])));
      	} else {
        	$date = explode('/', date('d/m/Y', time()));
      	}
			
    	if ($request->has('date_end_day', 'post')) {
      		$view->set('date_end_day', $request->gethtml('date_end_day', 'post'));
    	} else {
      		$view->set('date_end_day', $date[0]);
    	}			
			
    	if ($request->has('date_end_month', 'post')) {
      		$view->set('date_end_month', $request->gethtml('date_end_month', 'post'));
    	} else {
      		$view->set('date_end_month', $date[1]);
    	}

    	if ($request->has('date_end_year', 'post')) {
      		$view->set('date_end_year', $request->gethtml('date_end_year', 'post'));
    	} else {
      		$view->set('date_end_year', $date[2]);
    	}

    	if ($request->has('uses_total', 'post')) {
      		$view->set('uses_total', $request->gethtml('uses_total', 'post'));
    	} else {
      		$view->set('uses_total', @$coupon_info['uses_total']);
    	}
  
    	if ($request->has('uses_customer', 'post')) {
      		$view->set('uses_customer', $request->gethtml('uses_customer', 'post'));
    	} else {
      		$view->set('uses_customer', @$coupon_info['uses_customer']);
    	}

    	$product_data = array();

    	$results = $database->cache('product', "select * from product p left join product_description pd on (p.product_id = pd.product_id) where pd.language_id = '" . (int)$language->getId() . "' order by pd.name");
 
    	foreach ($results as $result) {
			if (($request->gethtml('coupon_id')) && (!$request->isPost())) {  
	  			$coupon_product_info = $database->getRow("select * from coupon_product where coupon_id = '" . (int)$request->gethtml('coupon_id') . "' and product_id = '" . (int)$result['product_id'] . "'");
			}

      		$product_data[] = array(
        		'product_id' => $result['product_id'],
        		'name'       => $result['name'],
        		'coupon_id'  => (isset($coupon_product_info) ? $coupon_product_info : in_array($result['product_id'], $request->gethtml('product', 'post', array())))
      		);
    	}

    	$view->set('products', $product_data);

    	if ($request->has('status', 'post')) {
      		$view->set('status', $request->gethtml('status', 'post'));
    	} else {
      		$view->set('status', @$coupon_info['status']);
    	}
		 
 		return $view->fetch('content/coupon.tpl');
  	}
	
  	function validateForm() {
		$request  =& $this->locator->get('request');
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');
        $validate =& $this->locator->get('validate');
 
    	if (!$user->hasPermission('modify', 'coupon')) {
      		$this->error['message'] = $language->get('error_permission');
    	}
	      
    	foreach ($request->gethtml('language', 'post') as $value) {
      		if (!$validate->strlen($value['name'],1,64)) {
        		$this->error['name'] = $language->get('error_name');
      		}
    	}
		
		foreach ($request->gethtml('language', 'post') as $value) {
      		if (!$validate->strlen($value['description'],1)) {
        		$this->error['description'] = $language->get('error_description');
      		}
    	}

    	if (!$validate->strlen($request->gethtml('code', 'post'),1,10)) {
      		$this->error['code'] = $language->get('error_code');
    	}

    	if (!checkdate($request->gethtml('date_start_month', 'post'), $request->gethtml('date_start_day', 'post'), $request->gethtml('date_start_year', 'post'))) {
	  		$this->error['date_start'] = $language->get('error_date_start');
		}

    	if (!checkdate($request->gethtml('date_end_month', 'post'), $request->gethtml('date_end_day', 'post'), $request->gethtml('date_end_year', 'post'))) {
	  		$this->error['date_end'] = $language->get('error_date_end');
		}
		
    	if (!$this->error) {
      		return TRUE;
    	} else {
      		return FALSE;
    	}
  	}

  	function validateDelete() {
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');  
    
    	if (!$user->hasPermission('modify', 'coupon')) {
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
	  		$session->set('coupon.search', $request->gethtml('search', 'post'));
		}
	 
		if (($request->has('page', 'post')) || ($request->has('search', 'post'))) {
	  		$session->set('coupon.page', $request->gethtml('page', 'post'));
		} 
	
		if ($request->has('sort', 'post')) {
	  		$session->set('coupon.order', (($session->get('coupon.sort') == $request->gethtml('sort', 'post')) && ($session->get('coupon.order') == 'asc') ? 'desc' : 'asc'));
		}
		
		if ($request->has('sort', 'post')) {
			$session->set('coupon.sort', $request->gethtml('sort', 'post'));
		}
				
		$response->redirect($url->ssl('coupon'));
  	} 	
}
?>
