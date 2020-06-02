<?php 
class ShippingFlat extends Shipping {    
	function __construct(&$locator) { 
		$this->address  =& $locator->get('address');
		$this->config   =& $locator->get('config');
		$this->currency =& $locator->get('currency');
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->session  =& $locator->get('session');
		$this->tax      =& $locator->get('tax');
				
		$this->language->load('extension/shipping/flat.php');
  	}
  	
  	function quote() {
		if ($this->config->get('flat_status')) {
      		if (!$this->config->get('flat_geo_zone_id')) {
        		$status = true;
      		} elseif ($this->database->getRows("select * from zone_to_geo_zone where geo_zone_id = '" . (int)$this->config->get('flat_geo_zone_id') . "' and country_id = '" . (int)$this->address->getCountryId($this->session->get('shipping_address_id')) . "' and (zone_id = '" . (int)$this->address->getZoneId($this->session->get('shipping_address_id')) . "' or zone_id = '0')")) {
        		$status = true;
      		} else {
        		$status = false;
      		}
		} else {
			$status = false;
		}
		
		$method_data = array();
	
		if ($status) {
			$quote_data = array();
			
      		$quote_data['flat'] = array(
        		'id'    => 'flat_flat',
        		'title' => $this->language->get('text_flat_description'),
        		'cost'  => $this->config->get('flat_cost'),
        		'text'  => $this->currency->format($this->tax->calculate($this->config->get('flat_cost'), $this->config->get('flat_tax_class_id'), $this->config->get('config_tax')))
      		);

      		$method_data = array(
        		'id'           => 'flat',
        		'title'        => $this->language->get('text_flat_title'),
        		'quote'        => $quote_data,
        		'tax_class_id' => $this->config->get('flat_tax_class_id'),
				'sort_order'   => $this->config->get('flat_sort_order'),
        		'error'        => false
      		);
		}
	
		return $method_data;
  	}
}
?>
