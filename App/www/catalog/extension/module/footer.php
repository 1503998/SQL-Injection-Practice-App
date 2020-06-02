<?php // AlegroCart 
class ModuleFooter extends Controller {
	function fetch() {
		$config   =& $this->locator->get('config');
		$language =& $this->locator->get('language');	
		$session  =& $this->locator->get('session');
		$request  =& $this->locator->get('request');
		
		if ($config->get('footer_status')) {	
			$language->load('extension/module/footer.php');

			$view = $this->locator->create('template');
			$view->set('w3c_status', currentpage($request->get('controller')));
			$view->set('store', $config->get('config_store'));
			$view->set('version', $config->get('version'));
			
			$view->set('text_powered_by', $language->get('text_powered_by'));

			return $view->fetch('module/footer.tpl');
		}
	}
}
// Check current controller
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
	case 'cart':
	  $w3c_status = true;
	  break;
	default:
	  $w3c_status = false;
	  break;
	}
	return $w3c_status;
}
?>