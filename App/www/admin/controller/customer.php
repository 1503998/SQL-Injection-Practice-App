<?php   
class ControllerCustomer extends Controller { 
	var $error = array();
  
  	function index() {
    	$response =& $this->locator->get('response');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$module   =& $this->locator->get('module');
			    	
		$language->load('controller/customer.php');
		
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

		$language->load('controller/customer.php');

    	$template->set('title', $language->get('heading_title'));
			
		if ($request->isPost() && $request->has('firstname', 'post') && $this->validateForm()) {
      		$sql = "insert into customer set firstname = '?', lastname = '?', email = '?', telephone = '?', fax = '?', newsletter = '?', password = '?', status = '?', date_added = now()";
      		$database->query($database->parse($sql, $request->gethtml('firstname', 'post'), $request->gethtml('lastname', 'post'), $request->gethtml('email', 'post'), $request->gethtml('telephone', 'post'), $request->gethtml('fax', 'post'), $request->gethtml('newsletter', 'post'), md5($request->gethtml('password', 'post')), $request->gethtml('status', 'post')));
      	  	
			$session->set('message', $language->get('text_message'));
		  
	  		$response->redirect($url->ssl('customer'));
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

		$language->load('controller/customer.php');

    	$template->set('title', $language->get('heading_title'));
		
    	if ($request->isPost() && $request->has('firstname', 'post') && $this->validateForm()) {
      		$sql = "update customer set firstname = '?', lastname = '?', email = '?', telephone = '?', fax = '?', newsletter = '?', status = '?' where customer_id = '?'";
      		$database->query($database->parse($sql, $request->gethtml('firstname', 'post'), $request->gethtml('lastname', 'post'), $request->gethtml('email', 'post'), $request->gethtml('telephone', 'post'), $request->gethtml('fax', 'post'), $request->gethtml('newsletter', 'post'), $request->gethtml('status', 'post'), $request->gethtml('customer_id')));

      		if ($request->gethtml('password', 'post')) {
        		$sql = "update customer set password = '?' where customer_id = '?'";
        		$database->query($database->parse($sql, md5($request->gethtml('password', 'post')), $request->gethtml('customer_id')));
      		}
	  		
			$session->set('message', $language->get('text_message'));
	  
	  		$response->redirect($url->ssl('customer'));
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

		$language->load('controller/customer.php');

    	$template->set('title', $language->get('heading_title'));
			
    	if (($request->gethtml('customer_id')) && ($this->validateDelete())) {
      		$database->query("delete from customer where customer_id = '" . (int)$request->gethtml('customer_id') . "'");
      		$database->query("delete from address where customer_id = '" . (int)$request->gethtml('customer_id') . "'"); 
			
			$session->set('message', $language->get('text_message'));

	  		$response->redirect($url->ssl('customer'));
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
      		'name'  => $language->get('column_lastname'),
      		'sort'  => 'lastname',
      		'align' => 'left'
    	);

    	$cols[] = array(
      		'name'  => $language->get('column_firstname'),
      		'sort'  => 'firstname',
      		'align' => 'left'
    	);

    	$cols[] = array(
      		'name'  => $language->get('column_status'),
      		'sort'  => 'status',
      		'align' => 'center'
    	);
    	
		$cols[] = array(
      		'name'  => $language->get('column_date_added'),
      		'sort'  => 'date_added',
      		'align' => 'left'
    	);

    	$cols[] = array(
      		'name'  => $language->get('column_action'),
      		'align' => 'right'
    	);
								
    	if (!$session->get('customer.search')) {
      		$sql = "select customer_id, lastname, firstname, status, date_added from customer";
		} else {
      		$sql = "select customer_id, lastname, firstname, status, date_added from customer where lastname like '?' or firstname like '?'";
    	}

		$sort = array(
      		'lastname', 
	  		'firstname',
			'status', 
	  		'date_added'
		);
	
		if (in_array($session->get('customer.sort'), $sort)) {
      		$sql .= " order by " . $session->get('customer.sort') . " " . (($session->get('customer.order') == 'desc') ? 'desc' : 'asc');
    	} else {
      		$sql .= " order by lastname, firstname asc";
    	}
  
    	$results = $database->getRows($database->splitQuery($database->parse($sql, '%' . $session->get('customer.search') . '%', '%' . $session->get('customer.search') . '%'), $session->get('customer.page'), $config->get('config_max_rows')));

    	$rows = array();

    	foreach ($results as $result) {
      		$cell = array();

      		$cell[] = array(
        		'value' => $result['lastname'],
        		'align' => 'left'
      		);

      		$cell[] = array(
        		'value' => $result['firstname'],
        		'align' => 'left'
      		);

      		$cell[] = array(
        		'icon'  => ($result['status'] ? 'enabled.png' : 'disabled.png'),
        		'align' => 'center'
      		);
      		
			$cell[] = array(
        		'value' => $language->formatDate($language->get('date_format_short'), strtotime($result['date_added'])),
        		'align' => 'left'
      		);

			$action = array();
      		
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $language->get('button_update'),
				'href' => $url->ssl('customer', 'update', array('customer_id' => $result['customer_id']))
      		);
						
			$action[] = array(
        		'icon' => 'delete.png',
				'text' => $language->get('button_delete'),
				'href' => $url->ssl('customer', 'delete', array('customer_id' => $result['customer_id']))
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
		
    	$view->set('action', $url->ssl('customer', 'page'));
  
    	$view->set('search', $session->get('customer.search'));
    	$view->set('sort', $session->get('customer.sort'));
    	$view->set('order', $session->get('customer.order'));
    	$view->set('page', $session->get('customer.page'));
  
    	$view->set('cols', $cols);
    	$view->set('rows', $rows);
  
		$view->set('list', $url->ssl('customer'));
   
    	$view->set('insert', $url->ssl('customer', 'insert'));
  
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

    	$view->set('entry_firstname', $language->get('entry_firstname'));
    	$view->set('entry_lastname', $language->get('entry_lastname'));
    	$view->set('entry_email', $language->get('entry_email'));
    	$view->set('entry_telephone', $language->get('entry_telephone'));
    	$view->set('entry_fax', $language->get('entry_fax'));
    	$view->set('entry_password', $language->get('entry_password'));
    	$view->set('entry_confirm', $language->get('entry_confirm'));
		$view->set('entry_newsletter', $language->get('entry_newsletter'));
    	$view->set('entry_status', $language->get('entry_status'));
  
    	$view->set('button_list', $language->get('button_list'));
    	$view->set('button_insert', $language->get('button_insert'));
    	$view->set('button_update', $language->get('button_update'));
    	$view->set('button_delete', $language->get('button_delete'));
    	$view->set('button_save', $language->get('button_save'));
    	$view->set('button_cancel', $language->get('button_cancel'));
	
		$view->set('tab_general', $language->get('tab_general'));
	  
    	$view->set('error', @$this->error['message']);
    	$view->set('error_firstname', @$this->error['firstname']);
    	$view->set('error_lastname', @$this->error['lastname']);
    	$view->set('error_email', @$this->error['email']);
    	$view->set('error_telephone', @$this->error['telephone']);
    	$view->set('error_password', @$this->error['password']);
    	$view->set('error_confirm', @$this->error['confirm']);
    
    	$view->set('action', $url->ssl('customer', $request->gethtml('action'), array('customer_id' => $request->gethtml('customer_id'))));
      
    	$view->set('list', $url->ssl('customer'));
 
    	$view->set('insert', $url->ssl('customer', 'insert'));
  
    	if ($request->gethtml('customer_id')) {
      		$view->set('update', $url->ssl('customer', 'update', array('customer_id' => $request->gethtml('customer_id'))));
	  		$view->set('delete', $url->ssl('customer', 'delete', array('customer_id' => $request->gethtml('customer_id'))));
    	}
	  
    	$view->set('cancel', $url->ssl('customer'));

    	if (($request->gethtml('customer_id')) && (!$request->isPost())) {
      		$customer_info = $database->getRow("select distinct * from customer where customer_id = '" . (int)$request->gethtml('customer_id') . "'");
    	}

    	if ($request->has('firstname', 'post')) {
      		$view->set('firstname', $request->gethtml('firstname', 'post'));
    	} else {
      		$view->set('firstname', @$customer_info['firstname']);
    	}

    	if ($request->has('lastname', 'post')) {
      		$view->set('lastname', $request->gethtml('lastname', 'post'));
    	} else {
      		$view->set('lastname', @$customer_info['lastname']);
    	}

    	if ($request->has('email', 'post')) {
      		$view->set('email', $request->gethtml('email', 'post'));
    	} else {
      		$view->set('email', @$customer_info['email']);
    	}

    	if ($request->has('telephone', 'post')) {
      		$view->set('telephone', $request->gethtml('telephone', 'post'));
    	} else {
      		$view->set('telephone', @$customer_info['telephone']);
    	}

    	if ($request->has('fax', 'post')) {
      		$view->set('fax', $request->gethtml('fax', 'post'));
    	} else {
      		$view->set('fax', @$customer_info['fax']);
    	}

    	if ($request->has('newsletter', 'post')) {
      		$view->set('newsletter', $request->gethtml('newsletter', 'post'));
    	} else {
      		$view->set('newsletter', @$customer_info['newsletter']);
    	}
		
    	if ($request->has('status', 'post')) {
      		$view->set('status', $request->gethtml('status', 'post'));
    	} else {
      		$view->set('status', @$customer_info['status']);
    	}

    	$view->set('password', $request->gethtml('password', 'post'));

    	$view->set('confirm', $request->gethtml('confirm', 'post'));

		return $view->fetch('content/customer.tpl');
	}  
	 
  	function validateForm() {
    	$request  =& $this->locator->get('request');
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');
		$validate =& $this->locator->get('validate');

    	if (!$user->hasPermission('modify', 'customer')) {
      		$this->error['message'] = $language->get('error_permission');
    	}

    	if (!$validate->strlen($request->gethtml('firstname', 'post'),1,32)) {
      		$this->error['firstname'] = $language->get('error_firstname');
    	}

    	if (!$validate->strlen($request->gethtml('lastname', 'post'),1,32)) {
      		$this->error['lastname'] = $language->get('error_lastname');
    	}

    	if ((!$validate->strlen($request->gethtml('email', 'post'), 1, 32)) || (!$validate->email($request->gethtml('email', 'post')))) {
      		$this->error['email'] = $language->get('error_email');
    	}

    	if (!$validate->strlen($request->gethtml('telephone', 'post'),1,32)) {
      		$this->error['telephone'] = $language->get('error_telephone');
    	}

    	if (($request->gethtml('password', 'post')) || ($request->gethtml('action') == 'insert')) {
      		if (!$validate->strlen($request->gethtml('password', 'post'),4,20)) {
        		$this->error['password'] = $language->get('error_password');
      		}
	
	  		if ($request->gethtml('password', 'post') != $request->gethtml('confirm', 'post')) {
	    		$this->error['confirm'] = $language->get('error_confirm');
	  		}
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
	
    	if (!$user->hasPermission('modify', 'customer')) {
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
	  		$session->set('customer.search', $request->gethtml('search', 'post'));
		}
	
		if (($request->has('page', 'post')) || ($request->has('search', 'post'))) {
	  		$session->set('customer.page', $request->gethtml('page', 'post'));
		} 
	
		if ($request->has('sort', 'post')) {
			$session->set('customer.order', (($session->get('customer.sort') == $request->gethtml('sort', 'post')) && ($session->get('customer.order') == 'asc') ? 'desc' : 'asc'));
		}

		if ($request->has('sort', 'post')) {
			$session->set('customer.sort', $request->gethtml('sort', 'post'));
		}
				
		$response->redirect($url->ssl('customer'));
  	} 	
}
?>
