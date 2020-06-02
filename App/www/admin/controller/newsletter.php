<?php 
class ControllerNewsletter extends Controller {
	var $error = array();
	
	function index() {
		$response =& $this->locator->get('response');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template'); 
		$module   =& $this->locator->get('module');

		$language->load('controller/newsletter.php');

		$template->set('title', $language->get('heading_title'));

		$template->set('content', $this->getList());

		$template->set($module->fetch());

		$response->set($template->fetch('layout.tpl'));
	}
	
	function insert() {
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$database =& $this->locator->get('database');
		$config   =& $this->locator->get('config');
		$url      =& $this->locator->get('url');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$session  =& $this->locator->get('session');
		$module   =& $this->locator->get('module');
		$mail     =& $this->locator->get('mail');

		$language->load('controller/newsletter.php');

		$template->set('title', $language->get('heading_title'));

		if ($request->isPost() && $request->has('subject', 'post') && $this->validateForm()) {
			$sql = "insert into newsletter set subject = '?', content = '?', date_added = now(), date_sent = now()";
			$database->query($database->parse($sql, $request->get('subject', 'post'), $request->get('content',  'post')));

			if ($request->get('send', 'post')) {
				$email = array();
			
				$results = $database->getRows("select email from customer where newsletter = '1'");
					
				foreach ($results as $result) {
					$email[] = $result['email'];
				}
				
				if ($email) {
					$mail->setTo($email);
					$mail->setFrom($config->get('config_email'));
	    			$mail->setSender($config->get('config_store'));
	    			$mail->setSubject($request->get('subject', 'post'));
					$mail->setHtml($request->get('content', 'post'));	    	
	    			$mail->send();
				}
			}
			
			$session->set('message', $language->get('text_message'));
			
			$response->redirect($url->ssl('newsletter'));
		}

		$template->set('content', $this->getForm());

		$template->set($module->fetch());

		$response->set($template->fetch('layout.tpl'));
	}

