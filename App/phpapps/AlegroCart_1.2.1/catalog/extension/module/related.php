<?php  // Related Products AlegroCart
class ModuleRelated extends Controller {
	function fetch() {
		$config   =& $this->locator->get('config');
		$currency =& $this->locator->get('currency');
		$database =& $this->locator->get('database');
		$language =& $this->locator->get('language');
		$image    =& $this->locator->get('image');
		$tax      =& $this->locator->get('tax');
		$url      =& $this->locator->get('url');
		$request  =& $this->locator->get('request');
		$template =& $this->locator->get('template');  // Added		
		$rand     =& $this->locator->get('randomnumber');  //New intialize Random class
		$head_def =& $this->locator->get('HeaderDefinition');  // New Header
		$this->modelProducts =& $this->model->get('model_products');   // Model
		require_once('library/application/string_modify.php');   //New 		
    	if ($config->get('related_status')) {

    	  	$language->load('extension/module/related.php');
      		$view = $this->locator->create('template');
      		$view->set('heading_title', $language->get('heading_title'));
			$view->set('onhand', $language->get('onhand'));

            if ($config->get('related_limit') == '0') {
                $limit = '';
            } else {
				$limit = (int)$config->get('related_limit');   //New return number to display
            }

           $results = $this->modelProducts->get_related((int)$request->gethtml('product_id'));
			
      		$product_data = array();			
    	  	foreach ($results as $result) {
				if ($result['alt_description']){
				  $desc = formatedstring($result['alt_description'],4);
				} else {
				  $desc = formatedstring($result['description'],4);
				}
    	  		$product_data[] = array(
    	  			'name'  => $result['name'],
					'product_id'  => $result['product_id'],  // New
    	  			'description'  => $desc,
					'stock_level' => $result['quantity'],
					'min_qty'	  => $result['min_qty'],      // New
    	  			'href'  => $url->href('product', FALSE, array('product_id' => $result['product_id'])),
					'popup'     => $image->href($result['filename']), //New Shared
					'thumb' => $image->resize($result['filename'],$config->get('related_image_width'), $config->get('related_image_height')),
				    'special_price' => $currency->format($tax->calculate($result['special_price'], $result['tax_class_id'], $config->get('config_tax'))), // New
                	'price' => $currency->format($tax->calculate($result['price'], $result['tax_class_id'], $config->get('config_tax'))),
					'sale_start_date' => $result['sale_start_date'], // New
					'sale_end_date'   => $result['sale_end_date'], // New
					'options' => $this->modelProducts->check_options($result['product_id']) //New
    	  		);
    	  	}
			$maxrow = count($product_data)-1;     //New block Get random records from pool of Related and define in tpl
		
			if ($product_data) {
				if ($maxrow < $limit){
					$view->set('products', $product_data);
				} else {
					$I = 0;
					while ($I < $limit){
						$rand->uRand(0,$maxrow);
						$I ++;
					}
					$I = 0;
					$product_rand = array();
					foreach ($rand->RandomNumbers as $mykey){
						$product_rand[$I] = $product_data[$mykey];
						$I ++;			
					}
					$view->set('products', $product_rand);
				}				
			} else {
				$view->set('text_notfound', $language->get('text_notfound'));
			}
			$rand->clearrand();                          // End of new block
			$view->set('options_text', $language->get('options_text'));
			$view->set('Add_to_Cart', $language->get('button_add_to_cart'));
			$view->set('Added_to_Cart', $language->get('button_added_to_cart'));
			$view->set('regular_price', $language->get('regular_price'));
			$view->set('sale_price', $language->get('sale_price'));			
			$view->set('head_def',$head_def);    // New Header
			$template->set('head_def',$head_def);    // New Header
			$view->set('add_enable',TRUE); // New Shared
			$view->set('text_enlarge', $language->get('text_enlarge'));// New Shared
			$view->set('this_controller', 'related'); // New Shared
			$view->set('addtocart',$config->get('related_addtocart')); // New Shared
            return $view->fetch('module/module_column.tpl');
    	}
	}
}
?>