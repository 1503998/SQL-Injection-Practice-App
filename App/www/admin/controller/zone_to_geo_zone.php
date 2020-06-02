<?php  
class ControllerZoneToGeoZone extends Controller {
  	var $error = array();
   
  	function index() {
		$response =& $this->locator->get('response');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template'); 
		$module   =& $this->locator->get('module');
	
    	$language->load('controller/zone_to_geo_zone.php');

    	$template->set('title', $language->get('heading_title'));

    	$template->set('content', $this->getList());
	
		$template->set($module->fetch());
	
    	$response->set($template->fetch('layout.tpl'));	
  	}

  	function insert() {
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$database =& $this->locator->get('database');
		$url      =& $this->locator->get('url');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$session  =& $this->locator->get('session');
		$module   =& $this->locator->get('module');

    	$language->load('controller/zone_to_geo_zone.php');

    	$template->set('title', $language->get('heading_title'));
		
    	if ($request->isPost() && $request->has('country_id', 'post') && $this->validate()) {
	  		$sql = "insert into zone_to_geo_zone set country_id = '?', zone_id = '?', geo_zone_id = '?', date_added = now()";
      		$database->query($database->parse($sql, $request->gethtml('country_id', 'post'), $request->gethtml('zone_id', 'post'), $request->gethtml('geo_zone_id')));

			$session->set('message', $language->get('text_message'));
			
	  		$response->redirect($url->ssl('zone_to_geo_zone', FALSE, array('geo_zone_id' => $request->gethtml('geo_zone_id'))));
    	}
    
		$template->set('content', $this->getForm());
	
		$template->set($module->fetch());
	
    	$response->set($template->fetch('layout.tpl'));
  	}

  	function update() {
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$database =& $this->locator->get('database');
		$url      =& $this->locator->get('url');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$session  =& $this->locator->get('session');
		$module   =& $this->locator->get('module');

    	$language->load('controller/zone_to_geo_zone.php');

    	$template->set('title', $language->get('heading_title'));
	
    	if ($request->isPost() && $request->has('country_id', 'post') && $this->validate()) {
	  		$sql = "update zone_to_geo_zone set country_id = '?', zone_id = '?', date_modified = now() where zone_to_geo_zone_id = '?' and geo_zone_id = '?'";
      		$database->query($database->parse($sql, $request->gethtml('country_id', 'post'), $request->gethtml('zone_id', 'post'), $request->gethtml('zone_to_geo_zone_id'), $request->gethtml('geo_zone_id')));
      		
			$session->set('message', $language->get('text_message'));
			
	  		$query = array(
	    		'zone_to_geo_zone_id' => $request->gethtml('zone_to_geo_zone_id'),
	    		'geo_zone_id'         => $request->gethtml('geo_zone_id')
	  		);
	  	
	  		$response->redirect($url->ssl('zone_to_geo_zone', FALSE, $query));	  
    	}
    
		$template->set('content', $this->getForm());
	
		$template->set($module->fetch());
	
    	$response->set($template->fetch('layout.tpl'));  
  	}

  	function delete() {
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$database =& $this->locator->get('database');
		$url      =& $this->locator->get('url');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$session  =& $this->locator->get('session');
		$module   =& $this->locator->get('module');
 
    	$language->load('controller/zone_to_geo_zone.php');

    	$template->set('title', $language->get('heading_title'));
		
    	if (($request->gethtml('zone_to_geo_zone_id')) && ($this->validate())) {
      		$database->query("delete from zone_to_geo_zone where zone_to_geo_zone_id = '" . (int)$request->gethtml('zone_to_geo_zone_id') . "'");
			
			$session->set('message', $language->get('text_message'));

	  		$response->redirect($url->ssl('zone_to_geo_zone', FALSE, array('geo_zone_id' => $request->gethtml('geo_zone_id'))));
    	}
    
    	$template->set('content', $this->getList());
	
		$template->set($module->fetch());
	
    	$response->set($template->fetch('layout.tpl'));
  	}