	function update() {
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$database =& $this->locator->get('database');
		$config   =& $this->locator->get('config');
		$url      =& $this->locator->get('url');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$module   =& $this->locator->get('module');
		$session  =& $this->locator->get('session');
		$mail     =& $this->locator->get('mail');

		$language->load('controller/newsletter.php');

		$template->set('title', $language->get('heading_title'));

		if ($request->isPost() && $request->has('subject', 'post') && $this->validateForm()) {
			$sql = "update newsletter set subject = '?', content = '?' where newsletter_id = '?'";
			$database->query($database->parse($sql, $request->get('subject', 'post'), $request->get('content', 'post'), $request->gethtml('newsletter_id')));

			if ($request->get('send', 'post')) {
				$database->query("update newsletter set date_sent = now() where newsletter_id = '" . (int)$request->gethtml('newsletter_id') . "'");
			
				$email = array();
			
				$results = $database->getRows("select email from customer where newsletter = '1'");
			
				foreach ($results as $result) {
					$email[] = $result['email'];
				}
				
				if ($email) {
					$mail->setTo($email);
					$mail->setFrom($config->get('config_email'));
	    			$mail->setSender($config->get('config_store'));
	    			$mail->setSubject($request->get('subject', 'post'));
					$mail->setHtml($request->get('content', 'post'));
	    			$mail->send();
				}
			}
			
			$session->set('message', $language->get('text_message'));
			
			$response->redirect($url->ssl('newsletter'));
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

		$language->load('controller/newsletter.php');

		$template->set('title', $language->get('heading_title'));

		if (($request->gethtml('newsletter_id')) && ($this->validateDelete())) {
			$database->query("delete from newsletter where newsletter_id = '" . (int)$request->gethtml('newsletter_id') . "'");
			
			$session->set('message', $language->get('text_message'));

			$response->redirect($url->ssl('newsletter'));
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
			'name'  => $language->get('column_subject'),
			'sort'  => 'subject',
			'align' => 'left'
		);

		$cols[] = array(
			'name'  => $language->get('column_date_added'),
			'sort'  => 'date_added',
			'align' => 'left'
		);
		
		$cols[] = array(
			'name'  => $language->get('column_date_sent'),
			'sort'  => 'date_sent',
			'align' => 'left'
		);

    	$cols[] = array(
      		'name'  => $language->get('column_action'),
      		'align' => 'right'
    	);
						
		if (!$session->get('newsletter.search')) {
			$sql = "select newsletter_id, subject, date_added, date_sent from newsletter";
		} else {
			$sql = "select newsletter_id, subject, date_added, date_sent from newsletter where subject like '?'";
		}

		$sort = array(
			'subject',
			'date_added',
			'date_sent'
		);

		if (in_array($session->get('newsletter.sort'), $sort)) {
			$sql .= " order by " . $session->get('newsletter.sort') . " " . (($session->get('newsletter.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by date_added asc";
		}

		$results = $database->getRows($database->splitQuery($database->parse($sql, '%' . $session->get('newsletter.search') . '%'), $session->get('newsletter.page'), $config->get('config_max_rows')));

		$rows = array();

		foreach ($results as $result) {
			$cell = array();

			$cell[] = array(
				'value' => $result['subject'],
				'align' => 'left'
			);

			$cell[] = array(
				'value' => $language->formatDate($language->get('date_format_short'), strtotime($result['date_added'])),
				'align' => 'left'
			);

			$cell[] = array(
				'value' => $language->formatDate($language->get('date_format_short'), strtotime($result['date_sent'])),
				'align' => 'left'
			);

			$action = array();
      		
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $language->get('button_update'),
				'href' => $url->ssl('newsletter', 'update', array('newsletter_id' => $result['newsletter_id']))
      		);
						
			$action[] = array(
        		'icon' => 'delete.png',
				'text' => $language->get('button_delete'),
				'href' => $url->ssl('newsletter', 'delete', array('newsletter_id' => $result['newsletter_id']))
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
		
		$view->set('action', $url->ssl('newsletter', 'page'));
 
		$view->set('search', $session->get('newsletter.search'));
		$view->set('sort', $session->get('newsletter.sort'));
		$view->set('order', $session->get('newsletter.order'));
		$view->set('page', $session->get('newsletter.page'));

		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('list', $url->ssl('newsletter'));

		$view->set('insert', $url->ssl('newsletter', 'insert'));

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
		
		$view->set('text_yes', $language->get('text_yes'));
		$view->set('text_no', $language->get('text_no'));
				
		$view->set('entry_subject', $language->get('entry_subject'));
		$view->set('entry_content', $language->get('entry_content'));
		$view->set('entry_send', $language->get('entry_send'));
		
		$view->set('button_list', $language->get('button_list'));
		$view->set('button_insert', $language->get('button_insert'));
		$view->set('button_update', $language->get('button_update'));
		$view->set('button_delete', $language->get('button_delete'));
		$view->set('button_save', $language->get('button_save'));
		$view->set('button_cancel', $language->get('button_cancel'));
		
		$view->set('error', @$this->error['message']);
		$view->set('error_subject', @$this->error['subject']);
		$view->set('error_content', @$this->error['content']);
		
		$view->set('action', $url->ssl('newsletter', $request->gethtml('action'), array('newsletter_id' => $request->gethtml('newsletter_id'))));
		
		$view->set('list', $url->ssl('newsletter'));

		$view->set('insert', $url->ssl('newsletter', 'insert'));

		if ($request->gethtml('newsletter_id')) {
			$view->set('update', $url->ssl('newsletter', 'update', array('newsletter_id' => $request->gethtml('newsletter_id'))));
			$view->set('delete', $url->ssl('newsletter', 'delete', array('newsletter_id' => $request->gethtml('newsletter_id'))));
		}

		$view->set('cancel', $url->ssl('newsletter'));

		if (($request->gethtml('newsletter_id')) && (!$request->isPost())) {
			$newsletter_info = $database->getRow("select distinct * from newsletter where newsletter_id = '" . (int)$request->gethtml('newsletter_id') . "'");
		}
										
		if ($request->has('subject', 'post')) {
			$view->set('subject', $request->get('subject', 'post'));
		} else {
			$view->set('subject', @$newsletter_info['subject']);
		}
		 
		if ($request->has('content', 'post')) {
			$view->set('content', $request->get('content', 'post'));
		} else {
			$view->set('content', @$newsletter_info['content']);
		}
		
		if ($request->has('send', 'post')) {
			$view->set('send', $request->gethtml('send', 'post'));
		} else {
			$view->set('send', @$newsletter_info['send']);
		}
						
		return $view->fetch('content/newsletter.tpl');
	}
	
	function validateForm() {
		$request  =& $this->locator->get('request');
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');	
		
		if (!$user->hasPermission('modify', 'newsletter')) {
			$this->error['message'] = $language->get('error_permission');
		}
				
		if (!$request->get('subject', 'post')) {
			$this->error['subject'] = $language->get('error_subject');
		}
		
		if (!$request->get('content', 'post')) {
			$this->error['content'] = $language->get('error_content');
		}
						
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}	
	
	function validateDelete() {
		$request  =& $this->locator->get('request');
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');

		if (!$user->hasPermission('modify', 'newsletter')) {
			$this->error['message'] = $language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}	
}
?>