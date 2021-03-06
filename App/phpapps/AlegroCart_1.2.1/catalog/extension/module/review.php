<?php  //ReviewModule AlegroCart
class ModuleReview extends Controller {
	function fetch() {
		$config   =& $this->locator->get('config');
		$head_def =& $this->locator->get('HeaderDefinition');  // New Header
		$image    =& $this->locator->get('image');
		$language =& $this->locator->get('language');
		$request  =& $this->locator->get('request');
		$template =& $this->locator->get('template');  // Added		
		$url      =& $this->locator->get('url');
		$this->modelReview = $this->model->get('model_review');   // Model
		require_once('library/application/string_modify.php');   //new
		
		if ($config->get('review_status')) {
			$review_info = $this->modelReview->get_review();
    		
			if ($review_info) {
				$language->load('extension/module/review.php');
				$view = $this->locator->create('template');
      			$view->set('heading_title', $language->get('heading_title'));
	
     			$view->set('text_rating', $language->get('text_rating', $review_info['rating']));
     			$view->set('name', $review_info['name']);
      			$view->set('rating', $review_info['rating']);
      			$view->set('desciption', strippedstring($review_info['text'], 55));
		
     			$view->set('image', $image->resize($review_info['filename'], $config->get('config_image_width'), $config->get('config_image_height')));

      			$view->set('review', $url->href('review_info', false, array('product_id' =>$review_info['product_id'], 'review_id' => $review_info['review_id'])));

   	   			$view->set('reviews', $url->href('review'));
				$template->set('head_def',$head_def);    // New Header
	  			$view->set('head_def',$head_def);    // New Header
				return $view->fetch('module/review.tpl');
			} else {
				return;
			}
		}
	}
}
?>
