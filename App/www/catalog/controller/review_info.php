<?php // Review Information AlegroCart
class ControllerReviewInfo extends Controller { 
	function index() {
		$config   =& $this->locator->get('config');
		$currency =& $this->locator->get('currency');
		$language =& $this->locator->get('language');
		$image    =& $this->locator->get('image');
		$module   =& $this->locator->get('module');
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$tax      =& $this->locator->get('tax');
		$template =& $this->locator->get('template');
		$url      =& $this->locator->get('url');
		$head_def =& $this->locator->get('HeaderDefinition');  // New Header
		$this->modelReview = $this->model->get('model_review');   // Model
				  
    	$language->load('controller/review_info.php');
 		
		$review_info = $this->modelReview->getRow_review($request->gethtml('review_id'));
    	
		if ($review_info) {
	  		$template->set('title', $language->get('heading_title', $review_info['name'], $review_info['author']));
 
      		$view = $this->locator->create('template');
      		$view->set('heading_title', $language->get('heading_title', $review_info['name'], $review_info['author']));

      		$view->set('text_enlarge', $language->get('text_enlarge'));
      		$view->set('text_author', $language->get('text_author'));
      		$view->set('text_date_added', $language->get('text_date_added'));
      		$view->set('text_rating', $language->get('text_rating'));
      		$view->set('text_out_of', $language->get('text_out_of', $review_info['rating']));

      		$view->set('button_reviews', $language->get('button_reviews'));
      		$view->set('button_write', $language->get('button_write'));

      		$view->set('name', $review_info['name']);

			$view->set('href', $url->href('product', FALSE, array('product_id' => $review_info['product_id'])));

      		$view->set('price', $currency->format($tax->calculate($review_info['price'], $review_info['tax_class_id'], $config->get('config_tax'))));
			$view->set('special_price', $review_info['special_price']>0 ? $currency->format($tax->calculate($review_info['special_price'], $review_info['tax_class_id'], $config->get('config_tax'))):""); // New
			
			$view->set('popup', $image->href($review_info['filename']));
      		$view->set('thumb', $image->resize($review_info['filename'], 160,160));
      		
      		$view->set('author', $review_info['author']);
      		$view->set('text', nl2br($review_info['text']));
      		$view->set('rating', $review_info['rating']);

      		$view->set('date_added', $language->formatDate($language->get('date_format_long'), strtotime($review_info['date_added'])));
      
	  		$query = array(
	    		'product_id' => $review_info['product_id']
				//'review_id'  => $request->gethtml('review_id')
	  		);
	  
      		$view->set('review', $url->href('review', FALSE, $query));

      		$query = array(
        		'product_id' => $review_info['product_id']
        		//'review_id'  => $request->gethtml('review_id')
      		); 

      		$view->set('write', $url->href('review_write', FALSE, $query));
			$view->set('head_def',$head_def);    // New Header
			$template->set('head_def',$head_def);    // New Header	
	  		$template->set('content', $view->fetch('content/review_info.tpl'));
			
	  		$template->set($module->fetch());
	
      		$response->set($template->fetch('layout.tpl'));	  
    	} else {  // Error no Reviews
      		$template->set('title', $language->get('text_error'));
      		$view = $this->locator->create('template');
      		$view->set('heading_title', $language->get('text_error'));
      		$view->set('text_error', $language->get('text_error'));
      		$view->set('button_continue', $language->get('button_continue'));
      		$view->set('continue', $url->href('home'));
			$view->set('head_def',$head_def);    // New Header
			$template->set('head_def',$head_def);    // New Header	
	  		$template->set('content', $view->fetch('content/error.tpl'));
			
	  		$template->set($module->fetch());
      		$response->set($template->fetch('layout.tpl'));   	  
    	}
  	}
}
?>
