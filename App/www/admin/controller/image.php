<?php 

class ControllerImage extends Controller {
	var $html='<img src="%s" alt="%s" title="%s" width="%s" height="%s">';
	var $size=100;
	var $types=array('jpg','gif','jpeg','png');
  	var $error = array(); 
   
  	function index() {
    	$response =& $this->locator->get('response');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$module   =& $this->locator->get('module');
		
		$language->load('controller/image.php');
	
    	$template->set('title', $language->get('heading_title'));
			
    	$template->set('content', $this->getList());
	
		$template->set($module->fetch());
	
    	$response->set($template->fetch('layout.tpl'));
  	}

	function checkFiles() {
		$request	=& $this->locator->get('request');
		$database	=& $this->locator->get('database');
		$cache    	=& $this->locator->get('cache');

		$files=glob(DIR_IMAGE.'*.*');
		if (!$files) { return; }
		foreach ($files as $file) {
			$pattern='/\.('.implode('|',$this->types).')$/';
			if (preg_match($pattern,$file)) {
				$filename=basename($file);
				$result = $database->getRow("select * from image where filename = '".$filename."'");
				if (!$result) { $this->init($filename); }
			}
		}
		$cache->delete('image');
	}

	function init($filename) {
		$request	=& $this->locator->get('request');
		$database	=& $this->locator->get('database');
		$language	=& $this->locator->get('language');
		$config		=& $this->locator->get('config');

		$sql = "insert into image set filename = '?', date_added = now()";
		$sql = $database->parse($sql, $filename);
		$database->query($sql);

		$insert_id = $database->getLastId();
		$title = $this->getTitle($filename);
		
		$key = $language->languages[$config->get('config_language')]['language_id'];
		$sql = "insert into image_description set image_id = '?', language_id = '?', title = '?'";
		$sql = $database->parse($sql, $insert_id, $key, $title);
		$database->query($sql);
	}

	function getTitle($file) {
		$str=$file;
		$str=pathinfo_filename($file);
		$str=str_replace(array('_','-'),' ',$str);
		$str=ucwords($str);
		return $str;
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
		$upload   =& $this->locator->get('upload');
	
		$language->load('controller/image.php');
	
    	$template->set('title', $language->get('heading_title'));
    
		if ($request->isPost() && $upload->has('image') && $this->validateForm() ) {
			if ($upload->save('image', DIR_IMAGE . $upload->getName('image'))) {
				$sql = "insert into image set filename = '?', date_added = now()";
				$database->query($database->parse($sql, $upload->getName('image')));

      		    $insert_id = $database->getLastId();

                foreach ($request->gethtml('language', 'post') as $key => $value) {
				    if (empty($value['title'])) { $value['title']=$this->getTitle($upload->getName('image')); }
        		    $sql = "insert into image_description set image_id = '?', language_id = '?', title = '?'";
        		    $database->query($database->parse($sql, $insert_id, $key, $value['title']));
      		    }
          
	  		    $cache->delete('image');
	  	    
			    $session->set('message', $language->get('text_message'));
			    
	  		    $response->redirect($url->ssl('image'));
            }
            $this->error['file'] = $language->get('error_upload');
		}
    
    	$template->set('content', $this->getForm());
	
		$template->set($module->fetch());
	
    	$response->set($template->fetch('layout.tpl'));
  	}

  	function update() {
    	$request   =& $this->locator->get('request');
		$response  =& $this->locator->get('response');
		$database  =& $this->locator->get('database');
		$url       =& $this->locator->get('url');
		$language  =& $this->locator->get('language');
		$template  =& $this->locator->get('template');
		$session   =& $this->locator->get('session');
		$module    =& $this->locator->get('module');
		$cache     =& $this->locator->get('cache');
    	$upload    =& $this->locator->get('upload');
		
		$language->load('controller/image.php');
	
		$template->set('title', $language->get('heading_title'));
    
		if ($request->isPost() && $request->has('image_id') && $this->validateForm() ) {
      		if ($upload->has('image') && ($upload->save('image', DIR_IMAGE . $upload->getName('image')))) {
					$sql = "update image set filename = '?' where image_id = '?'";
					$database->query($database->parse($sql, $upload->getName('image'), $request->gethtml('image_id')));
      		}

      		$database->query("delete from image_description where image_id = '" . (int)$request->gethtml('image_id') . "'");

      		foreach ($request->gethtml('language', 'post') as $key => $value) {
        		$sql = "insert into image_description set image_id = '?', language_id = '?', title = '?'";
        		$database->query($database->parse($sql, $request->gethtml('image_id'), $key, $value['title']));
      		} 
      
	  		$cache->delete('image');
	  		
			$session->set('message', $language->get('text_message'));
	  
	  		$response->redirect($url->ssl('image'));
    	}
    
		$template->set('content', $this->getForm());
	
		$template->set($module->fetch());
	
		$response->set($template->fetch('layout.tpl'));
  	}

