<?php //Manufacturer AlegroCart
class ControllerManufacturer extends Controller { 	
	function index() { 
		$config   =& $this->locator->get('config');
		$currency =& $this->locator->get('currency');
		$customer =& $this->locator->get('customer');
		$language =& $this->locator->get('language');
		$image    =& $this->locator->get('image');
		$module   =& $this->locator->get('module');
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$session  =& $this->locator->get('session');
		$tax      =& $this->locator->get('tax');
		$template =& $this->locator->get('template');
		$url      =& $this->locator->get('url');	
		$head_def =& $this->locator->get('HeaderDefinition');  // New Header
		$this->modelProducts = $this->model->get('model_products');
		$this->modelManufacturer = $this->model->get('model_manufacturer');
		require_once('library/application/string_modify.php');   //new
		//pagination
		if(!$config->get('manufacturer_status')){ RETURN;}
        $session->set('manufacturer.page', $request->has('page')?(int)$request->get('page'):1);
    	$language->load('controller/manufacturer.php');
	
	   	$view = $this->locator->create('template');
		$template->set('title', $language->get('heading_title'));
		$view->set('heading_title', $language->get('heading_title'));
		$view->set('text_error', $language->get('text_error'));
		$view->set('button_continue', $language->get('button_continue'));
		$view->set('continue', $url->href('home'));
		
		if ($request->has('manufacturer_id')){
			$result = $this->modelProducts->getRow_manufacturer($request->gethtml('manufacturer_id'));
			if ($result){
				$manufacturer = array(
					'name'  => $result['name'],
					'manufacturer_id' => $result['manufacturer_id']
					);
				}
			$session->set('manufacturer.name', $manufacturer['name']);
			$session->set('manufacturer.manufacturer_id', $manufacturer['manufacturer_id']);
		}
		$view->set('manufacturer', $session->get('manufacturer.name'));
		if ($request->isPost() && $request->has('sort_order','post')){
			$session->set('manufacturer.sort_order', $request->gethtml('sort_order','post'));
		}
		if ($request->isPost() && $request->has('sort_filter','post')){
			$session->set('manufacturer.sort_filter', $request->gethtml('sort_filter','post'));
		}
		if ($request->isPost() && $request->has('page_rows','post')){
			$session->set('manufacturer.page_rows', (int)$request->gethtml('page_rows','post'));
		}
		if ($request->isPost() && $request->has('max_rows','post')){
			$session->set('manufacturer.max_rows', (int)$request->gethtml('max_rows','post'));
		}
		if ($request->isPost() && $request->has('columns', 'post')){
			$session->set('manufacturer.columns', (int)$request->gethtml('columns', 'post'));
		}
		if ($request->isPost() && $request->has('model', 'post')){
			$session->set('manufacturer.model', $request->gethtml('model', 'post'));
		}
		
		if ($session->get('manufacturer.manufacturer_id')) {
			if ($session->get('manufacturer.columns')){
				$columns = $session->get('manufacturer.columns');
			} else {
				$columns = $config->get('manufacturer_columns');
				$session->set('manufacturer.columns', $columns);
			}			
			if ($session->get('manufacturer.page_rows')){
				$page_rows = (int)$session->get('manufacturer.page_rows');
			} else {
				$page_rows = $config->get('config_max_rows');
			}
			If ($columns > 1){
				$page_rows = (ceil($page_rows/$columns))*$columns;
			}
			$session->set('manufacturer.page_rows', $page_rows);
			if($session->get('manufacturer.max_rows')){
				$max_rows = (int)$session->get('manufacturer.max_rows');
				if ($max_rows < $page_rows && $max_rows > 0){
					$max_rows = $page_rows;
				} else if($max_rows < 0){
					$max_rows = 0;
				}
			} else {
				$max_rows = 0;
			}
			$session->set('manufacturer.max_rows', $max_rows);
			if ($session->get('manufacturer.sort_order')){
				$default_order = $session->get('manufacturer.sort_order');
			} else {
				$default_order = $language->get('entry_ascending');
				$session->set('manufacturer.sort_order', $default_order);
			}
			if ($session->get('manufacturer.sort_filter')){
				$default_filter = $session->get('manufacturer.sort_filter');
			} else {
				$default_filter = $language->get('entry_number');
				$session->set('manufacturer.sort_filter', $default_filter);
			}
			$man_id = explode('_', $session->get('manufacturer.model'));
			if ((int)end($man_id) == $session->get('manufacturer.manufacturer_id')){
				$model = substr($session->get('manufacturer.model'),0,strpos($session->get('manufacturer.model'),"_"));
			} else {
				$model = "all";
			}
			$results = $this->modelManufacturer->get_models($session->get('manufacturer.manufacturer_id'));
			if (count($results) > 1){
				$model_data = array();
				foreach($results as $result){
					$model_data[] = array(
						'model'			=> $result['model'],
						'model_value'	=> $result['model']."_".(int)$session->get('manufacturer.manufacturer_id')
					);
				}
			} else{
				$model_data = "";
			}
			$view->set('model', $model);
			$view->set('models_data', $model_data);			
			$view->set('default_max_rows', $max_rows);
			$view->set('default_page_rows', $page_rows);
			$view->set('default_order', $default_order);
			$view->set('default_filter', $default_filter);
			$view->set('display_lock', $config->get('manufacturer_display_lock'));
			$view->set('default_columns', $columns);
			$view->set('columns', $columns);
			if ($default_filter == $language->get('entry_number')){
				$search_filter = ' order by pd.name ';
			} else {
				$search_filter = ' order by p.price ';
			}
			If ($default_order == $language->get('entry_ascending')){
				$search_order = ' asc ';
			} else {
				$search_order = ' desc ';
			}
			if ($columns == 1){
				$image_width = $config->get('manufacturer_image_width');
				$image_height = $config->get('manufacturer_image_height');
				
			} else if ($columns == 3){
				$image_width = 175;
				$image_height = 175;
			} else {
				$image_width = 140;
				$image_height = 140;
			}
			If($model && $model != "all"){
				$model_sql = " and pd.model like ";
				$model_filter = "'".$model."'";
			} else {
				$model_sql = "";
				$model_filter = "";
			}
			$results = $this->modelManufacturer->get_products($model_sql,$model_filter,$search_filter,$search_order,$page_rows,$max_rows);
			if ($results) {
				$view->set('text_results', $this->modelManufacturer->get_text_results());
        		$product_data = array();
        		foreach ($results as $result) {
					$query = array(
						'manufacturer_id'  => $request->gethtml('manufacturer_id'),
						'product_id' => $result['product_id']
          			);
					// Product Discounts
					$discounts = $this->modelProducts->get_product_discount($result['product_id']);
					$product_discounts = array();
					if ($discounts) {
						foreach($discounts as $discount){
							if($result['special_price'] >0 && date('Y-m-d') >= $result['sale_start_date'] && date('Y-m-d') <= $result['sale_end_date']){
							  $discount_amount = $result['special_price'] * ($discount['discount'] / 100);
							} else {
							  $discount_amount = $result['price'] * ($discount['discount'] / 100);
							}
							$product_discounts[] = array(
							  'discount_quantity' => $discount['quantity'],
							  'discount_percent'  => round($discount['discount']),
							  'discount_amount'  => $currency->format($tax->calculate($discount_amount, $result['tax_class_id'], $config->get('config_tax')))
							);
						}
					}  // End product Discounts	
					// Product Options
					if ($columns == 1){
						$options = $this->modelProducts->get_options($result['product_id'],$result['tax_class_id']);
					} else {
						$options = $this->modelProducts->check_options($result['product_id']);
					} // End Product Options
					// Description
					if ($columns == 1){
						$desc = formatedstring($result['description'],6);
					} else if ($columns == 3){
						if ($result['alt_description']){
							$desc = formatedstring($result['alt_description'],4);
						} else {
							$desc = formatedstring($result['description'],4);
						}
					} else {
						if ($result['alt_description']){
							$desc = strippedstring($result['alt_description'],108);
						} else {
							$desc = strippedstring($result['description'],108);
						}
					} // End Description
          			$product_data[] = array(
            			'name'  => $result['name'],
						'product_id'  => $result['product_id'],  // New
						'description' => $desc,  // New
						'stock_level' => $result['quantity'],	  // New
						'min_qty'	  => $result['min_qty'],     // New
						'product_discounts' => $product_discounts,
            			'href'  => $url->href('product', FALSE, $query),
						'popup'     => $image->href($result['filename']), //New Shared
            			'thumb' => $image->resize($result['filename'], $image_width, $image_height),
						'special_price' => $currency->format($tax->calculate($result['special_price'], $result['tax_class_id'], $config->get('config_tax'))), // New
            			'price' => $currency->format($tax->calculate($result['price'], $result['tax_class_id'], $config->get('config_tax'))),
						'sale_start_date' => $result['sale_start_date'], // New
						'sale_end_date'   => $result['sale_end_date'], // New
						'options'         => $options
          			);
        		}

       			$view->set('products', $product_data);
	
				$breadcrumb = array();

				$breadcrumb[] = array(
					'href'      => $url->href('home'),
					'text'      => $language->get('text_home'),
					'separator' => FALSE
				);	
        		$breadcrumb[] = array(
          			'href'      => $url->href('manufacturer', FALSE, array('manufacturer_id'  => $request->gethtml('manufacturer_id'))),
          			'text'      => $session->get('manufacturer.name'),
          			'separator' => $language->get('text_separator')
        		);
				$view->set('breadcrumbs', $breadcrumb);
				
				$view->set('entry_page', $language->get('entry_page'));
 		       	$view->set('page', $session->get('manufacturer.page'));
				$view->set('onhand', $language->get('onhand'));  // New
      			$view->set('previous' , $language->get('previous_page')); // New Pagination
				$view->set('next' , $language->get('next_page'));  // New Pagination  
				$view->set('pages', $this->modelManufacturer->get_pagination());
				$query=array('manufacturer_id' => $session->get('manufacturer.manufacturer_id'));
		    	$view->set('action', $url->href('manufacturer', FALSE, $query));
				$view->set('sort_filter', $this->sort_filter());
				$view->set('sort_order', $this->sort_order());
				$view->set('first_page', $language->get('first_page'));
				$view->set('last_page', $language->get('last_page'));
				$view->set('total_pages', $this->modelManufacturer->get_pages());  // New pagination
				$view->set('options_text', $language->get('options_text'));
				$view->set('text_quantity_discount', $language->get('text_quantity_discount'));
				$view->set('Add_to_Cart', $language->get('button_add_to_cart'));
				$view->set('Added_to_Cart', $language->get('button_added_to_cart'));
				$view->set('regular_price', $language->get('regular_price'));
				$view->set('sale_price', $language->get('sale_price'));	
				$view->set('text_sort_by',$language->get('text_sort_by'));
				$view->set('text_order', $language->get('text_order'));
				$view->set('text_options', $language->get('text_options'));
				$view->set('text_max_rows', $language->get('text_max_rows'));
				$view->set('text_page_rows', $language->get('text_page_rows'));
				$view->set('text_columns', $language->get('text_columns'));
				$view->set('text_model', $language->get('text_model'));
				$view->set('text_all', $language->get('text_all'));
				$view->set('entry_submit', $language->get('entry_submit'));
				$view->set('display_options', $config->get('manufacturer_options_status'));	
				$view->set('product_options_select', $config->get('manufacturer_options_select')); //New
				// Currency
				$currency_code = $currency->code;
				$symbol_right = $currency->currencies[$currency_code]['symbol_right'];
				$symbol_left = $currency->currencies[$currency_code]['symbol_left'];
				$view->set('symbols', array($symbol_left,$symbol_right));
				$view->set('price_with_options', $language->get('price_with_options'). $symbol_left);
				$view->set('symbol_right', $symbol_right);
				$view->set('symbol_left', $symbol_left);
				$view->set('decimal_place', $currency->currencies[$currency_code]['decimal_place']);
			}
		$view->set('head_def',$head_def);    // New header
		$template->set('head_def',$head_def);    // New Header
		$view->set('addtocart',$config->get('manufacturer_addtocart')); // New
		$view->set('text_enlarge', $language->get('text_enlarge'));// New Shared
		$view->set('this_controller', 'manufacturer'); // New Shared
		if ($columns > 1 && $config->get('manufacturer_options_status')){
			$template->set($module->load('manufactureroptions'));
		}
		$template->set($module->load('manufacturer'));
		$template->set($module->load('popular')); // New
		$template->set($module->load('specials'));	// New
		$template->set('content', $view->fetch('content/manufacturer.tpl'));
    	} else {
		
	  		$template->set('title', $language->get('text_error'));
      		$view->set('heading_title', $language->get('text_error'));
      		$view->set('text_error', $language->get('text_error'));
	  		$template->set('content', $view->fetch('content/error.tpl'));
		}
		$template->set($module->fetch());	
    	$response->set($template->fetch('layout.tpl'));
  		
	}
	function sort_filter(){
		$language =& $this->locator->get('language');	
    	$language->load('controller/manufacturer.php');
		$sort_filter = array();
		$sort_filter[0] = $language->get('entry_number');
		$sort_filter[1] = $language->get('entry_price');
		return $sort_filter;
	}
	function sort_order(){
		$language =& $this->locator->get('language');
    	$language->load('controller/manufacturer.php');
		$sort_order = array();
		$sort_order[0] = $language->get('entry_ascending');
		$sort_order[1] = $language->get('entry_descending');
		return $sort_order;
	}
}
?>