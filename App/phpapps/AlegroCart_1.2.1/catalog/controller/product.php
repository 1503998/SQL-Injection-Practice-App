<?php  // Product ALegroCart 
class ControllerProduct extends Controller {
	function index() { 
		$cart     =& $this->locator->get('cart');
		$config   =& $this->locator->get('config');
		$currency =& $this->locator->get('currency');
		$language =& $this->locator->get('language');
		$image    =& $this->locator->get('image');
		$module   =& $this->locator->get('module');
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$shipping =& $this->locator->get('shipping');
		$tax      =& $this->locator->get('tax');
		$template =& $this->locator->get('template');
		$weight   =& $this->locator->get('weight');
		$url      =& $this->locator->get('url');
		$head_def =& $this->locator->get('HeaderDefinition');  // New Header
		$this->modelProducts = $this->model->get('model_products');   // Model
		$this->modelCategory = $this->model->get('model_category');
		require_once('library/application/string_modify.php');   //new

    	if ($request->isPost() && $request->has('product_id', 'post')) {
      		$cart->add($request->gethtml('product_id', 'post'), ($request->gethtml('quantity', 'post') > 0) ? $request->gethtml('quantity', 'post') : 1, $request->gethtml('option', 'post'));
      		$response->redirect($url->href('cart'));
    	}

    	$language->load('controller/product.php');
		
		$product_info = $this->modelProducts->getRow_product((int)$request->gethtml('product_id'));

    	
		if ($product_info) {
			$this->modelProducts->update_viewed((int)$request->gethtml('product_id'));
      		$breadcrumb = array();
      		$breadcrumb[] = array(
        		'href'      => $url->href('home'),
        		'text'      => $language->get('text_home'),
        		'separator' => FALSE
      		);
     		if ($request->gethtml('path')) {
        		foreach (explode('_', $request->gethtml('path')) as $category_id) {
					$category_info =$this->modelCategory->getRow_category_name($category_id);
          			$breadcrumb[] = array(
						'href'      => $url->href('category', FALSE, array('path' => $category_info['path'])),
            			'text'      => $category_info['name'],
            			'separator' => $language->get('text_separator')
          			);
        		}
      		}
			if ($request->gethtml('manufacturer_id')){
				$result = $this->modelProducts->getRow_manufacturer((int)$request->gethtml('manufacturer_id'));
				if ($result){
					$breadcrumb[] = array(
						'href'      => $url->href('manufacturer', FALSE, array('manufacturer_id'  => $request->gethtml('manufacturer_id'))),
						'text'      => $result['name'],
						'separator' => $language->get('text_separator')
					);
				}
			}
      		$query = array(
			    'manufacturer_id' => $request->gethtml('manufacturer_id'),
        		'path'       => $request->gethtml('path'),
        		'product_id' => $request->gethtml('product_id')
      		);
      		$breadcrumb[] = array(
        		'href'      => $url->href('product', FALSE, $query),
        		'text'      => $product_info['name'],
        		'separator' => $language->get('text_separator')
      		);

	  		$template->set('title', $product_info['name']);
      		$view = $this->locator->create('template');
      		$view->set('heading_title', $product_info['name']);
			$view->set('breadcrumbs', $breadcrumb);
      		$view->set('text_enlarge', $language->get('text_enlarge'));
      		$view->set('text_images', $language->get('text_images'));
      		$view->set('text_options', $language->get('text_options'));
            $view->set('text_min_qty', $language->get('text_min_qty'));
			$view->set('text_manufacturer', $language->get('text_manufacturer'));
			$view->set('text_quantity_discount', $language->get('text_quantity_discount'));
			$view->set('text_qty_discount', $language->get('text_qty_discount'));
			$view->set('text_discount', $language->get('text_discount'));
			$view->set('text_sale_start', $language->get('text_sale_start'));
			$view->set('text_sale_end', $language->get('text_sale_end'));
			$view->set('text_date', $language->get('text_date'));
			$view->set('text_shipping_yes', $language->get('text_shipping_yes'));
			$view->set('text_shipping_no', $language->get('text_shipping_no'));
			$view->set('text_weight', $language->get('text_weight'));			
			$view->set('text_review_by', $language->get('text_review_by'));
      		$view->set('text_date_added', $language->get('text_date_added'));
      		$view->set('text_rating', $language->get('text_rating'));
      		$view->set('text_error', $language->get('text_empty'));	
      		$view->set('button_reviews', $language->get('button_reviews'));
      		$view->set('button_add_to_cart', $language->get('button_add_to_cart'));
			$view->set('product_number', $language->get('product_number'));
			$view->set('quantity_available', $language->get('quantity_available'));
			$view->set('standard_price', $language->get('standard_price'));
			$view->set('tab_description', $language->get('tab_description')); // Tabs
			$view->set('tab_technical', $language->get('tab_technical'));
			$view->set('tab_images', $language->get('tab_images'));
			$view->set('tab_information', $language->get('tab_information'));
			$view->set('tab_reviews', $language->get('tab_reviews'));
			$view->set('tab_write', $language->get('tab_write'));
		
      		
      		$query = array(
        		'path'       => $request->gethtml('path'),
        		'product_id' => $request->gethtml('product_id')
      		);
     		$view->set('action', $url->href('product', FALSE, $query));

			$view->set('weight',$weight->format($weight->convert($product_info['weight'],$product_info['weight_class_id'], $config->get('config_weight_class_id')),$config->get('config_weight_class_id')));
			$view->set('shipping',$product_info['shipping']); //New
      		$view->set('description', formatedstring($product_info['description'],40));  // New
			$view->set('technical', formatedstring($product_info['technical'],40));  // New
			if (isset($product_info['alt_description'])){
			  $view->set('alt_description', formatedstring($product_info['alt_description'],4)); // New
			}
			$view->set('onhand', $language->get('onhand'));  // New			
			$view->set('Add_to_Cart', $language->get('button_add_to_cart')); // New
			$view->set('Added_to_Cart', $language->get('button_added_to_cart'));  // New
			$view->set('regular_price', $language->get('regular_price'));
			$view->set('sale_price', $language->get('sale_price'));			
			$view->set('stock_level', $product_info['quantity']); // New	
			$view->set('meta_title', $product_info['meta_title']); // New
			$view->set('meta_description', $product_info['meta_description']); // New
			$view->set('meta_keywords', $product_info['meta_keywords']); // New
			$view->set('product_options_select', $config->get('product_options_select')); //New
            //  Product Discounts
			$results = $this->modelProducts->get_product_discount((int)$request->gethtml('product_id'));
			$product_discounts = array();
			if ($results) {
          	  foreach($results as $result){
			    if($product_info['special_price'] >0 && date('Y-m-d') >= $product_info['sale_start_date'] && date('Y-m-d') <= $product_info['sale_end_date']){
				  $discount_amount = $product_info['special_price'] * ($result['discount'] / 100);
				} else {
				  $discount_amount = $product_info['price'] * ($result['discount'] / 100);
			    }
			  
				$product_discounts[] = array(
				  'discount_quantity' => $result['quantity'],
				  'discount_percent' => (round($result['discount']*10))/10,
				  'discount_amount'  => $currency->format($tax->calculate($discount_amount, $product_info['tax_class_id'], $config->get('config_tax')))
				);
			  }
			  $view->set('product_discounts',$product_discounts);
			}
			// New manufaturer
			$result = $this->modelProducts->getRow_manufacturer((int)$product_info['manufacturer_id']);
			if ($result){
			     $manufacturer = array(
					'name'  => $result['name']
			    );
				$view->set('manufacturer', $manufacturer['name']);
			}
			// Currency
			$currency_code = $currency->code;
			$symbol_right = $currency->currencies[$currency_code]['symbol_right'];
			$symbol_left = $currency->currencies[$currency_code]['symbol_left'];
			$view->set('symbols', array($symbol_left,$symbol_right));
			$view->set('price_with_options', $language->get('price_with_options'). $symbol_left);
			$view->set('symbol_right', $symbol_right);
			$view->set('symbol_left', $symbol_left);
			$view->set('decimal_place', $currency->currencies[$currency_code]['decimal_place']);
			
      		$image_data = array(); // Additional Images
			$results = $this->modelProducts->get_additional_images((int)$request->gethtml('product_id'));
      		foreach ($results as $result) {
        		$image_data[] = array(
          			'title' => $result['title'],
          			'popup' => $image->href($result['filename']),
          			'thumb' => $image->resize($result['filename'], $config->get('additional_image_width'), $config->get('additional_image_height')),
        		);
      		}
			
			$product_data = array(
				'product_id'=> $request->gethtml('product_id'),
				'thumb'     => $image->resize($product_info['filename'], $config->get('product_image_width'), $config->get('product_image_height')),
				'name'      => $product_info['name'],
				'popup'     => $image->href($product_info['filename']),
				'min_qty'   => isset($product_info['min_qty'])?$product_info['min_qty']:1,
						'special_price' => $currency->format($tax->calculate($product_info['special_price'], $product_info['tax_class_id'], $config->get('config_tax'))), // New

            			'price' => $currency->format($tax->calculate($product_info['price'], $product_info['tax_class_id'], $config->get('config_tax'))),
						'sale_start_date' => $product_info['sale_start_date'], // New
						'sale_end_date'   => $product_info['sale_end_date'], // New
				'options'         => $this->modelProducts->get_options($product_info['product_id'],$product_info['tax_class_id'])
			);
			$view->set('product',$product_data);
			
			
			if ($config->get('review_status')) {    //Review Write
			  $view->set('review_status', true);
			  $view->set('text_write', $language->get('text_write'));
			  $view->set('write', $url->href('review_write', false, array('product_id' => $request->gethtml('product_id'))));
			} else {
			  $view->set('review_status', false);			  
			}
			$view->set('review_data', $this->review());
			$view->set('maxrow', count($this->review()));
      		$view->set('images', $image_data);
			$view->set('head_def',$head_def);    // New Header
			$view->set('product_addtocart',$config->get('product_addtocart')); //New	
			$view->set('columns', 1);
			$view->set('this_controller', 'product'); //New for Shared		
			$template->set('head_def',$head_def);    // New Header
			$template->set($module->load('popular'));		
			$template->set($module->load('manufacturer'));	
			if ($product_info['related']){
				$template->set($module->load('related')); //New Related		
			} else {
				$template->set($module->load('specials'));// New Specials		
			}
	  		$template->set($module->fetch());			
	  		$template->set('content', $view->fetch('content/product.tpl'));
	  		$response->set($template->fetch('layout.tpl'));
			
    	} else {
		
      		$template->set('title', $language->get('text_error'));

      		$view = $this->locator->create('template');

      		$view->set('heading_title', $language->get('text_error'));

      		$view->set('text_error', $language->get('text_error'));

      		$view->set('button_continue', $language->get('button_continue'));

      		$view->set('continue', $url->href('home'));
      
	  		$template->set($module->fetch());
	  	  
	  		$template->set('content', $view->fetch('content/error.tpl'));
	  
	  		$response->set($template->fetch('layout.tpl'));	
    	}
  	}

	function review(){		
		$database =& $this->locator->get('database');		
		$language =& $this->locator->get('language');
		$image    =& $this->locator->get('image');
		$request  =& $this->locator->get('request');
		$url      =& $this->locator->get('url');

		$results = $this->modelProducts->get_reviews((int)$request->gethtml('product_id'));
		$review_data = array();
		foreach ($results as $result) {
        		$review_data[] = array(
          			'href'       => $url->href('review_info', FALSE, array('review_id' => $result['review_id'])),
          			'name'       => $result['name'],
          			'text'       => trim(substr(strip_tags($result['text']), 0, 1000)),
          			'rating'     => $result['rating'],
          			'out_of'     => $language->get('text_out_of', $result['rating']),
          			'author'     => $result['author'],
          			'date_added' => $language->formatDate($language->get('date_format_long'), strtotime($result['date_added']))
        		);
      		}
		return $review_data;
	}
}
?>