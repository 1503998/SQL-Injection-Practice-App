<?php 
class ShippingZone extends Shipping {    
	function __construct(&$locator) { 
		$this->address  =& $locator->get('address');
		$this->cart     =& $locator->get('cart');
		$this->config   =& $locator->get('config');
		$this->currency =& $locator->get('currency');
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->session  =& $locator->get('session');
		$this->tax      =& $locator->get('tax');
				
		$this->language->load('extension/shipping/zone.php');
  	}
  	
  	function quote() {
		$quote_data = array();
		
		$results = $this->database->cache('geo_zone', "select * from geo_zone order by name");
		
		foreach ($results as $result) {
   			if ($this->config->get('zone_' . $result['geo_zone_id'] . '_status')) {
   				if ($this->database->getRows("select * from zone_to_geo_zone where geo_zone_id = '" . (int)$result['geo_zone_id'] . "' and country_id = '" . (int)$this->address->getCountryId($this->session->get('shipping_address_id')) . "' and (zone_id = '" . (int)$this->address->getZoneId($this->session->get('shipping_address_id')) . "' or zone_id = '0')")) {
       				$status = true;
   				} else {
       				$status = false;
   				}
			} else {
				$status = false;
			}
			
			if ($status) {
				$cost = 0;
				
				$rates = explode(',', $this->config->get('zone_' . $result['geo_zone_id'] . '_cost'));

				foreach ($rates as $rate) {
  					$array = explode(':', $rate);
  					
					if ($this->cart->getWeight() <= $array[0]) {
    					$cost = @$array[1];
						
   						break;
  					}
				}
			
      			$quote_data[$result['geo_zone_id']] = array(
        			'id'    => 'zone_' . $result['geo_zone_id'],
        			'title' => $result['name'],
        			'cost'  => $cost,
        			'text'  => $this->currency->format($this->tax->calculate($cost, $this->config->get('zone_tax_class_id'), $this->config->get('config_tax')))
      			);			
			}
		}
		
		$method_data = array();
	
		if ($quote_data) {
      		$method_data = array(
        		'id'           => 'zone',
        		'title'        => $this->language->get('text_zone_title'),
        		'quote'        => $quote_data,
        		'tax_class_id' => $this->config->get('zone_tax_class_id'),
				'sort_order'   => $this->config->get('zone_sort_order'),
        		'error'        => false
      		);
		}
	
		return $method_data;
  	}
}
?>