  	function getList() {
		$request  =& $this->locator->get('request');
		$database =& $this->locator->get('database');
		$config   =& $this->locator->get('config');
		$url      =& $this->locator->get('url');
		$language =& $this->locator->get('language');
		$session  =& $this->locator->get('session');
	    
    	$cols = array();
  
    	$cols[] = array(
      		'name'  => $language->get('column_country'),
      		'sort'  => 'c.name',
      		'align' => 'left'
    	);
  
    	$cols[] = array(
      		'name'  => $language->get('column_zone'),
      		'sort'  => 'z.name',
      		'align' => 'left'
    	);

    	$cols[] = array(
      		'name'  => $language->get('column_action'),
      		'align' => 'right'
    	);
		
    	if (!$session->get('zone_to_geo_zone.search')) { 
      		$sql = "select a.zone_to_geo_zone_id, c.name, z.name as zone from zone_to_geo_zone a left join country c on a.country_id = c.country_id left join zone z on a.zone_id = z.zone_id where a.geo_zone_id = '" . (int)$request->gethtml('geo_zone_id') . "'";
    	} else {
      		$sql = "select a.zone_to_geo_zone_id, c.name, z.name as zone from zone_to_geo_zone a left join country c on a.country_id = c.country_id left join zone z on a.zone_id = z.zone_id where a.geo_zone_id = '" . (int)$request->gethtml('geo_zone_id') . "' and c.name like '?'";
    	}
    
		$sort = array(
	  		'c.name',  
	  		'z.name' 
		);
	
    	if (in_array($session->get('zone_to_geo_zone.sort'), $sort)) {
      		$sql .= " order by " . $session->get('zone_to_geo_zone.sort') . " " . (($session->get('zone_to_geo_zone.order') == 'desc') ? 'desc' : 'asc');
    	} else {
      		$sql .= " order by c.name, z.name asc";
    	}
    
    	$results = $database->getRows($database->splitQuery($database->parse($sql, '%' . $session->get('zone_to_geo_zone.search') . '%'), $session->get('zone_to_geo_zone.' . $request->gethtml('geo_zone_id') . '.page'), $config->get('config_max_rows')));

    	$rows = array();

    	foreach ($results as $result) {
      		$cell = array();

      		$cell[] = array(
        		'value' => $result['name'],
        		'align' => 'left'
      		);

      		$cell[] = array(
        		'value' => ($result['zone'] ? $result['zone'] : $language->get('text_all_zones')),
        		'align' => 'left'
      		);
			
			$query = array(
	    		'zone_to_geo_zone_id' => $result['zone_to_geo_zone_id'],
        		'geo_zone_id'         => $request->gethtml('geo_zone_id')
      		);
			
			$action = array();
      		
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $language->get('button_update'),
				'href' => $url->ssl('zone_to_geo_zone', 'update', $query)
      		);
						
			$action[] = array(
        		'icon' => 'delete.png',
				'text' => $language->get('button_delete'),
				'href' => $url->ssl('zone_to_geo_zone', 'delete', $query)
      		);

      		$cell[] = array(
        		'action' => $action,
        		'align'  => 'right'
      		);
			
      		$rows[] = array('cell' => $cell);
    	}

    	$view = $this->locator->create('template');

    	$view->set('heading_title', $language->get('heading_title'));
    	$view->set('heading_description', $language->get('heading_description'));

    	$view->set('text_previous', $language->get('text_previous'));
    	$view->set('text_results', $language->get('text_results', $database->getFrom(), $database->getTo(), $database->getTotal()));

    	$view->set('entry_page', $language->get('entry_page'));
    	$view->set('entry_search', $language->get('entry_search'));

    	$view->set('button_list', $language->get('button_list'));
    	$view->set('button_insert', $language->get('button_insert'));
    	$view->set('button_update', $language->get('button_update'));
    	$view->set('button_delete', $language->get('button_delete'));
    	$view->set('button_save', $language->get('button_save'));
    	$view->set('button_cancel', $language->get('button_cancel'));
    
