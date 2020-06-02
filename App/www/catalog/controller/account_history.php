<?php //Account History AlegroCart
class ControllerAccountHistory extends Controller {	
	function initialize() {//$this->initialize();  Required in each function 
		if(!isset($this->dataLink)){
			$this->dataLink = TRUE;
			$this->config   =& $this->locator->get('config');
			$this->currency =& $this->locator->get('currency');
			$this->customer =& $this->locator->get('customer');
			$this->head_def =& $this->locator->get('HeaderDefinition');  // New Header
			$this->language =& $this->locator->get('language');
			$this->module   =& $this->locator->get('module');
			$this->response =& $this->locator->get('response');
			$this->request  =& $this->locator->get('request');
			$this->session  =& $this->locator->get('session');
			$this->template =& $this->locator->get('template');
			$this->url      =& $this->locator->get('url');
			$this->modelAccountHistory =& $this->model->get('model_accounthistory');
		}
	}
	function index() {
		$this->initialize(); // Required 
		
    	if (!$this->customer->isLogged()) {
      		$this->session->set('redirect', $this->url->ssl('account_history'));

	  		$this->response->redirect($this->url->ssl('account_login'));
    	}
 
		//pagination
        $this->session->set('account_history.page', $this->request->has('page')?(int)$this->request->get('page'):1);

    	$this->language->load('controller/account_history.php');

    	$this->template->set('title', $this->language->get('heading_title'));
		$view = $this->locator->create('template');
		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('continue', $this->url->ssl('account'));
	  	$view->set('head_def',$this->head_def);    // New Header
		$this->template->set('head_def',$this->head_def);    // New Header	
		
		$results = $this->modelAccountHistory->get_orders($this->customer->getId());
		if ($results) {
      		$view->set('text_order', $this->language->get('text_order'));
      		$view->set('text_status', $this->language->get('text_status'));
     		$view->set('text_date_added', $this->language->get('text_date_added'));
      		$view->set('text_customer', $this->language->get('text_customer'));
      		$view->set('text_products', $this->language->get('text_products'));
      		$view->set('text_total', $this->language->get('text_total'));
     		$view->set('text_results',$this->modelAccountHistory->get_text_results());
			$view->set('first_page', $this->language->get('first_page'));
			$view->set('last_page', $this->language->get('last_page'));
      		$view->set('entry_page', $this->language->get('entry_page'));
			$view->set('previous' , $this->language->get('previous_page')); // New Pagination
			$view->set('next' , $this->language->get('next_page'));  // New Pagination  
      		$view->set('button_view', $this->language->get('button_view'));
      		$view->set('button_continue', $this->language->get('button_continue'));

			$view->set('action', $this->url->href('account_history', 'page'));

			$order_data = array();
      		foreach ($results as $result) {
        		$product_info = $this->modelAccountHistory->get_product_count($result['order_id']);

        		$order_data[] = array(
          			'reference'  => $result['reference'],
          			'href'       => $this->url->ssl('account_invoice', FALSE, array('order_id' => $result['order_id'])),
          			'name'       => $result['firstname'] . ' ' . $result['lastname'],
          			'status'     => $result['status'],
          			'date_added' => $this->language->formatDate($this->language->get('date_format_short'), strtotime($result['date_added'])),
          			'products'   => $product_info['products'],
          			'total'      => $this->currency->format($result['total'], $result['currency'], $result['value'])
        		);
      		}

      		$view->set('orders', $order_data);

      		$view->set('page', $this->session->get('account_history.page'));
		  	$view->set('pages', $this->modelAccountHistory->get_pagination());
			$view->set('total_pages', $this->modelAccountHistory->get_pages());
	  		$this->template->set('content', $view->fetch('content/account_history.tpl'));
    	} else {
      		$view->set('text_error', $this->language->get('text_error'));
      		$view->set('button_continue', $this->language->get('button_continue'));
	  		$this->template->set('content', $view->fetch('content/error.tpl'));
		}
		$this->template->set($this->module->fetch());
		$this->response->set($this->template->fetch('layout.tpl'));
	}
}
?>