  	function delete () {
    	$request	=& $this->locator->get('request');
		$response	=& $this->locator->get('response');
		$database	=& $this->locator->get('database');
		$url		=& $this->locator->get('url');
		$language	=& $this->locator->get('language');
		$template	=& $this->locator->get('template');
		$session	=& $this->locator->get('session');
		$module		=& $this->locator->get('module');
		$cache		=& $this->locator->get('cache');
		$image		=& $this->locator->get('image');
 
		$language->load('controller/image.php');
		
		$template->set('title', $language->get('heading_title'));
		 
		if (($request->gethtml('image_id')) && ($this->validateDelete())) {
      		$result = $database->getRows("select * from image where image_id = '" . (int)$request->gethtml('image_id') . "'");
			$result = array_shift($result);
			// Only delete the actual file if there's 1 database entry remaining
			$filename = $result['filename'];
			$rows = $database->getRows("select filename from image where filename='$filename'");
			if (count($rows) <= 1) {
				$image->delete($filename);
			}

			$database->query("delete from image where image_id = '" . (int)$request->gethtml('image_id') . "'");
      		$database->query("delete from image_description where image_id = '" . (int)$request->gethtml('image_id') . "'");
      
	  		$cache->delete('image');
	  		
			$session->set('message', $language->get('text_message'));
	  
		  	$response->redirect($url->ssl('image'));
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
		$image    =& $this->locator->get('image');
 
		$this->checkFiles();

 		$cols = array();

		$cols[] = array(
			'name'  => $language->get('column_title'),
			'sort'  => 'id.title',
			'align' => 'left'
		);

		$cols[] = array(
			'name'  => $language->get('column_filename'),
			'sort'  => 'i.filename',
			'align' => 'left'
		);

		$cols[] = array(
			'name'  => $language->get('column_date_added'),
			'sort'  => 'i.date_added',
			'align' => 'left'
		);

		$cols[] = array(
			'name'  => $language->get('column_image'),
			'align' => 'center'
		);

    	$cols[] = array(
      		'name'  => $language->get('column_action'),
      		'align' => 'right'
    	);
				
    	if (!$session->get('image.search')) {
      		$sql = "select i.image_id, id.title, i.filename, i.date_added from image i left join image_description id on (i.image_id = id.image_id) where id.language_id = '" . (int)$language->getId() . "'";
    	} else {
      		$sql = "select i.image_id, id.title, i.filename, i.date_added from image i left join image_description id on (i.image_id = id.image_id) where id.language_id = '" . (int)$language->getId() . "' and id.title like '?'";
    	}

		$sort = array(
			'id.title',
			'i.filename',
			'i.date_added',
		);

		if (in_array($session->get('image.sort'), $sort)) {
			$sql .= " order by " . $session->get('image.sort') . " " . (($session->get('image.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by id.title asc";
		}
				 
    	$results = $database->getRows($database->splitQuery($database->parse($sql, '%' . $session->get('image.search') . '%'), $session->get('image.page'), $config->get('config_max_rows')));

		$rows = array();

		foreach ($results as $result) {
			$cell = array();

			$cell[] = array(
				'value' => $result['title'],
				'align' => 'left',
			);

			$cell[] = array(
				'value' => $result['filename'],
				'align' => 'left'
			);

			$cell[] = array(
				'value' => $language->formatDate($language->get('date_format_short'), strtotime($result['date_added'])),
				'align' => 'left'
			);

			$cell[] = array(
				'image' => $image->resize($result['filename'], '22', '22'),
				'align' => 'center'
			);
			
			$action = array();
      		
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $language->get('button_update'),
				'href' => $url->ssl('image', 'update', array('image_id' => $result['image_id']))
      		);
						
			$action[] = array(
        		'icon' => 'delete.png',
				'text' => $language->get('button_delete'),
				'href' => $url->ssl('image', 'delete', array('image_id' => $result['image_id']))
      		);

      		$cell[] = array(
        		'action' => $action,
        		'align'  => 'right'
      		);
						
			$rows[] = array(
				'cell' => $cell
			);
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
		     
    	$view->set('action', $url->ssl('image', 'page'));
		
		$view->set('search', $session->get('image.search'));
		$view->set('sort', $session->get('image.sort'));
		$view->set('order', $session->get('image.order'));
		$view->set('page', $session->get('image.page'));
		
		$view->set('cols', $cols);
		$view->set('rows', $rows);
			    	
    	$view->set('list', $url->ssl('image'));
    
    	$view->set('insert', $url->ssl('image', 'insert'));
						
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

    	$view->set('entry_filename', $language->get('entry_filename'));
    	$view->set('entry_title', $language->get('entry_title'));
	
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
		$view->set('error_file', @$this->error['file']);
    	$view->set('error_delete', @$this->error['message']);

    	$view->set('action', $url->ssl('image', $request->gethtml('action'), array('image_id' => $request->gethtml('image_id'))));
  
    	$view->set('list', $url->ssl('image'));

    	$view->set('insert', $url->ssl('image', 'insert'));
  
    	if ($request->gethtml('image_id')) {	  
      		$view->set('update', $url->ssl('image', 'update', array('image_id' => $request->gethtml('image_id'))));
	  		$view->set('delete', $url->ssl('image', 'delete', array('image_id' => $request->gethtml('image_id'))));
    	}

    	$view->set('cancel', $url->ssl('image'));
		
		$image_data = array();
  
    	$results = $database->cache('language', "select * from language order by sort_order");
    
    	foreach ($results as $result) {	  
			if (($request->gethtml('image_id')) && (!$request->isPost())) {
	  			$image_description_info = $database->getRow("select * from image_description where image_id = '" . (int)$request->gethtml('image_id') . "' and language_id = '" . (int)$result['language_id'] . "'");
			} else {
				$image_description_info = $request->gethtml('language', 'post');
			}
			
	  		$image_data[] = array(
	    		'language_id' => $result['language_id'],
	    		'language'    => $result['name'],
	    		'title'       => (isset($image_description_info[$result['language_id']]) ? $image_description_info[$result['language_id']]['title'] : @$image_description_info['title']),	  		
			);
    	}

    	$view->set('images', $image_data);
  
 		return $view->fetch('content/image.tpl');
  	}

  	function validateForm() { 
		$request  =& $this->locator->get('request');
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');
		$upload   =& $this->locator->get('upload');
        $validate =& $this->locator->get('validate');
 
    	if (!$user->hasPermission('modify', 'image')) {
      		$this->error['message'] = $language->get('error_permission');
    	}

		if ($upload->has('image'))  {

	  		if ($upload->hasError('image')) {
	    		$this->error['file'] = $language->get('error_upload');
	  		}

	  		if (!$validate->strlen($upload->getName('image'),1,128)) {
        		$this->error['file'] = $language->get('error_filename');
			}
			
	    	$allowed = array(
	      		'image/jpeg',
	      		'image/pjpeg',
		  		'image/gif', 
		  		'image/png',
				'image/x-png'
	    	);
      
	    	if (!in_array($upload->getType('image'), $allowed)) {
          		$this->error['file'] = $language->get('error_filetype');
        	}
	  
			if ($upload->hasError('image')) {
				$this->error['message'] = $upload->getError('image');
			}
    	} elseif ($request->get('action') == 'insert') {
	    	$this->error['file'] = $language->get('error_filename');
		}

		foreach ($request->gethtml('language', 'post') as $value) {
			if (empty($value['title'])) { $value['title']=$this->getTitle($upload->getName('image')); }
      		if (!$validate->strlen($value['title'],1,64)) {
        		$this->error['title'] = $language->get('error_title');
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
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');
	
    	if (!$user->hasPermission('modify', 'image')) {
      		$this->error['message'] = $language->get('error_permission');
    	}	
	
  		$product_info = $database->getRow("select count(*) as total from product where image_id = '" . (int)$request->gethtml('image_id') . "'");
    
		if ($product_info['total']) {
	  		$this->error['message'] = $language->get('error_product', $product_info['total']);	
		}	

  		$category_info = $database->getRow("select count(*) as total from category where image_id = '" . (int)$request->gethtml('image_id') . "'");
    
		if ($category_info['total']) {
	  		$this->error['message'] = $language->get('error_category', $category_info['total']);
		}	 
 
   		$manufacturer_info = $database->getRow("select count(*) as total from manufacturer where image_id = '" . (int)$request->gethtml('image_id') . "'");
    
		if ($manufacturer_info['total']) {
	  		$this->error['message'] = $language->get('error_manufacturer', $manufacturer_info['total']);
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
			$session->set('image.search', $request->gethtml('search', 'post'));
		}

		if (($request->has('page', 'post')) || ($request->has('search', 'post'))) {
			$session->set('image.page', $request->gethtml('page', 'post'));
		}

		if ($request->has('sort', 'post')) {
			$session->set('image.order', (($session->get('image.sort') == $request->gethtml('sort', 'post')) && ($session->get('image.order') == 'asc')) ? 'desc' : 'asc');
		}

		if ($request->has('sort', 'post')) {
			$session->set('image.sort', $request->gethtml('sort', 'post'));
		}

		$response->redirect($url->ssl('image'));
	}	
	    
	function view() {
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$database =& $this->locator->get('database');
		$language =& $this->locator->get('language');
		$image    =& $this->locator->get('image');

		$image_info = $database->getRow("select * from image i left join image_description id on (i.image_id = id.image_id) where i.image_id = '" . (int)$request->gethtml('image_id') . "' and id.language_id = '" . (int)$language->getId() . "'");

		$response->set(sprintf($this->html,$image->resize($image_info['filename'], $this->size, $this->size), $image_info['title'], $image_info['title'], $this->size, $this->size));
	}	
}
?>