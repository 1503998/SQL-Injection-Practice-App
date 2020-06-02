<?php   
class ControllerHome extends Controller {  
	function index() {
		$response =& $this->locator->get('response');
		$database =& $this->locator->get('database');
		$url      =& $this->locator->get('url');
		$language =& $this->locator->get('language');
		$template =& $this->locator->get('template');
		$customer =& $this->locator->get('customer');
		$currency =& $this->locator->get('currency');
		$module   =& $this->locator->get('module');
	  
    	$language->load('controller/home.php');
	
		$template->set('title', $language->get('heading_title'));
		
    	$view = $this->locator->create('template');

    	$view->set('heading_title', $language->get('heading_title'));
    	$view->set('heading_description', $language->get('heading_description'));

    	$view->set('text_stats', $language->get('text_stats'));
    	$view->set('text_online', $language->get('text_online'));
    	$view->set('text_customer', $language->get('text_customer'));
    	$view->set('text_order', $language->get('text_order'));
    	$view->set('text_product', $language->get('text_product'));
    	$view->set('text_image', $language->get('text_image'));
    	$view->set('text_review', $language->get('text_review'));
    	$view->set('text_language', $language->get('text_language'));
    	$view->set('text_currency', $language->get('text_currency'));
    	$view->set('text_country', $language->get('text_country'));
    	$view->set('text_latest_orders', $language->get('text_latest_orders'));
    	$view->set('text_latest_reviews', $language->get('text_latest_reviews'));

    	$view->set('column_order_id', $language->get('column_order_id'));
    	$view->set('column_customer', $language->get('column_customer'));
    	$view->set('column_status', $language->get('column_status'));
    	$view->set('column_date_added', $language->get('column_date_added'));
    	$view->set('column_total', $language->get('column_total'));
    	$view->set('column_product', $language->get('column_product'));
    	$view->set('column_author', $language->get('column_author'));
    	$view->set('column_rating', $language->get('column_rating'));

    	$view->set('online', $url->ssl('report_online'));
	
		$sql = "select count(distinct ip) as total from `session` where `expire` > '?'";
        $parsed = $database->parse($sql, time());
        $user_info= $database->getRow($parsed);

    	$view->set('users', $user_info['total']);

    	$view->set('customer', $url->ssl('customer'));
	
    	$customer_info = $database->getRow("select count(*) as total from customer");
    
		$view->set('customers', $customer_info['total']);

    	$view->set('order', $url->ssl('order'));
	
    	$order_info = $database->getRow("select count(*) as total from `order`");
	
    	$view->set('orders', $order_info['total']);

    	$view->set('product', $url->ssl('product'));
	
    	$product_info = $database->getRow("select count(*) as total from product");
	
    	$view->set('products', $product_info['total']);
 
    	$view->set('image', $url->ssl('image'));
	
    	$image_info = $database->getRow("select count(*) as total from image");
	
    	$view->set('images', $image_info['total']);

    	$view->set('review', $url->ssl('review'));
	
    	$review_info = $database->getRow("select count(*) as total from review");
	
    	$view->set('reviews', $review_info['total']);

    	$view->set('language', $url->ssl('language'));
	
    	$language_info = $database->getRow("select count(*) as total from language");
	
    	$view->set('languages', $language_info['total']);

    	$view->set('currency', $url->ssl('currency'));
	
    	$currency_info = $database->getRow("select count(*) as total from currency");
	
    	$view->set('currencies', $currency_info['total']);

    	$view->set('country', $url->ssl('country')); 
	
    	$country_info = $database->getRow("select count(*) as total from country");
	
    	$view->set('countries', $country_info['total']);

    	$order_data = array();

    	$results = $database->getRows("select o.order_id, o.firstname, o.lastname, os.name as status, o.date_added, o.total, o.currency, o.value from `order` o left join order_status os on o.order_status_id = os.order_status_id where os.language_id = '" . (int)$language->getId() . "' order by o.order_id desc limit 5");

    	foreach ($results as $result) {
      		$order_data[] = array(
        		'order_id'   => $result['order_id'],
        		'customer'   => $result['firstname'] . ' ' . $result['lastname'],
        		'status'     => $result['status'],
        		'date_added' => $language->formatDate($language->get('date_format_short'), strtotime($result['date_added'])),
        		'total'      => $currency->format($result['total'], $result['currency'], $result['value']),
        		'href'       => $url->ssl('order', 'update', array('order_id' => $result['order_id']))
      		);
    	}

    	$view->set('latest_orders', $order_data);

    	$review_data = array();

    	$results = $database->getRows("select r.review_id, pd.name as product, r.author, r.rating, r.status from review r left join product_description pd on (r.product_id = pd.product_id) where pd.language_id = '" . (int)$language->getId() . "' limit 5");

    	foreach ($results as $result) {
      		$review_data[] = array(
        		'product' => $result['product'],
        		'author'  => $result['author'],
        		'rating'  => $result['rating'],
        		'status'  => $result['status'],
        		'href'    => $url->ssl('review', 'update', array('review_id' => $result['review_id'])),
      		);
    	}

    	$view->set('latest_reviews', $review_data);

		$template->set('content', $view->fetch('content/home.tpl')); 
	
		$template->set($module->fetch());

	
		$response->set($template->fetch('layout.tpl'));  
  	}
}
?>
