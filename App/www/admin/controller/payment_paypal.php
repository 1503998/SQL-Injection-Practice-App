<?php
class ControllerPaymentPayPal extends Controller {
	var $error = array();
	 
	function index() { 
		$database =& $this->locator->get('database');
		$language =& $this->locator->get('language');
		$module   =& $this->locator->get('module');
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$template =& $this->locator->get('template');
		$session  =& $this->locator->get('session');
		$url      =& $this->locator->get('url');
		
		$language->load('controller/payment_paypal.php');

		$template->set('title', $language->get('heading_title'));
				
		if ($request->isPost() && $request->has('global_paypal_status', 'post') && $this->validate()) {
			$database->query("delete from setting where `group` = 'paypal'");
			
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_status', `value` = '?'", $request->gethtml('global_paypal_status', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_geo_zone_id', `value` = '?'", $request->gethtml('global_paypal_geo_zone_id', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_email', `value` = '?'", $request->gethtml('global_paypal_email', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_test', `value` = '?'", $request->gethtml('global_paypal_test', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_currency', `value` = '?'", implode(',', $request->gethtml('global_paypal_currency', 'post', array()))));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_sort_order', `value` = '?'", $request->gethtml('global_paypal_sort_order', 'post')));
            $database->query($database->parse("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_auth_type', `value` = '?'", $request->gethtml('global_paypal_auth_type', 'post')));
            $database->query($database->parse("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_pdt_token', `value` = '?'", $request->gethtml('global_paypal_pdt_token', 'post')));
            $database->query($database->parse("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_itemized', `value` = '?'", $request->gethtml('global_paypal_itemized', 'post')));
            $database->query($database->parse("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_ipn_debug', `value` = '?'", $request->gethtml('global_paypal_ipn_debug', 'post')));
			
			$session->set('message', $language->get('text_message'));

			$response->redirect($url->ssl('extension', FALSE, array('type' => 'payment')));
		}
		
		$view = $this->locator->create('template');
		
		$view->set('heading_title', $language->get('heading_title'));
		$view->set('heading_description', $language->get('heading_description'));		

		$view->set('text_enabled', $language->get('text_enabled'));
		$view->set('text_disabled', $language->get('text_disabled'));
		$view->set('text_all_zones', $language->get('text_all_zones'));
		$view->set('text_none', $language->get('text_none'));
		$view->set('text_yes', $language->get('text_yes'));
		$view->set('text_no', $language->get('text_no'));
				
		$view->set('entry_status', $language->get('entry_status'));
		$view->set('entry_geo_zone', $language->get('entry_geo_zone'));
		$view->set('entry_email', $language->get('entry_email'));
		$view->set('entry_test', $language->get('entry_test'));
		$view->set('entry_currency', $language->get('entry_currency'));
		$view->set('entry_sort_order', $language->get('entry_sort_order'));
        $view->set('entry_auth_type', $language->get('entry_auth_type'));
        $view->set('entry_pdt_token', $language->get('entry_pdt_token'));
        $view->set('entry_itemized', $language->get('entry_itemized'));
        $view->set('entry_ipn_debug', $language->get('entry_ipn_debug'));
        
        $view->set('extra_auth_type', $language->get('extra_auth_type'));
        $view->set('extra_pdt_token', $language->get('extra_pdt_token'));
        $view->set('extra_itemized', $language->get('extra_itemized'));
        $view->set('extra_ipn_debug', $language->get('extra_ipn_debug'));
        $view->set('text_support', $language->get('text_support'));
        
		
		$view->set('button_list', $language->get('button_list'));
		$view->set('button_insert', $language->get('button_insert'));
		$view->set('button_update', $language->get('button_update'));
		$view->set('button_delete', $language->get('button_delete'));
		$view->set('button_save', $language->get('button_save'));
		$view->set('button_cancel', $language->get('button_cancel'));
        
        $view->set('text_authorization', $language->get('text_authorization'));
        $view->set('text_sale', $language->get('text_sale'));
        $view->set('text_order', $language->get('text_order'));

		$view->set('tab_general', $language->get('tab_general'));

		$view->set('error', @$this->error['message']);
		$view->set('error_email', @$this->error['email']);
        $view->set('error_pdt_token', @$this->error['pdt_token']);
		
