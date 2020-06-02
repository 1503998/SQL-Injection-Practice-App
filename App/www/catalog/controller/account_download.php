<?php // Account Download AlegroCart
class ControllerAccountDownload extends Controller {
	function initialize() {//$this->initialize();  Required in each function 
		if(!isset($this->dataLink)){
			$this->dataLink = TRUE;
			$this->config   =& $this->locator->get('config');
			$this->customer =& $this->locator->get('customer');
			$this->download =& $this->locator->get('download');
			$this->head_def =& $this->locator->get('HeaderDefinition');  // New Header
			$this->language =& $this->locator->get('language');
			$this->module   =& $this->locator->get('module');
			$this->response =& $this->locator->get('response');
			$this->request  =& $this->locator->get('request');
			$this->session  =& $this->locator->get('session');
			$this->template =& $this->locator->get('template');
			$this->url      =& $this->locator->get('url');
			$this->modelAccountDownload =& $this->model->get('model_accountdownload');
		}
	}
	function index() {
		$this->initialize(); // Required 
		
		if (!$this->customer->isLogged()) {
			$this->session->set('redirect', $this->url->ssl('account_download'));
			$this->response->redirect($this->url->ssl('account_login'));
		}

        $this->session->set('account_download.page', $this->request->has('page')?(int)$request->get('page'):1); //pagination
		$this->language->load('controller/account_download.php');
		$this->template->set('title', $this->language->get('heading_title'));
		$view = $this->locator->create('template');
		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('head_def',$this->head_def);    // New Header
		$this->template->set('head_def',$this->head_def);    // New Header		
		
		$results = $this->modelAccountDownload->get_downloads($this->customer->getId());		
		if ($results) {
			$view->set('text_order', $this->language->get('text_order'));
			$view->set('text_date_added', $this->language->get('text_date_added'));
			$view->set('text_name', $this->language->get('text_name'));
			$view->set('text_remaining', $this->language->get('text_remaining'));
			$view->set('text_size', $this->language->get('text_size'));
			$view->set('text_download', $this->language->get('text_download'));
			$view->set('text_results', $this->modelAccountDownload->get_text_results());
			$view->set('entry_page', $this->language->get('entry_page'));
			$view->set('total_pages', $this->modelAccountDownload->get_pages());
			$view->set('first_page', $this->language->get('first_page'));
			$view->set('last_page', $this->language->get('last_page'));
			$view->set('previous' , $this->language->get('previous_page')); // New Pagination
			$view->set('next' , $this->language->get('next_page'));  // New Pagination  
			$view->set('button_continue', $this->language->get('button_continue'));
			
			$view->set('action', $this->url->href('account_download'));

			$download_data = array();
			foreach ($results as $result) {
				$size = filesize(DIR_DOWNLOAD . $result['filename']);
				$i = 0;
				$suffix = array('B','KB','MB','GB','TB','PB','EB','ZB','YB');
				while (($size / 1024) > 1) {
					$size = $size / 1024;
					$i++;
				}
				$download_data[] = array(
					'order_id'   => $result['order_id'],
					'date_added' => $this->language->formatDate($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'name'       => $result['name'],
					'remaining'  => $result['remaining'],
					'size'       => round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i],
					'href'       => $this->url->ssl('account_download', 'download', array('order_download_id' => $result['order_download_id']))
				);
			}

			$view->set('downloads', $download_data);
			$view->set('page', $this->session->get('account_download.page'));
			$view->set('pages', $this->modelAccountDownload->get_pagination());
			$view->set('continue', $this->url->ssl('account'));
			$this->template->set('content', $view->fetch('content/account_download.tpl'));
		} else {
			$view->set('text_error', $this->language->get('text_error'));
			$view->set('button_continue', $this->language->get('button_continue'));
			$view->set('continue', $this->url->ssl('account'));
			$this->template->set('content', $view->fetch('content/error.tpl'));
		}
		
		$this->template->set($this->module->fetch());
		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function download() {
		$this->initialize(); // Required 

		if (!$this->customer->isLogged()) {
			$this->session->set('redirect', $this->url->ssl('account_download'));

			$this->response->redirect($this->url->ssl('account_login'));
		}
		$download_info = $this->modelAccountDownload->get_download($this->customer->getId(),$this->request->gethtml('order_download_id'));	
		if ($download_info) {
			$this->download->setSource(DIR_DOWNLOAD . $this->download_info['filename']);
			$this->download->setFilename($download_info['mask']);
			$this->download->output();
			if (!$this->download->getError()) {
				$this->modelAccountDownload->update_download($this->request->gethtml('order_download_id'));
			}
		} else {
			$this->response->redirect($this->url->ssl('account_download'));
		}
	}
}
?>