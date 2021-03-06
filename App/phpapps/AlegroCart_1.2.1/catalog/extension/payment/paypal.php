<?php 
class PaymentPayPal extends Payment {
    function __construct(&$locator) {
        $this->address   =& $locator->get('address');
        $this->cart      =& $locator->get('cart');
        $this->config    =& $locator->get('config');
        $this->coupon    =& $locator->get('coupon');
        $this->currency  =& $locator->get('currency');
        $this->customer  =& $locator->get('customer');
        $this->database  =& $locator->get('database');
        $this->language  =& $locator->get('language');
        $this->mail      = $locator->create('mail');
        $this->order     =& $locator->get('order');
        $this->request   =& $locator->get('request');
        $this->response  =& $locator->get('response');
        $this->session   =& $locator->get('session');
        $this->shipping  =& $locator->get('shipping');
        $this->tax       =& $locator->get('tax');
        $this->url       =& $locator->get('url');        
        
        $this->language->load('extension/payment/paypal.php');
    }
  
    function getTitle() {
        return $this->language->get('text_paypal_title');
    }
   
    function getMethod() {
        if ($this->config->get('paypal_status')) {
            if (!$this->config->get('paypal_geo_zone_id')) {
                $status = true;
            } elseif ($this->database->getRows("select * from zone_to_geo_zone where geo_zone_id = '" . (int)$this->config->get('paypal_geo_zone_id') . "' and country_id = '" . (int)$this->address->getCountryId($this->session->get('payment_address_id')) . "' and (zone_id = '" . (int)$this->address->getZoneId($this->session->get('payment_address_id')) . "' or zone_id = '0')")) {
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
                'id'         => 'paypal',
                'title'      => $this->language->get('text_paypal_title'),
                'sort_order' => $this->config->get('paypal_sort_order')
            );
        }
   
        return $method_data;
    }

      
    function getActionUrl() {
        if (!$this->config->get('paypal_test')) {
            return 'https://www.paypal.com/cgi-bin/webscr';
        } else {
            return 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        }
    }
      
    function fields() {    
        
        // New feature in checkout_process to prove that the checkout button was actually clicked by setting the confirm_id = session_id() and verifying it after post.
        //$this->session->set('confirm_id', session_id());
       
        // Validate the current currency is available for paypal, else use default currency. Weak design.
        $currency_data = explode(',', $this->config->get('paypal_currency'));
        if (in_array($this->currency->getCode(), $currency_data)) {
            $currency = $this->currency->getCode();
        } else {
            $currency = $this->config->get('config_currency');
        }

        $fields=array();
        $i=1;
        if ($this->config->get('paypal_itemized') && empty($this->coupon->data)) {
            $fields['cmd']='_cart';
            $fields['upload']=1; 
            //$fields['shipping_1']=$this->tax->calculate($this->shipping->getCost($this->session->get('shipping_method')), $this->config->get('config_tax'));           
            $fields['shipping_1']=$this->shipping->getCost($this->session->get('shipping_method'));           
            $taxtotal = 0;
			foreach ($this->cart->getTaxes() as $key => $value) {
				$taxtotal += $this->currency->format($value, $currency, FALSE, FALSE);
			}
			$fields['tax']=$taxtotal;
			$fields['tax_cart']=$taxtotal;

            foreach ($this->cart->getProducts() as $result) {
                $fields['item_number_' . $i . '']=$result['model_number'];
                $fields['item_name_' . $i . '']=$result['name'];
                $fields['amount_' . $i . '']=$this->currency->format($result['price'], $currency, FALSE, FALSE);
                $fields['quantity_' . $i . '']=$result['quantity'];
                $fields['weight_' . $i . '']=$result['weight'];
                if (!empty($result['option'])) {
                    $x=0;
                    foreach ($result['option'] as $res) {
                        $fields['on' . $x . '_' . $i . '']=$res['name'];
                        $fields['os' . $x . '_' . $i . '']=$res['value'];
                        $x++;
                    }
                }
                $i++;
            }
        } else {
            $fields['cmd']='_xclick';
            $fields['item_name']=$this->config->get('config_store');
            $fields['amount']=$this->currency->format($this->order->get('total'), $currency, FALSE, FALSE);       
        }
        
        $fields['business']=$this->config->get('paypal_email');
        $fields['currency_code']=$currency;
        $fields['first_name']=$this->order->get('firstname');
        $fields['last_name']=$this->order->get('lastname');
        $fields['address1']=$this->order->get('payment_address_1');
        $fields['address2']=$this->order->get('payment_address_2');
        $fields['city']=$this->order->get('payment_city');
        $fields['zip']=$this->order->get('payment_postcode');
        $fields['country']=$this->order->get('payment_country');
        $fields['address_override']=0;
        $fields['notify_url']=$this->url->rawssl('checkout_process', 'callback', array('payment' => 'paypal', 'method' => 'ipn', 'ref' => $this->order->getReference()));
        $fields['email']=$this->order->get('email');
        $fields['invoice']=$this->order->getReference();
        $fields['lc']=$this->language->getCode();
        $fields['return']=$this->url->rawssl('checkout_process', 'index', array('method' => 'return', 'ref' => $this->order->getReference()));
        $fields['rm']=2; //NOT USED IF AUTORETURN IS ENABLED
        $fields['no_note']=1;
        $fields['cancel_return']=$this->url->rawssl('checkout_process', 'index', array('method' => 'cancel'));
        $fields['paymentaction']=$this->config->get('paypal_auth_type');
        $fields['custom']=session_id();
      
        $output=array();
        foreach ($fields as $key => $value) {
            $output[]='<input type="hidden" name="'.$key.'" value="'.$value.'">';
        }

        $output=implode("\n",$output);
        
        return $output;
        
    }
    
