<?php //Checkout Address AlegroCart
class ControllerCheckoutAddress extends Controller {
	var $error = array(); 
	function initialize() {//$this->initialize();  Required in each function 
		if(!isset($this->dataLink)){
			$this->dataLink = TRUE;
			$this->address  =& $this->locator->get('address');
			$this->cart     =& $this->locator->get('cart');
			$this->config   =& $this->locator->get('config');
			$this->customer =& $this->locator->get('customer');
			$this->head_def =& $this->locator->get('HeaderDefinition');  // New Header
			$this->language =& $this->locator->get('language');
			$this->module   =& $this->locator->get('module');
			$this->response =& $this->locator->get('response');
			$this->request  =& $this->locator->get('request');
			$this->session  =& $this->locator->get('session');
			$this->template =& $this->locator->get('template');
			$this->url      =& $this->locator->get('url');
			$this->validate =& $this->locator->get('validate');
			$this->modelAccountAddress =& $this->model->get('model_accountaddress');
		}
	}
	function shipping() {
		$this->initialize(); // Required 
		
    	$this->language->load('controller/checkout_address.php');

    	$this->template->set('title', $this->language->get('heading_title')); 

		if (!$this->customer->isLogged()) {  
			$this->session->set('redirect', $this->url->ssl('checkout_shipping'));
      		
			$this->response->redirect($this->url->ssl('account_login'));
    	}
				  
    	if (($this->request->isPost()) && ($this->request->has('address_id', 'post'))) {
	  		$this->session->set('shipping_address_id', $this->request->gethtml('address_id', 'post'));

			$this->response->redirect($this->url->ssl('checkout_shipping'));
		}
		
		if ($this->request->isPost() && $this->request->has('firstname', 'post') && $this->validate()) {
			if(($this->session->get('account_validation') == $this->request->gethtml('account_validation', 'post')) && (strlen($this->session->get('account_validation')) > 10)){
				$this->modelAccountAddress->insert_address($this->customer->getId());
				$this->session->set('shipping_address_id', $this->modelAccountAddress->get_last_ID());
				$this->session->delete('account_validation');
				$this->session->delete('message');
				$this->response->redirect($this->url->ssl('checkout_shipping'));
			} else {
				$this->session->set('message',$this->language->get('error_referer'));			
				$this->session->delete('account_validation');
				$this->response->redirect($this->url->ssl('account_address',$this->request->gethtml('action')));
			}
    	}
	
		$this->template->set('content', $this->getForm());
		$this->template->set('head_def',$this->head_def);    // New Header	
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  	}
  
