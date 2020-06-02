<?php // Admin Category AlegroCart 1.0
class ControllerCategory extends Controller {
	var $error = array();
 
	function index() {
		$response =& $this->locator->get('response');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$module   =& $this->locator->get('module');

		$language->load('controller/category.php');

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

		$language->load('controller/category.php');

		$template->set('title', $language->get('heading_title'));

		if ($request->isPost() && $request->has('image_id', 'post') && $this->validateForm() ) {
			$url_alias = $config->get('config_url_alias');
			$url_seo = $config->get('config_seo');
			
			$sql = "insert into category set image_id = '?', sort_order = '?', parent_id = '?', date_added = now()";
			$database->query($database->parse($sql, $request->gethtml('image_id', 'post'), $request->gethtml('sort_order', 'post'), end(explode('_', $request->gethtml('path')))));

			$insert_id = $database->getLastId();

			if ($request->gethtml('path')) {
				$path = $request->gethtml('path') . '_' . $insert_id;
			} else {
				$path = $insert_id;
			}

			$sql = "update category set path = '?' where category_id = '?'";
			$database->query($database->parse($sql, $path, $insert_id));

			$description = $request->get('description', 'post');
			$meta_title = $request->get('meta_title', 'post');
			$meta_description = $request->get('meta_description', 'post');
			$meta_keywords = $request->get('meta_keywords', 'post');

			foreach ($request->gethtml('language', 'post') as $key => $value) {
				$sql = "insert into category_description set category_id = '?', language_id = '?', name = '?', description = '?', meta_title = '?', meta_description = '?', meta_keywords = '?'";
				$database->query($database->parse($sql, $insert_id, $key, $value['name'], $description[$key], strip_tags($meta_title[$key]), strip_tags($meta_description[$key]), strip_tags($meta_keywords[$key])));
			}

			if($url_alias && $url_seo){
				$this->category_seo($insert_id, $path);
			}
			
			$cache->delete('category');
			
			$session->set('message', $language->get('text_message'));

			$response->redirect($url->ssl('category', FALSE, array('path' => $request->gethtml('path'))));
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

		$language->load('controller/category.php');

		$template->set('title', $language->get('heading_title'));

		if ($request->isPost() && $request->has('image_id', 'post') && $this->validateForm()) {
			$url_alias = $config->get('config_url_alias');
			$url_seo = $config->get('config_seo');
			$sql = "update category set image_id = '?', sort_order = '?', date_modified = now() where category_id = '?'";
			$database->query($database->parse($sql, $request->gethtml('image_id', 'post'), $request->gethtml('sort_order', 'post'), $request->gethtml('category_id')));

			$database->query("delete from category_description where category_id = '" . (int)$request->gethtml('category_id') . "'");

			$description = $request->get('description', 'post');
			$meta_title = $request->get('meta_title', 'post');
			$meta_description = $request->get('meta_description', 'post');
			$meta_keywords = $request->get('meta_keywords', 'post');

			foreach ($request->gethtml('language', 'post') as $key => $value) {
				$sql = "insert into category_description set category_id = '?', language_id = '?', name = '?', description = '?', meta_title = '?', meta_description = '?', meta_keywords = '?'";
				$database->query($database->parse($sql, $request->gethtml('category_id'), $key, $value['name'], $description[$key], strip_tags($meta_title[$key]), strip_tags($meta_description[$key]), strip_tags($meta_keywords[$key])));
			}

			if($url_alias && $url_seo){
				$category_info = $database->getRow("select path from category where category_id ='". (int)$request->gethtml('category_id')."'");
				$this->delete_category_seo($category_info['path']);
				$this->category_seo($request->gethtml('category_id'), $category_info['path']);
			}
			
			$cache->delete('category');
			
			$session->set('message', $language->get('text_message'));

			$response->redirect($url->ssl('category', FALSE, array('path' => $request->gethtml('path'))));
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

		$language->load('controller/category.php');

		$template->set('title', $language->get('heading_title'));

		if (($request->gethtml('category_id')) && ($this->validateDelete())) {
			$url_alias = $config->get('config_url_alias');
			$url_seo = $config->get('config_seo');
			
			$database->query("delete from category where category_id = '" . (int)$request->gethtml('category_id') . "'");
			$database->query("delete from category_description where category_id = '" . (int)$request->gethtml('category_id') . "'");

			if ($request->gethtml('path')) {
				$path = $request->gethtml('path') . '_' . $request->gethtml('category_id');
			} else {
				$path = $request->gethtml('category_id');
			}

			$sql = "delete category, category_description from category left join category_description on category.category_id = category_description.category_id where category.path like '?'";
			$database->query($database->parse($sql, $path . '%'));

			if($url_alias && $url_seo){
				$this->delete_category_seo($path);
			}
			
			$cache->delete('category');
			
			$session->set('message', $language->get('text_message'));

			$response->redirect($url->ssl('category', FALSE, array('path' => $request->gethtml('path'))));
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
		$image    =& $this->locator->get('image');   //new

		$cols = array();

		$cols[] = array('align' => 'center');
		
		$cols[] = array(
			'name'  => $language->get('column_name'),
			'sort'  => 'cd.name',
			'align' => 'left'
		);

		$cols[] = array(    //new
             'name'  => $language->get('column_image'),
             'sort'  => 'i.filename',
             'align' => 'center'
		);
		
		$cols[] = array(
			'name'  => $language->get('column_sort_order'),
			'sort'  => 'c.sort_order',
			'align' => 'right'
		);
    	
		$cols[] = array(
      		'name'  => $language->get('column_action'),
      		'align' => 'right'
    	);
		
		if ((!$session->has('category.search')) || ($request->gethtml('path'))) {
			$sql = "select c.category_id, cd.name, i.filename, c.sort_order from category c left join category_description cd on (c.category_id = cd.category_id) left join image i on (c.image_id = i.image_id) where c.parent_id = '" . (int)end(explode('_', $request->gethtml('path'))) . "' and language_id = '" . (int)$language->getId() . "'";
		} else {
			$sql = "select c.category_id, cd.name, i.filename, c.sort_order from category c left join category_description cd on (c.category_id = cd.category_id) left join image i on (c.image_id = i.image_id) where language_id = '" . (int)$language->getId() . "' and cd.name like '?'";
		}

		$sort = array(
			'cd.name',
			'c.sort_order',
			'i.filename'
		);

		if (in_array($session->get('category.sort'), $sort)) {
			$sql .= " order by " . $session->get('category.sort') . " " . (($session->get('category.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by cd.name asc";
		}

		$results = $database->getRows($database->splitQuery($database->parse($sql, '%' . $session->get('category.search') . '%'), ($request->has('path') ? $session->get('category.' . $request->gethtml('path') . '.page') : $session->get('category.page')), $config->get('config_max_rows')));

		$rows = array();

		foreach ($results as $result) {
			$cell = array();

			if ($request->gethtml('path')) {
				$path = $request->gethtml('path') . '_' . $result['category_id'];
			} else {
				$path = $result['category_id'];
			}

			$cell[] = array(
				'icon'  => 'folder.png',
				'align' => 'center',
				'path'  => $url->ssl('category', FALSE, array('path' => $path)),
			);
			
			$cell[] = array(
				'value' => $result['name'],
				'align' => 'left'
			);

		   $cell[] = array(       //new
               'image' => $image->resize($result['filename'], '22', '22'),
               'align' => 'center'
             );
			 
			$cell[] = array(
				'value' => $result['sort_order'],
				'align' => 'right'
			);

			$query = array(
				'category_id' => $result['category_id'],
				'path'        => $request->gethtml('path'),
			);
			
			$action = array();
      		
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $language->get('button_update'),
				'href' => $url->ssl('category', 'update', $query)
      		);
						
			$action[] = array(
        		'icon' => 'delete.png',
				'text' => $language->get('button_delete'),
				'href' => $url->ssl('category', 'delete', $query)
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
		
		$view->set('action', $url->ssl('category', 'page', array('path' => $request->gethtml('path'))));

		if ($request->gethtml('path')) {
			$path = explode('_', $request->gethtml('path'));

			if (count($path) > 1) {
				array_pop($path);

				$view->set('previous', $url->ssl('category', FALSE, array('path' => implode('_', $path))));
			} else {
				$view->set('previous', $url->ssl('category'));
			}
		} 

		$view->set('search', $session->get('category.search'));
		$session->delete('category.search');
		$view->set('sort', $session->get('category.sort'));
		$view->set('order', $session->get('category.order'));
		$view->set('page', ($request->has('path') ? $session->get('category.' . $request->gethtml('path') . 'page') : $session->get('category.page')));

		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('list', $url->ssl('category', FALSE, array('path' => $request->gethtml('path'))));

		$view->set('insert', $url->ssl('category', 'insert', array('path' => $request->gethtml('path'))));

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
        $view->set('entry_description', $language->get('entry_description'));
		$view->set('entry_meta_title', $language->get('entry_meta_title')); // Meta Title
		$view->set('entry_meta_description', $language->get('entry_meta_description')); // Meta Description
		$view->set('entry_meta_keywords', $language->get('entry_meta_keywords')); // Meta Keywords		
		$view->set('entry_sort_order', $language->get('entry_sort_order'));
		$view->set('entry_image', $language->get('entry_image'));

		$view->set('button_list', $language->get('button_list'));
		$view->set('button_insert', $language->get('button_insert'));
		$view->set('button_update', $language->get('button_update'));
		$view->set('button_delete', $language->get('button_delete'));
		$view->set('button_save', $language->get('button_save'));
		$view->set('button_cancel', $language->get('button_cancel'));

		$view->set('tab_general', $language->get('tab_general'));
		$view->set('tab_data', $language->get('tab_data'));
		$view->set('tab_image', $language->get('tab_image'));

		$view->set('error_description', @$this->error['message']); // not sure why just yet

        $view->set('error', @$this->error['message']);
		$view->set('error_name', @$this->error['name']);

		$query = array(
			'category_id' => $request->gethtml('category_id'),
			'path'        => $request->gethtml('path')
		);

		$view->set('action', $url->ssl('category', $request->gethtml('action'), $query));

		$view->set('list', $url->ssl('category', FALSE, array('path' => $request->gethtml('path'))));

		$view->set('insert', $url->ssl('category', 'insert', array('path' => $request->gethtml('path'))));

		if ($request->gethtml('category_id')) {
			$query = array(
				'category_id' => $request->gethtml('category_id'),
				'path'        => $request->gethtml('path')
			);

			$view->set('update', $url->ssl('category', 'update', $query));
			$view->set('delete', $url->ssl('category', 'delete', $query));
		}

		$view->set('cancel', $url->ssl('category', FALSE, array('path' => $request->gethtml('path'))));
				
		$category_data = array();
		
		$results = $database->cache('language', "select * from language order by sort_order");

		foreach ($results as $result) {
			if (($request->gethtml('category_id')) && (!$request->isPost())) {
				$category_description_info = $database->getRow("select name, description, meta_title, meta_description, meta_keywords from category_description where category_id = '" . (int)$request->gethtml('category_id') . "' and language_id = '" . (int)$result['language_id'] . "'");
			} else {
				$category_description_info = $request->gethtml('language', 'post');
			}
			
			$category_data[] = array(
				'language_id' => $result['language_id'],
				'language'    => $result['name'],
	    		'name'        => (isset($category_description_info[$result['language_id']]) ? $category_description_info[$result['language_id']]['name'] : @$category_description_info['name']),
                'description' => (isset($description[$result['language_id']]) ? $description[$result['language_id']] : @$category_description_info['description']),
				
	    		'meta_title' 	=> (isset($meta_title[$result['language_id']]) ? $meta_title[$result['language_id']] : @$category_description_info['meta_title']),			
	    		'meta_description'=> (isset($meta_description[$result['language_id']]) ? $meta_description[$result['language_id']] : @$category_description_info['meta_description']),
	    		'meta_keywords' => (isset($meta_keywords[$result['language_id']]) ? $meta_keywords[$result['language_id']] : @$category_description_info['meta_keywords'])					
			);
		}

		$view->set('categories', $category_data);

		if (($request->gethtml('category_id')) && (! $request->isPost())) {
			$category_info = $database->getRow("select distinct * from category where category_id = '" . (int)$request->gethtml('category_id') . "'");
		}

		if ($request->has('sort_order', 'post')) {
			$view->set('sort_order', $request->gethtml('sort_order', 'post'));
		} else {
			$view->set('sort_order', @$category_info['sort_order']);
		}

		if ($request->has('image_id', 'post')) {
			$view->set('image_id', $request->gethtml('image_id', 'post'));
		} else {
			$view->set('image_id', @$category_info['image_id']);
		}

		$image_data = array();

		$results = $database->cache('image', "select i.image_id, i.filename, id.title from image i left join image_description id on i.image_id = id.image_id where id.language_id = '" . (int)$language->getId() . "'order by id.title");

		foreach ($results as $result) {
			$image_data[] = array(
				'image_id' => $result['image_id'],
				'title'    => $result['title']
			);
		}

		$view->set('images', $image_data);

		return $view->fetch('content/category.tpl');
	}

	function validateForm() {
		$request  =& $this->locator->get('request');
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');
		$validate =& $this->locator->get('validate');

		if (!$user->hasPermission('modify', 'category')) {
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
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');

		if (!$user->hasPermission('modify', 'category')) {
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
	
		$session->delete('category.search');
		if ($request->has('search', 'post') && $request->gethtml('search','post') != '') {
	  		$session->set('category.search', $request->gethtml('search', 'post'));
		}
			
		if (($request->has('page', 'post')) || ($request->has('search', 'post'))) {
			$session->set(($request->has('path') ? 'category.' . $request->gethtml('path') . '.page' : 'category.page'), $request->gethtml('page', 'post'));
		}
			
		if ($request->has('sort', 'post')) {
			$session->set('category.order', (($session->get('category.sort') == $request->gethtml('sort', 'post')) && ($session->get('category.order') == 'asc') ? 'desc' : 'asc'));
		}

		if ($request->has('sort', 'post')) {
			$session->set('category.sort', $request->gethtml('sort', 'post'));
		} 
		
		$response->redirect($url->ssl('category', FALSE, array('path' => $request->gethtml('path'))));
  	} 
	function category_seo($category_id, $path){
		$generate_seo 	=& $this->locator->get('generateseo');
		$database 		=& $this->locator->get('database');
		$language 		=& $this->locator->get('language');
		$language_id = (int)$language->getId();
		$categories = explode('_', $path);
		$alias = '';
		foreach ($categories as $category){
			$row = $database->getRow("select name from category_description where category_id = '".$category."' and language_id = '".$language_id."'");
			$alias .= $generate_seo->clean_alias($row['name']);
			$alias .= '/';
		}
		$alias = rtrim($alias, '/');
		$alias .= '.html';
		$query_path = 'controller=category&path=' . $path;
		$generate_seo->_insert_url_alias($query_path, $alias);
	}
	function delete_category_seo($path){
		$database 		=& $this->locator->get('database');
		$query_path = 'controller=category&path=' . $path;
		$database->query("delete from url_alias where query = '".$query_path."'");
	}
}
?>
