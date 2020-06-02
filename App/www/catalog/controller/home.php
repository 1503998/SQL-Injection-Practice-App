<?php // Home AlegroCart
class ControllerHome extends Controller {
	function index() {
		$config   =& $this->locator->get('config');
		$customer =& $this->locator->get('customer');
		$language =& $this->locator->get('language');
		$module   =& $this->locator->get('module');
		$response =& $this->locator->get('response');
		$template =& $this->locator->get('template');
		$url      =& $this->locator->get('url');
		$request  =& $this->locator->get('request');
		$session  =& $this->locator->get('session');
		$this->modelCore = $this->model->get('model_core');
		$language->load('controller/home.php');

		$template->set('title', $language->get('title', $config->get('config_store')));

		$view = $this->locator->create('template');
		$view->set('action', HTTP_ADMIN);
		$view->set('heading_title', $language->get('heading_title', $config->get('config_store')));

		if ($customer->isLogged()) {
			$view->set('text_greeting', $language->get('text_logged', $customer->getFirstName()));
		} else {
			$view->set('text_greeting', $language->get('text_greeting', $url->ssl('account_login'), $url->ssl('account_create')));
		}
		$home_data = $this->modelCore->get_homepage();
		If($home_data['run_times'] != -1){
			if($home_data['run_times'] > 0){
				If($session->has('homepage')){
					$times = $session->get('homepage');
					If ($times < $home_data['run_times']){
						$run_homepage = TRUE;
						$session->set('homepage', $times+1);
					} else {
						$run_homepage = FALSE;
					}
				} else {
					$session->set('homepage', 1);
					$run_homepage = TRUE;
				}
			} else {
				$run_homepage = TRUE;
			}
			If($run_homepage == TRUE){
				$template->set($module->load('homepage'));
			}
		}	
		
 		$view->set('add_enable', currentpage($request->get('controller')));
		$view->set('ishome', TRUE);
		$template->set('content', $view->fetch('content/home.tpl'));
		$template->set($module->load('popular')); // New Load Modules
		$template->set($module->load('specials'));
		$template->set($module->load('review'));
		$template->set($module->load('featured'));		
		$template->set($module->load('latest'));
		$template->set($module->load('manufacturer'));

		$template->set($module->fetch());

		$response->set($template->fetch('layout.tpl'));
	}

	function currentpage($current_page){  
		switch($current_page){
			case '':
			case 'home':
			case 'information':
			case 'sitemap':
			case 'search':
			case 'contact':
			case 'category':
			case 'product':

			$add_enable = true;
			break;
		default:
			$add_enable = false;
			break;
		}
		return $add_enable;
	}
}
?>