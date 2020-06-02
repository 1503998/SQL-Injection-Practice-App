<?php  
class ControllerOptionValue extends Controller { 
	var $error = array();
   
  	function index() {
    	$response =& $this->locator->get('response');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$module   =& $this->locator->get('module');

		$language->load('controller/option_value.php');
	
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

		$language->load('controller/option_value.php');
	
    	$template->set('title', $language->get('heading_title'));
				
		if ($request->isPost() && $request->has('language', 'post') && $this->validateForm()) {
      		foreach ($request->gethtml('language', 'post') as $key => $value) {
	    		$sql = "insert into option_value set option_value_id = '?', option_id = '?', language_id = '?', name = '?'";
        		$database->query($database->parse($sql, @$insert_id, $request->gethtml('option_id'), $key, $value['name']));
        
				$insert_id = $database->getLastId();
      		}
      	  	
			$session->set('message', $language->get('text_message'));
		  
	  		$response->redirect($url->ssl('option_value', FALSE, array('option_id' => $request->gethtml('option_id'))));
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

		$language->load('controller/option_value.php');
	
    	$template->set('title', $language->get('heading_title'));
			
    	if ($request->isPost() && $request->has('language', 'post') && $this->validateForm()) {
      		$database->query("delete from `option_value` where option_value_id = '" . (int)$request->gethtml('option_value_id') . "'");
      
      		foreach ($request->gethtml('language', 'post') as $key => $value) {
	  			$sql = "insert into option_value set option_value_id = '?', option_id = '?', language_id = '?', name = '?'";
        		$database->query($database->parse($sql, $request->gethtml('option_value_id'), $request->gethtml('option_id'), $key, $value['name']));
      		}
			
			$session->set('message', $language->get('text_message'));

	  		$response->redirect($url->ssl('option_value', FALSE, array('option_id' => $request->gethtml('option_id'))));
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

		$language->load('controller/option_value.php');
	
    	$template->set('title', $language->get('heading_title'));
			
    	if (($request->gethtml('option_value_id')) && ($this->validateDelete())) {  
      		$database->query("delete from `option_value` where option_value_id = '" . (int)$request->gethtml('option_value_id') . "' and option_id = '" . (int)$request->gethtml('option_id') . "'");
	  		$database->query("delete from `product_to_option` where option_id = '" . (int)$request->gethtml('option_id') . "' and option_value_id = '" . (int)$request->gethtml('option_value_id') . "'");
			
			$session->set('message', $language->get('text_message'));

	 	 	$response->redirect($url->ssl('option_value', FALSE, array('option_id' => $request->gethtml('option_id'))));
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
		
    	if (!$session->get('option_value.search')) {
      		$sql = "select option_value_id, name from option_value where language_id = '" . (int)$language->getId() . "' and option_id = '" . (int)$request->gethtml('option_id') . "'";
    	} else {
      		$sql = "select option_value_id, name from option_value where language_id = '" . (int)$language->getId() . "' and option_id = '" . (int)$request->gethtml('option_id') . "' and name like '?'";
    	}

    	if (in_array($session->get('option_value.sort'), array('name'))) {
      		$sql .= " order by " . $session->get('option_value.sort') . " " . (($session->get('option_value.order') == 'desc') ? 'desc' : 'asc');
    	} else {
      		$sql .= " order by name asc";
    	}
  
    	$results = $database->getRows($database->splitQuery($database->parse($sql, '%' . $session->get('option_value.search') . '%'), $session->get('option_value.' . $request->gethtml('option_id') . '.page'), $config->get('config_max_rows')));

    	$rows = array();

    	foreach ($results as $result) {
      		$cell = array();

      		$cell[] = array(
        		'value' => $result['name'],
        		'align' => 'left'
      		);
      
	  		$query = array(
	    		'option_value_id' => $result['option_value_id'],
	    		'option_id'       => $request->gethtml('option_id')
	  		);

			$action = array();
      		
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $language->get('button_update'),
				'href' => $url->ssl('option_value', 'update', $query)
      		);
						
			$action[] = array(
        		'icon' => 'delete.png',
				'text' => $language->get('button_delete'),
				'href' => $url->ssl('option_value', 'delete', $query)
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
		   
    	$view->set('action', $url->ssl('option_value', 'page', array('option_id' => $request->gethtml('option_id'))));
  
    	$view->set('previous', $url->ssl('option', FALSE, array('option_id' => $request->gethtml('option_id'))));
  
    	$view->set('search', $session->get('option_value.search'));
    	$view->set('sort', $session->get('option_value.sort'));
    	$view->set('order', $session->get('option_value.order'));
		$view->set('page', $session->get('option_value.' . $request->gethtml('option_id') . '.page'));
		      
    	$view->set('cols', $cols);
    	$view->set('rows', $rows);

    	$view->set('list', $url->ssl('option_value', FALSE, array('option_id' => $request->gethtml('option_id'))));
    
    	$view->set('insert', $url->ssl('option_value', 'insert', array('option_id' => $request->gethtml('option_id'))));
  
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

    	$query = array(
	  		'option_value_id' => $request->gethtml('option_value_id'),
	  		'option_id'       => $request->gethtml('option_id')
		);
    
		$view->set('action', $url->ssl('option_value', $request->gethtml('action'), $query));
      
    	$view->set('list', $url->ssl('option_value', FALSE, array('option_id' => $request->gethtml('option_id'))));
 
    	$view->set('insert', $url->ssl('option_value', 'insert', array('option_id' => $request->gethtml('option_id'))));
  
    	if ($request->gethtml('option_value_id')) {
      		$query = array(
	    		'option_value_id' => $request->gethtml('option_value_id'),
	    		'option_id'       => $request->gethtml('option_id')
	  		);
  
      		$view->set('update', $url->ssl('option_value', 'update', $query));
	  		$view->set('delete', $url->ssl('option_value', 'delete', $query));
    	}
	      
    	$view->set('cancel', $url->ssl('option_value', FALSE, array('option_id' => $request->gethtml('option_id'))));
				
    	$option_value_data = array();
  
    	$results = $database->cache('language', "select * from language order by sort_order");
  
    	foreach ($results as $result) {
	  		if (($request->gethtml('option_value_id')) && (!$request->isPost())) {
	    		$option_value_description_info = $database->getRow("select name from option_value where option_value_id = '" . (int)$request->gethtml('option_value_id') . "' and language_id = '" . (int)$result['language_id'] . "'");
	  		} else {
				$option_value_description_info = $request->gethtml('language', 'post');
			}
			
	  		$option_value_data[] = array(
	    		'language_id' => $result['language_id'],
	    		'language'    => $result['name'],
	    		'name'        => (isset($option_value_description_info[$result['language_id']]) ? $option_value_description_info[$result['language_id']]['name'] : @$option_value_description_info['name']),
	  		);
    	}

    	$view->set('option_values', $option_value_data);  
  
 		return $view->fetch('content/option_value.tpl');
  	}

  	function validateForm() {
		$request  =& $this->locator->get('request');
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');
        $validate =& $this->locator->get('validate');

    	if (!$user->hasPermission('modify', 'option_value')) {
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
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');
	
		if (!$user->hasPermission('modify', 'option_value')) {
      		$this->error['message'] = $language->get('error_permission');
    	}	

  		$product_info = $database->getRow("select count(*) as total from product_to_option where option_value_id = '" . (int)$request->gethtml('option_value_id') . "'");
    
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
	  		$session->set('option_value.search', $request->gethtml('search', 'post'));
		}
		
		if ($request->has('page', 'post')) {
			$session->set('option_value.' . $request->gethtml('option_id') . '.page', $request->gethtml('page', 'post'));
		}
			
		if ($request->has('sort', 'post')) {
	  		$session->set('option_value.order', (($session->get('option_value.sort') == $request->gethtml('sort', 'post')) && ($session->get('option_value.order') == 'asc') ? 'desc' : 'asc'));
		}
		
		if ($request->has('sort', 'post')) {
			$session->set('option_value.sort', $request->gethtml('sort', 'post'));
		}
				
		$response->redirect($url->ssl('option_value', FALSE, array('option_id' => $request->gethtml('option_id'))));
  	} 	  
}
?>