		$view->set('action', $url->ssl('payment_paypal'));
		
		$view->set('list', $url->ssl('extension', FALSE, array('type' => 'payment')));

		$view->set('cancel', $url->ssl('extension', FALSE, array('type' => 'payment')));	

		$results = $database->getRows("select * from setting where `group` = 'paypal'");
			
		foreach ($results as $result) {
			$setting_info[$result['type']][$result['key']] = $result['value'];
		}
		
		if ($request->has('global_paypal_status', 'post')) {
			$view->set('global_paypal_status', $request->gethtml('global_paypal_status', 'post'));
		} else {
			$view->set('global_paypal_status', @$setting_info['global']['paypal_status']);
		}
		
		if ($request->has('global_paypal_geo_zone_id', 'post')) {
			$view->set('geo_zone_id', $request->gethtml('global_paypal_geo_zone_id', 'post'));
		} else {
			$view->set('geo_zone_id', @$setting_info['global']['paypal_geo_zone_id']); 
		} 

		if ($request->has('global_paypal_email', 'post')) {
			$view->set('global_paypal_email', $request->gethtml('global_paypal_email', 'post'));
		} else {
			$view->set('global_paypal_email', @$setting_info['global']['paypal_email']);
		}
        
        if ($request->has('global_paypal_pdt_token', 'post')) {
            $view->set('global_paypal_pdt_token', $request->gethtml('global_paypal_pdt_token', 'post'));
        } else {
            $view->set('global_paypal_pdt_token', @$setting_info['global']['paypal_pdt_token']);
        }
		
		if ($request->has('global_paypal_test', 'post')) {
			$view->set('global_paypal_test', $request->gethtml('global_paypal_test', 'post'));
		} else {
			$view->set('global_paypal_test', @$setting_info['global']['paypal_test']);
		}
        
		if ($request->has('global_paypal_currency', 'post')) {
			$payment_info = $request->gethtml('global_paypal_currency', 'post');
		} else {
			$payment_info = explode(',', @$setting_info['global']['paypal_currency']);
		}

		$currency_data = array();
		
		$currency_data[] = array(
			'text'     => $language->get('text_cad'),
			'value'    => 'CAD',
			'selected' => in_array('CAD', $payment_info)
		);

		$currency_data[] = array(
			'text'     => $language->get('text_eur'),
			'value'    => 'EUR',
			'selected' => in_array('EUR', $payment_info)
		);
		$currency_data[] = array(
			'text'     => $language->get('text_gbp'),
			'value'    => 'GBP',
			'selected' => in_array('GBP', $payment_info)
		);

		$currency_data[] = array(
			'text'     => $language->get('text_usd'),
			'value'    => 'USD',
			'selected' => in_array('USD', $payment_info)
		);

		$currency_data[] = array(
			'text'     => $language->get('text_jpy'),
			'value'    => 'JPY',
			'selected' => in_array('JPY', $payment_info)
		);
		
		$currency_data[] = array(
			'text'     => $language->get('text_aud'),
			'value'    => 'AUD',
			'selected' => in_array('AUD', $payment_info)
		);
		
		$currency_data[] = array(
			'text'     => $language->get('text_nzd'),
			'value'    => 'NZD',
			'selected' => in_array('NZD', $payment_info)
		);
		
		$currency_data[] = array(
			'text'     => $language->get('text_chf'),
			'value'    => 'CHF',
			'selected' => in_array('CHF', $payment_info)
		);

		$currency_data[] = array(
			'text'     => $language->get('text_hkd'),
			'value'    => 'HKD',
			'selected' => in_array('HKD', $payment_info)
		);
		
		$currency_data[] = array(
			'text'     => $language->get('text_sgd'),
			'value'    => 'SGD',
			'selected' => in_array('SGD', $payment_info)
		);	

		$currency_data[] = array(
			'text'     => $language->get('text_sek'),
			'value'    => 'SEK',
			'selected' => in_array('SEK', $payment_info)
		);

		$currency_data[] = array(
			'text'     => $language->get('text_dkk'),
			'value'    => 'DKK',
			'selected' => in_array('DKK', $payment_info)
		);

		$currency_data[] = array(
			'text'     => $language->get('text_pln'),
			'value'    => 'PLN',
			'selected' => in_array('PLN', $payment_info)
		);
																																	
