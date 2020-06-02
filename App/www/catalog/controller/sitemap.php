<?php  // Sitemap AlegroCart
class ControllerSitemap extends Controller {
	function index() {
		$language =& $this->locator->get('language');
		$module   =& $this->locator->get('module');
		$response =& $this->locator->get('response');
		$template =& $this->locator->get('template');
		$url      =& $this->locator->get('url');
		$this->modelSitemap =& $this->model->get('model_sitemap');
		$head_def =& $this->locator->get('HeaderDefinition');  // New Header
		
    	$language->load('controller/sitemap.php');
 
    	$template->set('title', $language->get('heading_title'));

    	$view = $this->locator->create('template');

    	$view->set('heading_title', $language->get('heading_title'));

    	$view->set('text_account', $language->get('text_account'));
    	$view->set('text_edit', $language->get('text_edit'));
    	$view->set('text_password', $language->get('text_password'));
    	$view->set('text_address', $language->get('text_address'));
    	$view->set('text_history', $language->get('text_history'));
    	$view->set('text_download', $language->get('text_download'));
    	$view->set('text_cart', $language->get('text_cart'));
    	$view->set('text_checkout', $language->get('text_checkout'));
    	$view->set('text_search', $language->get('text_search'));
    	$view->set('text_information', $language->get('text_information'));
    	$view->set('text_contact', $language->get('text_contact'));

    	$category_data = array();
		$results = $this->modelSitemap->get_categories();
    	foreach ($results as $result) {
      		$category_data[] = array(
        		'category_id' => $result['category_id'],
        		'name'        => $result['name'],
        		'href'        => $url->href('category', FALSE, array('path' => $result['path'])),
        		'level'       => count(explode('_', $result['path'])) - 1
      		);
    	}

    	$view->set('categories', $category_data);
    	$view->set('account', $url->ssl('account'));
    	//$view->set('edit', $url->ssl('account_edit'));
    	$view->set('password', $url->ssl('account_password'));
    	//$view->set('address', $url->ssl('account_address'));
    	//$view->set('history', $url->ssl('account_history'));
    	//$view->set('download', $url->ssl('account_download'));
    	$view->set('cart', $url->href('cart'));
    	$view->set('checkout', $url->ssl('checkout_shipping'));
    	$view->set('search', $url->href('search'));
    	$view->set('contact', $url->href('contact'));

    	$information_data = array();
		$results = $this->modelSitemap->get_information();
    	foreach ($results as $result) {
      		$information_data[] = array(
        		'title' => $result['title'],
        		'href'  => $url->href('information', FALSE, array('information_id' => $result['information_id']))
      		);
    	}

    	$view->set('informations', $information_data);
		$view->set('head_def',$head_def);    // New Header
		$template->set('head_def',$head_def);    // New Header
		$template->set('content', $view->fetch('content/sitemap.tpl'));
		$template->set($module->load('popular')); // New Load Modules
		$template->set($module->load('specials'));    
		$template->set($module->fetch());
	
    	$response->set($template->fetch('layout.tpl'));	
  	}
}
?>
