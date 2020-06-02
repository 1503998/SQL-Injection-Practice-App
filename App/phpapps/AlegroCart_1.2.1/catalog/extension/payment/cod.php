<?php 
class PaymentCod extends Payment {
	function __construct(&$locator) {
		$this->address  =& $locator->get('address');
		$this->config   =& $locator->get('config');
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->order    =& $locator->get('order');
		$this->session  =& $locator->get('session');
		$this->url      =& $locator->get('url');	    
		
		$this->language->load('extension/payment/cod.php');
  	}
  
	function getTitle() {
		return $this->language->get('text_cod_title');
  	}
   
  	function getMethod() {
		if ($this->config->get('cod_status')) {
      		if (!$this->config->get('cod_geo_zone_id')) {
        		$status = true;
      		} elseif ($this->database->getRows("select * from zone_to_geo_zone where geo_zone_id = '" . (int)$this->config->get('cod_geo_zone_id') . "' and country_id = '" . (int)$this->address->getCountryId($this->session->get('payment_address_id')) . "' and (zone_id = '" . (int)$this->address->getZoneId($this->session->get('payment_address_id')) . "' or zone_id = '0')")) {
        		$status = true;
      		} else {
        		$status = false;
      		}
		} else {
			$status = false;
		}
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'id'         => 'cod',
        		'title'      => $this->language->get('text_cod_title'),
				'sort_order' => $this->config->get('cod_sort_order')
      		);
    	}
   
    	return $method_data;
  	}

  	function getActionUrl() {
    	return $this->url->ssl('checkout_process');
	}

  	function process() {
		$this->order->load($this->order->getReference());
		$this->order->process();
  	}
}
?>