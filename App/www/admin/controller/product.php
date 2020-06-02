<?php // Admin Product AlegroCart 1.0
class ControllerProduct extends Controller {
	var $error = array();
	
  	function index() {
		$response =& $this->locator->get('response');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$module   =& $this->locator->get('module');
			  
		$language->load('controller/product.php');
 
        $language->load('controller/product_lfs.php');// latest/featured/specials/related
 
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
        		 
    	$language->load('controller/product.php');

        $language->load('controller/product_lfs.php');// latest/featured/specials/related

    	$template->set('title', $language->get('heading_title'));
		// featured/specials/related, Dated Special fields added
    	if (($request->isPost()) && ($this->validateForm())) {
			$url_alias = $config->get('config_url_alias');
			$url_seo = $config->get('config_seo');
		   	foreach ($request->get('name', 'post', array()) as $value) {
				if ($database->getRow("select name from product_description where name = '". $value ."'")){
					$session->set('message',  $language->get('error_already_exists'));
					$response->redirect($url->ssl('product'));
				}
			}
		
      		$sql = "insert into product set quantity = '?', date_available = '?', manufacturer_id = '?', image_id = '?', shipping = '?', price = '?', sort_order = '?', weight = '?', weight_class_id = '?', status = '?', tax_class_id = '?', min_qty = '?', featured = '?', special_offer = '?', related = '?', special_price = '?', sale_start_date = '?', sale_end_date = '?', date_added = now()";
      		$database->query($database->parse($sql, $request->gethtml('quantity', 'post'), date('Y-m-d', strtotime($request->gethtml('date_available_year', 'post') . '/' . $request->gethtml('date_available_month', 'post') . '/' . $request->gethtml('date_available_day', 'post'))), $request->gethtml('manufacturer_id', 'post'), $request->gethtml('image_id', 'post'), $request->gethtml('shipping', 'post'), $request->gethtml('price', 'post'), $request->gethtml('sort_order', 'post'), $request->gethtml('weight', 'post'), $request->gethtml('weight_class_id', 'post'), $request->gethtml('status', 'post'), $request->gethtml('tax_class_id', 'post'), ($request->gethtml('min_qty', 'post') != null && $request->gethtml('min_qty', 'post') > 0) ? $request->gethtml('min_qty', 'post') : 1, $request->gethtml('featured', 'post'), $request->gethtml('special_offer', 'post'), $request->gethtml('related', 'post'), $request->gethtml('special_price', 'post'), date('Y-m-d', strtotime($request->gethtml('start_date_year', 'post') . '/' . $request->gethtml('start_date_month', 'post') . '/' . $request->gethtml('start_date_day', 'post'))), date('Y-m-d', strtotime($request->gethtml('end_date_year', 'post') . '/' . $request->gethtml('end_date_month', 'post') . '/' . $request->gethtml('end_date_day', 'post')))));

      		$insert_id = $database->getLastId();

			$name        = $request->get('name', 'post');
			$model		 = $request->get('model', 'post');
			$model_number= $request->get('model_number' , 'post');
			$description = $request->get('description', 'post');
			$technical   = $request->get('technical', 'post');    // New Technical
			$alt_description = $request->get('alt_description', 'post');
			$meta_title = $request->get('meta_title', 'post');
			$meta_description = $request->get('meta_description', 'post');
			$meta_keywords = $request->get('meta_keywords', 'post');			
      		foreach ($request->get('name', 'post', array()) as $key => $value) {
				if($key == (int)$language->getId() && $url_alias && $url_seo){
					$this->product_seo($insert_id, @htmlspecialchars($name[$key]));
				}
        		$sql = "insert into product_description set product_id = '?', language_id = '?', name = '?', description = '?', technical = '?', model = '?', model_number = '?', alt_description = '?', meta_title = '?', meta_description = '?', meta_keywords = '?'";
                $database->query($database->parse($sql, $insert_id, $key, @htmlspecialchars($name[$key]), $description[$key], $technical[$key], @htmlspecialchars($model[$key]), @htmlspecialchars($model_number[$key]), $alt_description[$key], strip_tags($meta_title[$key]), strip_tags($meta_description[$key]), strip_tags($meta_keywords[$key])));
      		}

			foreach ($request->gethtml('product_discount', 'post', array()) as $product_discount) {
				$sql = "insert into product_discount set product_id = '?', quantity = '?', discount = '?'";
				$database->query($database->parse($sql, $insert_id, $product_discount['quantity'], $product_discount['discount']));
			}

      		foreach ($request->gethtml('image', 'post', array()) as $image_id) {
        		$sql = "insert into product_to_image set product_id = '?', image_id = '?'";
        		$database->query($database->parse($sql, $insert_id, $image_id));
      		}

      		foreach ($request->gethtml('download', 'post', array()) as $download_id) {
        		$sql = "insert into product_to_download set product_id = '?', download_id = '?'";
        		$database->query($database->parse($sql, $insert_id, $download_id));
      		}
			
      		foreach ($request->gethtml('category', 'post', array()) as $category_id) {
        		$sql = "insert into product_to_category set product_id = '?', category_id = '?'";
        		$database->query($database->parse($sql, $insert_id, $category_id));
				if($url_alias && $url_seo){
					$this->product_to_category_seo($insert_id,$category_id);
				}
	  		}
			
			if($url_alias && $url_seo){
				$this->manufacturer_to_product_seo($insert_id, $request->gethtml('manufacturer_id', 'post'));
			}
			
            foreach ($request->gethtml('relateddata', 'post', array()) as $product_id) {  //   New Related Products
				$sql = "insert into related_products set product_id = '?', related_product_id = '?'";
				$database->query($database->parse($sql, $insert_id, $product_id));
	  		}			
			
	  		$cache->delete('product');
	  		
			$session->set('message', $language->get('text_message'));
	  
	  		$response->redirect($url->ssl('product'));
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

    	$language->load('controller/product.php');

        $language->load('controller/product_lfs.php');// latest/featured/specials/related

    	$template->set('title', $language->get('heading_title'));

		// featured/specials/related fields added		
    	if (($request->isPost()) && ($this->validateForm())) {
			$url_alias = $config->get('config_url_alias');
			$url_seo = $config->get('config_seo');
		   	foreach ($request->get('name', 'post', array()) as $value) {
				if ($database->getRow("select name, product_id from product_description where name = '". $value ."' and product_id != '".(int)$request->gethtml('product_id') ."'")){
					$session->set('message', $language->get('error_duplicate_name'));
					$response->redirect($url->ssl('product'));
				}
			}
		
      		$sql = "update product set quantity = '?', date_available = '?', manufacturer_id = '?', image_id = '?', shipping = '?', price = '?', sort_order = '?', weight = '?', weight_class_id = '?', status = '?', tax_class_id = '?', min_qty = '?', featured = '?', special_offer = '?', related = '?', special_price = '?', sale_start_date = '?', sale_end_date = '?', date_modified = now() where product_id = '?'";
      		$database->query($database->parse($sql, $request->gethtml('quantity', 'post'), date('Y-m-d', strtotime($request->gethtml('date_available_year', 'post') . '/' . $request->gethtml('date_available_month', 'post') . '/' . $request->gethtml('date_available_day', 'post'))), $request->gethtml('manufacturer_id', 'post'), $request->gethtml('image_id', 'post'), $request->gethtml('shipping', 'post'), $request->gethtml('price', 'post'), $request->gethtml('sort_order', 'post'), $request->gethtml('weight', 'post'), $request->gethtml('weight_class_id', 'post'), $request->gethtml('status', 'post'), $request->gethtml('tax_class_id', 'post'), ($request->gethtml('min_qty', 'post') > 0) ? $request->gethtml('min_qty', 'post') : 1, $request->gethtml('featured', 'post'), $request->gethtml('special_offer', 'post'), $request->gethtml('related','post'), $request->gethtml('special_price', 'post'), date('Y-m-d', strtotime($request->gethtml('start_date_year', 'post') . '/' . $request->gethtml('start_date_month', 'post') . '/' . $request->gethtml('start_date_day', 'post'))), date('Y-m-d', strtotime($request->gethtml('end_date_year', 'post') . '/' . $request->gethtml('end_date_month', 'post') . '/' . $request->gethtml('end_date_day', 'post'))), $request->gethtml('product_id')));
			      		
			$database->query("delete from product_description where product_id = '" . (int)$request->gethtml('product_id') . "'");

			$name        = $request->gethtml('name', 'post');
			
			
			
			$name        = $request->gethtml('name', 'post');
			$model		 = $request->get('model', 'post');
			$model_number= $request->get('model_number' , 'post');			
			$description = $request->get('description', 'post');
			$technical   = $request->get('technical', 'post');    // New Technical
			$alt_description = $request->get('alt_description', 'post');
			$meta_title = $request->get('meta_title', 'post');
			$meta_description = $request->get('meta_description', 'post');
			$meta_keywords = $request->get('meta_keywords', 'post');
		  	foreach ($request->get('name', 'post', array()) as $key => $value) {
				if($key == (int)$language->getId() && $url_alias && $url_seo){
					$this->delete_product_seo($request->gethtml('product_id'));
					$this->product_seo($request->gethtml('product_id'), @htmlspecialchars($value));
				}
        		$sql = "insert into product_description set product_id = '?', language_id = '?', name = '?', description = '?', technical = '?', model = '?', model_number = '?', alt_description = '?', meta_title = '?', meta_description = '?', meta_keywords = '?'";
        		$database->query($database->parse($sql, $request->gethtml('product_id'), $key, @htmlspecialchars($value), $description[$key], $technical[$key], @htmlspecialchars($model[$key]), @htmlspecialchars($model_number[$key]), $alt_description[$key], strip_tags($meta_title[$key]), strip_tags($meta_description[$key]), strip_tags($meta_keywords[$key])));
      		}

			$database->query("delete from product_discount where product_id = '" . (int)$request->gethtml('product_id') . "'");

			foreach ($request->gethtml('product_discount', 'post', array()) as $product_discount) {
				$sql = "insert into product_discount set product_id = '?', quantity = '?', discount = '?'";
				$database->query($database->parse($sql, $request->gethtml('product_id'), $product_discount['quantity'], $product_discount['discount']));
			}

      		$database->query("delete from product_to_image where product_id = '" . (int)$request->gethtml('product_id') . "'");

      		foreach ($request->gethtml('image', 'post', array()) as $image_id) {
        		$sql = "insert into product_to_image set product_id = '?', image_id = '?'";
        		$database->query($database->parse($sql, $request->gethtml('product_id'), $image_id));
      		}

      		$database->query("delete from product_to_download where product_id = '" . (int)$request->gethtml('product_id') . "'");

      		foreach ($request->gethtml('download', 'post', array()) as $download_id) {
        		$sql = "insert into product_to_download set product_id = '?', download_id = '?'";
        		$database->query($database->parse($sql, $request->gethtml('product_id'), $download_id));
      		}
	  
      		$database->query("delete from product_to_category where product_id = '" . (int)$request->gethtml('product_id') . "'");

      		foreach ($request->gethtml('category', 'post', array()) as $category_id) { 
        		$sql = "insert into product_to_category set product_id = '?', category_id = '?'";
        		$database->query($database->parse($sql, $request->gethtml('product_id'), $category_id));
				if($url_alias && $url_seo){
					$this->delete_product_to_category_seo($request->gethtml('product_id'),$category_id);
					$this->product_to_category_seo($request->gethtml('product_id'),$category_id);
				}
      		} 

			if($url_alias && $url_seo){
				$this->delete_manufacturer_to_product_seo($request->gethtml('product_id'), $request->gethtml('manufacturer_id', 'post'));
				$this->manufacturer_to_product_seo($request->gethtml('product_id'), $request->gethtml('manufacturer_id', 'post'));
			}
			
                //   New Related Products
            $database->query("delete from related_products where product_id = '" . (int)$request->gethtml('product_id') . "'");
            
            foreach ($request->gethtml('relateddata', 'post', array()) as $product_id) {
				$sql = "insert into related_products set product_id = '?', related_product_id = '?'";
				$database->query($database->parse($sql, $request->gethtml('product_id'), $product_id));
	  		}

			$cache->delete('product');
	  		
			$session->set('message', $language->get('text_message'));
	  		
			$response->redirect($url->ssl('product'));
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

    	$language->load('controller/product.php');
		$language->load('controller/product_lfs.php');// latest/featured/specials/related

    	$template->set('title', $language->get('heading_title'));
	
    	if (($request->gethtml('product_id')) && ($this->validateDelete())) {
			$url_alias = $config->get('config_url_alias');
			$url_seo = $config->get('config_seo');
			
			if($url_alias && $url_seo){
				$this->delete_product_seo($request->gethtml('product_id'));
				$manufacturer_info = $database->getRow("select manufacturer_id from product where product_id = '" . $request->gethtml('product_id') . "'");
				$this->delete_manufacturer_to_product_seo($request->gethtml('product_id'), $manufacturer_info['manufacturer_id']);
				$categorys = $database->getRows("select category_id from product_to_category where product_id ='". $request->gethtml('product_id')."'");
				foreach ($categorys as $category) { 
					if($url_alias && $url_seo){
						$this->delete_product_to_category_seo($request->gethtml('product_id'),$category['category_id']);
					}
				} 
			}

      		$database->query("delete from product where product_id = '" . (int)$request->gethtml('product_id') . "'");
      		$database->query("delete from product_description where product_id = '" . (int)$request->gethtml('product_id') . "'");
			$database->query("delete from product_discount where product_id = '" . (int)$request->gethtml('product_id') . "'");			
      		$database->query("delete from product_to_option where product_id = '" . (int)$request->gethtml('product_id') . "'");
      		$database->query("delete from product_to_image where product_id = '" . (int)$request->gethtml('product_id') . "'");
	  		$database->query("delete from product_to_download where product_id = '" . (int)$request->gethtml('product_id') . "'");
      		$database->query("delete from product_to_category where product_id = '" . (int)$request->gethtml('product_id') . "'");
	  		$database->query("delete from review where product_id = '" . (int)$request->gethtml('product_id') . "'");
			$database->query("delete from related_products where product_id = '" . (int)$request->gethtml('product_id') . "'");// New Related Products

	  		$cache->delete('product');
	  		
			$session->set('message', $language->get('text_message'));
	  
	  		$response->redirect($url->ssl('product'));
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
      		'sort'  => 'pd.name',
      		'align' => 'left'
    	);
		
		$cols[] = array(     //new
             'name'  => $language->get('column_price'),
             'sort'  => 'p.price',
             'align' => 'left'
       );
	   
        $cols[] = array(     //new
             'name'  => $language->get('column_stock'),
             'sort'  => 'p.quantity',
             'align' => 'left'
       );
	   
        $cols[] = array(     //new
             'name'  => $language->get('column_weight'),
             'sort'  => 'p.weight',
             'align' => 'left'
       );
		
    	$cols[] = array(
      		'name'  => $language->get('column_model'),
      		'sort'  => 'p.model',
      		'align' => 'left'
    	);
		
		$cols[] = array(    // New
			'name'  => $language->get('column_dated_special'),
			'sort'  => 'p.special_price',
			'align' => 'left'
		);
		
		$cols[] = array(    // New
			'name'  => $language->get('column_featured'),
			'sort'  => 'p.featured',
			'align' => 'left'
		);		
  
    	$cols[] = array(
      		'name'  => $language->get('column_status'),
      		'sort'  => 'p.status',
      		'align' => 'center'
    	);
		
		$cols[] = array(    //new
             'name'  => $language->get('column_image'),
             'sort'  => 'i.filename',
             'align' => 'right'
       );
				
    	$cols[] = array(
      		'name'  => $language->get('column_sort_order'),
      		'sort'  => 'p.sort_order',
      		'align' => 'right'
    	);

    	$cols[] = array(
      		'name'  => $language->get('column_action'),
      		'align' => 'right'
    	);
								
		// New Added fields for Columns
		if (!$session->get('product.search')) {
             $sql = "select p.product_id, pd.name, p.price, p.quantity, p.weight, pd.model, p.sort_order, p.status, p.special_price, p.featured, p.special_offer, i.filename from product p left join product_description pd on (p.product_id = pd.product_id) left join image i on (p.image_id = i.image_id) where pd.language_id = '" . (int)$language->getId() . "'";
       } else {
             $sql = "select p.product_id, pd.name, p.price, p.quantity, p.weight, pd.model, p.sort_order, p.status, p.special_price, p.featured, p.special_offer, i.filename from product p left join product_description pd on (p.product_id = pd.product_id) left join image i on (p.image_id = i.image_id) where pd.language_id = '" . (int)$language->getId() . "' and pd.name like '?'";
       }
    
		$sort = array(
			'pd.name',
            'p.price', // New
            'p.quantity', // New
            'p.weight', // New
            'pd.model',
            'p.sort_order',
			'p.featured',
            'p.status',
			'p.special_price', // New
            'i.filename' // New
			
		);
	
    	if (in_array($session->get('product.sort'), $sort)) {
      		$sql .= " order by " . $session->get('product.sort') . " " . (($session->get('product.order') == 'desc') ? 'desc' : 'asc');
    	} else {
      		$sql .= " order by pd.name asc";
    	}
    
    	$results = $database->getRows($database->splitQuery($database->parse($sql, '%' . $session->get('product.search') . '%'), $session->get('product.page'), $config->get('config_max_rows')));

    	$rows = array();

    	foreach ($results as $result) {
      		$cell = array();

      		$cell[] = array(
        		'icon'  => 'folder.png',
        		'align' => 'center',
				'path'  => $url->ssl('product_option', FALSE, array('product_id' => $result['product_id']))
		  	);
			
      		$cell[] = array(
        		'value' => $result['name'],
        		'align' => 'left'
		  	);
			
			$cell[] = array(     //new
               'value' => number_format($result['price'],2),
               'align' => 'left'
            );
                $cell[] = array(  // new
               'value' => $result['quantity'],
               'align' => 'left'
            );
               $cell[] = array(     //new
               'value' => $result['weight'],
               'align' => 'left'
            );

      		$cell[] = array(
        		'value' => $result['model'],
        		'align' => 'left'
      		);
			
			$cell[] = array(    // new
			    'value' => number_format($result['special_price'],2),
				'align' => 'left'
			);
			
			$featured_special = "";
			$featured_special .= $result['featured'] ? " F " : "";
			$featured_special .= $result['special_offer'] ? " S " : "";
			$cell[] = array(    // new
				'value' => $featured_special,
				'align' => 'left'
			);
			
      		$cell[] = array(
        		'icon'  => ($result['status'] ? 'enabled.png' : 'disabled.png'),
        		'align' => 'center'
      		);
			
		   $cell[] = array(       //new
               'image' => $image->resize($result['filename'], '22', '22'),
               'align' => 'right'
             );
						
      		$cell[] = array(
        		'value' => $result['sort_order'],
        		'align' => 'right'
      		);
				
			$action = array();
      		
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $language->get('button_update'),
				'href' => $url->ssl('product', 'update', array('product_id' => $result['product_id']))
      		);
						
			/*$action[] = array(
        		'icon' => 'delete.png',
				'text' => $language->get('button_delete'),
				'href' => $url->ssl('product', 'delete', array('product_id' => $result['product_id']))
      		);*/

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
		 
    	$view->set('action', $url->ssl('product', 'page'));

    	$view->set('search', $session->get('product.search'));
    	$view->set('sort', $session->get('product.sort'));
    	$view->set('order', $session->get('product.order'));
    	$view->set('page', $session->get('product.page'));
 
    	$view->set('cols', $cols);
    	$view->set('rows', $rows);

    	$view->set('list', $url->ssl('product'));
    
    	$view->set('insert', $url->ssl('product', 'insert'));
  
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
    	$view->set('text_none', $language->get('text_none'));
    	$view->set('text_yes', $language->get('text_yes'));
    	$view->set('text_no', $language->get('text_no'));
 		$view->set('text_plus', $language->get('text_plus'));
		$view->set('text_minus', $language->get('text_minus'));
		$view->set('text_model', $language->get('text_model'));
		$view->set('select_model', $language->get('select_model'));
		$view->set('text_unique', $language->get('text_unique'));
		
    	$view->set('entry_name', $language->get('entry_name'));
    	$view->set('entry_description', $language->get('entry_description'));
    	$view->set('entry_model', $language->get('entry_model'));
		$view->set('entry_model_number', $language->get('entry_model_number'));
		$view->set('entry_manufacturer', $language->get('entry_manufacturer'));
    	$view->set('entry_shipping', $language->get('entry_shipping'));
    	$view->set('entry_date_available', $language->get('entry_date_available'));
    	$view->set('entry_quantity', $language->get('entry_quantity'));
		$view->set('entry_discount', $language->get('entry_discount'));
    	$view->set('entry_status', $language->get('entry_status'));
    	$view->set('entry_sort_order', $language->get('entry_sort_order'));
    	$view->set('entry_tax_class', $language->get('entry_tax_class'));
    	$view->set('entry_price', $language->get('entry_price'));
    	$view->set('entry_weight_class', $language->get('entry_weight_class'));
    	$view->set('entry_weight', $language->get('entry_weight'));
		$view->set('entry_prefix', $language->get('entry_prefix'));
    	$view->set('entry_image', $language->get('entry_image'));
    	$view->set('entry_images', $language->get('entry_images'));
    	$view->set('entry_download', $language->get('entry_download'));
    	$view->set('entry_category', $language->get('entry_category'));
        $view->set('entry_min_qty', $language->get('entry_min_qty'));
        $view->set('entry_featured', $language->get('entry_featured'));// latest/featured/specials
        $view->set('entry_specials', $language->get('entry_specials'));// latest/featured/specials
		$view->set('entry_related', $language->get('entry_related'));// related
		$view->set('entry_dated_special', $language->get('entry_dated_special')); // dated special price
		$view->set('entry_regular_price', $language->get('entry_regular_price'));
		$view->set('entry_percent_discount', $language->get('entry_percent_discount'));
		$view->set('entry_start_date', $language->get('entry_start_date')); // dated special start date
		$view->set('entry_end_date', $language->get('entry_end_date')); // dated special end date
		$view->set('entry_alt_description', $language->get('entry_alt_description')); // Alternate Description
		$view->set('entry_technical', $language->get('entry_technical')); // Technical
		$view->set('entry_meta_title', $language->get('entry_meta_title')); // Meta Title
		$view->set('entry_meta_description', $language->get('entry_meta_description')); // Meta Description
		$view->set('entry_meta_keywords', $language->get('entry_meta_keywords')); // Meta Keywords
		$view->set('entry_quantity_discount',$language->get('entry_quantity_discount')); //New
		
    	$view->set('button_list', $language->get('button_list'));
    	$view->set('button_insert', $language->get('button_insert'));
    	$view->set('button_update', $language->get('button_update'));
    	$view->set('button_delete', $language->get('button_delete'));
    	$view->set('button_save', $language->get('button_save'));
    	$view->set('button_cancel', $language->get('button_cancel'));
		$view->set('button_add', $language->get('button_add'));
		$view->set('button_remove', $language->get('button_remove'));
		
    	$view->set('tab_general', $language->get('tab_general'));
    	$view->set('tab_data', $language->get('tab_data'));
		$view->set('tab_discount', $language->get('tab_discount'));
    	$view->set('tab_image', $language->get('tab_image'));
    	$view->set('tab_download', $language->get('tab_download'));
    	$view->set('tab_category', $language->get('tab_category'));
        $view->set('tab_home', $language->get('tab_home'));// latest/featured/specials
		$view->set('tab_dated_special', $language->get('tab_dated_special')); // dated special		
		$view->set('tab_alt_description', $language->get('tab_alt_description')); // Alt description & Meta Tags
		
    	$view->set('error', @$this->error['message']);
    	$view->set('error_name', @$this->error['name']);
    	$view->set('error_description', @$this->error['description']);
    	$view->set('error_model', @$this->error['model']);
    	$view->set('error_date_available', @$this->error['date_available']);
    	$view->set('error_start_date', @$this->error['start_date']); // Dated special error
    	$view->set('error_end_date', @$this->error['end_date']); // Dated special error
		
    	$view->set('action', $url->ssl('product', $request->gethtml('action'), array('product_id' => $request->gethtml('product_id'))));
  
    	$view->set('list', $url->ssl('product'));
 
    	$view->set('insert', $url->ssl('product', 'insert'));
  
    	if ($request->gethtml('product_id')) {
     		$view->set('update', $url->ssl('product', 'update', array('product_id' => $request->gethtml('product_id'))));
      		$view->set('delete', $url->ssl('product', 'delete', array('product_id' => $request->gethtml('product_id'))));
    	}

    	$view->set('cancel', $url->ssl('product'));

    	$product_data = array();
     		 
    	$results = $database->cache('language', "select * from language order by sort_order");
    
    	foreach ($results as $result) {
			$model_data = array();
			$models_results = $database->getRows("select distinct model from product_description where language_id = '" . (int)$result['language_id'] . "' order by model asc");//Get all models
			foreach($models_results as $model_result){
				$model_data[] = array(
					'model'	=> $model_result['model']
				);
			}		
			if (($request->gethtml('product_id')) && (!$request->isPost())) {
	  			$product_description_info = $database->getRow("select name, description, technical, model, model_number,alt_description, meta_title, meta_description, meta_keywords from product_description where product_id = '" . (int)$request->gethtml('product_id') . "' and language_id = '" . (int)$result['language_id'] . "'");
			}
			
			$name             = $request->get('name', 'post');
			$model			  = $request->get('model', 'post');
			$model_number     = $request->get('model_number', 'post');
			$description      = $request->get('description', 'post');
			$technical        = $request->get('technical', ' post');    // New Technical
			$alt_description  = $request->get('alt_destription', 'post');
			$meta_title       = $request->gethtml('meta_title', 'post');
			$meta_description = $request->gethtml('meta_description', 'post');
			$meta_keywords    = $request->gethtml('meta_keywords', 'post');
	  		
			$product_data[] = array(
				'models_data'   => $model_data,
	    		'language_id' 	=> $result['language_id'],
	    		'language'    	=> $result['name'],
	    		'name'        	=> (isset($name[$result['language_id']]) ? $name[$result['language_id']] : @$product_description_info['name']),
				'model'         => (isset($model[$result['language_id']]) ? $model[$result['language_id']] : @$product_description_info['model']),
				'model_number'  => (isset($model_number[$result['language_id']]) ? $model_number[$result['language_id']] : @$product_description_info['model_number']),
	    		'description' 	=> (isset($description[$result['language_id']]) ? $description[$result['language_id']] : @$product_description_info['description']),
				'technical'     => (isset($technical[$result['language_id']]) ? $technical[$result['language_id']] : @$product_description_info['technical']),
				'alt_description' => (isset($alt_description[$result['language_id']]) ? $alt_description[$result['language_id']] : @$product_description_info['alt_description']),
	    		'meta_title' 	=> (isset($meta_title[$result['language_id']]) ? $meta_title[$result['language_id']] : @$product_description_info['meta_title']),			
	    		'meta_description'=> (isset($meta_description[$result['language_id']]) ? $meta_description[$result['language_id']] : @$product_description_info['meta_description']),
	    		'meta_keywords' => (isset($meta_keywords[$result['language_id']]) ? $meta_keywords[$result['language_id']] : @$product_description_info['meta_keywords'])				
			);
    	}

    	$view->set('products', $product_data);

    	if (($request->gethtml('product_id')) && (!$request->isPost())) {
      		$product_info = $database->getRow("select distinct * from product where product_id = '" . (int)$request->gethtml('product_id') . "'");
    	}
		
    	$view->set('manufacturers', $database->cache('manufacturer', "select * from manufacturer order by sort_order, name asc")); // New Sorted

    	if ($request->has('manufacturer_id', 'post')) {
      		$view->set('manufacturer_id', $request->gethtml('manufacturer_id', 'post'));
    	} else {
      		$view->set('manufacturer_id', @$product_info['manufacturer_id']);
    	} 
		
    	if ($request->has('shipping', 'post')) {
      		$view->set('shipping', $request->gethtml('shipping', 'post'));
    	} else {
      		$view->set('shipping', @$product_info['shipping']);
    	}
  
    	if ($request->has('image_id', 'post')) {
      		$view->set('image_id', $request->gethtml('image_id', 'post'));
    	} else {
      		$view->set('image_id', @$product_info['image_id']);
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
      	
		if (isset($product_info['date_available'])) {
        	$date = explode('/', date('d/m/Y', strtotime($product_info['date_available'])));
      	} else {
        	$date = explode('/', date('d/m/Y', time()));
      	}
			
    	if ($request->has('date_available_day', 'post')) {
      		$view->set('date_available_day', $request->gethtml('date_available_day', 'post'));
    	} else {
      		$view->set('date_available_day', $date[0]);
    	}			
			
    	if ($request->has('date_available_month', 'post')) {
      		$view->set('date_available_month', $request->gethtml('date_available_month', 'post'));
    	} else {
      		$view->set('date_available_month', $date[1]);
    	}

    	if ($request->has('date_available_year', 'post')) {
      		$view->set('date_available_year', $request->gethtml('date_available_year', 'post'));
    	} else {
      		$view->set('date_available_year', $date[2]);
    	}					
			
    	if ($request->has('quantity', 'post')) {
      		$view->set('quantity', $request->gethtml('quantity', 'post'));
    	} else {
      		$view->set('quantity', @$product_info['quantity']);
    	}

    	if ($request->has('price', 'post')) {
      		$view->set('price', $request->gethtml('price', 'post'));
    	} else {
      		$view->set('price', @$product_info['price']);
    	}
  
    	if ($request->has('sort_order', 'post')) {
      		$view->set('sort_order', $request->gethtml('sort_order', 'post'));
    	} else {
      		$view->set('sort_order', @$product_info['sort_order']);
    	}

    	if ($request->has('status', 'post')) {
      		$view->set('status', $request->gethtml('status', 'post'));
    	} else {
      		$view->set('status', @$product_info['status']);
    	}

    	if ($request->has('featured', 'post')) {     // New Featured
      		$view->set('featured', $request->gethtml('featured', 'post'));
    	} else {
      		$view->set('featured', @$product_info['featured']);
    	}

    	if ($request->has('special_offer', 'post')) {    // New Special Offer
      		$view->set('special_offer', $request->gethtml('special_offer', 'post'));
    	} else {
      		$view->set('special_offer', @$product_info['special_offer']);
    	}	

		if ($request->has('related', 'post')) {    // New Related Products
      		$view->set('related', $request->gethtml('related', 'post'));
    	} else {
      		$view->set('related', @$product_info['related']);
    	}
		
		if (isset($product_info['sale_start_date']) && $product_info['sale_start_date'] >= "1970-01-01") {  // Special Start Date
        	$start_date = explode('/', date('d/m/Y', strtotime($product_info['sale_start_date'])));
      	} else {
        	$start_date = array('00', '00', '0000'); 
      	}
		
		if ($request->has('start_date_day', 'post')) {
      		$view->set('start_date_day', $request->gethtml('start_date_day', 'post'));
    	} else {
      		$view->set('start_date_day', $start_date[0]);
    	}			
			
    	if ($request->has('start_date_month', 'post')) {
      		$view->set('start_date_month', $request->gethtml('start_date_month', 'post'));
    	} else {
      		$view->set('start_date_month', $start_date[1]);
    	}

    	if ($request->has('start_date_year', 'post')) {
      		$view->set('start_date_year', $request->gethtml('start_date_year', 'post'));
    	} else {
      		$view->set('start_date_year', $start_date[2]);
    	}			
		
		if (isset($product_info['sale_end_date']) && $product_info['sale_end_date'] >= "1970-01-01") {  // Special End Date
        	$end_date = explode('/', date('d/m/Y', strtotime($product_info['sale_end_date'])));
      	} else {
        	$end_date = array('00', '00', '0000');
      	}
		
		if ($request->has('end_date_day', 'post')) {
      		$view->set('end_date_day', $request->gethtml('end_date_day', 'post'));
    	} else {
      		$view->set('end_date_day', $end_date[0]);
    	}			
			
    	if ($request->has('end_date_month', 'post')) {
      		$view->set('end_date_month', $request->gethtml('end_date_month', 'post'));
    	} else {
      		$view->set('end_date_month', $end_date[1]);
    	}

    	if ($request->has('end_date_year', 'post')) {
      		$view->set('end_date_year', $request->gethtml('end_date_year', 'post'));
    	} else {
      		$view->set('end_date_year', $end_date[2]);
    	}
		
		if ($request->has('special_price', 'post')) {  // Special price
      		$view->set('special_price', $request->gethtml('special_price', 'post'));
    	} else {
      		$view->set('special_price', @$product_info['special_price']);
    	}
		
    	if ($request->has('tax_class_id', 'post')) {
      		$view->set('tax_class_id', $request->gethtml('tax_class_id', 'post'));
    	} else {
      		$view->set('tax_class_id', @$product_info['tax_class_id']);
    	}

    	$view->set('tax_classes', $database->cache('tax_class', "select * from tax_class"));

    	if ($request->has('weight', 'post')) {
      		$view->set('weight', $request->gethtml('weight', 'post'));
    	} else {
      		$view->set('weight', @$product_info['weight']);
    	} 
        
        if ( $request->has('min_qty', 'post')) {
            $view->set('min_qty', $request->gethtml('min_qty', 'post'));
        } elseif (isset($product_info['min_qty'])) {
            $view->set('min_qty', $product_info['min_qty']);
        } else {
            $view->set('min_qty', 1);
        }

    	if ($request->has('weight_class_id', 'post')) {
      		$view->set('weight_class_id', $request->gethtml('weight_class_id', 'post'));
    	} elseif (isset($product_info['weight_class_id'])) {
      		$view->set('weight_class_id', $product_info['weight_class_id']);
    	} else {
      		$view->set('weight_class_id', '');
    	}

    	$view->set('weight_classes', $database->cache('weight_class-' . $language->getId(), "select weight_class_id, title from weight_class where language_id = '" . (int)$language->getId() . "'"));

     	$product_discount_data = array();
		
		if (!$request->has('product_discount', 'post')) {
    		$results = $database->getRows("select product_discount_id, quantity, discount from product_discount where product_id = '" . (int)$request->gethtml('product_id') . "' order by quantity asc");

    		foreach ($results as $result) {
      			$product_discount_data[] = array(
        			'quantity' => $result['quantity'],
					'discount' => $result['discount']
      			);
    		}
  		
			$view->set('product_discounts', $product_discount_data);
		} else {
			$view->set('product_discounts', $request->gethtml('product_discount', 'post', array()));
		}
		    
    	$image_data = array();

    	$results = $database->cache('image', "select i.image_id, i.filename, id.title from image i left join image_description id on (i.image_id = id.image_id) where id.language_id = '" . (int)$language->getId() . "' order by id.title");

    	foreach ($results as $result) {
			if (($request->gethtml('product_id')) && (!$request->isPost())) {  
	  			$product_to_image_info = $database->getRow("select * from product_to_image where product_id = '" . (int)$request->gethtml('product_id') . "' and image_id = '" . (int)$result['image_id'] . "'");
			}

      		$image_data[] = array(
        		'image_id'   => $result['image_id'],
        		'title'      => $result['title'],
        		'product_id' => (isset($product_to_image_info) ? $product_to_image_info : in_array($result['image_id'], $request->gethtml('image', 'post', array())))
      		);
    	}

    	$view->set('images', $image_data);

    	$download_data = array();

    	$results = $database->getRows("select d.download_id, d.filename, dd.name from download d left join download_description dd on d.download_id = dd.download_id where dd.language_id = '" . (int)$language->getId() . "' order by dd.name");

    	foreach ($results as $result) {
			if (($request->gethtml('product_id')) && (!$request->isPost())) {
	  			$product_to_download_info = $database->getRow("select * from product_to_download where product_id = '" . (int)$request->gethtml('product_id') . "' and download_id = '" . (int)$result['download_id'] . "'");
			}
			
      		$download_data[] = array(
        		'download_id' => $result['download_id'],
        		'name'        => $result['name'],
        		'product_id'  => (isset($product_to_download_info) ? $product_to_download_info : in_array($result['download_id'], $request->gethtml('download', 'post', array())))
      		);
    	}

    	$view->set('downloads', $download_data);
	
    	$category_data = array();
   
    	$results = $database->getRows("select c.category_id, cd.name, c.parent_id, c.path from category c left join category_description cd on (c.category_id = cd.category_id) where cd.language_id = '" . (int)$language->getId() . "' order by c.path");

    	foreach ($results as $result) {
			if (($request->gethtml('product_id')) && (!$request->isPost())) {
	  			$product_to_category_info = $database->getRow("select * from product_to_category where product_id = '" . (int)$request->gethtml('product_id') . "' and category_id = '" . (int)$result['category_id'] . "'");
			} 		
      		
			$category_data[] = array(
        		'category_id' => $result['category_id'],
        		'name'        => str_repeat('&nbsp;&nbsp;&nbsp;', count(explode('_', $result['path'])) - 1) . $result['name'],
        		'product_id'  => (isset($product_to_category_info) ? $product_to_category_info : in_array($result['category_id'], $request->gethtml('category', 'post', array())))
      		);
    	}
 
    	$view->set('categories', $category_data);
		
        // Related products

    	$related_data = array();
   
    	$results = $database->getRows("select p.product_id, pd.name from product p left join product_description pd on (p.product_id = pd.product_id) where pd.language_id = '" . (int)$language->getId() . "' order by pd.name asc"); // Add Sort order

    	foreach ($results as $result) {
			if (($request->gethtml('product_id')) && (!$request->isPost())) {
	  			$related_info = $database->getRow("select * from related_products where product_id = '" . (int)$request->gethtml('product_id') . "' and related_product_id = '" . (int)$result['product_id'] . "'");
			}
      		
			$related_data[] = array(
        		'product_id' => $result['product_id'],
        		'name'        => $result['name'],
				'relateddata'	=> (isset($related_info) ? $related_info : in_array($result['product_id'], $request->gethtml('relateddata', 'post', array()))));
    	}
 
    	$view->set('relateddata', $related_data);

        // End related products		

 		return $view->fetch('content/product.tpl');
  	}
	
  	function validateForm() {
		$request  =& $this->locator->get('request');
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');
        $validate =& $this->locator->get('validate');
		$database =& $this->locator->get('database');

    	if (!$user->hasPermission('modify', 'product')) {
      		$this->error['message'] = $language->get('error_permission');
    	}
	      
    	foreach ($request->get('name', 'post', array()) as $value) {
            if (!$validate->strlen($value,1,64)) {
                $this->error['name'] = $language->get('error_name');
            }
    	}

		if (!$request->gethtml('date_available_month', 'post') || !$request->gethtml('date_available_day', 'post') || !$request->gethtml('date_available_year', 'post')) {
	  		$this->error['date_available'] = $language->get('error_date_available');
		}
    	elseif (!checkdate($request->gethtml('date_available_month', 'post'), $request->gethtml('date_available_day', 'post'), $request->gethtml('date_available_year', 'post'))) {
	  		$this->error['date_available'] = $language->get('error_date_available');
		}
		
	   if (!($request->gethtml('start_date_month', 'post') === '00' && $request->gethtml('start_date_day', 'post') === '00' && $request->gethtml('start_date_year', 'post') === '0000') && (!checkdate($request->gethtml('start_date_month', 'post'), $request->gethtml('start_date_day', 'post'), $request->gethtml('start_date_year', 'post')))){
			$this->error['start_date'] = $language->get('error_start_date');
		}
	   
	   if (!($request->gethtml('end_date_month', 'post') === '00' && $request->gethtml('end_date_day', 'post') === '00' && $request->gethtml('end_date_year', 'post') === '0000') && (!checkdate($request->gethtml('end_date_month', 'post'), $request->gethtml('end_date_day', 'post'), $request->gethtml('end_date_year', 'post')))){
			$this->error['end_date'] = $language->get('error_end_date');
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
    
    	if (!$user->hasPermission('modify', 'product')) {
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
	  		$session->set('product.search', $request->gethtml('search', 'post'));
		}
	 
		if (($request->has('page', 'post')) || ($request->has('search', 'post'))) {
	  		$session->set('product.page', $request->gethtml('page', 'post'));
		} 
	
		if ($request->has('sort', 'post')) {
	  		$session->set('product.order', (($session->get('product.sort') == $request->gethtml('sort', 'post')) && ($session->get('product.order') == 'asc') ? 'desc' : 'asc'));
		}
		
		if ($request->has('sort', 'post')) {
			$session->set('product.sort', $request->gethtml('sort', 'post'));
		}
				
		$response->redirect($url->ssl('product'));
  	} 	

	function discount() {
		$database =& $this->locator->get('database');
		$language =& $this->locator->get('language');
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		
		$language->load('controller/product.php');
			
		$view = $this->locator->create('template');
	
		$view->set('entry_quantity', $language->get('entry_quantity'));
		$view->set('entry_discount', $language->get('entry_discount'));
		$view->set('entry_percent_discount', $language->get('entry_percent_discount'));
		$view->set('button_add', $language->get('button_add'));
		$view->set('button_remove', $language->get('button_remove'));
						
		$option_data = array();

		$view->set('discount_id', $request->gethtml('discount_id'));
		
		$response->set($view->fetch('content/product_discount.tpl'));
	}	
	function product_seo($product_id, $product_name){
		$generate_seo 	=& $this->locator->get('generateseo');
		$alias = '';
		$alias .= $generate_seo->clean_alias($product_name);
		$query_path = 'controller=product&product_id=' . $product_id;
		$alias .= '.html';
		$generate_seo->_insert_url_alias($query_path, $alias);
	}
	function delete_product_seo($product_id){
		$database 		=& $this->locator->get('database');
		$query_path = 'controller=product&product_id=' . $product_id;
		$database->query("delete from url_alias where query = '".$query_path."'");
	}
	function product_to_category_seo($product_id,$category_id){
		$generate_seo 	=& $this->locator->get('generateseo');
		$database 		=& $this->locator->get('database');
		$language 		=& $this->locator->get('language');
		
		$product_info = $database->getRow("select name as product_name from product_description where product_id = '".$product_id."' and language_id = '".(int)$language->getId()."'");
		$category_info = $database->getRow("select path from category where category_id ='". $category_id."'");
		$categories = explode('_', $category_info['path']);
		$alias = '';
		foreach ($categories as $cat_id){
			$row = $database->getRow("select name as category_name from category_description where category_id = '".$cat_id."' and language_id = '".(int)$language->getId()."'");
			$alias .= $generate_seo->clean_alias($row['category_name']);
			$alias .= '/';
		}
		$alias .= $generate_seo->clean_alias($product_info['product_name']);	
		$alias .= '.html';
		$query_path = 'controller=product&path=' . $category_info['path'] . '&product_id=' . $product_id;
		$generate_seo->_insert_url_alias($query_path, $alias);
	}
	function delete_product_to_category_seo($product_id,$category_id){
		$database 		=& $this->locator->get('database');
		$category_info = $database->getRow("select path from category where category_id ='". $category_id."'");
		$query_path = 'controller=product&path=' . $category_info['path'] . '&product_id=' . $product_id;
		$database->query("delete from url_alias where query = '".$query_path."'");
	}
	function manufacturer_to_product_seo($product_id, $manufacturer_id){
		$generate_seo 	=& $this->locator->get('generateseo');
		$database 		=& $this->locator->get('database');
		$language 		=& $this->locator->get('language');
		$manufacturer_info = $database->getRow("select name from manufacturer where manufacturer_id = '" . $manufacturer_id . "'");
		$product_info = $database->getRow("select name from product_description where product_id = '".$product_id."' and language_id = '".(int)$language->getId()."'");
		$alias = '';
		$query_path = 'controller=product&manufacturer_id=' . $manufacturer_id . '&product_id=' . $product_id;
		$alias = $generate_seo->clean_alias($manufacturer_info['name']). '/';
		$alias .= $generate_seo->clean_alias($product_info['name']);
		$alias .= '.html';
		$generate_seo->_insert_url_alias($query_path, $alias);
	}
	function delete_manufacturer_to_product_seo($product_id, $manufacturer_id){
		$database 		=& $this->locator->get('database');
		$query_path = 'controller=product&manufacturer_id=' . $manufacturer_id . '&product_id=' . $product_id;
		$database->query("delete from url_alias where query = '".$query_path."'");
	}
}
?>