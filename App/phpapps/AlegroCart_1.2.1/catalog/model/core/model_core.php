<?php //ModelCore AlegroCart
class Model_Core extends Model {
	function __construct(&$locator) {	
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
	}
	function get_categories(){
		$results = $this->database->getRows("select c.category_id, c.parent_id, c.path, c.sort_order, cd.name from category c left join category_description cd on (c.category_id = cd.category_id) where cd.language_id = '" . (int)$this->language->getId() . "' order by c.path");
		return $results;
	}
	function get_currencies(){
		$currencies = $this->database->cache('currency', "select * from currency order by title");
		return $currencies;
	}
	function get_homepage(){
		$results = $this->database->getRow("select * from home_page h left join home_description hd on(h.home_id = hd.home_id) left join image i on(hd.image_id = i.image_id) where hd.language_id = '" . (int)$this->language->getId() . "' and h.status = '1'");
		return $results;
	}
	function get_information(){ 
		$results = $this->database->cache('information-' . (int)$this->language->getId(), "select * from information i left join information_description id on (i.information_id = id.information_id) where id.language_id = '" . (int)$this->language->getId() . "' order by i.sort_order");
		return $results;
	}
	function getRow_information($information_id){ 
			$result = $this->database->getRow("select * from information i left join information_description id on (i.information_id = id.information_id) where i.information_id = '" . (int)$information_id . "' and id.language_id = '" . (int)$this->language->getId() . "'");
		return $result;
	}
	function get_languages(){
		$results = $this->database->cache('language', "select * from language order by sort_order");
		return $results;
	}
}
?>