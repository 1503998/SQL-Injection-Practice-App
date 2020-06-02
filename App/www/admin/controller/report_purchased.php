<?php
class ControllerReportPurchased extends Controller {
	function index() {  
		$config   =& $this->locator->get('config');
		$currency =& $this->locator->get('currency');
		$database =& $this->locator->get('database');
		$language =& $this->locator->get('language');
		$module   =& $this->locator->get('module');
		$response =& $this->locator->get('response');
		$template =& $this->locator->get('template');
		$url      =& $this->locator->get('url');
 
		$language->load('controller/report_purchased.php');

		$template->set('title', $language->get('heading_title'));

		$view = $this->locator->create('template');

		$view->set('heading_title', $language->get('heading_title'));
		$view->set('heading_description', $language->get('heading_description'));
		
		$view->set('column_name', $language->get('column_name'));
		$view->set('column_model_number', $language->get('column_model_number'));
		$view->set('column_quantity', $language->get('column_quantity'));
		$view->set('column_total', $language->get('column_total'));
		
		$product_data = array();
		
		$results = $database->getRows("select name, model_number, sum(quantity) as quantity, sum(total) as total from order_product group by model_number order by total desc");
		
		foreach ($results as $result) {
			$product_data[] = array(
				'name'         => $result['name'],
				'model_number' => $result['model_number'],
				'quantity'     => $result['quantity'],
				'total'        => $currency->format($result['total'], $config->get('config_currency'))
			);
		}
		
		$view->set('products', $product_data);

		$template->set('content', $view->fetch('content/report_purchased.tpl'));

		$template->set($module->fetch());

		$response->set($template->fetch('layout.tpl'));
	}
}
?>