<?php // Admin HomePage Entry AlegroCart
class ControllerHomepage extends Controller {
	var $error = array();
	var $types=array('swf','FXG','as','mxml','flv');
	function initialize() {//$this->initialize();  Required in each function 
		if(!isset($this->dataLink)){
			$this->dataLink = TRUE;
			$this->cache    	=& $this->locator->get('cache');
			$this->config   	=& $this->locator->get('config');
			$this->image   		=& $this->locator->get('image');
			$this->language 	=& $this->locator->get('language');
			$this->module   	=& $this->locator->get('module');
			$this->request 	 	=& $this->locator->get('request');
			$this->response	 	=& $this->locator->get('response');
			$this->session 	 	=& $this->locator->get('session');
			$this->template 	=& $this->locator->get('template');
			$this->upload   	=& $this->locator->get('upload');
			$this->url     		=& $this->locator->get('url');
			$this->user    	 	=& $this->locator->get('user');
			$this->validate 	=& $this->locator->get('validate');
			$this->modelAdminHomepage = $this->model->get('model_admin_homepage');
			$this->language->load('controller/generate_url_alias.php');
		}
	}
  	function index() {
		$this->initialize(); // Required 
			  
		$this->language->load('controller/homepage.php');

		$this->template->set('title', $this->language->get('heading_title'));
    	$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());
    	
