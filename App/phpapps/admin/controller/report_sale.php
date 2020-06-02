<?php
class ControllerReportSale extends Controller {
	function index() { 
		$config   =& $this->locator->get('config');
		$currency =& $this->locator->get('currency');
		$database =& $this->locator->get('database');
		$language =& $this->locator->get('language');
		$module   =& $this->locator->get('module');
		$response =& $this->locator->get('response');
		$session  =& $this->locator->get('session');
		$template =& $this->locator->get('template');
		$url      =& $this->locator->get('url');
 
		$language->load('controller/report_sale.php');

		$template->set('title', $language->get('heading_title'));

		$cols = array();

		$cols[] = array(
			'name'  => $language->get('column_date'),
			'align' => 'left',
			'sort'  => 'date_added'
		);

		$cols[] = array(
			'name'  => $language->get('column_orders'),
			'align' => 'right',
			'sort'  => 'order_id'
		);

		$cols[] = array(
			'name'  => $language->get('column_amount'),
			'align' => 'right',
			'sort'  => 'total'
		);
		
		$sql = "select min(date_added) as date_from, max(date_added) as date_to, count(order_id) as orders, sum(total) as amount from `order`";

		if (($session->has('report_sale.date_from')) && ($session->has('report_sale.date_to'))) {
			$date_from = $session->get('report_sale.date_from');
			
			$from = date('Y-m-d', strtotime($date_from['year'] . '/' . $date_from['month'] . '/' . $date_from['day']));
			
			$date_to = $session->get('report_sale.date_to');
			
			$to = date('Y-m-d', strtotime($date_to['year'] . '/' . $date_to['month'] . '/' . $date_to['day']));
			
			$sql .= $database->parse(" where date_added between '?' and '?'", $from, $to);
		} else {
			$date = explode('/', date('d/m/Y', time()));

			$date_from = array(
				'day'   => $date[0],
				'month' => ($date[1] != '01') ? $date[1] - 1 : $date[1],
				'year'  => $date[2]
			);

			$date_to = array(
				'day'   => $date[0] + 1,
				'month' => $date[1],
				'year'  => $date[2]
			);

			$sql .= $database->parse(" where date_added between '?' and now()", date('Y-m-d', strtotime(implode('/', $date_from))));
		}

		if ($session->get('report_sale.order_status_id')) {
			$sql .= " and order_status_id = '" . (int)$session->get('report_sale.order_status_id') . "'";
		}

		$group = array(
			'year',
			'month',
			'week',
			'dayofweek'
		);

		if (in_array($session->get('report_sale.group'), $group)) {
			$sql .= " group by " . $session->get('report_sale.group') . "(date_added)";
		} else {
			$sql .= " group by week(date_added)";
			
			$session->set('report_sale.group', 'week');
		}

		$sort = array(
			'date_added',
			'order_id',
			'total'
		);

		if (in_array($session->get('report_sale.sort'), $sort)) {
			$sql .= " order by " . $session->get('report_sale.sort') . " " . (($session->get('report_sale.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by date_added asc";
		}

		$results = $database->getRows($database->splitQuery($sql, $session->get('report_sale.page'), $config->get('config_max_rows')));

		$rows = array();

		foreach ($results as $result) {
			$cell = array();
 
			$cell[] = array(
				'value' => $language->formatDate($language->get('date_format_short'), strtotime($result['date_from'])) . ' - ' . $language->formatDate($language->get('date_format_short'), strtotime($result['date_to'])),
				'align' => 'left'
			);

			$cell[] = array(
				'value' => $result['orders'],
				'align' => 'right'
			);

			$cell[] = array(
				'value' => $currency->format($result['amount'], $config->get('config_currency')),
				'align' => 'right'
			);

			$rows[] = array('cell' => $cell);
		}

		$view = $this->locator->create('template');

		$view->set('heading_title', $language->get('heading_title'));
		$view->set('heading_description', $language->get('heading_description'));

		$view->set('text_results', $language->get('text_results', $database->getFrom(), $database->getTo(), $database->getTotal()));

		$view->set('entry_status', $language->get('entry_status'));
		$view->set('entry_date', $language->get('entry_date'));
		$view->set('entry_page', $language->get('entry_page'));
		$view->set('entry_group', $language->get('entry_group'));

		$view->set('button_search', $language->get('button_search'));

		$view->set('sort', $session->get('report_sale.sort'));
		$view->set('order', $session->get('report_sale.order'));
		$view->set('page', $session->get('report_sale.page'));
		$view->set('group', $session->get('report_sale.group'));

		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('action', $url->ssl('report_sale', 'page'));

		$group_data = array();

		$group_data[] = array(
			'text'  => $language->get('text_year'),
			'value' => 'year',
		);

		$group_data[] = array(
			'text'  => $language->get('text_month'),
			'value' => 'month',
		);

		$group_data[] = array(
			'text'  => $language->get('text_week'),
			'value' => 'week',
		);

		$group_data[] = array(
			'text'  => $language->get('text_day'),
			'value' => 'dayofweek',
		);

		$view->set('groups', $group_data);

		$month_data = array();

		$month_data[] = array(
			'value' => '01',
			'text'  => $language->get('text_january')
		);
 
		$month_data[] = array(
			'value' => '02',
			'text'  => $language->get('text_february')
		);

		$month_data[] = array(
			'value' => '03',
			'text'  => $language->get('text_march')
		);

		$month_data[] = array(
			'value' => '04',
			'text'  => $language->get('text_april')
		);

		$month_data[] = array(
			'value' => '05',
			'text'  => $language->get('text_may')
		);

		$month_data[] = array(
			'value' => '06',
			'text'  => $language->get('text_june')
		);

		$month_data[] = array(
			'value' => '07',
			'text'  => $language->get('text_july')
		);

		$month_data[] = array(
			'value' => '08',
			'text'  => $language->get('text_august')
		);

		$month_data[] = array(
			'value' => '09',
			'text'  => $language->get('text_september')
		);

		$month_data[] = array(
			'value' => '10',
			'text'  => $language->get('text_october')
		);

		$month_data[] = array(
			'value' => '11',
			'text'  => $language->get('text_november')
		);

		$month_data[] = array(
			'value' => '12',
			'text'  => $language->get('text_december')
		);

		$view->set('months', $month_data);

		$view->set('date_from_day', $date_from['day']);
		$view->set('date_from_month', $date_from['month']);
		$view->set('date_from_year', $date_from['year']);

		$view->set('date_to_day', $date_to['day']);
		$view->set('date_to_month', $date_to['month']);
		$view->set('date_to_year', $date_to['year']);

		$view->set('order_status_id', $session->get('report_sale.order_status_id'));

		$order_status_data = array();

		$results = $database->cache('order_status-' . (int)$language->getId(), "select order_status_id, name from order_status where language_id = '" . (int)$language->getId() . "' order by name");

		$order_status_data[] = array(
			'text'  => $language->get('text_all_status'),
			'value' => '0'
		);

		foreach ($results as $result) {
			$order_status_data[] = array(
				'text'  => $result['name'],
				'value' => $result['order_status_id'],
				'href'  => $url->ssl('report_sale', FALSE, array('order_status_id' => $result['order_status_id']))
			);
		}

		$view->set('order_statuses', $order_status_data);

		$page_data = array();

		for ($i = 1; $i <= $database->getPages(); $i++) {
			$page_data[] = array(
				'text'  => $language->get('text_pages', $i, $database->getPages()),
				'value' => $i
			);
		}

		$view->set('pages', $page_data);

		$template->set('content', $view->fetch('content/report_sale.tpl'));

		$template->set($module->fetch());

		$response->set($template->fetch('layout.tpl'));
	}
	
	function page() {
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$url      =& $this->locator->get('url');
    	$session  =& $this->locator->get('session');
				
		if ($request->has('sort', 'post')) {
			$session->set('report_sale.sort', $request->gethtml('sort', 'post'));
		}
			
		if ($request->has('sort', 'post')) {
			$session->set('report_sale.order', (($session->get('report_sale.sort') == $request->gethtml('sort', 'post')) && ($session->get('report_sale.order') == 'asc') ? 'desc' : 'asc'));
		}
		
		if ($request->has('page', 'post')) {
			$session->set('report_sale.page', $request->gethtml('page', 'post'));
		}
		
		if ($request->has('group', 'post')) {
			$session->set('report_sale.group', $request->gethtml('group', 'post'));
		}
		
		if ($request->has('order_status_id', 'post')) {
			$session->set('report_sale.order_status_id', $request->gethtml('order_status_id', 'post'));
		}
		
		if ($request->has('date_from', 'post')) {
			$session->set('report_sale.date_from', $request->gethtml('date_from', 'post'));
		}
		
		if ($request->has('date_to', 'post')) {
			$session->set('report_sale.date_to', $request->gethtml('date_to', 'post'));
		}
		
		$response->redirect($url->ssl('report_sale'));
	}
}
?>