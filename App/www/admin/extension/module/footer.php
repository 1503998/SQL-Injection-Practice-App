<?php
class ModuleFooter extends Controller {  
	function fetch() {
		$config   =& $this->locator->get('config');
		$language =& $this->locator->get('language');
		
		if ($config->get('footer_status')) {
			$language->load('extension/module/footer.php');
	
			$view = $this->locator->create('template');

    		return $view->fetch('module/footer.tpl');
  		}
	}
}
?>