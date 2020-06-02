<?php 
class ControllerDownload extends Controller {  
	var $error = array();
   
  	function index() {
    	$response =& $this->locator->get('response');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$module   =& $this->locator->get('module');
	     	 
		$language->load('controller/download.php');

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
		$upload   =& $this->locator->get('upload');
	
		$language->load('controller/download.php');
    
    	$template->set('title', $language->get('heading_title'));
			
		if ($request->isPost() && $upload->has('download') && $this->validateForm()) {
      		$sql = "insert into download set filename = '?', mask = '?', remaining = '?', date_added = now()";
      		$database->query($database->parse($sql, $upload->getName('download'), $request->gethtml('mask', 'post') ? $request->gethtml('mask', 'post') : $upload->getName('download'), $request->gethtml('remaining', 'post')));

      		$insert_id = $database->getLastId();

      		foreach ($request->gethtml('language', 'post') as $key => $value) {
        		$sql = "insert into download_description set download_id = '?', language_id = '?', name = '?'";
        		$database->query($database->parse($sql, $insert_id, $key, $value['name']));
      		}     
   	  		
			$session->set('message', $language->get('text_message'));
	  
	  		$response->redirect($url->ssl('download'));
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
		$upload   =& $this->locator->get('upload');
	
		$language->load('controller/download.php');

    	$template->set('title', $language->get('heading_title'));
			
    	if ($request->isPost() && $request->has('download_id') && $this->validateForm()) {
      		if ($upload->has('download')) {
        		$sql = "update download set filename = '?', mask = '?', remaining = '?' where download_id = '?'";
        		$database->query($database->parse($sql, $upload->getName('download'),  $request->gethtml('mask', 'post') ? $request->gethtml('mask', 'post') : $upload->getName('download'), $request->gethtml('remaining', 'post'), $request->gethtml('download_id')));
      		}
 
      		$database->query("delete from download_description where download_id = '" . (int)$request->gethtml('download_id') . "'");

      		foreach ($request->gethtml('language', 'post') as $key => $value) {
        		$sql = "insert into download_description set download_id = '?', language_id = '?', name = '?'";
        		$database->query($database->parse($sql, $request->gethtml('download_id'), $key, $value['name']));
      		}
	  		
			$session->set('message', $language->get('text_message'));
	  
	  		$response->redirect($url->ssl('download'));
    	}
    
    	$template->set('content', $this->getForm());
	
		$template->set($module->fetch());
	
    	$response->set($template->fetch('layout.tpl'));
  	}

  	function delete() {
		$request  =& $this->locator->get('request');
		$database =& $this->locator->get('database');
		$url      =& $this->locator->get('url');
		$language =& $this->locator->get('language');
		$response =& $this->locator->get('response');
		$template =& $this->locator->get('template');
		$session  =& $this->locator->get('session');
		$module   =& $this->locator->get('module');

		$language->load('controller/download.php');
 
    	$template->set('title', $language->get('heading_title'));
			
    	if (($request->gethtml('download_id')) && ($this->validateDelete())) {	  
      		$database->query("delete from download where download_id = '" . (int)$request->gethtml('download_id') . "'");
	  		$database->query("delete from download_description where download_id = '" . (int)$request->gethtml('download_id') . "'");
			
			$session->set('message', $language->get('text_message'));

      		$response->redirect($url->ssl('download'));
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
      		'sort'  => 'dd.name',
      		'align' => 'left'
    	);
  
    	$cols[] = array(
      		'name'  => $language->get('column_filename'),
      		'sort'  => 'd.filename',
      		'align' => 'left'
    	);

    	$cols[] = array(
      		'name'  => $language->get('column_mask'),
      		'sort'  => 'd.mask',
      		'align' => 'left'
    	);

    	$cols[] = array(
      		'name'  => $language->get('column_remaining'),
      		'sort'  => 'd.remaining',
      		'align' => 'right'
    	);
 
    	$cols[] = array(
      		'name'  => $language->get('column_action'),
      		'align' => 'right'
    	);
		       
    	if (!$session->get('download.search')) {
      		$sql = "select d.download_id, dd.name, d.filename, d.mask, d.remaining from download d left join download_description dd on d.download_id = dd.download_id where dd.language_id = '" . (int)$language->getId() . "'";
    	} else {
      		$sql = "select d.download_id, dd.name, d.filename, d.mask, d.remaining from download d left join download_description dd on d.download_id = dd.download_id where dd.language_id = '" . (int)$language->getId() . "' and dd.name like '?'";
    	}
    
		$sort = array(
	  		'dd.name', 
	  		'd.filename',
			'd.mask', 
	  		'd.max_days', 
	  		'd.remaining'
		);
	
    	if (in_array($session->get('download.sort'), $sort)) {
      		$sql .= " order by " . $session->get('download.sort') . " " . (($session->get('download.order') == 'desc') ? 'desc' : 'asc');
    	} else {
      		$sql .= " order by dd.name asc";
    	}
    
    	$results = $database->getRows($database->splitQuery($database->parse($sql, '%' . $session->get('download.search') . '%'), $session->get('download.page'), $config->get('config_max_rows')));

    	$rows = array();

    	foreach ($results as $result) {
      		$cell = array();
    	
      		$cell[] = array(
        		'value' => $result['name'],
        		'align' => 'left'
      		);
	
     		$cell[] = array(
        		'value' => $result['filename'],
        		'align' => 'left'
      		);

     		$cell[] = array(
        		'value' => $result['mask'],
        		'align' => 'left'
      		);

      		$cell[] = array(
        		'value' => $result['remaining'],
       			'align' => 'right'
      		);

			$action = array();
      		
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $language->get('button_update'),
				'href' => $url->ssl('download', 'update', array('download_id' => $result['download_id']))
      		);
						
			$action[] = array(
        		'icon' => 'delete.png',
				'text' => $language->get('button_delete'),
				'href' => $url->ssl('download', 'delete', array('download_id' => $result['download_id']))
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
		   
    	$view->set('action', $url->ssl('download', 'page'));

    	$view->set('search', $session->get('download.search'));
    	$view->set('sort', $session->get('download.sort'));
    	$view->set('order', $session->get('download.order'));
    	$view->set('page', $session->get('download.page'));

    	$view->set('cols', $cols);
    	$view->set('rows', $rows);

    	$view->set('list', $url->ssl('download'));
    
    	$view->set('insert', $url->ssl('download', 'insert'));

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
    	$view->set('entry_filename', $language->get('entry_filename'));
		$view->set('entry_mask', $language->get('entry_mask'));
    	$view->set('entry_remaining', $language->get('entry_remaining'));
  
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
    	$view->set('error_file', @$this->error['file']);
		$view->set('error_mask', @$this->error['mask']);
	  
    	$view->set('action', $url->ssl('download', $request->gethtml('action'), array('download_id' => $request->gethtml('download_id'))));

    	$view->set('list', $url->ssl('download'));
 
    	$view->set('insert', $url->ssl('download', 'insert'));
  
    	if ($request->gethtml('download_id')) {	  
      		$view->set('update', $url->ssl('download', 'update', array('download_id' => $request->gethtml('download_id'))));
	  		$view->set('delete', $url->ssl('download', 'delete', array('download_id' => $request->gethtml('download_id'))));
    	}
    
    	$view->set('cancel', $url->ssl('download'));
    	
		$download_data = array();
  		
    	$results = $database->cache('language', "select * from language order by sort_order");
  
    	foreach ($results as $result) {
		 	if (($request->gethtml('download_id')) && (!$request->isPost())) {
	    		$download_description_info = $database->getRow("select name from download_description where download_id = '" . (int)$request->gethtml('download_id') . "' and language_id = '" . (int)$result['language_id'] . "'");
	  		} else {
				$download_description_info = $request->gethtml('language', 'post');
			}
			
			$name = $request->gethtml('name', 'post');
	
	  		$download_data[] = array(
	    		'language_id' => $result['language_id'],
	    		'language'    => $result['name'],
	    		'name'        => (isset($download_description_info[$result['language_id']]) ? $download_description_info[$result['language_id']]['name'] : @$download_description_info['name']),
	  		);
    	}

    	$view->set('downloads', $download_data);

    	if (($request->gethtml('download_id')) && (!$request->isPost())) {
      		$download_info = $database->getRow("select distinct * from download where download_id = '" . (int)$request->gethtml('download_id') . "'");
    	}
  
    	if ($request->has('mask', 'post')) {
      		$view->set('mask', $request->gethtml('mask', 'post'));
    	} else {
      		$view->set('mask', @$download_info['mask']);
    	}

		if ($request->has('remaining', 'post')) {
      		$view->set('remaining', $request->gethtml('remaining', 'post'));
    	} elseif (@$download_info['remaining']) {
      		$view->set('remaining', $download_info['remaining']);
    	} else {
      		$view->set('remaining', 1);
    	}
 
 		return $view->fetch('content/download.tpl');
  	}

  	function validateForm() { 
		$request  =& $this->locator->get('request');
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');
		$upload   =& $this->locator->get('upload');
        $validate =& $this->locator->get('validate');
    
    	if (!$user->hasPermission('modify', 'download')) {
      		$this->error['message'] = $language->get('error_permission');
    	}
	
    	if ($upload->has('download')) {
	  		if (!$upload->save('download', DIR_DOWNLOAD . $upload->getName('download'))) {
	    		$this->error['file'] = $language->get('error_upload');
	  		}
	  
            if (!$validate->strlen($upload->getName('download'),1,128)) {
        		$this->error['file'] = $language->get('error_filename');
	  		}
	    	
			if (substr(strrchr($upload->getName('filename'), '.'), 1) == 'php') {
          		$this->error['file'] = $language->get('error_filetype');
        	}
			
			if ($upload->hasError('download')) {
				$this->error['message'] = $upload->getError('download');
			}
		}

    	foreach ($request->gethtml('language', 'post') as $value) {
      		if (!$validate->strlen($value['name'],1,64)) {
        		$this->error['name'] = $language->get('error_name');
      		}
    	}
        
		/*if (!$validate->strlen($request->gethtml('mask', 'post'),1,128)) {
			$this->error['mask'] = $language->get('error_mask');
		}*/
		
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}

  	function validateDelete() {
		$request  =& $this->locator->get('request');
		$database =& $this->locator->get('database');
		$user     =& $this->locator->get('user');
		$language =& $this->locator->get('language');
		
    	if (!$user->hasPermission('modify', 'download')) {
      		$this->error['message'] = $language->get('error_permission');
    	}	
	
		$product_info = $database->getRow("select count(*) as total from product_to_download where download_id = '" . (int)$request->gethtml('download_id') . "'");
    
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
			$session->set('download.search', $request->gethtml('search', 'post'));
		}

		if (($request->has('page', 'post')) || ($request->has('search', 'post'))) {
			$session->set('download.page', $request->gethtml('page', 'post'));
		}

		if ($request->has('sort', 'post')) {
			$session->set('download.order', (($session->get('download.sort') == $request->gethtml('sort', 'post')) && ($session->get('download.order') == 'asc')) ? 'desc' : 'asc');
		}

		if ($request->has('sort', 'post')) {
			$session->set('download.sort', $request->gethtml('sort', 'post'));
		}

		$response->redirect($url->ssl('download'));
	}	    
}
?>