		$this->response->set($this->template->fetch('layout.tpl'));
  	}
  	function insert() {
		$this->initialize(); // Required 

    	$this->language->load('controller/homepage.php');
    	$this->template->set('title', $this->language->get('heading_title'));

    	if (($this->request->isPost()) && ($this->validateForm())) {
			if($this->request->gethtml('status','post')){
				$this->modelAdminHomepage->delete_status();
			}
			$this->modelAdminHomepage->insert_status();
			$this->modelAdminHomepage->insert_description();
			$this->session->set('message', $this->language->get('text_message'));
			$this->response->redirect($this->url->ssl('homepage'));
		}
    	$this->template->set('content', $this->getForm());	
		$this->template->set($this->module->fetch());
    	$this->response->set($this->template->fetch('layout.tpl'));
	}
  	function update() {
		$this->initialize(); // Required 

    	$this->language->load('controller/homepage.php');
    	$this->template->set('title', $this->language->get('heading_title'));
    	if (($this->request->isPost()) && ($this->validateForm())) {
			if($this->request->gethtml('status', 'post')){
				$this->modelAdminHomepage->delete_status();
			}
			$this->modelAdminHomepage->update_status();
			$this->modelAdminHomepage->delete_description($this->request->gethtml('home_id'));
			$this->modelAdminHomepage->update_description();
			$this->session->set('message', $this->language->get('text_message'));
			$this->response->redirect($this->url->ssl('homepage'));
		}
    	$this->template->set('content', $this->getForm());	
		$this->template->set($this->module->fetch());
    	$this->response->set($this->template->fetch('layout.tpl'));
	}
  	function delete() {
		$this->initialize(); // Required 

    	$this->language->load('controller/homepage.php');
    	$this->template->set('title', $this->language->get('heading_title'));
    	if (($this->request->gethtml('home_id')) && ($this->validateDelete())) {
			$this->modelAdminHomepage->delete_homepage($this->request->gethtml('home_id'));
			$this->modelAdminHomepage->delete_description($this->request->gethtml('home_id'));
			$this->session->set('message', $this->language->get('text_message'));
			$this->response->redirect($this->url->ssl('homepage'));
		}
    	$this->template->set('content', $this->getList());	
		$this->template->set($this->module->fetch());
    	$this->response->set($this->template->fetch('layout.tpl'));
	}
  	function getList() {
		$this->initialize(); // Required 

		$cols = array();
    	$cols[] = array(
      		'name'  => $this->language->get('column_name'),
      		'sort'  => 'h.name',
      		'align' => 'left'
    	);
		$cols[] = array(     //new
             'name'  => $this->language->get('column_title'),
             'sort'  => 'hd.title',
             'align' => 'left'
        );
    	$cols[] = array(
      		'name'  => $this->language->get('column_status'),
      		'sort'  => 'h.status',
      		'align' => 'center'
    	);
		$cols[] = array(    //new
             'name'  => $this->language->get('column_image'),
             'sort'  => 'i.filename',
             'align' => 'right'
        );
    	$cols[] = array(
      		'name'  => $this->language->get('column_action'),
      		'align' => 'right'
    	);
		
		if (!$this->session->get('homepage.search')){
			$sql = "select h.home_id, h.name, h.status, hd.title, i.filename from home_page h left join home_description hd on(h.home_id = hd.home_id) left join image i on (hd.image_id = i.image_id) where hd.language_id = '" . (int)$this->language->getId() . "'";
		} else {
			$sql = "select h.home_id, h.name, h.status, hd.title, i.filename from home_page h left join home_description hd on(h.home_id = hd.home_id) left join image i on (hd.image_id = i.image_id) where hd.language_id = '" . (int)$this->language->getId() . "' and h.name like '?'";
		}
		$sort = array(
			'h.name',
			'h.status',
			'hd.title',
			'i.filename'
		);
		if(in_array($this->session->get('home.sort'), $sort)){
			$sql .= " order by " . $this->session->get('home.sort'). " " . (($this->session->get('home.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by h.name asc";
		}
		
		$results = $this->modelAdminHomepage->get_page($sql);
		
		$rows = array();
    	foreach ($results as $result) {
      		$cell = array();
      		$cell[] = array(
        		'value' => $result['name'],
        		'align' => 'left'
		  	);
      		$cell[] = array(
        		'value' => $result['title'],
        		'align' => 'left'
		  	);
      		$cell[] = array(
        		'icon'  => ($result['status'] ? 'enabled.png' : 'disabled.png'),
        		'align' => 'center'
      		);
			$cell[] = array(       //new
               'image' => $result['filename']?$this->image->resize($result['filename'], '22', '22'):$this->image->resize('no_image.png', '22', '22'),
               'align' => 'right'
             );
			
			$action = array();
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('homepage', 'update', array('home_id' => $result['home_id']))
      		);
			$action[] = array(
        		'icon' => 'delete.png',
				'text' => $this->language->get('button_delete'),
				'href' => $this->url->ssl('homepage', 'delete', array('home_id' => $result['home_id']))
      		);
      		$cell[] = array(
        		'action' => $action,
        		'align'  => 'right'
      		);
			$rows[] = array('cell' => $cell);
		}
		
		$view = $this->locator->create('template');
		$view->set('heading_title', $this->language->get('heading_title'));
    	$view->set('heading_description', $this->language->get('heading_description'));
		$view->set('text_results', $this->modelAdminHomepage->get_text_results());
		
    	$view->set('entry_page', $this->language->get('entry_page'));
    	$view->set('entry_search', $this->language->get('entry_search'));

    	$view->set('button_list', $this->language->get('button_list'));
    	$view->set('button_insert', $this->language->get('button_insert'));
    	$view->set('button_update', $this->language->get('button_update'));
   	 	$view->set('button_delete', $this->language->get('button_delete'));
    	$view->set('button_save', $this->language->get('button_save'));
    	$view->set('button_cancel', $this->language->get('button_cancel'));

    	$view->set('error', @$this->error['message']);
 
 		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		
    	$view->set('action', $this->url->ssl('homepage', 'page'));
    	$view->set('search', $this->session->get('homepage.search'));
		$view->set('sort', $this->session->get('home.sort'));
    	$view->set('order', $this->session->get('homepage.order'));
    	$view->set('page', $this->session->get('homepage.page'));
    	$view->set('cols', $cols);
    	$view->set('rows', $rows);

    	$view->set('list', $this->url->ssl('homepage'));
    	$view->set('insert', $this->url->ssl('homepage', 'insert'));

    	$view->set('pages', $this->modelAdminHomepage->get_pagination());
		return $view->fetch('content/list.tpl');
	}
  	function getForm() {
		$this->initialize(); // Required 
   
    	$view = $this->locator->create('template');

    	$view->set('heading_title', $this->language->get('heading_title'));
    	$view->set('heading_description', $this->language->get('heading_description'));

    	$view->set('text_enabled', $this->language->get('text_enabled'));
    	$view->set('text_disabled', $this->language->get('text_disabled'));
		$view->set('text_runtimes', $this->language->get('text_runtimes'));
		$view->set('entry_name', $this->language->get('entry_name'));
		$view->set('entry_title', $this->language->get('entry_title'));
		$view->set('entry_status', $this->language->get('entry_status'));
		$view->set('entry_description', $this->language->get('entry_description'));
		$view->set('entry_meta_title', $this->language->get('entry_meta_title'));
		$view->set('entry_meta_description', $this->language->get('entry_meta_description'));
		$view->set('entry_meta_keywords', $this->language->get('entry_meta_keywords'));
		$view->set('entry_run_times', $this->language->get('entry_run_times'));
		$view->set('entry_welcome', $this->language->get('entry_welcome'));
		$view->set('entry_image', $this->language->get('entry_image'));
		$view->set('entry_flash', $this->language->get('entry_flash'));
		$view->set('entry_flash_width', $this->language->get('entry_flash_width'));
		$view->set('entry_flash_height', $this->language->get('entry_flash_height'));
		$view->set('entry_filename', $this->language->get('entry_filename'));
		
		$view->set('button_upload', $this->language->get('button_upload'));
    	$view->set('button_list', $this->language->get('button_list'));
    	$view->set('button_insert', $this->language->get('button_insert'));
    	$view->set('button_update', $this->language->get('button_update'));
    	$view->set('button_delete', $this->language->get('button_delete'));
    	$view->set('button_save', $this->language->get('button_save'));
    	$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_add', $this->language->get('button_add'));
		$view->set('button_remove', $this->language->get('button_remove'));
		
		$view->set('tab_name', $this->language->get('tab_name'));
		$view->set('tab_description', $this->language->get('tab_description'));
		$view->set('tab_meta', $this->language->get('tab_meta'));
		
    	$view->set('error_title', @$this->error['title']);	
    	$view->set('error', @$this->error['message']);
		$view->set('error_file', "");

    	$view->set('action', $this->url->ssl('homepage', $this->request->gethtml('action'), array('home_id' => $this->request->gethtml('home_id'))));
    	$view->set('list', $this->url->ssl('homepage'));
    	$view->set('insert', $this->url->ssl('homepage', 'insert'));
    	if ($this->request->gethtml('home_id')) {
     		$view->set('update', $this->url->ssl('homepage', 'update', array('home_id' => $this->request->gethtml('home_id'))));
      		$view->set('delete', $this->url->ssl('homepage', 'delete', array('home_id' => $this->request->gethtml('home_id'))));
    	}
    	$view->set('cancel', $this->url->ssl('homepage'));
		$view->set('action_flash', $this->url->ssl('homepage', 'flash_upload',array('home_id' => $this->request->gethtml('home_id'))));
	
    	$home_description_data = array();	
		$results = $this->modelAdminHomepage->get_languages();

		foreach ($results as $result) {
			if (($this->request->gethtml('home_id')) && (!$this->request->isPost())) {
				$home_description_info = $this->modelAdminHomepage->get_descriptions($this->request->gethtml('home_id'),$result['language_id']);
			}
			
			$title			= $this->request->get('title', 'post');
			$description	= $this->request->get('description', 'post');
			$welcome		= $this->request->get('welcome', 'post');
			$meta_title 	= $this->request->get('meta_title', 'post');
			$meta_description = $this->request->get('meta_description', 'post');
			$meta_keywords 	= $this->request->get('meta_keywords', 'post');
			$flash			= $this->request->get('flash', 'post');
			$flash_width    = $this->request->gethtml('flash_width', 'post');
			$flash_height   = $this->request->gethtml('flash_height','post');
			$image_id		= $this->request->get('image_id', 'post');
			$run_times		= $this->request->gethtml('run_times', 'post');
			
			$home_description_data[] = array(
				'language_id' 	=> $result['language_id'],
	    		'language'    	=> $result['name'],
				'title'			=> (isset($title[$result['language_id']]) ? $title[$result['language_id']] : @$home_description_info['title']),
				'description'	=> (isset($description[$result['language_id']]) ? $description[$result['language_id']] : @$home_description_info['description']),
				'welcome'		=> (isset($welcome[$result['language_id']]) ? $welcome[$result['language_id']] : @$home_description_info['welcome']),
				'meta_title' 	=> (isset($meta_title[$result['language_id']]) ? $meta_title[$result['language_id']] : @$home_description_info['meta_title']),
				'meta_description'=> (isset($meta_description[$result['language_id']]) ? $meta_description[$result['language_id']] : @$home_description_info['meta_description']),
				'meta_keywords'	=> (isset($meta_keywords[$result['language_id']]) ? $meta_keywords[$result['language_id']] : @$home_description_info['meta_keywords']),
				'flash'			=> (isset($flash[$result['language_id']]) ? $flash[$result['language_id']] : @$home_description_info['flash']),
				'flash_width'   => (isset($flash_width[$result['language_id']]) ? $flash_width[$result['language_id']] : @$home_description_info['flash_width']),
				'flash_height'   => (isset($flash_height[$result['language_id']]) ? $flash_height[$result['language_id']] : @$home_description_info['flash_height']),
				'image_id'		=> (isset($image_id[$result['language_id']]) ? $image_id[$result['language_id']] : @$home_description_info['image_id']),
				'run_times'		=> (isset($run_times[$result['language_id']]) ? $run_times[$result['language_id']] : @$home_description_info['run_times'])
			);
		}
		$view->set('home_descriptions', $home_description_data);
	
		If(($this->request->gethtml('home_id')) && (!$this->request->isPost())){
			$homepage_info = $this->modelAdminHomepage->getRow_homepage_info($this->request->gethtml('home_id'));
		}
	
		if ($this->request->has('name', 'post')){
			$view->set('name', $this->request->get('name', 'post'));
		} else {
			$view->set('name', @$homepage_info['name']);
		}
		
    	if ($this->request->has('status', 'post')) {
      		$view->set('status', $this->request->gethtml('status', 'post'));
    	} else {
      		$view->set('status', @$homepage_info['status']);
    	}
		
    	$image_data = array();

    	$results = $this->modelAdminHomepage->get_images();
		
		foreach ($results as $result) {
			$image_data[] = array(
        		'image_id'   => $result['image_id'],
        		'title'      => $result['title'],
				'filename'   => $result['filename']
			);
		}
		$view->set('images', $image_data);
		$view->set('flash_files', $this->checkFiles());
		return $view->fetch('content/homepage.tpl');
	}
	function checkFiles() {
		$this->initialize(); // Required 

		$flash_data = array();
		$files=glob(DIR_FLASH.'*.*');
		if (!$files) { return; }
		foreach ($files as $file) {
			$pattern='/\.('.implode('|',$this->types).')$/';
			if (preg_match($pattern,$file)) {
				$filename = basename($file);
				$flash_data[] = array(
					'flash'   => $filename
				);	
			}
		}
		return $flash_data;
	}
  	function validateForm() {
		$this->initialize(); // Required 

    	if (!$this->user->hasPermission('modify', 'homepage')) {
      		$this->error['message'] = $this->language->get('error_permission');
    	}
		if(!$this->validate->strlen($this->request->get('name', 'post'),1,64)){
			$this->error['name'] = $this->language->get('error_name');
		}
		
    	foreach ($this->request->get('title', 'post', array()) as $value) {
            if (!$this->validate->strlen($value,1,64)) {
                $this->error['title'] = $this->language->get('error_title');
            }
    	}
		
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
	}
  	function validateDelete() {
		$this->initialize(); // Required 
    
    	if (!$this->user->hasPermission('modify', 'homepage')) {
      		$this->error['message'] = $this->language->get('error_permission');  
    	}
	  	
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
	}
  	function page() {
		$this->initialize(); // Required 
	
		if ($this->request->has('search', 'post')) {
	  		$this->session->set('homepage.search', $this->request->gethtml('search', 'post'));
		}
	 
		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
	  		$this->session->set('homepage.page', $this->request->gethtml('page', 'post'));
		} 

		$response->redirect($url->ssl('homepage'));	
	}
	function flash_upload(){
		$this->initialize(); // Required 
		
		if ($this->user->hasPermission('modify', 'homepage')) {
		  if($this->upload->has('flashimage') && !$this->upload->hasError('flashimage') ){
		    $pattern='/\.('.implode('|',$this->types).')$/';
		    if (preg_match($pattern,$this->upload->getName('flashimage'))) {
			  $this->upload->save('flashimage',DIR_FLASH . $this->upload->getName('flashimage'));
		    }
		  }
		}
		if($this->request->has('home_id')){
		$this->response->redirect($this->url->ssl('homepage', 'update', array('home_id' => $this->request->gethtml('home_id'))));	
		} else {
		$this->response->redirect($this->url->ssl('homepage', 'insert'));	
		}
	}
	function flash_refresh(){
		$this->initialize(); // Required 
		
		$lan_id = $this->request->gethtml('flash_id');
		$flash_files = $this->checkFiles();
		$output = '<select name="flash[' . $lan_id .']" id="flash' . $lan_id . '">';
		$output.= '<option value=""> </option';
		foreach($flash_files as $flash){
		    $output .= '<option value="' . $flash['flash'] . '">' . $flash['flash'] . '</option>';
		}
		$output .= '</select>';
		$this->response->set($output);
	}
}
?>