		$currency_data[] = array(
			'text'     => $language->get('text_nok'),
			'value'    => 'NOK',
			'selected' => in_array('NOK', $payment_info)
		);

		$currency_data[] = array(
			'text'     => $language->get('text_huf'),
			'value'    => 'HUF',
			'selected' => in_array('HUF', $payment_info)
		);
		
		$currency_data[] = array(
			'text'     => $language->get('text_czk'),
			'value'    => 'CZK',
			'selected' => in_array('CZK', $payment_info)
		);

		$view->set('currencies', $currency_data);
		
		if ($request->has('global_paypal_sort_order', 'post')) {
			$view->set('global_paypal_sort_order', $request->gethtml('global_paypal_sort_order', 'post'));
		} else {
			$view->set('global_paypal_sort_order', @$setting_info['global']['paypal_sort_order']);
		}
        
        if ($request->has('global_paypal_auth_type', 'post')) {
            $view->set('global_paypal_auth_type', $request->gethtml('global_paypal_auth_type', 'post'));
        } else {
            $view->set('global_paypal_auth_type', @$setting_info['global']['paypal_auth_type']);
        }
        
        if ($request->has('global_paypal_itemized', 'post')) {
            $view->set('global_paypal_itemized', $request->gethtml('global_paypal_itemized', 'post'));
        } else {
            $view->set('global_paypal_itemized', @$setting_info['global']['paypal_itemized']);
        }
        
        if ($request->has('global_paypal_ipn_debug', 'post')) {
            $view->set('global_paypal_ipn_debug', $request->gethtml('global_paypal_ipn_debug', 'post'));
        } else {
            $view->set('global_paypal_ipn_debug', @$setting_info['global']['paypal_ipn_debug']);
        }
										
		$view->set('geo_zones', $database->cache('geo_zone', "select * from geo_zone"));
								
		$template->set('content', $view->fetch('content/payment_paypal.tpl'));

		$template->set($module->fetch());

		$response->set($template->fetch('layout.tpl'));
	}
	
	function validate() {
		$language =& $this->locator->get('language');
		$request  =& $this->locator->get('request');
		$user     =& $this->locator->get('user');

		if (!$user->hasPermission('modify', 'payment_paypal')) {
			$this->error['message'] = $language->get('error_permission');
		}
		
		if (!$request->gethtml('global_paypal_email', 'post')) {
			$this->error['email'] = $language->get('error_email');
		}
				
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
	
	function install() {
		$database =& $this->locator->get('database');
		$language =& $this->locator->get('language');
		$response =& $this->locator->get('response');
		$session  =& $this->locator->get('session');
		$url      =& $this->locator->get('url');
		$user     =& $this->locator->get('user');
		
		$language->load('controller/payment_paypal.php');
				
		if ($user->hasPermission('modify', 'payment_paypal')) {
			$database->query("delete from setting where `group` = 'paypal'");
			
			$database->query("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_status', value = '0'");
			$database->query("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_geo_zone_id', value = '0'");
			$database->query("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_email', value = ''");
			$database->query("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_test', value = '0'");		
			$database->query("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_currency', value = 'USD'");
			$database->query("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_sort_order', value = '1'");
            $database->query("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_auth_type', value = 'sale'");
            $database->query("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_pdt_token', value = ''");
            $database->query("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_itemized', value = '0'");
            $database->query("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_ipn_debug', value = '0'");
		
			$session->set('message', $language->get('text_message'));
		} else {
			$session->set('error', $language->get('error_permission'));
		}	
				
		$response->redirect($url->ssl('extension', FALSE, array('type' => 'payment')));
	}
	
	function uninstall() {
		$database =& $this->locator->get('database');
		$language =& $this->locator->get('language');
		$response =& $this->locator->get('response');
		$session  =& $this->locator->get('session');
		$url      =& $this->locator->get('url');
		$user     =& $this->locator->get('user');

		$language->load('controller/payment_paypal.php');
				
		if ($user->hasPermission('modify', 'payment_paypal')) {
			$database->query("delete from setting where `group` = 'paypal'");
			
			$session->set('message', $language->get('text_message'));
		} else {
			$session->set('error', $language->get('error_permission'));
		}	
				
		$response->redirect($url->ssl('extension', FALSE, array('type' => 'payment')));
	}
}
?>