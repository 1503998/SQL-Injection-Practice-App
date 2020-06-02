<?php
class ControllerPaymentauthnetaim extends Controller {
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
        
        $language->load('controller/payment_authnetaim.php');

        $template->set('title', $language->get('heading_title'));
                
        if (($request->isPost()) && ($this->validate())) {
            // Get existing usps values before delete. Then repopulate with new values. For loop replaces static method.
            $results = $database->getRows("select * from setting where `group` = 'authnetaim' and `key` != 'authnetaim_currency'");
            $database->query("delete from setting where `group` = 'authnetaim'");
            foreach ($results as $result) {
                //if ($result['key'] != 'authnetaim_currency') {
                    $combo = $result['type'] . "_" . $result['key'];
                    $database->query($database->parse("insert into setting set type = '" . $result['type'] . "', `group` = 'authnetaim', `key` = '" . $result['key'] . "', `value` = '?'", htmlspecialchars($request->gethtml($combo, 'post'))));
                //}
            }
            // Special case for currency multiple select box
            $database->query($database->parse("insert into setting set type = 'global', `group` = 'authnetaim', `key` = 'authnetaim_currency', `value` = '?'", implode(',', $request->gethtml('global_authnetaim_currency', 'post', array()))));
            //
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
        $view->set('text_authonly', $language->get('text_authonly'));
        $view->set('text_authcapture', $language->get('text_authcapture'));
        
        $view->set('button_list', $language->get('button_list'));
        $view->set('button_insert', $language->get('button_insert'));
        $view->set('button_update', $language->get('button_update'));
        $view->set('button_delete', $language->get('button_delete'));
        $view->set('button_save', $language->get('button_save'));
        $view->set('button_cancel', $language->get('button_cancel'));

        $view->set('tab_general', $language->get('tab_general'));
        $view->set('tab_form', $language->get('tab_form'));
        $view->set('text_form_options', $language->get('text_form_options'));

        $view->set('error', @$this->error['message']);
        
        $view->set('action', $url->ssl('payment_authnetaim'));
        $view->set('list', $url->ssl('extension', FALSE, array('type' => 'payment')));
        $view->set('cancel', $url->ssl('extension', FALSE, array('type' => 'payment')));    

        // Get each key from the database for group usps and set to the view dynamically (Replaces OpenCart's static method)
        $results = $database->getRows("select * from setting where `group` = 'authnetaim'");
        foreach ($results as $result) {
            $setting_info[$result['type']][$result['key']] = $result['value'];
            $combo = $result['type'] . "_" . $result['key'];
            if ($request->has($combo, 'post')) {
                $view->set($combo, $request->gethtml($combo, 'post'));
            } else {
                $view->set($combo, htmlspecialchars_decode(@$setting_info[$result['type']][$result['key']]));
            }
            // dynamically set all entry_ & extra_ values to match the language file.
            $view->set('entry_' . $result['key'], $language->get('entry_' . $result['key']));
            $view->set('extra_' . $result['key'], $language->get('extra_' . $result['key']));
        }
        //
                                        
        $view->set('geo_zones', $database->cache('geo_zone', "select * from geo_zone"));
                                
        $template->set('content', $view->fetch('content/payment_authnetaim.tpl'));

        $template->set($module->fetch());

        $response->set($template->fetch('layout.tpl'));
    }
    
    function validate() {
        $language =& $this->locator->get('language');
        $request  =& $this->locator->get('request');
        $user     =& $this->locator->get('user');

        if (!$user->hasPermission('modify', 'payment_authnetaim')) {
            $this->error['message'] = $language->get('error_permission');
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
        
        $language->load('controller/payment_authnetaim.php');
                
        if ($user->hasPermission('modify', 'payment_authnetaim')) {
            $database->query("delete from setting where `group` = 'authnetaim'");
            
            // Add Order status 99 "Needs Payment Review" if not exist.
            $database->query("replace into order_status(order_status_id, language_id, name) values ('99', '1', 'Paid Unconfirmed')");
            
            $database->query("insert into setting set type = 'global', `group` = 'authnetaim', `key` = 'authnetaim_status', value = '0'");
            $database->query("insert into setting set type = 'global', `group` = 'authnetaim', `key` = 'authnetaim_geo_zone_id', value = '0'");
            $database->query("insert into setting set type = 'global', `group` = 'authnetaim', `key` = 'authnetaim_sendemail', value = 'FALSE'");
            $database->query("insert into setting set type = 'global', `group` = 'authnetaim', `key` = 'authnetaim_test', value = '1'");
            $database->query("insert into setting set type = 'global', `group` = 'authnetaim', `key` = 'authnetaim_prod_login', value = ''");
            $database->query("insert into setting set type = 'global', `group` = 'authnetaim', `key` = 'authnetaim_prod_txnkey', value = ''");
            $database->query("insert into setting set type = 'global', `group` = 'authnetaim', `key` = 'authnetaim_test_login', value = ''");
            $database->query("insert into setting set type = 'global', `group` = 'authnetaim', `key` = 'authnetaim_test_txnkey', value = ''");
            $database->query("insert into setting set type = 'global', `group` = 'authnetaim', `key` = 'authnetaim_authtype', value = 'auth_capture'");
            $database->query("insert into setting set type = 'global', `group` = 'authnetaim', `key` = 'authnetaim_sort_order', value = '1'");
            
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

        $language->load('controller/payment_authnetaim.php');
                
        if ($user->hasPermission('modify', 'payment_authnetaim')) {
            $database->query("delete from setting where `group` = 'authnetaim'");
            
            $session->set('message', $language->get('text_message'));
        } else {
            $session->set('error', $language->get('error_permission'));
        }    
                
        $response->redirect($url->ssl('extension', FALSE, array('type' => 'payment')));
    }
}
?>