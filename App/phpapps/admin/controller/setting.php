<?php //AlegroCart
class ControllerSetting extends Controller {
	var $error = array();
	var $types=array('css');
	function index() { 
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$database =& $this->locator->get('database');
		$url      =& $this->locator->get('url');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$module   =& $this->locator->get('module');
		$session  =& $this->locator->get('session');
		$cache    =& $this->locator->get('cache');

		$language->load('controller/setting.php');

		$template->set('title', $language->get('heading_title'));

		if ($request->isPost() && $request->has('global_config_store', 'post') && $this->validate()) {
			$database->query("delete from setting where `group` = 'config'");

			$database->query($database->parse("insert into setting set type = 'global', `group` = 'config', `key` = 'config_store', `value` = '?'", $request->gethtml('global_config_store', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'config', `key` = 'config_owner', `value` = '?'", $request->gethtml('global_config_owner', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'config', `key` = 'config_address', `value` = '?'", $request->gethtml('global_config_address', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'config', `key` = 'config_telephone', `value` = '?'", $request->gethtml('global_config_telephone', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'config', `key` = 'config_fax', `value` = '?'", $request->gethtml('global_config_fax', 'post')));
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'config', `key` = 'config_template', `value` = '?'", $request->gethtml('catalog_config_template', 'post')));
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'config', `key` = 'config_max_rows', `value` = '?'", $request->gethtml('catalog_config_max_rows', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'config', `key` = 'config_url_alias', `value` = '?'", $request->gethtml('global_config_url_alias', 'post')));
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'config', `key` = 'config_parse_time', `value` = '?'", $request->gethtml('catalog_config_parse_time', 'post')));
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'config', `key` = 'config_ssl', `value` = '?'", $request->gethtml('catalog_config_ssl', 'post')));
			$database->query($database->parse("insert into setting set type = 'admin', `group` = 'config', `key` = 'config_template', `value` = '?'", $request->gethtml('admin_config_template', 'post')));
			$database->query($database->parse("insert into setting set type = 'admin', `group` = 'config', `key` = 'config_max_rows', `value` = '?'", $request->gethtml('admin_config_max_rows', 'post')));
			$database->query($database->parse("insert into setting set type = 'admin', `group` = 'config', `key` = 'config_parse_time', `value` = '?'", $request->gethtml('admin_config_parse_time', 'post')));
			$database->query($database->parse("insert into setting set type = 'admin', `group` = 'config', `key` = 'config_ssl', `value` = '?'", $request->gethtml('admin_config_ssl', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'config', `key` = 'config_country_id', `value` = '?'", $request->gethtml('global_config_country_id', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'config', `key` = 'config_zone_id', `value` = '?'", $request->gethtml('global_config_zone_id', 'post')));
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'config', `key` = 'config_language', `value` = '?'", $request->gethtml('catalog_config_language', 'post')));
            $database->query($database->parse("insert into setting set type = 'admin', `group` = 'config', `key` = 'config_language', `value` = '?'", $request->gethtml('admin_config_language', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'config', `key` = 'config_currency', `value` = '?'", $request->gethtml('global_config_currency', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'config', `key` = 'config_weight_class_id', `value` = '?'", $request->gethtml('global_config_weight_class_id', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'config', `key` = 'config_tax', `value` = '?'", $request->gethtml('global_config_tax', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'config', `key` = 'config_order_status_id', `value` = '?'", $request->gethtml('global_config_order_status_id', 'post')));
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'config', `key` = 'config_stock_check', `value` = '?'", $request->gethtml('catalog_config_stock_check', 'post')));
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'config', `key` = 'config_stock_checkout', `value` = '?'", $request->gethtml('catalog_config_stock_checkout', 'post')));
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'config', `key` = 'config_stock_subtract', `value` = '?'", $request->gethtml('catalog_config_stock_subtract', 'post')));
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'config', `key` = 'config_vat', `value` = '?'", $request->gethtml('catalog_config_vat', 'post')));
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'config', `key` = 'config_account_id', `value` = '?'", $request->gethtml('catalog_config_account_id', 'post')));
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'config', `key` = 'config_checkout_id', `value` = '?'", $request->gethtml('catalog_config_checkout_id', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'config', `key` = 'config_email', `value` = '?'", $request->gethtml('global_config_email', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'config', `key` = 'config_email_send', `value` = '?'", $request->gethtml('global_config_email_send', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'config', `key` = 'config_cache_query', `value` = '?'", $request->gethtml('global_config_cache_query', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'config', `key` = 'config_compress_output', `value` = '?'", $request->gethtml('global_config_compress_output', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'config', `key` = 'config_compress_level', `value` = '?'", $request->gethtml('global_config_compress_level', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'config', `key` = 'config_image_resize', `value` = '?'", $request->gethtml('global_config_image_resize', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'config', `key` = 'config_image_width', `value` = '?'", $request->gethtml('global_config_image_width', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'config', `key` = 'config_image_height', `value` = '?'", $request->gethtml('global_config_image_height', 'post')));
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'config', `key` = 'config_download', `value` = '?'", $request->gethtml('catalog_config_download', 'post')));
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'config', `key` = 'config_download_status', `value` = '?'", $request->gethtml('catalog_config_download_status', 'post')));
			// New Block
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'config', `key` = 'product_image_width', `value` = '?'", $request->gethtml('catalog_product_image_width', 'post')));
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'config', `key` = 'product_image_height', `value` = '?'", $request->gethtml('catalog_product_image_height', 'post')));
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'config', `key` = 'product_addtocart', `value` = '?'", $request->gethtml('catalog_product_addtocart', 'post')));
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'config', `key` = 'additional_image_width', `value` = '?'", $request->gethtml('catalog_additional_image_width', 'post')));
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'config', `key` = 'additional_image_height', `value` = '?'", $request->gethtml('catalog_additional_image_height', 'post')));
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'config', `key` = 'category_image_width', `value` = '?'", $request->gethtml('catalog_category_image_width', 'post')));
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'config', `key` = 'category_image_height', `value` = '?'", $request->gethtml('catalog_category_image_height', 'post')));
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'config', `key` = 'category_addtocart', `value` = '?'", $request->gethtml('catalog_category_addtocart', 'post')));
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'config', `key` = 'search_image_width', `value` = '?'", $request->gethtml('catalog_search_image_width', 'post')));
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'config', `key` = 'search_image_height', `value` = '?'", $request->gethtml('catalog_search_image_height', 'post')));
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'config', `key` = 'search_addtocart', `value` = '?'", $request->gethtml('catalog_search_addtocart', 'post')));
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'config', `key` = 'search_limit', `value` = '?'", $request->gethtml('catalog_search_limit', 'post')));
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'config', `key` = 'product_options_select', `value` = '?'", $request->gethtml('catalog_product_options_select', 'post')));
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'config', `key` = 'category_options_select', `value` = '?'", $request->gethtml('catalog_category_options_select', 'post')));
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'config', `key` = 'search_options_select', `value` = '?'", $request->gethtml('catalog_search_options_select', 'post')));
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'config', `key` = 'config_styles', `value` = '?'", $request->gethtml('catalog_config_styles', 'post')));
			$database->query($database->parse("insert into setting set type = 'global', `group` = 'config', `key` = 'config_seo', `value` = '?'", $request->gethtml('global_config_seo', 'post')));
			$database->query($database->parse("insert into setting set type = 'catalog', `group` = 'config', `key` = 'config_colors', `value` = '?'", $request->gethtml('catalog_config_colors', 'post')));
			// End of New Block
 
			$session->set('message', $language->get('text_message'));

			$response->redirect($url->ssl('setting'));
		}

		$view = $this->locator->create('template');

		$view->set('heading_title', $language->get('heading_title'));
		$view->set('heading_description', $language->get('heading_description'));

		$view->set('text_yes', $language->get('text_yes'));
		$view->set('text_no', $language->get('text_no'));
		$view->set('text_none', $language->get('text_none'));
		$view->set('text_enabled', $language->get('text_enabled'));
		$view->set('text_disabled', $language->get('text_disabled'));
		$view->set('text_select', $language->get('text_select'));
		$view->set('text_radio', $language->get('text_radio'));
		
		$view->set('entry_store', $language->get('entry_store'));
		$view->set('entry_owner', $language->get('entry_owner'));
		$view->set('entry_address', $language->get('entry_address'));
		$view->set('entry_telephone', $language->get('entry_telephone'));
		$view->set('entry_fax', $language->get('entry_fax'));
		$view->set('entry_template', $language->get('entry_template'));
		$view->set('entry_styles', $language->get('entry_styles'));
		$view->set('entry_colors', $language->get('entry_colors'));
		$view->set('entry_url_alias', $language->get('entry_url_alias'));
		$view->set('entry_seo', $language->get('entry_seo'));
		$view->set('entry_parse_time', $language->get('entry_parse_time'));
		$view->set('entry_ssl', $language->get('entry_ssl'));
		$view->set('entry_rows_per_page', $language->get('entry_rows_per_page'));
		$view->set('entry_country', $language->get('entry_country'));
		$view->set('entry_zone', $language->get('entry_zone'));
		$view->set('entry_language', $language->get('entry_language'));
		$view->set('entry_currency', $language->get('entry_currency'));
		$view->set('entry_weight', $language->get('entry_weight'));
		$view->set('entry_tax', $language->get('entry_tax'));
		$view->set('entry_order_status', $language->get('entry_order_status'));
		$view->set('entry_stock_check', $language->get('entry_stock_check'));
		$view->set('entry_stock_checkout', $language->get('entry_stock_checkout'));
		$view->set('entry_stock_subtract', $language->get('entry_stock_subtract'));
		$view->set('entry_vat', $language->get('entry_vat'));
		$view->set('entry_account', $language->get('entry_account'));
		$view->set('entry_checkout', $language->get('entry_checkout'));
		$view->set('entry_email', $language->get('entry_email'));
		$view->set('entry_email_send', $language->get('entry_email_send'));
		$view->set('entry_cache_query', $language->get('entry_cache_query'));
		$view->set('entry_compress_output', $language->get('entry_compress_output'));
		$view->set('entry_compress_level', $language->get('entry_compress_level'));
		$view->set('entry_download', $language->get('entry_download'));
		$view->set('entry_download_status', $language->get('entry_download_status'));		
		$view->set('entry_image_resize', $language->get('entry_image_resize'));
		$view->set('entry_image_width', $language->get('entry_image_width'));
		$view->set('entry_image_height', $language->get('entry_image_height'));
		$view->set('entry_product_width', $language->get('entry_product_width')); //New
		$view->set('entry_product_height',$language->get('entry_product_height')); //New
		$view->set('entry_product_addtocart',$language->get('entry_product_addtocart')); //New
		$view->set('entry_additional_width',$language->get('entry_additional_width')); //New
		$view->set('entry_additional_height',$language->get('entry_additional_height')); //New
		$view->set('entry_category_width',$language->get('entry_category_width')); //New
		$view->set('entry_category_height',$language->get('entry_category_height')); //New
		$view->set('entry_category_addtocart',$language->get('entry_category_addtocart')); //New
		$view->set('entry_search_width',$language->get('entry_search_width')); //New
		$view->set('entry_search_height',$language->get('entry_search_height')); //New
		$view->set('entry_search_addtocart',$language->get('entry_search_addtocart')); //New
		$view->set('entry_search_limit',$language->get('entry_search_limit')); //New
		$view->set('entry_options_select',$language->get('entry_options_select')); //New
 
		$view->set('button_list', $language->get('button_list'));
		$view->set('button_insert', $language->get('button_insert'));
		$view->set('button_update', $language->get('button_update'));
		$view->set('button_delete', $language->get('button_delete'));
		$view->set('button_save', $language->get('button_save'));
		$view->set('button_cancel', $language->get('button_cancel'));

		$view->set('tab_shop', $language->get('tab_shop'));
		$view->set('tab_admin', $language->get('tab_admin'));
		$view->set('tab_local', $language->get('tab_local'));
		$view->set('tab_stock', $language->get('tab_stock'));
		$view->set('tab_option', $language->get('tab_option'));
		$view->set('tab_mail', $language->get('tab_mail'));
		$view->set('tab_cache', $language->get('tab_cache'));
		$view->set('tab_image', $language->get('tab_image'));
		$view->set('tab_download', $language->get('tab_download'));

		$view->set('error', @$this->error['message']);
		$view->set('error_store', @$this->error['store']);
		$view->set('error_owner', @$this->error['owner']);
		$view->set('error_address', @$this->error['address']);
		$view->set('error_telephone', @$this->error['telephone']);
		$view->set('error_color', @$this->error['color']);
		
		$view->set('message', $session->get('message'));
		
		$session->delete('message');
		
		$view->set('action', $url->ssl('setting'));
		
		$view->set('cancel', $url->ssl('setting'));

		$results = $database->getRows("select * from setting where `group` = 'config'");

		foreach ($results as $result) {
			$setting_info[$result['type']][$result['key']] = $result['value'];
		}

		if ($request->has('global_config_store', 'post')) {
			$view->set('global_config_store', $request->gethtml('global_config_store', 'post'));
		} else {
			$view->set('global_config_store', @$setting_info['global']['config_store']);
		}

		if ($request->has('global_config_owner', 'post')) {
			$view->set('global_config_owner', $request->gethtml('global_config_owner', 'post'));
		} else {
			$view->set('global_config_owner', @$setting_info['global']['config_owner']);
		}

		if ($request->has('global_config_address', 'post')) {
			$view->set('global_config_address', $request->gethtml('global_config_address', 'post'));
		} else {
			$view->set('global_config_address', @$setting_info['global']['config_address']);
		}

		if ($request->has('global_config_telephone', 'post')) {
			$view->set('global_config_telephone', $request->gethtml('global_config_telephone', 'post'));
		} else {
			$view->set('global_config_telephone', @$setting_info['global']['config_telephone']);
		}

		if ($request->has('global_config_fax', 'post')) {
			$view->set('global_config_fax', $request->gethtml('global_config_fax', 'post'));
		} else {
			$view->set('global_config_fax', @$setting_info['global']['config_fax']);
		}

		if ($request->has('global_config_url_alias', 'post')) {
			$view->set('global_config_url_alias', $request->gethtml('global_config_url_alias', 'post'));
		} else {
			$view->set('global_config_url_alias', @$setting_info['global']['config_url_alias']);
		}
		
		if ($request->has('global_config_seo', 'post')) {
			$view->set('global_config_seo', $request->gethtml('global_config_seo', 'post'));
		} else {
			$view->set('global_config_seo', @$setting_info['global']['config_seo']);
		}
		
		// Catalog Template
		if ($request->has('catalog_config_template', 'post')) {
			$view->set('catalog_config_template', $request->gethtml('catalog_config_template', 'post'));
		} else {
			$view->set('catalog_config_template', @$setting_info['catalog']['config_template']); 
		}
		$template_data = array();
		foreach (glob(DIR_CATALOG_TEMPLATE . '*', GLOB_ONLYDIR) as $dirctory) {
			$template_data[] = basename($dirctory);
		}
		$view->set('catalog_templates', $template_data);
		
		// Catalog Styles
		if ($request->has('catalog_config_styles', 'post')) {
			$style = $request->gethtml('catalog_config_styles', 'post');
			$view->set('catalog_config_styles', $style);
		} else {
			$style = @$setting_info['catalog']['config_styles'];
			$view->set('catalog_config_styles', $style);
		}
		$styles_data = array();
		foreach (glob(DIR_CATALOG_STYLES . '*', GLOB_ONLYDIR) as $dir_style) {
			$styles_data[] = basename($dir_style);
		}
		$view->set('catalog_styles', $styles_data);
		
		// Cataloge Colors
		if ($request->has('catalog_config_colors', 'post')) {
			$view->set('catalog_config_colors', $request->gethtml('catalog_config_colors', 'post'));
		} else {
			$view->set('catalog_config_colors', @$setting_info['catalog']['config_colors']);
		}
		$view->set('catalog_colors', $this->checkFiles($style));
		
		// Admin Template
		if ($request->has('admin_config_template', 'post')) {
			$view->set('admin_config_template', $request->gethtml('admin_config_template', 'post'));
		} else {
			$view->set('admin_config_template', @$setting_info['admin']['config_template']);
		}
		$template_data = array();
		foreach (glob(DIR_TEMPLATE . '*', GLOB_ONLYDIR) as $dirctory) {
			$template_data[] = basename($dirctory);
		}
		$view->set('admin_templates', $template_data);

		if ($request->has('catalog_config_max_rows', 'post')) {
			$view->set('catalog_config_max_rows', $request->gethtml('catalog_config_max_rows', 'post'));
		} else {
			$view->set('catalog_config_max_rows', @$setting_info['catalog']['config_max_rows']);
		}

		if ($request->has('admin_config_max_rows', 'post')) {
			$view->set('admin_config_max_rows', $request->gethtml('admin_config_max_rows', 'post'));
		} else {
			$view->set('admin_config_max_rows', @$setting_info['admin']['config_max_rows']);
		}

		if ($request->has('global_config_tax', 'post')) {
			$view->set('global_config_tax', $request->gethtml('global_config_tax', 'post'));
		} else {
			$view->set('global_config_tax', @$setting_info['global']['config_tax']);
		}

		if ($request->has('global_config_email', 'post')) {
			$view->set('global_config_email', $request->gethtml('global_config_email', 'post'));
		} else {
			$view->set('global_config_email', @$setting_info['global']['config_email']);
		}

		if ($request->has('global_config_email_send', 'post')) {
			$view->set('global_config_email_send', $request->gethtml('global_config_email_send', 'post'));
		} else {
			$view->set('global_config_email_send', @$setting_info['global']['config_email_send']);
		}

		if ($request->has('catalog_config_parse_time', 'post')) {
			$view->set('catalog_config_parse_time', $request->gethtml('catalog_config_parse_time', 'post'));
		} else {
			$view->set('catalog_config_parse_time', @$setting_info['catalog']['config_parse_time']);
		}

		if ($request->has('admin_config_parse_time', 'post')) {
			$view->set('admin_config_parse_time', $request->gethtml('admin_config_parse_time', 'post'));
		} else {
			$view->set('admin_config_parse_time', @$setting_info['admin']['config_parse_time']);
		}

		if ($request->has('catalog_config_ssl', 'post')) {
			$view->set('catalog_config_ssl', $request->gethtml('catalog_config_ssl', 'post'));
		} else {
			$view->set('catalog_config_ssl', @$setting_info['catalog']['config_ssl']);
		}

		if ($request->has('admin_config_ssl', 'post')) {
			$view->set('admin_config_ssl', $request->gethtml('admin_config_ssl', 'post'));
		} else {
			$view->set('admin_config_ssl', @$setting_info['admin']['config_ssl']);
		}

		if ($request->has('catalog_config_stock_check', 'post')) {
			$view->set('catalog_config_stock_check', $request->gethtml('catalog_config_stock_check', 'post'));
		} else {
			$view->set('catalog_config_stock_check', @$setting_info['catalog']['config_stock_check']);
		}

		if ($request->has('catalog_config_stock_checkout')) {
			$view->set('catalog_config_stock_checkout', $request->gethtml('catalog_config_stock_checkout'));
		} else {
			$view->set('catalog_config_stock_checkout', @$setting_info['catalog']['config_stock_checkout']);
		}

		if ($request->has('catalog_config_stock_subtract')) {
			$view->set('catalog_config_stock_subtract', $request->gethtml('catalog_config_stock_subtract'));
		} else {
			$view->set('catalog_config_stock_subtract', @$setting_info['catalog']['config_stock_subtract']);
		}

		if ($request->has('catalog_config_vat')) {
			$view->set('catalog_config_vat', $request->gethtml('catalog_config_vat'));
		} else {
			$view->set('catalog_config_vat', @$setting_info['catalog']['config_vat']);
		}

		if ($request->has('catalog_config_account_id')) {
			$view->set('catalog_config_account_id', $request->gethtml('catalog_config_account_id'));
		} else {
			$view->set('catalog_config_account_id', @$setting_info['catalog']['config_account_id']);
		}

		if ($request->has('catalog_config_checkout_id')) {
			$view->set('catalog_config_checkout_id', $request->gethtml('catalog_config_checkout_id'));
		} else {
			$view->set('catalog_config_checkout_id', @$setting_info['catalog']['config_checkout_id']);
		}

		$view->set('informations', $database->cache('information-' . (int)$language->getId(), "select * from information i left join information_description id on (i.information_id = id.information_id) where id.language_id = '" . (int)$language->getId() . "' order by i.sort_order"));

		if ($request->has('global_config_cache_query')) {
			$view->set('global_config_cache_query', $request->gethtml('global_config_cache_query'));
		} else {
			$view->set('global_config_cache_query', @$setting_info['global']['config_cache_query']);
		}

		if ($request->has('global_config_compress_output')) {
			$view->set('global_config_compress_output', $request->gethtml('global_config_compress_output'));
		} else {
			$view->set('global_config_compress_output', @$setting_info['global']['config_compress_output']);
		}

		if ($request->has('global_config_compress_level')) {
			$view->set('global_config_compress_level', $request->gethtml('global_config_compress_level'));
		} else {
			$view->set('global_config_compress_level', @$setting_info['global']['config_compress_level']);
		}

		if ($request->has('global_config_image_resize')) {
			$view->set('global_config_image_resize', $request->gethtml('global_config_image_resize'));
		} else {
			$view->set('global_config_image_resize', @$setting_info['global']['config_image_resize']);
		}

		if ($request->has('global_config_image_width')) {
			$view->set('global_config_image_width', $request->gethtml('global_config_image_width'));
		} else {
			$view->set('global_config_image_width', @$setting_info['global']['config_image_width']);
		}

		if ($request->has('global_config_image_height')) {
			$view->set('global_config_image_height', $request->gethtml('global_config_image_height'));
		} else {
			$view->set('global_config_image_height', @$setting_info['global']['config_image_height']);
		}
		//New Block for Images & Addtocart
		if ($request->has('catalog_product_image_width')) {
			$view->set('catalog_product_image_width', $request->gethtml('catalog_product_image_width'));
		} else {
			$view->set('catalog_product_image_width', @$setting_info['catalog']['product_image_width']);
		}
		if ($request->has('catalog_product_image_height')) {
			$view->set('catalog_product_image_height', $request->gethtml('catalog_product_image_height'));
		} else {
			$view->set('catalog_product_image_height', @$setting_info['catalog']['product_image_height']);
		}
		if ($request->has('catalog_product_addtocart')) {
			$view->set('catalog_product_addtocart', $request->gethtml('catalog_product_addtocart'));
		} else {
			$view->set('catalog_product_addtocart', @$setting_info['catalog']['product_addtocart']);
		}
		if ($request->has('catalog_additional_image_width')) {
			$view->set('catalog_additional_image_width', $request->gethtml('catalog_additional_image_width'));
		} else {
			$view->set('catalog_additional_image_width', @$setting_info['catalog']['additional_image_width']);
		}
		if ($request->has('catalog_additional_image_height')) {
			$view->set('catalog_additional_image_height', $request->gethtml('catalog_additional_image_height'));
		} else {
			$view->set('catalog_additional_image_height', @$setting_info['catalog']['additional_image_height']);
		}
		if ($request->has('catalog_category_image_width')) {
			$view->set('catalog_category_image_width', $request->gethtml('catalog_category_image_width'));
		} else {
			$view->set('catalog_category_image_width', @$setting_info['catalog']['category_image_width']);
		}
		if ($request->has('catalog_category_image_height')) {
			$view->set('catalog_category_image_height', $request->gethtml('catalog_category_image_height'));
		} else {
			$view->set('catalog_category_image_height', @$setting_info['catalog']['category_image_height']);
		}
		if ($request->has('catalog_category_addtocart')) {
			$view->set('catalog_category_addtocart', $request->gethtml('catalog_category_addtocart'));
		} else {
			$view->set('catalog_category_addtocart', @$setting_info['catalog']['category_addtocart']);
		}
		if ($request->has('catalog_search_image_width')) {
			$view->set('catalog_search_image_width', $request->gethtml('catalog_search_image_width'));
		} else {
			$view->set('catalog_search_image_width', @$setting_info['catalog']['search_image_width']);
		}
		if ($request->has('catalog_search_image_height')) {
			$view->set('catalog_search_image_height', $request->gethtml('catalog_search_image_height'));
		} else {
			$view->set('catalog_search_image_height', @$setting_info['catalog']['search_image_height']);
		}
		if ($request->has('catalog_search_addtocart')) {
			$view->set('catalog_search_addtocart', $request->gethtml('catalog_search_addtocart'));
		} else {
			$view->set('catalog_search_addtocart', @$setting_info['catalog']['search_addtocart']);
		}
		if ($request->has('catalog_search_limit')) {
			$view->set('catalog_search_limit', $request->gethtml('catalog_search_limit'));
		} else {
			$view->set('catalog_search_limit', @$setting_info['catalog']['search_limit']);
		}
		if ($request->has('catalog_product_options_select')) {
			$view->set('catalog_product_options_select', $request->gethtml('catalog_product_options_select'));
		} else {
			$view->set('catalog_product_options_select', @$setting_info['catalog']['product_options_select']);
		}
		if ($request->has('catalog_category_options_select')) {
			$view->set('catalog_category_options_select', $request->gethtml('catalog_category_options_select'));
		} else {
			$view->set('catalog_category_options_select', @$setting_info['catalog']['category_options_select']);
		}
		if ($request->has('catalog_search_options_select')) {
			$view->set('catalog_search_options_select', $request->gethtml('catalog_search_options_select'));
		} else {
			$view->set('catalog_search_options_select', @$setting_info['catalog']['search_options_select']);
		}
		//End of New Block
		if ($request->has('global_config_country_id')) {
			$view->set('global_config_country_id', $request->gethtml('global_config_country_id'));
		} else {
			$view->set('global_config_country_id', @$setting_info['global']['config_country_id']);
		}

		$view->set('countries', $database->cache('country', "select country_id, name from country order by name"));

		if ($request->has('global_config_zone_id')) {
			$view->set('global_config_zone_id', $request->gethtml('global_config_zone_id'));
		} else {
			$view->set('global_config_zone_id', @$setting_info['global']['config_zone_id']);
		}

		$view->set('zones', $database->cache('zone', "select * from zone order by country_id, name"));

		if ($request->has('catalog_config_language')) {
			$view->set('catalog_config_language', $request->gethtml('catalog_config_language'));
		} else {
			$view->set('catalog_config_language', @$setting_info['catalog']['config_language']);
		}
        
        if ($request->has('admin_config_language')) {
            $view->set('admin_config_language', $request->gethtml('admin_config_language'));
        } else {
            $view->set('admin_config_language', @$setting_info['admin']['config_language']);
        }

		$view->set('languages', $database->cache('language', "select * from language order by sort_order"));

		if ($request->has('global_config_currency')) {
			$view->set('global_config_currency', $request->gethtml('global_config_currency'));
		} else {
			$view->set('global_config_currency', @$setting_info['global']['config_currency']);
		}

		$view->set('currencies', $database->cache('currency', "select * from currency"));

		if ($request->has('global_config_weight_class_id')) {
			$view->set('global_config_weight_class_id', $request->gethtml('global_config_weight_class_id'));
		} else {
			$view->set('global_config_weight_class_id', @$setting_info['global']['config_weight_class_id']);
		}

		$view->set('weight_classes', $database->cache('weight_class-' . (int)$language->getId(), "select weight_class_id, title from weight_class where language_id = '" . (int)$language->getId() . "'"));

		if ($request->has('global_config_order_status_id')) {
			$view->set('global_config_order_status_id', $request->gethtml('global_config_order_status_id'));
		} else {
			$view->set('global_config_order_status_id', @$setting_info['global']['config_order_status_id']);
		}

		$view->set('order_statuses', $database->cache('order_status-' . (int)$language->getId(), "select order_status_id, name from order_status where language_id = '" . (int)$language->getId() . "' order by name"));
 
		if ($request->has('catalog_config_download')) {
			$view->set('catalog_config_download', $request->gethtml('catalog_config_download'));
		} else {
			$view->set('catalog_config_download', @$setting_info['catalog']['config_download']);
		}

		if ($request->has('catalog_config_download_status')) {
			$view->set('catalog_config_download_status', $request->gethtml('catalog_config_download_status'));
		} else {
			$view->set('catalog_config_download_status', @$setting_info['catalog']['config_download_status']);
		}

		$template->set('content', $view->fetch('content/setting.tpl'));

		$template->set($module->fetch());

		$response->set($template->fetch('layout.tpl'));
	}
	function checkFiles($style) {
		$request	=& $this->locator->get('request');
		$database	=& $this->locator->get('database');
		$colors_data = array();
		$files = glob(DIR_CATALOG_STYLES.$style.D_S.'colors'.D_S.'*.*');
		if (!$files) { return; }
		foreach ($files as $file) {
			$pattern='/\.('.implode('|',$this->types).')$/';
			if (preg_match($pattern,$file)) {
				$filename = basename($file);
				$colors_data[] = array(
					'colorcss'   => $filename
				);	
			}
		}
		return $colors_data;
	}
	function validate() {
		$request  =& $this->locator->get('request');
		$language =& $this->locator->get('language');
		$module   =& $this->locator->get('module');
		$user     =& $this->locator->get('user');
        $validate =& $this->locator->get('validate');
			
		if (!$user->hasPermission('modify', 'setting')) {
			$this->error['message'] = $language->get('error_permission');
		}
		
        if (!$validate->strlen($request->gethtml('global_config_store', 'post'),1,32)) {
			$this->error['store'] = $language->get('error_store');
		}

        if (!$validate->strlen($request->gethtml('global_config_owner', 'post'),1,32)) {
			$this->error['owner'] = $language->get('error_owner');
		}

        if (!$validate->strlen($request->gethtml('global_config_address', 'post'),1,128)) {
			$this->error['address'] = $language->get('error_address');
		}

        if (!$validate->strlen($request->gethtml('global_config_telephone', 'post'),5,32)) {
			$this->error['telephone'] = $language->get('error_telephone');
		}
		if (!$validate->strlen($request->gethtml('catalog_config_colors','post'),6,32)){
			$this->error['color'] = $language->get('error_color');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	function getColors(){
		$request	=& $this->locator->get('request');
		$response =& $this->locator->get('response');
		
		$style = $request->gethtml('style');
		$results = $this->checkFiles($style);
		if($results){
			$output = '<select name="catalog_config_colors">';
			foreach($results as $result){
				$output .= '<option value="'. $result['colorcss'].'">';
				$output .= $result['colorcss']. '</option>';
			}
			$output .= '</select>';
		} else {
			$output = '<select name="catalog_config_colors"></select>';
		}
		$response->set($output);
	}
	function zone() {
		$database =& $this->locator->get('database');
		$language =& $this->locator->get('language');
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		
		$output = '<select name="global_config_zone_id">';

		$results = $database->cache('zone-' . (int)$request->gethtml('country_id'), "select zone_id, name from zone where country_id = '" . (int)$request->gethtml('country_id') . "' order by name");

		foreach ($results as $result) {
			$output .= '<option value="' . $result['zone_id'] . '"';

			if ($request->gethtml('zone_id') == $result['zone_id']) {
				$output .= ' SELECTED';
			}

			$output .= '>' . $result['name'] . '</option>';
		}

		if (!$results) {
			$output .= '<option value="0">' . $language->get('text_none') . '</option>';
		}

		$output .= '</select>';

		$response->set($output);
	}
}
?>