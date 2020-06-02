<?php
class ControllerReportViewed extends Controller {
	function index() {   
		$response =& $this->locator->get('response');
		$database =& $this->locator->get('database');
		$url      =& $this->locator->get('url');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$module   =& $this->locator->get('module');

		$language->load('controller/report_viewed.php');

		$template->set('title', $language->get('heading_title'));
 
		$view = $this->locator->create('template');

		$view->set('heading_title', $language->get('heading_title'));
		$view->set('heading_description', $language->get('heading_description'));
 		
		$view->set('column_name', $language->get('column_name'));
		$view->set('column_viewed', $language->get('column_viewed'));
		$view->set('column_percent', $language->get('column_percent'));
		
		$product_data = array();
		
		$results = $database->getRows("select * from product p left join product_description pd on (p.product_id = pd.product_id) where pd.language_id = '" . (int)$language->getId() . "' order by viewed desc");
		
		$total = 0;
		
		foreach ($results as $result) {
			$total += $result['viewed'];
		}
		
		foreach ($results as $result) {
			$percent=$total?round(($result['viewed'] / $total) * 100, 2):0;
			$product_data[] = array(
				'name'    => $result['name'],
				'viewed'  => $result['viewed'],
				'percent' => $percent.'%'
			);
		}
		
		$view->set('products', $product_data);
			
		$template->set('content', $view->fetch('content/report_viewed.tpl'));

		$template->set($module->fetch());

		$response->set($template->fetch('layout.tpl'));
	}
}
?>