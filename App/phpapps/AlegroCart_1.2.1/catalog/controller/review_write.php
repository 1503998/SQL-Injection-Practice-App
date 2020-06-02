<?php //ReviewWrite AlegroCart
class ControllerReviewWrite extends controller { 
	var $error = array();
	function initialize() {//$this->initialize();  Required in each function 
		if(!isset($this->dataLink)){
			$this->dataLink = TRUE;
			$this->address  =& $this->locator->get('address');
			$this->config   =& $this->locator->get('config');
			$this->currency =& $this->locator->get('currency');
			$this->customer =& $this->locator->get('customer');
			$this->head_def =& $this->locator->get('HeaderDefinition');  // New Header
			$this->image    =& $this->locator->get('image');
			$this->language =& $this->locator->get('language');
			$this->module   =& $this->locator->get('module');
			$this->response =& $this->locator->get('response');
			$this->request  =& $this->locator->get('request');
			$this->session  =& $this->locator->get('session');
			$this->tax      =& $this->locator->get('tax');
			$this->template =& $this->locator->get('template');
			$this->url      =& $this->locator->get('url');
			$this->validate =& $this->locator->get('validate');
			$this->modelAccountAddress = $this->model->get('model_accountaddress');
			$this->modelReview = $this->model->get('model_review');   // Model
		}
	}
  	function index() {
		$this->initialize(); // Required 
		
    	if (!$this->customer->isLogged()) {
	  		$query = array(
	    		'product_id' => $this->request->gethtml('product_id'),
				'review_id'  => $this->request->gethtml('review_id')
	  		);
	
      		$this->session->set('redirect', $this->url->ssl('review_write', FALSE, $query));

      		$this->response->redirect($this->url->ssl('account_login'));
    	} 
	
    	$this->language->load('controller/review_write.php');
	
		if ($this->request->isPost() && $this->request->has('product_id') && $this->validate()) {
      		$this->modelReview->insert_review($this->request->gethtml('product_id'));
	  		$this->response->redirect($this->url->ssl('review_success'));
    	}
    	
		$product_info = $this->modelReview->get_product($this->request->gethtml('product_id'));
		
		if ($product_info) {	  
	  		$this->template->set('title', $this->language->get('heading_title'));
	  
	  		$view = $this->locator->create('template');
    
	  		$view->set('heading_title', $this->language->get('heading_title'));

      		$view->set('text_enlarge', $this->language->get('text_enlarge'));
      		$view->set('text_author', $this->language->get('text_author'));
      		$view->set('text_note', $this->language->get('text_note'));
      		$view->set('text_product', $this->language->get('text_product'));

      		$view->set('entry_review', $this->language->get('entry_review'));
      		$view->set('entry_rating', $this->language->get('entry_rating'));
      		$view->set('entry_good', $this->language->get('entry_good'));
      		$view->set('entry_bad', $this->language->get('entry_bad'));

      		$view->set('button_continue', $this->language->get('button_continue'));
      		$view->set('button_back', $this->language->get('button_back'));
    
	  		$view->set('error', @$this->error['message']);

	  		$query = array(
	    		'product_id' => $this->request->gethtml('product_id'),
				'review_id'  => $this->request->gethtml('review_id')
	  		);
	      
	  		$view->set('action', $this->url->ssl('review_write', FALSE, $query));
      
	  		$view->set('product', $product_info['name']);
	  		$view->set('price', $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'))));
			$view->set('special_price', $product_info['special_price']>0 ? $this->currency->format($this->tax->calculate($product_info['special_price'], $product_info['tax_class_id'], $this->config->get('config_tax'))): ""); // New
	  		$view->set('popup', $this->image->href($product_info['filename']));
	  		$view->set('thumb', $this->image->resize($product_info['filename'], 160,160));
	  		$view->set('author', $this->customer->getFirstName() . ' ' . $this->customer->getLastName());
	  		$view->set('text', $this->request->sanitize('text', 'post'));
	  		$view->set('rating', $this->request->gethtml('rating', 'post'));
      
	  		$query = array(
	    		'product_id' => $this->request->gethtml('product_id')
				//'review_id'  => $this->request->gethtml('review_id')
	  		);
	  
	  		$view->set('back', $this->url->href('review', FALSE, $query));
			$view->set('head_def',$this->head_def);    // New Header
			$this->template->set('head_def',$this->head_def);    // New Header	
	  		$this->template->set('content', $view->fetch('content/review_write.tpl'));
			
	  		$this->template->set($this->module->fetch());
      		$this->response->set($this->template->fetch('layout.tpl'));
    	} else {
      		$this->template->set('title', $this->language->get('text_error'));

      		$view = $this->locator->create('template');
      		$view->set('heading_title', $this->language->get('text_error'));
      		$view->set('text_error', $this->language->get('text_error'));

      		$view->set('button_continue', $this->language->get('button_continue'));
      		$view->set('continue', $this->url->href('home'));
			$view->set('head_def',$this->head_def);    // New Header
			$this->template->set('head_def',$this->head_def);    // New Header	
	  		$this->template->set('content', $view->fetch('content/error.tpl'));
			
	  		$this->template->set($this->module->fetch());
      		$this->response->set($this->template->fetch('layout.tpl'));  	
		}
  	}
		
  	function validate() {
		$this->initialize(); // Required 
			
    	if ((strlen($this->request->sanitize('text', 'post')) < 25) || (strlen($this->request->sanitize('text', 'post')) > 1000)) {
      		$this->error['message'] = $this->language->get('error_text');
    	}

    	if (!$this->request->gethtml('rating', 'post')) {
      		$this->error['message'] = $this->language->get('error_rating');
    	}
	
    	if (!$this->error) {
      		return TRUE;
    	} else {
      		return FALSE;
    	}	
	}
}
?>