		$view->set('error', @$this->error['message']);
		
		$view->set('message', $session->get('message'));
		
		$session->delete('message');
		  
    	$view->set('action', $url->ssl('zone_to_geo_zone', 'page', array('geo_zone_id' => $request->gethtml('geo_zone_id'))));
  
    	$view->set('previous', $url->ssl('geo_zone', FALSE, array('geo_zone_id' => $request->gethtml('geo_zone_id'))));
   
    	$view->set('search', $session->get('zone_to_geo_zone.search'));
    	$view->set('sort', $session->get('zone_to_geo_zone.sort'));
    	$view->set('order', $session->get('zone_to_geo_zone.order'));
  		$view->set('page', $session->get('zone_to_geo_zone.' . $request->gethtml('geo_zone_id') . '.page'));
    	
		$view->set('cols', $cols);
    	$view->set('rows', $rows);
  
    	$view->set('list', $url->ssl('zone_to_geo_zone', FALSE, array('geo_zone_id' => $request->gethtml('geo_zone_id'))));
    
    	$view->set('insert', $url->ssl('zone_to_geo_zone', 'insert', array('geo_zone_id' => $request->gethtml('geo_zone_id'))));
      
    	$page_data = array();

    	for ($i = 1; $i <= $database->getPages(); $i++) {	  
      		$page_data[] = array(
        		'text'  => $language->get('text_pages', $i, $database->getPages()),
        		'value' => $i
      		);
    	}

    	$view->set('pages', $page_data);
    	
