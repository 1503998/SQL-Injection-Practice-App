<?php   // Add to Cart AlegroCart
class ControllerAddToCart extends Controller {
	function add() {
		$config   =& $this->locator->get('config');
		$cart     =& $this->locator->get('cart');
		$currency =& $this->locator->get('currency');
		$language =& $this->locator->get('language');
		$url      =& $this->locator->get('url');
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$tax      =& $this->locator->get('tax');
		$template =& $this->locator->get('template');	
        $session  =& $this->locator->get('session');
		$module   =& $this->locator->get('module');
		
		if ($request->isPost() && $request->has('item', 'post')){
			$cart->add($request->gethtml('item', 'post'), ($request->gethtml('quantity', 'post') > 0) ? $request->gethtml('quantity', 'post') : 1, $request->gethtml('option', 'post'));
		} else {
			$cart->add($request->gethtml('item'), ($request->gethtml('quantity') > 0) ? $request->gethtml('quantity') : 1, $request->gethtml('option'));
		}				
		$item = $request->get('item');
		if ($item){
			$cart->subtotal = '0';
			$cart->total = '0';
			$cart->data_refresh();
			if ($config->get('cart_status')) {	
				$language->load('extension/module/cart.php');
				$view = $this->locator->create('template');
				$view->set('heading_title', $language->get('heading_title'));
				$view->set('text_subtotal', $language->get('text_subtotal'));
				$view->set('text_view_cart', $language->get('text_view_cart'));
				$view->set('text_products', $language->get('text_products'));
				$view->set('text_items', $language->get('text_items'));				
				$view->set('view_cart', $url->href('cart'));
				$view->set('text_checkout', $language->get('text_checkout'));
				$view->set('checkout', $url->href('cart'));

				$product_data = array();

				foreach ($cart->getProducts() as $result) {
					$product_data[] = array(
						'href'     => $url->href('product', false, array('product_id' => $result['product_id'])),
						'name'     => $result['name'],
						'quantity' => $result['quantity'],
						'total'    => $currency->format($result['total'])
					);
				}
 
				$view->set('products', $product_data);
				$view->set('subtotal', $currency->format($cart->getsubTotal()));
				$view->set('item_total', $cart->countProducts());
				$view->set('product_total', count($product_data));
				$view->set('text_empty', $language->get('text_empty'));
				$response->set($view->fetch('module/cart.tpl'));
			}
		}
	}
}
?>