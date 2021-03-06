<?php
class Payment {
	var $data = array();
 
	function __construct(&$locator) {
		$this->database =& $locator->get('database');
		
		$results = $this->database->getRows("select * from extension where type = 'payment'");
		
		foreach ($results as $result) {
			$file  = DIR_EXTENSION . $result['directory'] . '/' . $result['filename'];
			$class = 'Payment' . str_replace('_', '', $result['code']);

			if (file_exists($file)) {
				require_once($file);
	
				$this->data[$result['code']] = new $class($locator);
			}
		}
	}
	
	function getMethods() {
		$method_data = array();
		
		foreach (array_keys($this->data) as $key) {
			$data = $this->data[$key]->getMethod();
			
			if ($data) {
				$method_data[$data['id']] = array(
					'id'         => $data['id'],
					'title'      => $data['title'],
					'sort_order' => $data['sort_order']
				);
			}
		}

		$sort_order = array();
	  
		foreach ($method_data as $key => $value) {
      		$sort_order[$key] = $value['sort_order'];
    	}

    	array_multisort($sort_order, SORT_ASC, $method_data);
				
		return $method_data;
	}
	
	function getTitle($key) {
		if (method_exists($this->data[$key], 'getTitle')) {
			return $this->data[$key]->getTitle();
		}
	}

	function hasMethod($key) {
		return isset($this->data[$key]);
	}

	function getActionUrl($key) {
		if (method_exists($this->data[$key], 'getActionUrl')) {
			return $this->data[$key]->getActionUrl();
		}
	}		

	function getFields($key) {
		if (method_exists($this->data[$key], 'fields')) {
			return $this->data[$key]->fields(); 
		}
	}

	function runCallback($key) {
		if (method_exists($this->data[$key], 'callback')) {
			$this->data[$key]->callback();
		}
	}

	function runProcess($key) {
		if ((isset($this->data[$key])) && method_exists($this->data[$key], 'process')) {
			$this->data[$key]->process();
			return true;
		}
	}
}
?>