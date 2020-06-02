<?php
class ControllerReview extends Controller {
	var $error = array();

	function index() {
		$response =& $this->locator->get('response');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template'); 
		$module   =& $this->locator->get('module');

		$language->load('controller/review.php');

		$template->set('title', $language->get('heading_title'));

		$template->set('content', $this->getList());

		$template->set($module->fetch());

		$response->set($template->fetch('layout.tpl'));
	} 

	function insert() {
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$database =& $this->locator->get('database');
		$url      =& $this->locator->get('url');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$session  =& $this->locator->get('session');
		$module   =& $this->locator->get('module');

		$language->load('controller/review.php');

		$template->set('title', $language->get('heading_title'));

		if ($request->isPost() && $request->has('author', 'post') && $this->validateForm()) {
			$sql = "insert into review set author = '?', product_id = '?', text = '?', rating = '?', status = '?', date_added = now()";
			$database->query($database->parse($sql, $request->gethtml('author', 'post'), $request->gethtml('product_id', 'post'), strip_tags($request->gethtml('text', 'post')), $request->gethtml('rating', 'post'), $request->gethtml('status', 'post')));
			
			$session->set('message', $language->get('text_message'));

			$response->redirect($url->ssl('review'));
		}

		$template->set('content', $this->getForm());

		$template->set($module->fetch());

		$response->set($template->fetch('layout.tpl'));
	}

	function update() {
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$database =& $this->locator->get('database');
		$url      =& $this->locator->get('url');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$session  =& $this->locator->get('session');
		$module   =& $this->locator->get('module');

		$language->load('controller/review.php');

		$template->set('title', $language->get('heading_title'));

		if ($request->isPost() && $request->has('author', 'post') && $this->validateForm()) {
			$sql = "update review set author = '?', product_id = '?', text = '?', rating = '?', status = '?', date_added = now() where review_id = '?'";
			$database->query($database->parse($sql, $request->gethtml('author', 'post'), $request->gethtml('product_id', 'post'), strip_tags($request->gethtml('text', 'post')), $request->gethtml('rating', 'post'), $request->gethtml('status', 'post'), $request->gethtml('review_id')));
			
			$session->set('message', $language->get('text_message'));

			$response->redirect($url->ssl('review'));
		}

		$template->set('content', $this->getForm());

		$template->set($module->fetch());

		$response->set($template->fetch('layout.tpl'));
	}

	function delete() { 
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$database =& $this->locator->get('database');
		$url      =& $this->locator->get('url');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$session  =& $this->locator->get('session');
		$module   =& $this->locator->get('module');
 
		$language->load('controller/review.php');

		$template->set('title', $language->get('heading_title'));

		if (($request->gethtml('review_id')) && ($this->validateDelete())) {
			$database->query("delete from review where review_id = '" . (int)$request->gethtml('review_id') . "'");

			$session->set('message', $language->get('text_message'));

			$response->redirect($url->ssl('review'));
		}

		$template->set('content', $this->getList());

		$template->set($module->fetch());

		$response->set($template->fetch('layout.tpl'));
	}

