<?php 
class ControllerOrderStatus extends Controller {
	var $error = array();
   
  	function index() {
    	$response =& $this->locator->get('response');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$module   =& $this->locator->get('module');

		$language->load('controller/order_status.php');
	
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
	
		$language->load('controller/order_status.php');
	
    	$template->set('title', $language->get('heading_title'));
			
		if ($request->isPost() && $request->has('language', 'post') && $this->validateForm()) {
      		foreach ($request->gethtml('language', 'post') as $key => $value) {
	  			$sql = "insert into order_status set order_status_id = '?', language_id = '?', name = '?'";
        		$database->query($database->parse($sql, @$insert_id, $key, $value['name']));

        		$insert_id = $database->getLastId();
      		}
			
			$cache->delete('order_status');
		  	
			$session->set('message', $language->get('text_message'));
			
      		$response->redirect($url->ssl('order_status'));
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

		$language->load('controller/order_status.php');
	
    	$template->set('title', $language->get('heading_title'));
	
    	if ($request->isPost() && $request->has('language', 'post') && $this->validateForm()) {
			$database->query("delete from order_status where order_status_id = '" . (int)$request->gethtml('order_status_id') . "'");

      		foreach ($request->gethtml('language', 'post') as $key => $value) {
	  			$sql = "insert into order_status set order_status_id = '?', language_id = '?', name = '?'";
        		$database->query($database->parse($sql, $request->gethtml('order_status_id'), $key, $value['name']));
      		} 
	  
	  		$cache->delete('order_status');
	  		
			$session->set('message', $language->get('text_message'));
	  
	  		$response->redirect($url->ssl('order_status'));
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
 
		$language->load('controller/order_status.php');
	
    	$template->set('title', $language->get('heading_title'));
			
    	if (($request->gethtml('order_status_id')) && ($this->validateDelete())) {
      		$database->query("delete from order_status where order_status_id = '" . (int)$request->gethtml('order_status_id') . "'");
			
			$cache->delete('order_status');
      		
			$session->set('message', $language->get('text_message'));
			
			$response->redirect($url->ssl('order_status'));
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

    	$cols[] = array(
      		'name'  => $language->get('column_action'),
      		'align' => 'right'
    	);
		
    	if (!$session->get('order_status.search')) {
      		$sql = "select order_status_id, name from order_status where language_id = '" . (int)$language->getId() . "'";
    	} else {
      		$sql = "select order_status_id, name from order_status where language_id = '" . (int)$language->getId() . "' and name like '?'";
    	}

    	if (in_array($session->get('order_status.sort'), array('name'))) {
      		$sql .= " order by " . $session->get('order_status.sort') . " " . (($session->get('order_status.order') == 'desc') ? 'desc' : 'asc');
    	} else {
      		$sql .= " order by name asc";
    	} 
      
    	$results = $database->getRows($database->splitQuery($database->parse($sql, '%' . $session->get('order_status.search') . '%'), $session->get('order_status.page'), $config->get('config_max_rows')));

    	$rows = array();

    	foreach ($results as $result) {
      		$cell = array();

      		$cell[] = array(
        		'value'   => $result['name'],
        		'align'   => 'left',
        		'default' => ($result['order_status_id'] == $config->get('config_order_status_id'))
      		);

			$action = array();
      		
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $language->get('button_update'),
				'href' => $url->ssl('order_status', 'update', array('order_status_id' => $result['order_status_id']))
      		);
						
			$action[] = array(
        		'icon' => 'delete.png',
				'text' => $language->get('button_delete'),
				'href' => $url->ssl('order_status', 'delete', array('order_status_id' => $result['order_status_id']))
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
		  
    	$view->set('action', $url->ssl('order_status', 'page'));

    	$view->set('search', $session->get('order_status.search'));
    	$view->set('sort', $session->get('order_status.sort'));
    	$view->set('order', $session->get('order_status.order'));
    	$view->set('page', $session->get('order_status.page'));
  
    	$view->set('cols', $cols);
    	$view->set('rows', $rows);
  
    	$view->set('list', $url->ssl('order_status'));
    
    	$view->set('insert', $url->ssl('order_status', 'insert'));
  
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

    	$view->set('button_list', $language->get('button_list'));
    	$view->set('button_insert', $language->get('button_insert'));
    	$view->set('button_update', $language->get('button_update'));
    	$view->set('button_delete', $language->get('button_delete'));
    	$view->set('button_save', $language->get('button_save'));
    	$view->set('button_cancel', $language->get('button_cancel'));

    	$view->set('tab_general', $language->get('tab_general'));
    
		$view->set('error', @$this->error['message']);
    	$view->set('error_name', @$this->error['name']);
	
    	$view->set('action', $url->ssl('order_status', $request->gethtml('action'), array('order_status_id' => $request->gethtml('order_status_id'))));
      
    	$view->set('list', $url->ssl('order_status'));
 
    	$view->set('insert', $url->ssl('order_status', 'insert'));
  
    	if ($request->gethtml('order_status_id')) {  
			$view->set('update', $url->ssl('order_status', 'update', array('order_status_id' => $request->gethtml('order_status_id'))));
			$view->set('delete', $url->ssl('order_status', 'delete', array('order_status_id' => $request->gethtml('order_status_id'))));
    	}
  
    	$view->set('cancel', $url->ssl('order_status'));

		$post_info = $request->gethtml('post');

    	$order_status_data = array();
  
    	$results = $database->cache('language', "select * from language order by sort_order");
  
        foreach ($results as $result) {
			if (($request->gethtml('order_status_id')) && (!$request->isPost())) {
				$order_status_description_info = $database->getRow("select name from order_status where order_status_id = '" . (int)$request->gethtml('order_status_id') . "' and language_id = '" . (int)$result['language_id'] . "'");
			} else {
				$order_status_description_info = $request->gethtml('language', 'post');
			}

			$order_status_data[] = array(
				'language_id' => $result['language_id'],
				'language'    => $result['name'],
				'name'        => (isset($order_status_description_info[$result['language_id']]) ? $order_status_description_info[$result['language_id']]['name'] : @$order_status_description_info['name']),
			);
        }

    	$view->set('order_statuses', $order_status_data);  

 		return $view->fetch('content/order_status.tpl');	
  	}
  	
	function validateForm() {
    	$request  =& $this->locator->get('request');
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');
        $validate =& $this->locator->get('validate');
        
    	if (!$user->hasPermission('modify', 'order_status')) {
      		$this->error['message'] = $language->get('error_permission');
    	}
	
    	foreach ($request->gethtml('language', 'post') as $value) {
      		if (!$validate->strlen($value['name'],1,32)) {
        		$this->error['name'] = $language->get('error_name');
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
	
		if (!$user->hasPermission('modify', 'order_status')) {
      		$this->error['message'] = $language->get('error_permission');
    	}

    	if ($config->get('config_order_status_id') == $request->gethtml('order_status_id')) {
	  		$this->error['message'] = $language->get('error_default');	
		}  
	
  		$order_info = $database->getRow("select count(*) as total from order_history where order_status_id = '" . (int)$request->gethtml('order_status_id') . "' group by order_id");

		if ($order_info['total']) {
	  		$this->error['message'] = $language->get('error_order', $order_info['total']);	
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
	  		$session->set('order_status.search', $request->gethtml('search', 'post'));
		}
	
		if (($request->has('page', 'post')) || ($request->has('search', 'post'))) {
	  		$session->set('order_status.page', $request->gethtml('page', 'post'));
		} 
	
		if ($request->has('sort', 'post')) {
	  		$session->set('order_status.order', (($session->get('order_status.sort') == $request->gethtml('sort', 'post')) && ($session->get('order_status.order') == 'asc') ? 'desc' : 'asc'));
		}
		
		if ($request->has('sort', 'post')) {
			$session->set('order_status.sort', $request->gethtml('sort', 'post'));
		}
				
		$response->redirect($url->ssl('order_status'));
  	} 	  
}
?>