		return $view->fetch('content/list.tpl');
  	}

  	function getForm() {
		$request  =& $this->locator->get('request');
		$database =& $this->locator->get('database');
		$url      =& $this->locator->get('url');
		$language =& $this->locator->get('language');

    	$view = $this->locator->create('template');

    	$view->set('heading_title', $language->get('heading_title'));
    	$view->set('heading_description', $language->get('heading_description'));

    	$view->set('text_select', $language->get('text_select'));

    	$view->set('entry_country', $language->get('entry_country'));
    	$view->set('entry_zone', $language->get('entry_zone'));

    	$view->set('button_list', $language->get('button_list'));
    	$view->set('button_insert', $language->get('button_insert'));
    	$view->set('button_update', $language->get('button_update'));
    	$view->set('button_delete', $language->get('button_delete'));
    	$view->set('button_save', $language->get('button_save'));
    	$view->set('button_cancel', $language->get('button_cancel'));

    	$view->set('tab_general', $language->get('tab_general'));
    
		$view->set('error', @$this->error['message']);
    
		$query = array(
      		'zone_to_geo_zone_id' => $request->gethtml('zone_to_geo_zone_id'),
	  		'geo_zone_id'         => $request->gethtml('geo_zone_id')
    	); 
  
    	$view->set('action', $url->ssl('zone_to_geo_zone', $request->gethtml('action'), $query));
  
    	$view->set('list', $url->ssl('zone_to_geo_zone', FALSE, array('geo_zone_id' => $request->gethtml('geo_zone_id'))));
 
    	$view->set('insert', $url->ssl('zone_to_geo_zone', 'insert', array('geo_zone_id' => $request->gethtml('geo_zone_id'))));
  
    	if ($request->gethtml('zone_to_geo_zone_id')) {
      		$query = array(
        		'zone_to_geo_zone_id' => $request->gethtml('zone_to_geo_zone_id'),
	    		'geo_zone_id'         => $request->gethtml('geo_zone_id')
      		);
	  
      		$view->set('update', $url->ssl('zone_to_geo_zone', 'update', $query));
	  		$view->set('delete', $url->ssl('zone_to_geo_zone', 'delete', $query));
    	}
  
    	$view->set('cancel', $url->ssl('zone_to_geo_zone', FALSE, array('geo_zone_id' => $request->gethtml('geo_zone_id'))));
        
    	if (($request->gethtml('zone_to_geo_zone_id')) && (!$request->isPost())) {
      		$zone_to_geo_zone_info = $database->getRow("select distinct * from zone_to_geo_zone where zone_to_geo_zone_id = '" . (int)$request->gethtml('zone_to_geo_zone_id') . "'");
    	}
		// Add to get a default country when adding new geo zone
		if(!isset($zone_to_geo_zone_info)){ 
			$geo_zone_id_info = $database->getRow("select distinct * from zone_to_geo_zone where geo_zone_id ='" . (int)$request->gethtml('geo_zone_id') . "'");
		}
		
    	if ($request->has('country_id', 'post')) {
      		$view->set('country_id', $request->gethtml('country_id', 'post'));
		} else if(!isset($zone_to_geo_zone_info)){
			$view->set('country_id', @$geo_zone_id_info['country_id']);		// Default when adding new geo zone
		} else {			
      		$view->set('country_id', @$zone_to_geo_zone_info['country_id']);			
    	}
		
    	if ($request->has('zone_id', 'post')) {
      		$view->set('zone_id', $request->gethtml('zone_id', 'post'));
		} else {
      		$view->set('zone_id', @$zone_to_geo_zone_info['zone_id']);
    	}
		// Add ountry Status to Query
    	$view->set('countries', $database->cache('country', "select country_id, name, country_status from country where country_status = '1' order by name"));
		// Add Zone Status to Query
    	$view->set('zones', $database->cache('zone', "select * from zone where zone_status = '1' order by country_id, name"));
 
 		return $view->fetch('content/zone_to_geo_zone.tpl');
  	}
  
  	function validate() {
		$user     =& $this->locator->get('user');
		$language =& $this->locator->get('language');

    	if (!$user->hasPermission('modify', 'zone_to_geo_zone')) {
      		$this->error['message'] = $language->get('error_permission');
    	}
 
    	if (!$this->error) {
      		return TRUE;
    	} else {
      		return FALSE;
    	}
  	}
    
	function page() {
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$url      =& $this->locator->get('url');
		$session  =& $this->locator->get('session');

		if ($request->has('search', 'post')) {
			$session->set('zone_to_geo_zone.search', $request->gethtml('search', 'post'));
		}

		if (($request->has('page', 'post')) || ($request->has('search', 'post'))) {
			$session->set('zone_to_geo_zone.' . $request->gethtml('geo_zone_id') . '.page', $request->gethtml('page', 'post'));			
		}

		if ($request->has('sort', 'post')) {
			$session->set('zone_to_geo_zone.order', (($session->get('zone_to_geo_zone.sort') == $request->gethtml('sort', 'post')) && ($session->get('zone_to_geo_zone.order') == 'asc')) ? 'desc' : 'asc');
		}

		if ($request->has('sort', 'post')) {
			$session->set('zone_to_geo_zone.sort', $request->gethtml('sort', 'post'));
		}

		$response->redirect($url->ssl('zone_to_geo_zone', FALSE, array('geo_zone_id' => $request->gethtml('geo_zone_id'))));
	}    
  	
	function zone() {
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$database =& $this->locator->get('database');
		$language =& $this->locator->get('language');
		
		$output = '<select name="zone_id">';
		
		$output .= '<option value="0">' . $language->get('text_all_zones') . '</option>';
		// Add Zone Status
		$results = $database->cache('zone-' . (int)$request->gethtml('country_id'), "select zone_id, name, zone_status from zone where country_id = '" . (int)$request->gethtml('country_id') . "' AND zone_status = '1' order by name");

		foreach ($results as $result) {
			$output .= '<option value="' . $result['zone_id'] . '"';

			if ($request->gethtml('zone_id') == $result['zone_id']) {
				$output .= ' SELECTED';
			}

			$output .= '>' . $result['name'] . '</option>';
		}

		$output .= '</select>';

		$response->set($output);
	} 	
}
?>