	function getList() {
		$request  =& $this->locator->get('request');
		$database =& $this->locator->get('database');
		$config   =& $this->locator->get('config');
		$url      =& $this->locator->get('url');
		$language =& $this->locator->get('language');
		$session  =& $this->locator->get('session');

		$cols = array();

		$cols[] = array(
			'name'  => $language->get('column_product'),
			'sort'  => 'pd.name',
			'align' => 'left'
		);

		$cols[] = array(
			'name'  => $language->get('column_author'),
			'sort'  => 'r.author',
			'align' => 'left'
		);

		$cols[] = array(
			'name'  => $language->get('column_rating'),
			'sort'  => 'r.rating',
			'align' => 'right'
		);

		$cols[] = array(
			'name'  => $language->get('column_status'),
			'sort'  => 'r.status',
			'align' => 'center'
		);

    	$cols[] = array(
      		'name'  => $language->get('column_action'),
      		'align' => 'right'
    	);
		
		if (!$session->get('review.search')) {
			$sql = "select r.review_id, pd.name, r.author, r.rating, r.status from review r left join product_description pd on (r.product_id = pd.product_id) where pd.language_id = '" . (int)$language->getId() . "'";
		} else {
			$sql = "select r.review_id, pd.name, r.author, r.rating, r.status from review r left join product_description pd on (r.product_id = pd.product_id) where pd.language_id = '" . (int)$language->getId() . "' and r.name like '?'";
		}

		$sort = array(
			'pd.name',
			'r.author',
			'r.rating',
			'r.status'
		);

		if (in_array($session->get('review.sort'), $sort)) {
			$sql .= " order by " . $session->get('review.sort') . " " . (($session->get('review.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by pd.name asc";
		}

		$results = $database->getRows($database->splitQuery($database->parse($sql, '%' . $session->get('review.search') . '%'), $session->get('review.page'), $config->get('config_max_rows')));

		$rows = array();

		foreach ($results as $result) {
			$cell = array();

			$cell[] = array(
				'value' => $result['name'],
				'align' => 'left'
			);

			$cell[] = array(
				'value' => $result['author'],
				'align' => 'left'
			);

			$cell[] = array(
				'value' => $result['rating'],
				'align' => 'right'
			);

			$cell[] = array(
				'icon'  => ($result['status'] ? 'enabled.png' : 'disabled.png'),
				'align' => 'center'
			);
			
			$action = array();
      		
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $language->get('button_update'),
				'href' => $url->ssl('review', 'update', array('review_id' => $result['review_id']))
      		);
						
			$action[] = array(
        		'icon' => 'delete.png',
				'text' => $language->get('button_delete'),
				'href' => $url->ssl('review', 'delete', array('review_id' => $result['review_id']))
      		);

      		$cell[] = array(
        		'action' => $action,
        		'align'  => 'right'
      		);
			
			$rows[] = array('cell' => $cell);
		}

		$view = $this->locator->create('template');

		$view->set('heading_title', $language->get('heading_title'));
		$view->set('heading_description', $language->get('heading_description'));

		$view->set('text_results', $language->get('text_results', $database->getFrom(), $database->getTo(), $database->getTotal()));

		$view->set('entry_page', $language->get('entry_page'));
		$view->set('entry_search', $language->get('entry_search'));
 
		$view->set('button_list', $language->get('button_list'));
		$view->set('button_insert', $language->get('button_insert'));
		$view->set('button_update', $language->get('button_update'));
		$view->set('button_delete', $language->get('button_delete'));
		$view->set('button_save', $language->get('button_save'));
		$view->set('button_cancel', $language->get('button_cancel'));

		$view->set('error', @$this->error['message']);

		$view->set('message', $session->get('message'));
		
		$session->delete('message');
		
		$view->set('action', $url->ssl('review', 'page'));
 
		$view->set('search', $session->get('review.search'));
		$view->set('sort', $session->get('review.sort'));
		$view->set('order', $session->get('review.order'));
		$view->set('page', $session->get('review.page'));

		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('list', $url->ssl('review'));

		$view->set('insert', $url->ssl('review', 'insert'));

		$page_data = array();

		for ($i = 1; $i <= $database->getPages(); $i++) {
			$page_data[] = array(
				'text'  => $language->get('text_pages', $i, $database->getPages()),
				'value' => $i
			);
		}

		$view->set('pages', $page_data);

		return $view->fetch('content/list.tpl');
	}

	function getForm() {
		$request  =& $this->locator->get('request');
		$database =& $this->locator->get('database');
		$url      =& $this->locator->get('url');
		$language =& $this->locator->get('language');

		$view = $this->locator->create('template');

		$view->set('heading_title', $language->get('heading_title'));
		$view->set('heading_description', $language->get('heading_description'));

		$view->set('text_enabled', $language->get('text_enabled'));
		$view->set('text_disabled', $language->get('text_disabled'));

		$view->set('entry_product', $language->get('entry_product'));
		$view->set('entry_author', $language->get('entry_author'));
		$view->set('entry_rating', $language->get('entry_rating'));
		$view->set('entry_status', $language->get('entry_status'));
		$view->set('entry_text', $language->get('entry_text'));
		$view->set('entry_good', $language->get('entry_good'));
		$view->set('entry_bad', $language->get('entry_bad'));

		$view->set('button_list', $language->get('button_list'));
		$view->set('button_insert', $language->get('button_insert'));
		$view->set('button_update', $language->get('button_update'));
		$view->set('button_delete', $language->get('button_delete'));
		$view->set('button_save', $language->get('button_save'));
		$view->set('button_cancel', $language->get('button_cancel'));

		$view->set('tab_general', $language->get('tab_general'));

		$view->set('error', @$this->error['message']);
		$view->set('error_author', @$this->error['author']);
		$view->set('error_text', @$this->error['text']);

		$view->set('action', $url->ssl('review', $request->gethtml('action'), array('review_id' => $request->gethtml('review_id'))));

		$view->set('list', $url->ssl('review'));
 
		$view->set('insert', $url->ssl('review', 'insert'));

		if ($request->gethtml('review_id')) {
			$view->set('update', $url->ssl('review', 'update', array('review_id' => $request->gethtml('review_id'))));
			$view->set('delete', $url->ssl('review', 'delete', array('review_id' => $request->gethtml('review_id'))));
		}

		$view->set('cancel', $url->ssl('review'));

		if (($request->gethtml('review_id')) && (!$request->isPost())) {
			$review_info = $database->getRow("select distinct * from review where review_id = '" . (int)$request->gethtml('review_id') . "'");
		}

		if ($request->has('product_id', 'post')) {
			$view->set('product_id', $request->gethtml('product_id', 'post'));
		} else {
			$view->set('product_id', @$review_info['product_id']);
		}

		$view->set('products', $database->cache('product', "select product_id, name from product_description where language_id = '" . (int)$language->getId() . "'"));

		if ($request->has('author', 'post')) {
			$view->set('author', $request->gethtml('author', 'post'));
		} else {
			$view->set('author', @$review_info['author']);
		}

		if ($request->has('text', 'post')) {
			$view->set('text', $request->gethtml('text', 'post'));
		} else {
			$view->set('text', @$review_info['text']);
		}

		if ($request->has('rating', 'post')) {
			$view->set('rating', $request->gethtml('rating', 'post'));
		} else {
			$view->set('rating', @$review_info['rating']);
		}

		if ($request->has('status', 'post')) {
			$view->set('status', $request->gethtml('status', 'post'));
		} else {
			$view->set('status', @$review_info['status']);
		}

		return $view->fetch('content/review.tpl');
	}

	function validateForm() {
		$request  =& $this->locator->get('request');
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');
        $validate =& $this->locator->get('validate');

		if (!$user->hasPermission('modify', 'review')) {
			$this->error['message'] = $language->get('error_permission');
		}

        if (!$validate->strlen($request->gethtml('author', 'post'),1,64)) {
			$this->error['author'] = $language->get('error_author');
		}

        if (!$validate->strlen($request->gethtml('text', 'post'),1,1000)) {
			$this->error['text'] = $language->get('error_text');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function validateDelete() {
		$user     =& $this->locator->get('user');
		$language =& $this->locator->get('language');

		if (!$user->hasPermission('modify', 'review')) {
			$this->error['message'] = $language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function page() {
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$url      =& $this->locator->get('url');
		$session  =& $this->locator->get('session');

		if ($request->has('search', 'post')) {
			$session->set('review.search', $request->gethtml('search', 'post'));
		}

		if (($request->has('page', 'post')) || ($request->has('search', 'post'))) {
			$session->set('review.page', $request->gethtml('page', 'post'));
		}

		if ($request->has('sort', 'post')) {
			$session->set('review.order', (($session->get('review.sort') == $request->gethtml('sort', 'post')) && ($session->get('review.order') == 'asc')) ? 'desc' : 'asc');
		}

		if ($request->has('sort', 'post')) {
			$session->set('review.sort', $request->gethtml('sort', 'post'));
		}

		$response->redirect($url->ssl('review'));
	}	
}
?>