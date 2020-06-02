<?php //Search AlegroCart
class ControllerSearch extends Controller {
	function initialize() {//$this->initialize();  Required in each function
		$this->modelProducts = $this->model->get('model_products');
		$this->modelSearch = $this->model->get('model_search');
	}
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
		require_once('library/application/string_modify.php');   //new
		$this->initialize(); // Required 
		//pagination
        $session->set('search.page', $request->has('page')?(int)$request->get('page'):1);
    	$language->load('controller/search.php');
	
		$template->set('title', $language->get('heading_title'));
	  	  
    	$view = $this->locator->create('template');

    	$view->set('heading_title', $language->get('heading_title'));
  
    	$view->set('text_critea', $language->get('text_critea'));
    	$view->set('text_search', $language->get('text_search'));
		$view->set('text_keywords', $language->get('text_keywords'));
		$view->set('text_error', $language->get('text_error'));
	 
		$view->set('entry_search', $language->get('entry_search'));
    	$view->set('entry_description', $language->get('entry_description'));
    	$view->set('entry_page', $language->get('entry_page'));
		  
    	$view->set('button_search', $language->get('button_search'));
    
    	$view->set('action', $url->href('search', 'search_page'));

		// Set Session Variables for options
		if ($session->get('search.columns')){
			$columns = $session->get('search.columns');
		} else {
			$columns = $config->get('search_columns');
			$session->set('search.columns', $columns);
		}
		if ($session->get('search.page_rows')){
			$page_rows = (int)$session->get('search.page_rows');
		} else {
			$page_rows = $config->get('config_max_rows');
		}
		If ($columns > 1){
			$page_rows = (ceil($page_rows/$columns))*$columns;
		}
		$session->set('search.page_rows', $page_rows);
		if($session->has('search.max_rows') && $session->get('search.max_rows')>= 0){
			$max_rows = (int)$session->get('search.max_rows');
			if ($max_rows < $page_rows && $max_rows > 0){
				$max_rows = $page_rows;
			} else if($max_rows < 0){
				$max_rows = 0;
			}
		} else {
			$max_rows = $config->get('search_limit');
		}
		$session->set('search.max_rows', $max_rows);
		if ($session->get('search.sort_order')){
			$default_order = $session->get('search.sort_order');
		} else {
			$default_order = $language->get('entry_ascending');
			$session->set('search.sort_order', $default_order);
		}
		if ($session->get('search.sort_filter')){
			$default_filter = $session->get('search.sort_filter');
		} else {
			$default_filter = $language->get('entry_number');
			$session->set('search.sort_filter', $default_filter);
		}
		if ($default_filter == $language->get('entry_number')){
			$search_filter = ' order by p.sort_order, pd.name ';
		} else {
			$search_filter = ' order by p.price ';
		}
		If ($default_order == $language->get('entry_ascending')){
			$search_order = ' asc ';
		} else {
			$search_order = ' desc ';
		}
		// Manufacturer Session
		$man_search = explode('*_*', $session->get('search.manufacturer'));
		if (end($man_search) == $session->get('search.search')){
			$manufacturer_id = (int)substr($session->get('search.manufacturer'),0,strpos($session->get('search.manufacturer'),"*_*"));
			if ($manufacturer_id > 0){
				$manufacturer_filter = "'".$manufacturer_id."'";
				$manufacturer_sql = " and p.manufacturer_id = ";
			} else {
				$manufacturer_filter = "";
				$manufacturer_sql = "";
				$session->set('search.manufacturer', 0 . "*_*".$session->get('search.search'));
			}
		} else {
			$manufacturer_filter = "";
			$manufacturer_sql = "";
			$session->set('search.manufacturer', 0 . "*_*".$session->get('search.search'));
		}
		// Model Session
		$model_search = explode('*_*', $session->get('search.model'));
		if (end($model_search) == $session->get('search.search')){
			$model = substr($session->get('search.model'),0,strpos($session->get('search.model'),"*_*"));
			If($model && $model != "all"){
				$model_sql = " and pd.model like ";
				$model_filter = "'".$model."'";
			} else {
				$model_sql = "";
				$model_filter = "";
				$session->set('search.model', 'all'."*_*".$session->get('search.search'));
			}
		} else {
			$model_sql = "";
			$model_filter = "";
			$session->set('search.model', 'all'."*_*".$session->get('search.search'));
		}
		$view->set('model', $session->get('search.model'));
		$view->set('manufacturer', $session->get('search.manufacturer'));
		// End Set Session Variables for options
		
