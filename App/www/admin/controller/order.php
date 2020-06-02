<?php 
class ControllerOrder extends Controller {
	var $error = array();
   
  	function index() {
    	$response =& $this->locator->get('response');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$module   =& $this->locator->get('module');
	
		$language->load('controller/order.php');
	
		$template->set('title', $language->get('heading_title'));
		
    	$template->set('content', $this->getList());
	
		$template->set($module->fetch());
	
    	$response->set($template->fetch('layout.tpl'));	
  	}
              
  	function update() {	
    	$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$database =& $this->locator->get('database');
		$config   =& $this->locator->get('config');
		$url      =& $this->locator->get('url');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$session  =& $this->locator->get('session');
		$module   =& $this->locator->get('module');
		$mail     =& $this->locator->get('mail');

		$language->load('controller/order.php');
	
		$template->set('title', $language->get('heading_title'));
		    	
    	if ($request->isPost() && $request->has('order_status_id', 'post') && $this->validate()) {  
      		$database->query("update `order` set order_status_id = '" . (int)$request->gethtml('order_status_id', 'post') . "', date_modified = now() where order_id = '" . (int)$request->gethtml('order_id') . "'");

      		$sql = "insert into order_history set order_id = '?', order_status_id = '?', date_added = now(), notify = '?', comment = '?'";
      		$database->query($database->parse($sql, $request->gethtml('order_id'), $request->gethtml('order_status_id', 'post'), $request->gethtml('notify', 'post'), strip_tags($request->gethtml('comment', 'post'))));

      		if (($config->get('config_email_send')) && ($request->gethtml('notify', 'post'))) {
        		$order_info = $database->getRow("select o.reference, o.firstname, o.lastname, o.email, o.date_added, os.name as status from `order` o left join order_status os on o.order_status_id = os.order_status_id where o.order_id = '" . (int)$request->gethtml('order_id') . "' and os.language_id = '" . (int)$language->getId() . "'");

	    		$order_id = $order_info['reference'];
				$invoice  = $url->create(HTTP_CATALOG, 'account_invoice', FALSE, array('order_id' => $request->gethtml('order_id')));
	    		$date     = $language->formatDate($language->get('date_format_long'),strtotime($order_info['date_added']));
	    		$status   = $order_info['status'];
	    		$comment  = strip_tags($request->gethtml('comment', 'post'));
		
	    		$mail->setTo($order_info['email']);
				$mail->setFrom($config->get('config_email'));
	    		$mail->setSender($config->get('config_store'));
	    		$mail->setSubject($language->get('email_subject', $order_id));
	    		$mail->setText($language->get('email_message', $order_id, $invoice, $date, $status, $comment));
	    		$mail->send();
      		}
			
			$session->set('message', $language->get('text_message'));
	  		
			$response->redirect($url->ssl('order'));
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
 
		$language->load('controller/order.php');
	
		$template->set('title', $language->get('heading_title'));
			
    	if (($request->gethtml('order_id')) && ($this->validate())) {
      		$database->query("delete from `order` where order_id = '" . (int)$request->gethtml('order_id') . "'");
      		$database->query("delete from order_history where order_id = '" . (int)$request->gethtml('order_id') . "'");
      		$database->query("delete from order_product where order_id = '" . (int)$request->gethtml('order_id') . "'");
      		$database->query("delete from order_option where order_id = '" . (int)$request->gethtml('order_id') . "'");
	  		$database->query("delete from order_download where order_id = '" . (int)$request->gethtml('order_id') . "'");
      		$database->query("delete from order_total where order_id = '" . (int)$request->gethtml('order_id') . "'");
			
			$session->set('message', $language->get('text_message'));
	  		
			$response->redirect($url->ssl('order'));
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
		$currency =& $this->locator->get('currency');
			
    	$cols = array();
    
    	$cols[] = array(
      		'name'  => $language->get('column_order_id'),
      		'sort'  => 'o.order_id',
      		'align' => 'left'
    	);

    	$cols[] = array(
      		'name'  => $language->get('column_reference'),
      		'sort'  => 'o.reference',
      		'align' => 'left'
    	);
		
    	$cols[] = array(
      		'name'  => $language->get('column_name'),
      		'sort'  => 'o.firstname',
      		'align' => 'left'
    	);

    	$cols[] = array(
      		'name'  => $language->get('column_status'),
      		'sort'  => 'os.name',
      		'align' => 'left'
    	);

    	$cols[] = array(
      		'name'  => $language->get('column_date_added'),
      		'sort'  => 'o.date_added',
      		'align' => 'left'
    	);
  
    	$cols[] = array(
      		'name'  => $language->get('column_total'),
      		'sort'  => 'o.total',
      		'align' => 'right'
    	);

    	$cols[] = array(
      		'name'  => $language->get('column_action'),
      		'align' => 'right'
    	);
		  
    	if (!$session->get('order.search')) {
      		$sql = "select o.order_id, o.reference, o.firstname, o.lastname, os.name as status, o.date_added, o.total, o.currency, o.value from `order` o left join order_status os on o.order_status_id = os.order_status_id where os.language_id = '" . (int)$language->getId() . "'";
    	} else {
      		$sql = "select o.order_id, o.reference, o.firstname, o.lastname, os.name as status, o.date_added, o.total, o.currency, o.value from `order` o left join order_status os on o.order_status_id = os.order_status_id where o.reference like '?' and os.language_id = '" . (int)$language->getId() . "'";
    	}
     
		$sort = array(
	  		'o.order_id', 
			'o.reference',
	  		'o.firstname', 
	  		'os.name', 
	  		'o.date_added', 
	  		'o.total'
		);
    
		if (in_array($session->get('order.sort'), $sort)) {
      		$sql .= " order by " . $session->get('order.sort') . " " . (($session->get('order.order') == 'desc') ? 'desc' : 'asc');
    	} else {
      		$sql .= " order by o.date_added desc";
    	}
  
    	$results = $database->getRows($database->splitQuery($database->parse($sql, '%' . $session->get('order.search') . '%'), $session->get('order.page'), $config->get('config_max_rows')));

    	$rows = array();

    	foreach ($results as $result) {
      		$cell = array();

      		$cell[] = array(
        		'value' => $result['order_id'],
        		'align' => 'left'
      		);

      		$cell[] = array(
        		'value' => $result['reference'],
        		'align' => 'left'
      		);			

      		$cell[] = array(
        		'value' => $result['firstname'] . ' ' . $result['lastname'],
        		'align' => 'left'
      		);

      		$cell[] = array(
        		'value' => $result['status'],
        		'align' => 'left'
      		);

      		$cell[] = array(
        		'value' => $language->formatDate($language->get('date_format_short'), strtotime($result['date_added'])),
        		'align' => 'left'
      		);

      		$cell[] = array(
        		'value' => $currency->format($result['total'], $result['currency'], $result['value']),
        		'align' => 'right'
      		);
			
			$action = array();
      		
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $language->get('button_update'),
				'href' => $url->ssl('order', 'update', array('order_id' => $result['order_id']))
      		);
						
			$action[] = array(
        		'icon' => 'delete.png',
				'text' => $language->get('button_delete'),
				'href' => $url->ssl('order', 'delete', array('order_id' => $result['order_id']))
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
			    
    	$view->set('action', $url->ssl('order', 'page'));
 
    	$view->set('search', $session->get('order.search'));
    	$view->set('sort', $session->get('order.sort'));
    	$view->set('order', $session->get('order.order'));
    	$view->set('page', $session->get('order.page'));

    	$view->set('cols', $cols);
    	$view->set('rows', $rows);

    	$view->set('list', $url->ssl('order'));
      
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
		$currency =& $this->locator->get('currency');
		$address  =& $this->locator->get('address');
		$config   =& $this->locator->get('config');
	    	
    	$view = $this->locator->create('template');

    	$view->set('heading_title', $language->get('heading_title') . ' #' . $request->gethtml('order_id'));
    	$view->set('heading_description', $language->get('heading_description'));

    	$view->set('text_order', $language->get('text_order'));
		$view->set('text_email', $language->get('text_email'));
		$view->set('text_telephone', $language->get('text_telephone'));
		$view->set('text_fax', $language->get('text_fax'));
		$view->set('text_shipping_address', $language->get('text_shipping_address'));
    	$view->set('text_shipping_method', $language->get('text_shipping_method'));
    	$view->set('text_payment_address', $language->get('text_payment_address'));
    	$view->set('text_payment_method', $language->get('text_payment_method'));
    	$view->set('text_order_history', $language->get('text_order_history'));
    	$view->set('text_order_download', $language->get('text_order_download'));
    	$view->set('text_order_update', $language->get('text_order_update'));
    	$view->set('text_product', $language->get('text_product'));
    	$view->set('text_model_number', $language->get('text_model_number'));
    	$view->set('text_quantity', $language->get('text_quantity'));
    	$view->set('text_price', $language->get('text_price'));
    	$view->set('text_total', $language->get('text_total'));

    	$view->set('column_date_added', $language->get('column_date_added'));
    	$view->set('column_status', $language->get('column_status'));
    	$view->set('column_download', $language->get('column_download'));
    	$view->set('column_filename', $language->get('column_filename'));
    	$view->set('column_remaining', $language->get('column_remaining'));
    	$view->set('column_notify', $language->get('column_notify'));
    	$view->set('column_comment', $language->get('column_comment'));

    	$view->set('entry_status', $language->get('entry_status'));
    	$view->set('entry_comment', $language->get('entry_comment'));
    	$view->set('entry_notify', $language->get('entry_notify'));

    	$view->set('button_list', $language->get('button_list'));
    	$view->set('button_insert', $language->get('button_insert'));
    	$view->set('button_update', $language->get('button_update'));
    	$view->set('button_delete', $language->get('button_delete'));
    	$view->set('button_save', $language->get('button_save'));
    	$view->set('button_cancel', $language->get('button_cancel'));

    	$view->set('tab_general', $language->get('tab_general'));
	
		$view->set('error', @$this->error['message']);

    	$view->set('action', $url->ssl('order', 'update', array('order_id' => $request->gethtml('order_id'))));
      
    	$view->set('list', $url->ssl('order'));
   
    	if ($request->gethtml('order_id')) {	    
      		$view->set('update', $url->ssl('order', 'update', array('order_id' => $request->gethtml('order_id'))));
	  		$view->set('delete', $url->ssl('order', 'delete', array('order_id' => $request->gethtml('order_id'))));
    	}
      
    	$view->set('cancel', $url->ssl('order'));

    	$order_info = $database->getRow("select * from `order` where order_id = '" . (int)$request->gethtml('order_id') . "'");
		
		$view->set('reference', $order_info['reference']);
		$view->set('email', $order_info['email']);
		$view->set('telephone', $order_info['telephone']);
		$view->set('fax', $order_info['fax']);

    	$shipping_address = array(
      		'firstname' => $order_info['shipping_firstname'],
      		'lastname'  => $order_info['shipping_lastname'],
      		'company'   => $order_info['shipping_company'],
      		'address_1' => $order_info['shipping_address_1'],
      		'address_2' => $order_info['shipping_address_2'],
      		'city'      => $order_info['shipping_city'],
      		'postcode'  => $order_info['shipping_postcode'],
      		'zone'      => $order_info['shipping_zone'],
      		'country'   => $order_info['shipping_country']
    	);

    	$view->set('shipping_address', $address->format($shipping_address, $order_info['shipping_address_format'], '<br />'));
  
    	$view->set('shipping_method', $order_info['shipping_method']);

    	$payment_address = array(
      		'firstname' => $order_info['payment_firstname'],
      		'lastname'  => $order_info['payment_lastname'],
      		'company'   => $order_info['payment_company'],
      		'address_1' => $order_info['payment_address_1'],
      		'address_2' => $order_info['payment_address_2'],
      		'city'      => $order_info['payment_city'],
      		'postcode'  => $order_info['payment_postcode'],
      		'zone'      => $order_info['payment_zone'],
      		'country'   => $order_info['payment_country']
    	);

    	$view->set('payment_address', nl2br($address->format($payment_address, $order_info['payment_address_format'])));

    	$view->set('payment_method', $order_info['payment_method']);

    	$products = $database->getRows("select * from order_product where order_id = '" . (int)$request->gethtml('order_id') . "'");

    	$product_data = array();

    	foreach ($products as $product) {
      		$options = $database->getRows("select * from order_option where order_id = '" . (int)$request->gethtml('order_id') . "' and order_product_id = '" . (int)$product['order_product_id'] . "'");

      		$option_data = array();

      		foreach ($options as $option) {
        		$option_data[] = array(
          			'name'  => $option['name'],
          			'value' => $option['value'],
        		);
      		}
      	  
			if ($config->get('config_tax')) {
				$product_data[] = array(
					'name'         => $product['name'],
					'model_number' => $product['model_number'],
					'option'       => $option_data,
					'quantity'     => $product['quantity'],
					'price'        => $currency->format(($product['price'] + ($product['price'] * $product['tax'] / 100)), $order_info['currency'], $order_info['value']),
					'discount'     => (ceil($product['discount']) ? $currency->format(($product['price'] - $product['discount']) + (($product['price'] - $product['discount']) * $product['tax'] / 100), $order_info['currency'], $order_info['value']) : NULL),
					'total'        => $currency->format($product['total'] + ($product['total'] * $product['tax'] / 100), $order_info['currency'], $order_info['value'])
				);
			}
			else {
				$product_data[] = array(
					'name'         => $product['name'],
					'model_number' => $product['model_number'],
					'option'       => $option_data,
					'quantity'     => $product['quantity'],
					'price'        => $currency->format($product['price'], $order_info['currency'], $order_info['value']),
					'discount'     => (ceil($product['discount']) ? $currency->format(($product['price'] - $product['discount']) , $order_info['currency'], $order_info['value']) : NULL),
					'total'        => $currency->format($product['total'], $order_info['currency'], $order_info['value'])
				);
			}
    	}

    	$view->set('products', $product_data);

    	$view->set('totals', $database->getRows("select * from order_total where order_id = '" . (int)$request->gethtml('order_id') . "'"));

    	$history_data = array();

    	$results = $database->getRows("select date_added, os.name as status, oh.comment, oh.notify from order_history oh left join order_status os on oh.order_status_id = os.order_status_id where oh.order_id = '" . (int)$request->gethtml('order_id') . "' and os.language_id = '" . (int)$language->getId() . "' order by oh.date_added");

    	foreach ($results as $result) {
      		$history_data[] = array(
        		'date_added' => $language->formatDate($language->get('date_format_short'), strtotime($result['date_added'])),
        		'status'     => $result['status'],
        		'comment'    => $result['comment'],
        		'notify'     => $result['notify']
      		);
    	}

    	$view->set('historys', $history_data);
  
    	$download_data = array();
  
    	$results = $database->getRows("select * from order_download where order_id = '" . (int)$request->gethtml('order_id') . "' order by name");

    	foreach ($results as $result) {
      		$download_data[] = array(
        		'name'      => $result['name'],
        		'filename'  => $result['filename'],
        		'remaining' => $result['remaining']
      		);
    	}

    	$view->set('downloads', $download_data);

    	$view->set('order_status_id', $order_info['order_status_id']);

    	$view->set('order_statuses', $database->cache('order_status-' . (int)$language->getId(), "select order_status_id, name from order_status where language_id = '" . (int)$language->getId() . "' order by name"));

		return $view->fetch('content/order.tpl');
  	}
  	
	function validate() {
		$user     =& $this->locator->get('user');
		$language =& $this->locator->get('language');
    
    	if (!$user->hasPermission('modify', 'order')) {
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
	  		$session->set('order.search', $request->gethtml('search', 'post'));
		}
	
		if (($request->has('page', 'post')) || ($request->has('search', 'post'))) {
	  		$session->set('order.page', $request->gethtml('page', 'post'));
		} 
	
		if ($request->has('sort', 'post')) {
	  		$session->set('order.order', (($session->get('order.sort') == $request->gethtml('sort', 'post')) && ($session->get('order.order') == 'asc') ? 'desc' : 'asc'));
		}
		
		if ($request->has('sort', 'post')) {
			$session->set('order.sort', $request->gethtml('sort', 'post'));
		}
				
		$response->redirect($url->ssl('order'));
  	}     
}
?>