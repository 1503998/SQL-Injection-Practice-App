<?php //Review AlegroCart
class ControllerReview extends Controller {
	function index() {  
		$config   =& $this->locator->get('config');
		$currency =& $this->locator->get('currency');
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
		$this->modelReview = $this->model->get('model_review');   // Model
		//pagination
        $session->set('review.page', $request->has('page')?(int)$request->get('page'):1);

		$language->load('controller/review.php');

		$results = $this->modelReview->get_reviews($session->get('review.page'));
 
    	if ($results) {
      		$view = $this->locator->create('template');

      		$product_info = $this->modelReview->get_product($request->gethtml('product_id'));
	  
	  		$template->set('title', $language->get('heading_title', $product_info['name']));

      		$view->set('heading_title', $language->get('heading_title', $product_info['name']));

      		$view->set('price', $currency->format($tax->calculate($product_info['price'], $product_info['tax_class_id'], $config->get('config_tax'))));
			
			$view->set('special_price', $product_info['special_price']>0 ? $currency->format($tax->calculate($product_info['special_price'], $product_info['tax_class_id'], $config->get('config_tax'))): ""); // New
      		$view->set('text_results', $this->modelReview->get_text_results());
      		$view->set('text_review_by', $language->get('text_review_by'));
      		$view->set('text_date_added', $language->get('text_date_added'));
      		$view->set('text_rating', $language->get('text_rating'));

      		$view->set('entry_page', $language->get('entry_page'));

      		$view->set('action', $url->href('review', FALSE, array('product_id' => $request->gethtml('product_id'))));

      		$review_data = array();

      		foreach ($results as $result) {
        		$review_data[] = array(
          			'href'       => $url->href('review_info', FALSE, array('product_id' => $result['product_id'], 'review_id' => $result['review_id'])),
          			'name'       => $result['name'],
          			'thumb'      => $image->resize($result['filename'], $config->get('config_image_width'), $config->get('config_image_height')),
          			'text'       => trim(substr(strip_tags($result['text']), 0, 150)) . '...',
          			'rating'     => $result['rating'],
          			'out_of'     => $language->get('text_out_of', $result['rating']),
          			'author'     => $result['author'],
          			'date_added' => $language->formatDate($language->get('date_format_long'), strtotime($result['date_added']))
        		);
      		}

      		$view->set('reviews', $review_data);

      		$view->set('page', $session->get('review.page'));
      		$view->set('pages', $this->modelReview->get_pagination());
			$view->set('total_pages', $this->modelReview->get_pages());
			$view->set('previous' , $language->get('previous_page')); // New Pagination
			$view->set('next' , $language->get('next_page'));  // New Pagination  
			$view->set('first_page', $language->get('first_page'));
			$view->set('last_page', $language->get('last_page'));
			$view->set('head_def',$head_def);    // New Header
			$template->set('head_def',$head_def);    // New Header		
	  		$template->set('content', $view->fetch('content/review.tpl'));
				  
	  		$template->set($module->fetch());
      		$response->set($template->fetch('layout.tpl'));   
    	} else {
      		$template->set('title', $language->get('text_empty'));
 
      		$view = $this->locator->create('template');
      		$view->set('heading_title', $language->get('text_empty'));
      		$view->set('text_error', $language->get('text_empty'));
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
