<?php
class ControllerReportOnline extends Controller {
	function index() { 
		$database =& $this->locator->get('database');
		$language =& $this->locator->get('language');
		$response =& $this->locator->get('response');
		$template =& $this->locator->get('template');
		$module   =& $this->locator->get('module');
 
		$language->load('controller/report_online.php');

		$template->set('title', $language->get('heading_title'));

        $sql = "select `value`, `ip`, `time`, `url` from `session` where `expire` > '?'";
        $parsed = $database->parse($sql, time() - 86400);
        $results = $database->getRows($parsed);
		$results = $this->remove_duplicates($results, 'ip');
		$rows = array();

		foreach ($results as $result) {
			$value = array();
 
			$a = preg_split("/(\w+)\|/", $result['value'], - 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

			for ($i = 0; $i < count($a); $i = $i + 2) {
				$value[$a[$i]] = unserialize($a[$i + 1]);
			}

			if (isset($value['user_id'])) {
				$user_info = $database->getRow("select username from user where user_id = '" . (int)$value['user_id'] . "'");

				$name = $language->get('text_admin', $user_info['username']);
			} elseif (isset($value['customer_id'])) {
				$customer_info = $database->getRow("select concat(firstname,' ',lastname) as name from customer where customer_id = '" . (int)$value['customer_id'] . "'");

				$name = $customer_info['name'];
			} else {
				$name = $language->get('text_guest');
			}
			
			if (isset($value['cart'])) {
				$items = '';
				$keys = array_keys($value['cart']);
				$values = array_values($value['cart']);
				for ($i = 0; $i < count($keys); $i++) {
					$product = $database->getRow("select name from product p left join product_description pd on (p.product_id = pd.product_id) where p.product_id = '" .$keys[$i] . "' and pd.language_id = '" . (int)$language->getId() . "'"); 
					if (strlen($items) == 0) {
						$items .= '<hr style="margin:0px;padding:0px"/>';
					}
					$items .= $product['name'] . " x " . $values[$i] . "<br/>";
				}
			}

			$rows[] = array(
				'name'  => $name,
				'time'  => date('dS F Y h:i:s A', strtotime($result['time'])),
				'ip'    => $result['ip'],
				'url'   => $result['url'],
				'total' => (isset($value['cart']) ? array_sum($value['cart']).$items : 0)
			);
		}

		$view = $this->locator->create('template');

		$view->set('heading_title', $language->get('heading_title'));
		$view->set('heading_description', $language->get('heading_description'));

		$view->set('column_name', $language->get('column_name'));
		$view->set('column_time', $language->get('column_time'));
		$view->set('column_ip', $language->get('column_ip'));
		$view->set('column_url', $language->get('column_url'));
		$view->set('column_total', $language->get('column_total'));

		$view->set('rows', $rows);

		$template->set('content', $view->fetch('content/report_online.tpl'));

		$template->set($module->fetch());

		$response->set($template->fetch('layout.tpl'));
	}
	function remove_duplicates($array, $row_element) {   
		$new_array[0] = $array[0];
		foreach ($array as $current) {
			$add_flag = 1;
			foreach ($new_array as $tmp) {
				if ($current[$row_element]==$tmp[$row_element]) {
					$add_flag = 0; break;
				}
			}
			if ($add_flag) $new_array[] = $current;
		}
		return $new_array;
	} 
}

?>