  	function payment() {
		$this->initialize(); // Required 
		   	
		$this->language->load('controller/checkout_address.php');

    	$this->template->set('title', $this->language->get('heading_title')); 
		
		if (!$this->customer->isLogged()) {  
      		$this->session->set('redirect', $this->url->ssl('checkout_shipping'));

      		$this->response->redirect($this->url->ssl('account_login'));
    	}
		 	 
    	if (($this->request->isPost()) && ($this->request->has('address_id', 'post'))) {
			
	  		$this->session->set('payment_address_id', $this->request->gethtml('address_id', 'post'));
	  		
			$this->response->redirect($this->url->ssl('checkout_payment'));
		} 
	   
		if ($this->request->isPost() && $this->request->has('firstname', 'post') && $this->validate()) {
			if(($this->session->get('account_validation') == $this->request->gethtml('account_validation', 'post')) && (strlen($this->session->get('account_validation')) > 10)){
				$this->modelAccountAddress->insert_address($this->customer->getId());
				$this->session->set('payment_address_id', $this->modelAccountAddress->get_last_ID());
				$this->session->delete('message');
				$this->session->delete('account_validation');
				$this->response->redirect($this->url->ssl('checkout_payment'));
			} else {
				$this->session->set('message',$this->language->get('error_referer'));			
				$this->session->delete('account_validation');
				$this->response->redirect($this->url->ssl('account_address',$this->request->gethtml('action')));
			}
    	}
	
    	$this->template->set('content', $this->getForm());
		$this->template->set('head_def',$this->head_def);    // New Header	
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));    
  	}
  
  	function getForm() {
		$this->initialize(); // Required 
		
    	$view = $this->locator->create('template');

    	$view->set('heading_title', $this->language->get('heading_title'));

    	$view->set('text_new_address', $this->language->get('text_new_address'));
   	 	$view->set('text_entries', $this->language->get('text_entries'));

    	$view->set('entry_firstname', $this->language->get('entry_firstname'));
    	$view->set('entry_lastname', $this->language->get('entry_lastname'));
    	$view->set('entry_company', $this->language->get('entry_company'));
    	$view->set('entry_address_1', $this->language->get('entry_address_1'));
    	$view->set('entry_address_2', $this->language->get('entry_address_2'));
    	$view->set('entry_postcode', $this->language->get('entry_postcode'));
    	$view->set('entry_city', $this->language->get('entry_city'));
    	$view->set('entry_country', $this->language->get('entry_country'));
    	$view->set('entry_zone', $this->language->get('entry_zone'));
    
		$view->set('button_continue', $this->language->get('button_continue'));
    
		$view->set('error_firstname', @$this->error['firstname']);
    	$view->set('error_lastname', @$this->error['lastname']);
    	$view->set('error_address_1', @$this->error['address_1']);
    	$view->set('error_city', @$this->error['city']);
		$view->set('error_postcode', @$this->error['postcode']);

    	$view->set('action', $this->url->ssl('checkout_address', $this->request->gethtml('action')));
				
    	$view->set('default', $this->session->get($this->request->gethtml('action') . '_address_id'));
		$this->session->set('account_validation', md5(time()));
		$view->set('account_validation', $this->session->get('account_validation'));

    	$address_data = array();
		
		$results = $this->modelAccountAddress->get_addresses($this->customer->getId());

    	foreach ($results as $result) {
      		$address_data[] = array(
        		'address_id' => $result['address_id'],
	    		'address'    => $result['firstname'] . ' ' . $result['lastname'] . ', ' . $result['address_1'] . ', ' . $result['city'] . ', ' . (($result['zone']) ? $result['zone']  . ', ' : FALSE) . $result['country'],
        		'href'       => $this->url->ssl('account_address', $this->request->gethtml('action'), array('address_id' => $result['address_id']))
      		);
    	}

    	$view->set('addresses', $address_data);

    	$view->set('firstname', $this->request->sanitize('firstname', 'post'));
    	$view->set('lastname', $this->request->sanitize('lastname', 'post'));
    	$view->set('company', $this->request->sanitize('company', 'post'));
    	$view->set('address_1', $this->request->sanitize('address_1', 'post'));
    	$view->set('address_2', $this->request->sanitize('address_2', 'post'));
    	$view->set('city', $this->request->gethtml('city', 'post'));
    	$view->set('postcode', $this->request->sanitize('postcode', 'post'));

    	if ($this->request->has('country_id', 'post')) {
      		$view->set('country_id', $this->request->gethtml('country_id', 'post'));
    	} else {
      		$view->set('country_id', $this->config->get('config_country_id'));
    	}
 
    	if ($this->request->has('zone_id', 'post')) {
      		$view->set('zone_id', $this->request->gethtml('zone_id', 'post'));
    	} else {
      		$view->set('zone_id', $this->config->get('config_zone_id'));
    	}
		  // Add Country Staus to Query
    	$view->set('countries',$this->modelAccountAddress->get_countries());
		  // Add Zone Status to Query
    	$view->set('zones', $this->modelAccountAddress->get_zones());
		$view->set('head_def',$this->head_def);    // New Header
	
    	return $view->fetch('content/checkout_address.tpl'); 
  	}

  	function validate() {
		$this->initialize(); // Required 
		
		if (!$this->url->validate_referer()) {
			$this->session->set('message', $this->language->get('error_referer'));
			$this->session->delete('account_validation');
			$this->response->redirect($this->url->ssl('account_address',$this->request->gethtml('action')));
		}
		
    	if ((strlen($this->request->sanitize('firstname', 'post')) < 3) || (strlen($this->request->sanitize('firstname', 'post')) > 32)) {
      		$this->error['firstname'] = $this->language->get('error_firstname');
    	}

    	if ((strlen($this->request->sanitize('lastname', 'post')) < 3) || (strlen($this->request->sanitize('lastname', 'post')) > 32)) {
      		$this->error['lastname'] = $this->language->get('error_lastname');
    	}

    	if ((strlen($this->request->sanitize('address_1', 'post')) < 3) || (strlen($this->request->sanitize('address_1', 'post')) > 64)) {
      		$this->error['address_1'] = $this->language->get('error_address_1');
    	}

    	if ((strlen($this->request->sanitize('city', 'post')) < 3) || (strlen($this->request->sanitize('city', 'post')) > 32)) {
      		$this->error['city'] = $this->language->get('error_city');
    	}
		
		if (!$this->validate->strlen($this->request->sanitize('postcode', 'post'),4,10)){
			$this->error['postcode'] = $this->language->get('error_postcode');
		}
		
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}  
  	}

	function zone() {
		$this->initialize(); // Required 
		 
    	$results = $this->modelAccountAddress->return_zones($this->request->gethtml('country_id'));

    	$output = '<select name="zone_id">';
        
		foreach ($results as $result) {
        	$output .= '<option value="' . $result['zone_id'] . '"';
	
	    	if ($this->request->gethtml('zone_id') == $result['zone_id']) {
	      		$output .= ' SELECTED';
	    	}
	
	    	$output .= '>' . $result['name'] . '</option>';
      	}
	
		if (!$results) {
      		$output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
    	}

    	$output .= '</select>';
	
		$this->response->set($output);
  	} 
}
?>