    function process() {
       
        // if customer canceled then return to checkout_confirm page for retry.
        if ($this->request->gethtml('method') == 'cancel') {  
            $this->response->redirect($this->url->rawssl('checkout_confirm',false,array('error'=> $this->language->get('error_canceled'))));
        
        // If customer clicks on Return to Merchant or Auto-returns.
        // Remember: At this point, the customer paid for "something" 
        // The only way to hit the return path is to successfully pay
        // So he should only see success message, even if there is a problem.
        // Problems can be handled later by the store owner.
        } elseif ($this->request->gethtml('method') == 'return') {
            
            $this->order->load($this->order->getReference());
            $this->order->process($this->getOrderStatusId('order_status_paid_unconfirmed'));

            /////////////////////////////////////////////
            // Use PDT if available and PDT Token is set
            /////////////////////////////////////////////
            if ($this->request->get('tx') != null && $this->config->get('paypal_pdt_token') != '') {
                
                // Paypal possible values for payment_status
                $success_status = array('Completed', 'Pending', 'In-Progress', 'Processed');
                $failed_status = array('Denied', 'Expired', 'Failed');
                
                // read the post from PayPal system and add 'cmd'
                $req = 'cmd=_notify-synch';

                $tx_token = $this->request->get('tx');
                $auth_token = $this->config->get('paypal_pdt_token');
                $req .= "&tx=$tx_token&at=$auth_token";

                // post back to PayPal system to validate
                $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
                $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
                $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
                
                $url=$this->config->get('paypal_test')?'ssl://www.sandbox.paypal.com':'ssl://www.paypal.com';
                // NON-SSL
                //$fp = @fsockopen ($url, 80, $errno, $errstr, 30);
                // SSL
                $fp = fsockopen ($url, 443, $errno, $errstr, 30);

                if ($fp) {
                    fputs ($fp, $header . $req);
                    // read the body data
                    $res = '';
                    $headerdone = false;
                    while (!feof($fp)) {
                        $line = fgets ($fp, 1024);
                        if (strcmp($line, "\r\n") == 0) {
                            // read the header
                            $headerdone = true;
                        } else if ($headerdone) {
                            // header has been read. now read the contents
                            $res .= $line;
                        }
                    }
                    fclose ($fp);
                    
                    // parse the data
                    $lines = explode("\n", $res);
                    $keyarray = array();
                    $error = '';
                    if (strcmp ($lines[0], "SUCCESS") == 0) {
                        for ($i=1; $i<(count($lines)-1);$i++){
                            list($key,$val) = explode("=", $lines[$i]);
                            $keyarray[urldecode($key)] = urldecode($val);
                        }
                        
                        // Verify the payment_status isn't failed
                        if (in_array($keyarray['payment_status'], $failed_status)) {
                            $error = $this->language->get('error_failed_payment');
                        }
                        
                        // Verify the payment_gross matches the order total
                        $currency_data = explode(',', $this->config->get('paypal_currency'));
                        if (in_array($this->currency->getCode(), $currency_data)) {
                            $currency = $this->currency->getCode();
                        } else {
                            $currency = $this->config->get('config_currency');
                        }
                        if ($this->currency->format(($this->cart->getTotal() + $this->shipping->getCost($this->session->get('shipping_method'))) , $currency, FALSE, FALSE) != $keyarray['payment_gross']) {
                            $error = $this->language->get('error_mismatched_amount'); 
                        }

                        // Verify the receiver_email matches your paypal email
                        if ($this->config->get('paypal_email') != $keyarray['receiver_email']) {
                            $error = $this->language->get('error_wrong_receiver'); 
                        }
                        
                        // If there are no errors, update payment status.
                        // If there are errors, we still want to show success since the customer did pay
                        // It will just leave the order in "Paid Unconfirmed" state.
                        // Need a way to convey the real error to the store owner aside from not updating the order
                        if (!$error) { 
                            $this->orderUpdate();
                        }

                    } else {
                        // PDT Failed, but payment was still made, so customer should see success
                        // The order will just be in "Paid Unconfirmed" state for store owner to review.
                        // Need a way to convey the real error to the store owner aside from not updating the order
                        // This could happen for invalid PDT Token
                        // $error = $this->language->get('error_fail_header'); 
                    }
                } //eof if $fp
            } //eof if pdt
            return true; // return to checkout_process page
        } else {
            die('Unknown process method error: No return method was specified. Please contact store owner.');
        }
        return false; // return to checkout_process page
    }
    
    
    /**
    * The modified callback handler for paypal. If paypal does call back with a verification
    * then that is nice but we no longer rely on it as a work flow step in order processing.
    * 
    * Note:
    *       The value for "order_status_paid_unconfirmed" and "order_status_pending" 
    *       in the language file for the paypal payment extension for each
    *       supported language MUST exactly match the 'name' field of the order_status
    *       table for the same language. 
    */
    function callback() {
        
        
        // if IPN callback is called
        if ($this->request->gethtml('method') == 'ipn'){ 
            
            $this->order->load($this->request->get('ref'));
            $this->order->process($this->getOrderStatusId('order_status_paid_unconfirmed'));
            
            // read the post from PayPal system and add 'cmd'
            $req = 'cmd=_notify-validate';
            
            foreach ($_POST as $key => $value) {
                $req .= '&' . $key . '=' . urlencode(stripslashes($value));
            }
            
            //Debug
            if ($this->config->get('paypal_ipn_debug')) { $this->DoDebug($req); }
           
            // post back to PayPal system to validate
            $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
            $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
            
            $url=$this->config->get('paypal_test')?'ssl://www.sandbox.paypal.com':'ssl://www.paypal.com';
            //SSL version
            $fp = fsockopen($url, 443, $errno, $errstr, 30);
            //NON-SSL version
            //$fp = fsockopen ($url, 80, $errno, $errstr, 30);
            
            //Debug
            if ($this->config->get('paypal_ipn_debug')) { $this->DoDebug($header . $req); }
            
            if ($fp) {
                fputs($fp, $header . $req);            
                while (!feof($fp)) {
                    $res = fgets($fp, 1024);
                }
                fclose($fp);
                
                //Debug
                if ($this->config->get('paypal_ipn_debug')) { $this->DoDebug($res); }
                
                if (strcmp ($res, "VERIFIED") == 0) {
                    $this->orderUpdate(); // Update order to pending or specify new value in language file
                }
            }
        }
    }
    
    
    function getOrderStatusId ($status='order_status_paid_unconfirmed') {
        // Lookup order_status_paid_unconfirmed status id specified by the exact name in the language file.
        $sql = "select `order_status_id` from `order_status` where `name` = '?' and `language_id` = '?'";
        $parsed = $this->database->parse($sql, $this->language->get($status), $this->language->getId()); 
        $results = $this->database->getRow($parsed);
        
        // if no status exists for the current language, fallback to english status id
        if (empty($results)) { 
            $sql = "select `order_status_id` from `order_status` where `name` = '?' and `language_id` = '?'";
            $parsed = $this->database->parse($sql, $this->language->get($status), '1'); 
            $results = $this->database->getRow($parsed);
            
            // if no status exists for english either, then die
            if (empty($results)) { 
                // Perhaps the check for status should be before checkout so that there is no chance of the customer seeing this after payment is made.
                die('Configuration error: Store owner needs to create an order status for "Paid Unconfirmed" for every installed language. The following sql statement returned no rows and must cease ' . $parsed);
            }
        }
        return $results['order_status_id'];
    }
    
    
    // Debug logging
    function DoDebug($msg='') {
        $f=fopen("paypal_ipn_debug_" . date("Ymd") . ".txt","a");             
        fwrite($f, $msg . "\r\n\r\n");
        fclose($f);
    }
    
    
    // Update order to "Pending" (default) or specify variable name of the status you'd like as listed in the language file.
    function orderUpdate($status = 'final_order_status', $override = 0) {
        
        //Find the paid_unconfirmed status id
        $results = $this->getOrderStatusId('order_status_paid_unconfirmed');
        $paidUnconfirmedStatusId = $results?$results:0;
        
        //Find the final order status id
        $results = $this->getOrderStatusId($status);
        $finalStatusId = $results?$results:0;
        
        $reference = $this->request->get('ref');
        
        //Get Order Id
        $sql = "select order_id from `order` where `reference` = '?'";
        $parsed = $this->database->parse($sql, $reference);
        $res = $this->database->getrow($parsed);
        $order_id = $res['order_id'];
        
        //Update order only if state in paid unconfirmed OR override is set
        if ($order_id) {
            if ($override) {
                
                // Update order status
                $sql = "update `order` set `order_status_id` = '?' where `reference` = '?'";
                $parsed = $this->database->parse($sql, $finalStatusId, $reference);
                $result = $this->database->countAffected($this->database->query($parsed));
                
                // Update order_history
                if ($result) {
                    $sql = "insert into order_history set order_id = '?' , order_status_id = '?', date_added = now(), notify = '0', comment = 'override'";
                    $parsed = $this->database->parse($sql, $order_id, $finalStatusId);
                    $this->database->query($parsed);
                }
            } else {
                
                // Update order status only if status is currently paid_unconfirmed
                $sql = "update `order` set `order_status_id` = '?' where `reference` = '?' and `order_status_id` = '?'";
                $parsed = $this->database->parse($sql, $finalStatusId, $reference, $paidUnconfirmedStatusId);
                $result = $this->database->countAffected($this->database->query($parsed));
                // Update order_history
                if ($result)  {
                    $sql = "insert into order_history set order_id = '?' , order_status_id = '?', date_added = now(), notify = '0', comment = 'PDT/IPN'";
                    $parsed = $this->database->parse($sql, $order_id, $finalStatusId);
                    $this->database->query($parsed);
                }
            }
        }
        
    }
}
?>