		// Image size
		if ($columns == 1){
			$image_width = $config->get('search_image_width');
			$image_height = $config->get('search_image_height');
		} else if ($columns == 3){
			$image_width = 175;
			$image_height = 175;
		} else {
			$image_width = 140;
			$image_height = 140;
		}    // End image

		$view->set('max_rows', $max_rows);
		$view->set('columns', $columns);
		$view->set('page_rows', $page_rows);
		$view->set('default_order', $default_order);
		$view->set('default_filter', $default_filter);

		$search = wildcardsearch($session->get('search.search'));
		$view->set('search', $search);
		$description = $session->get('search.description');
		$view->set('description', $description);
		if ($description == "on"){
			$description_sql = "or pd.description like ";
			$search_description = $search;
		}else {
			$description_sql = '';
			$search_description = '';
		}

		if ($session->get('search.search')) {
			$results = $this->modelSearch->get_products($search,$description_sql,$search_description,$manufacturer_sql,$manufacturer_filter,$model_sql,$model_filter,$search_filter,$search_order,$page_rows,$max_rows);

			if ($results) {
				$view->set('text_results', $this->modelSearch->get_text_results());
		        
        		$product_data = array();

        		foreach ($results as $result) {
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
						'description' => $desc,   //New
						'stock_level' => $result['quantity'],	// New
						'min_qty'	  => $result['min_qty'],     // New
						'product_discounts' => $product_discounts,
            			'href'  => $url->href('product', FALSE, array('product_id' => $result['product_id'])),
						'popup'     => $image->href($result['filename']), //New Shared
            			'thumb' => $image->resize($result['filename'], $image_width, $image_height),
						'special_price' => $currency->format($tax->calculate($result['special_price'], $result['tax_class_id'], $config->get('config_tax'))), // New
            			'price' => $currency->format($tax->calculate($result['price'], $result['tax_class_id'], $config->get('config_tax'))),
						'sale_start_date' => $result['sale_start_date'], // New
						'sale_end_date'   => $result['sale_end_date'], // New
						'options'         => $options
          			);
        		}
				if($max_rows == $this->modelSearch->get_total()){
					$maximum_results = 1;
				} else {
					$maximum_results = 0;
				}
				$view->set('maximum_results', $maximum_results);
       			$view->set('products', $product_data);
 		       	$view->set('page', $session->get('search.page'));
				$view->set('onhand', $language->get('onhand'));  // New
      			$view->set('previous' , $language->get('previous_page')); // New Pagination
				$view->set('next' , $language->get('next_page'));  // New Pagination  
				$view->set('first_page', $language->get('first_page'));
				$view->set('last_page', $language->get('last_page'));
				
				$view->set('pages', $this->modelSearch->get_pagination());
        		$view->set('total_pages', $this->modelSearch->get_pages());  // New pagination
				$view->set('options_text', $language->get('options_text'));
				$view->set('product_options_select', $config->get('search_options_select')); //New
				$view->set('text_options', $language->get('text_options'));
				$view->set('text_quantity_discount', $language->get('text_quantity_discount'));
				$view->set('Add_to_Cart', $language->get('button_add_to_cart'));
				$view->set('Added_to_Cart', $language->get('button_added_to_cart'));
				$view->set('regular_price', $language->get('regular_price'));
				$view->set('sale_price', $language->get('sale_price'));
				// Currency
				$currency_code = $currency->code;
				$symbol_right = $currency->currencies[$currency_code]['symbol_right'];
				$symbol_left = $currency->currencies[$currency_code]['symbol_left'];
				$view->set('symbols', array($symbol_left,$symbol_right));
				$view->set('price_with_options', $language->get('price_with_options'). $symbol_left);
				$view->set('symbol_right', $symbol_right);
				$view->set('symbol_left', $symbol_left);
				$view->set('decimal_place', $currency->currencies[$currency_code]['decimal_place']); // End Currency
			}
		}
		
		$view->set('addtocart',$config->get('search_addtocart')); // New Shared
		$view->set('text_inc_results', $language->get('text_inc_results')); //New
		$view->set('text_max_reached', $language->get('text_max_reached')); //New
		$view->set('entry_max_results', $language->get('entry_max_results')); //New
		$view->set('head_def',$head_def);    // New header
		$template->set('head_def',$head_def);    // New Header 
		$view->set('text_enlarge', $language->get('text_enlarge'));// New Shared
		$view->set('this_controller', 'search'); // New Shared
		$template->set('content', $view->fetch('content/search.tpl'));
		$template->set($module->load('manufacturer'));
		$template->set($module->load('searchoptions'));
		$template->set($module->load('popular')); // New
		$template->set($module->load('specials'));	// New
		$template->set($module->fetch());

    	$response->set($template->fetch('layout.tpl'));
  	}

	function model(){
		$language =& $this->locator->get('language');
		$request  =& $this->locator->get('request');
		$session  =& $this->locator->get('session');
		$response =& $this->locator->get('response');
		require_once('library/application/string_modify.php');   //new
		$this->initialize(); // Required 
    	$language->load('extension/module/searchoptions.php');
		
		$text_all = $language->get('text_all');
		$text_model = $language->get('text_model');
		$search = wildcardsearch($session->get('search.search'));		
		$description = $session->get('search.description');
		if ($description == "on"){
			$description_sql = "or pd.description like ";
			$search_description = $search;
		}else {
			$description_sql = '';
			$search_description = '';
		}
		$manufacturer_id = (int)substr($request->gethtml('manufacturer'),0,strpos($request->gethtml('manufacturer'),"*_*"));
		$model = substr($session->get('search.model'),0,strpos($session->get('search.model'),"*_*"));		
		$search = wildcardsearch($session->get('search.search'));
		if ($manufacturer_id > 0){
			$manufacturer_sql = " and p.manufacturer_id = ";
			$manufacturer_filter = "'".$manufacturer_id."'";
		} else {
			$manufacturer_sql = "";
			$manufacturer_filter = "";
		}
		$results = $this->modelSearch->get_model($search,$description_sql,$search_description,$manufacturer_sql,$manufacturer_filter);		
		if (count($results) > 1){
			$models_data = array();
			foreach($results as $result){
				$models_data[] = array(
					'model'			=> $result['model'],
					'model_value'	=> $result['model']."*_*".$session->get('search.search')
				);
			}
		} else {
			$models_data = "";
		}		
		if ($models_data){
			$output = '<tr><td>' . $text_model . '</td></tr>'."\n";
			$output .= '<tr><td style="width: 190px;">';
			$output .= '<select style="width: 180px;" name="model">'."\n";		
			$output .= '<option value="all">';
			$output .= $text_all . '</option>'."\n";
			foreach ($models_data as $model_data){
				$output .= '<option value="'. $model_data['model_value'].'">';
				$output .= $model_data['model'] . '</option>'."\n";
			}
			$output .= '</select><div class="divider"></div></td></tr>'."\n";
		} else {
			$output = "";
		}
		$response->set($output);
	}
	
	function search_page() {
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$session  =& $this->locator->get('session');
		$url      =& $this->locator->get('url');

		$query=array();
		if ($request->has('search', 'post')) {
			$session->set('search.search', $request->sanitize('search', 'post'));
	  	}
		if ($request->has('description', 'post')) {
			$session->set('search.description', "on"); 
		} else {
			$session->set('search.description', "off");
		}
		if ($request->isPost() && $request->has('sort_order','post')){
			$session->set('search.sort_order', $request->gethtml('sort_order','post'));
		}
		if ($request->isPost() && $request->has('sort_filter','post')){
			$session->set('search.sort_filter', $request->gethtml('sort_filter','post'));
		}
		if ($request->isPost() && $request->has('page_rows','post')){
			$session->set('search.page_rows', (int)$request->gethtml('page_rows','post'));
		}		
		if ($request->isPost() && $request->has('max_rows','post')){
			$session->set('search.max_rows', (int)$request->gethtml('max_rows','post'));
		}
		if ($request->isPost() && $request->has('columns', 'post')){
			$session->set('search.columns', (int)$request->gethtml('columns', 'post'));
		}
		if ($request->isPost() && $request->has('manufacturer', 'post')){
			$session->set('search.manufacturer', $request->gethtml('manufacturer', 'post'));
		} else {
			$session->set('search.manufacturer',  0 . "*_* ");
		}
		if ($request->isPost() && $request->has('model', 'post')){
			$session->set('search.model', $request->gethtml('model', 'post'));
		} else {
			$session->set('search.model',  "all*_* ");
		}
		$response->redirect($url->href('search', FALSE, $query));
	}
}
?>