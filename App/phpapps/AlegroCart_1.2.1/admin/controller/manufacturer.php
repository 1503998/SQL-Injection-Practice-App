<?php   //Admin Manufacturer AlegroCart 1.0
class ControllerManufacturer extends Controller { 
	var $error = array();
  
  	function index() {
    	$response =& $this->locator->get('response');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$module   =& $this->locator->get('module');
			    	
		$language->load('controller/manufacturer.php');
		
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
		$config   =& $this->locator->get('config');

		$language->load('controller/manufacturer.php');

    	$template->set('title', $language->get('heading_title'));
			
		if ($request->isPost() && $request->has('name', 'post') && $this->validateForm()) {
			$url_alias = $config->get('config_url_alias');
			$url_seo = $config->get('config_seo');
      		$sql = "insert into manufacturer set name = '?', image_id = '?', sort_order = '?'";
      		$database->query($database->parse($sql, $request->gethtml('name', 'post'), $request->gethtml('image_id', 'post'), $request->gethtml('sort_order', 'post')));
      	  	
			$manufacturer_id = $database->getLastId();
			if($url_alias && $url_seo){
				$this->manufacturer_seo($manufacturer_id,$request->gethtml('name', 'post'));
			}
			
			$cache->delete('manufacturer');
			
			$session->set('message', $language->get('text_message'));
			
	  		$response->redirect($url->ssl('manufacturer'));
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
		$config   =& $this->locator->get('config');

		$language->load('controller/manufacturer.php');

    	$template->set('title', $language->get('heading_title'));
		
    	if ($request->isPost() && $request->has('name', 'post') && $this->validateForm()) {
			$url_alias = $config->get('config_url_alias');
			$url_seo = $config->get('config_seo');
      		$sql = "update manufacturer set name = '?', image_id = '?', sort_order = '?' where manufacturer_id = '?'";
      		$database->query($database->parse($sql, $request->gethtml('name', 'post'), $request->gethtml('image_id', 'post'), $request->gethtml('sort_order', 'post'), $request->gethtml('manufacturer_id')));
			if($url_alias && $url_seo){
				$this->delete_manufacturer_seo($request->gethtml('manufacturer_id'));
				$this->manufacturer_seo($request->gethtml('manufacturer_id'),$request->gethtml('name', 'post'));
			}
			$cache->delete('manufacturer');
			
			$session->set('message', $language->get('text_message'));

	  		$response->redirect($url->ssl('manufacturer'));
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
		$config   =& $this->locator->get('config');

		$language->load('controller/manufacturer.php');

    	$template->set('title', $language->get('heading_title'));
			
    	if (($request->gethtml('manufacturer_id')) && ($this->validateDelete())) {
			$url_alias = $config->get('config_url_alias');
			$url_seo = $config->get('config_seo');
      		$database->query("delete from manufacturer where manufacturer_id = '" . (int)$request->gethtml('manufacturer_id') . "'");
			if($url_alias && $url_seo){
				$this->delete_manufacturer_seo($request->gethtml('manufacturer_id'));
			}
			$cache->delete('manufacturer');
			
			$session->set('message', $language->get('text_message'));
			
	  		$response->redirect($url->ssl('manufacturer'));
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
      		'name'  => $language->get('column_sort_order'),
      		'sort'  => 'sort_order',
      		'align' => 'right'
    	);

    	$cols[] = array(
      		'name'  => $language->get('column_action'),
      		'align' => 'right'
    	);
		
    	if (!$session->get('manufacturer.search')) {
      		$sql = "select manufacturer_id, name, sort_order from manufacturer";
		} else {
      		$sql = "select manufacturer_id, name, sort_order from manufacturer where name like '?'";
    	}

		$sort = array(
      		'name', 
	  		'sort_order'
		);
	
		if (in_array($session->get('manufacturer.sort'), $sort)) {
      		$sql .= " order by " . $session->get('manufacturer.sort') . " " . (($session->get('manufacturer.order') == 'desc') ? 'desc' : 'asc');
    	} else {
      		$sql .= " order by sort_order asc, name asc";
    	}
  
    	$results = $database->getRows($database->splitQuery($database->parse($sql, '%' . $session->get('manufacturer.search') . '%', '%' . $session->get('manufacturer.search') . '%'), $session->get('manufacturer.page'), $config->get('config_max_rows')));

    	$rows = array();

    	foreach ($results as $result) {
      		$cell = array();

      		$cell[] = array(
        		'value' => $result['name'],
        		'align' => 'left'
      		);

      		$cell[] = array(
        		'value' => $result['sort_order'],
        		'align' => 'right'
      		);
			
			$action = array();
      		
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $language->get('button_update'),
				'href' => $url->ssl('manufacturer', 'update', array('manufacturer_id' => $result['manufacturer_id']))
      		);
						
			$action[] = array(
        		'icon' => 'delete.png',
				'text' => $language->get('button_delete'),
				'href' => $url->ssl('manufacturer', 'delete', array('manufacturer_id' => $result['manufacturer_id']))
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
		
    	$view->set('action', $url->ssl('manufacturer', 'page'));
  
    	$view->set('search', $session->get('manufacturer.search'));
    	$view->set('sort', $session->get('manufacturer.sort'));
    	$view->set('order', $session->get('manufacturer.order'));
    	$view->set('page', $session->get('manufacturer.page'));
  
    	$view->set('cols', $cols);
    	$view->set('rows', $rows);
  
		$view->set('list', $url->ssl('manufacturer'));
   
    	$view->set('insert', $url->ssl('manufacturer', 'insert'));
  
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
    	$view->set('entry_image', $language->get('entry_image'));
		$view->set('entry_sort_order', $language->get('entry_sort_order'));
  
    	$view->set('button_list', $language->get('button_list'));
    	$view->set('button_insert', $language->get('button_insert'));
    	$view->set('button_update', $language->get('button_update'));
    	$view->set('button_delete', $language->get('button_delete'));
    	$view->set('button_save', $language->get('button_save'));
    	$view->set('button_cancel', $language->get('button_cancel'));
	
		$view->set('tab_general', $language->get('tab_general'));
	  
    	$view->set('error', @$this->error['message']);
    	$view->set('error_name', @$this->error['name']);
    
    	$view->set('action', $url->ssl('manufacturer', $request->gethtml('action'), array('manufacturer_id' => $request->gethtml('manufacturer_id'))));
      
    	$view->set('list', $url->ssl('manufacturer'));
 
    	$view->set('insert', $url->ssl('manufacturer', 'insert'));
  
    	if ($request->gethtml('manufacturer_id')) {
      		$view->set('update', $url->ssl('manufacturer', 'update', array('manufacturer_id' => $request->gethtml('manufacturer_id'))));
	  		$view->set('delete', $url->ssl('manufacturer', 'delete', array('manufacturer_id' => $request->gethtml('manufacturer_id'))));
    	}
	  
    	$view->set('cancel', $url->ssl('manufacturer'));

    	if (($request->gethtml('manufacturer_id')) && (!$request->isPost())) {
      		$manufacturer_info = $database->getRow("select distinct * from manufacturer where manufacturer_id = '" . (int)$request->gethtml('manufacturer_id') . "'");
    	}

    	if ($request->has('name', 'post')) {
      		$view->set('name', $request->gethtml('name', 'post'));
    	} else {
      		$view->set('name', @$manufacturer_info['name']);
    	}

    	$image_data = array();

    	$results = $database->cache('image', "select i.image_id, i.filename, id.title from image i left join image_description id on (i.image_id = id.image_id) where id.language_id = '" . (int)$language->getId() . "' order by id.title");

    	foreach ($results as $result) {
      		$image_data[] = array(
        		'image_id'        => $result['image_id'],
        		'title'           => $result['title']
      		);
    	}

    	$view->set('images', $image_data);

    	if ($request->has('image_id', 'post')) {
      		$view->set('image_id', $request->gethtml('image_id', 'post'));
    	} else {
      		$view->set('image_id', @$manufacturer_info['image_id']);
    	}
						
    	if ($request->has('sort_order', 'post')) {
      		$view->set('sort_order', $request->gethtml('sort_order', 'post'));
    	} else {
      		$view->set('sort_order', @$manufacturer_info['sort_order']);
    	}

		return $view->fetch('content/manufacturer.tpl');
	}  
	 
  	function validateForm() {
    	$request  =& $this->locator->get('request');
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');
        $validate =& $this->locator->get('validate');

    	if (!$user->hasPermission('modify', 'manufacturer')) {
      		$this->error['message'] = $language->get('error_permission');
    	}
        
        if (!$validate->strlen($request->gethtml('name', 'post'),1,64)) {
      		$this->error['name'] = $language->get('error_name');
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
	
    	if (!$user->hasPermission('modify', 'manufacturer')) {
      		$this->error['message'] = $language->get('error_permission');
    	}	

  		$product_info = $database->getRow("select count(*) as total from product where manufacturer_id = '" . (int)$request->gethtml('manufacturer_id') . "'");
    
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
	  		$session->set('manufacturer.search', $request->gethtml('search', 'post'));
		}
	
		if (($request->has('page', 'post')) || ($request->has('search', 'post'))) {
	  		$session->set('manufacturer.page', $request->gethtml('page', 'post'));
		} 
	
		if ($request->has('sort', 'post')) {
			$session->set('manufacturer.order', (($session->get('manufacturer.sort') == $request->gethtml('sort', 'post')) && ($session->get('manufacturer.order') == 'asc') ? 'desc' : 'asc'));
		}

		if ($request->has('sort', 'post')) {
			$session->set('manufacturer.sort', $request->gethtml('sort', 'post'));
		}
				
		$response->redirect($url->ssl('manufacturer'));
  	}
	function manufacturer_seo($manufacturer_id,$manufacturer_name){
		$generate_seo 	=& $this->locator->get('generateseo');
		$database 		=& $this->locator->get('database');
		$query_path = 'controller=manufacturer&manufacturer_id=' . $manufacturer_id;
		$alias = '';
		$alias .= $generate_seo->clean_alias($manufacturer_name);
		$alias .= '.html';
		$generate_seo->_insert_url_alias($query_path, $alias);
	}
	function delete_manufacturer_seo($manufacturer_id){
		$database 		=& $this->locator->get('database');
		$query_path = 'controller=manufacturer&manufacturer_id=' . $manufacturer_id;
		$database->query("delete from url_alias where query = '".$query_path."'");
	}
}
?>
