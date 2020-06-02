<?php 
class ControllerBackup extends Controller {
	var $error = array();
	
	function index() {		
		$database =& $this->locator->get('database');
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$url      =& $this->locator->get('url');
		$language =& $this->locator->get('language');
		$session  =& $this->locator->get('session');
		$template =& $this->locator->get('template');
		$module   =& $this->locator->get('module');
		$upload   =& $this->locator->get('upload');
 
		$language->load('controller/backup.php');

		$template->set('title', $language->get('heading_title'));
				
		if ($request->isPost() && $upload->has('database') && $this->validate() ) {
			$file = $upload->get('database');
				
			$database->import($file['tmp_name']);
				
			$session->set('message', $language->get('text_message'));

			$response->redirect($url->href('backup'));
		}
		
		$view = $this->locator->create('template');
		
		$view->set('heading_title', $language->get('heading_title'));
		$view->set('heading_description', $language->get('heading_description'));
		
		$view->set('text_backup', $language->get('text_backup'));
		
		$view->set('entry_restore', $language->get('entry_restore'));
		 
		$view->set('button_list', $language->get('button_list'));
		$view->set('button_insert', $language->get('button_insert'));
		$view->set('button_update', $language->get('button_update'));
		$view->set('button_delete', $language->get('button_delete'));
		$view->set('button_save', $language->get('button_save'));
		$view->set('button_cancel', $language->get('button_cancel'));
		
		$view->set('tab_general', $language->get('tab_general'));
		
		$view->set('error', @$this->error['message']);
		
		$view->set('action', $url->ssl('backup'));

		$view->set('message', $session->get('message'));
		
		$session->delete('message');
				
		$view->set('cancel', $url->ssl('backup'));
		
		$view->set('download', $url->ssl('backup', 'download'));
				
		$template->set('content', $view->fetch('content/backup.tpl'));

		$template->set($module->fetch());

		$response->set($template->fetch('layout.tpl'));
	}
	
	function download() {
		$response =& $this->locator->get('response');
		$database =& $this->locator->get('database');
		$url      =& $this->locator->get('url');
		$session  =& $this->locator->get('session');
		
		if ($this->validate()) {
			$response->setheader('Pragma', 'public');
			$response->setheader('Expires', '0');
			$response->setheader('Content-Description', 'File Transfer');
			$response->setheader('Content-Type', 'application/octet-stream');
			$response->setheader('Content-Disposition', 'attachment; filename=backup.sql');
			$response->setheader('Content-Transfer-Encoding', 'binary');
			
			$response->set($database->export());
			$response->output();
			die(); //no further processing is required
		} else {
			$response->redirect($url->href('error'));
		}
	}
		
	function validate() {
		$language =& $this->locator->get('language');
		$user     =& $this->locator->get('user');
			
		if (!$user->hasPermission('modify', 'backup')) {
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