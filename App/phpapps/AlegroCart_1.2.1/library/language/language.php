<?php

define('D_LANG','english');
define('E_LANG','Error: Could not load language data!');
define('R_LANG','/^(%s)(;q=[0-9]\\.[0-9])?$/i');

class Language {
  	var $code;
  	var $languages = array();
  	var $data      = array();
	var $lang;
	var $expire = 2592000; // 60 * 60 * 24 * 30 (30 days)

  	function __construct(&$locator) {
		$this->config   =& $locator->get('config');
		$this->database =& $locator->get('database');
		$this->request  =& $locator->get('request');
		$this->session  =& $locator->get('session');

		$this->lang = strtolower(APP).'_language'; 

		$results = $this->database->cache('language', 'select * from language');

		if (!$results) { exit(E_LANG); }

    	foreach ($results as $result) {
      		$this->languages[$result['code']] = array(
        		'language_id' => $result['language_id'],
        		'name'        => $result['name'],
        		'code'        => $result['code'],
				'directory'   => $result['directory'],
				'filename'    => $result['filename']
      		);
    	}
 		
    	if (array_key_exists($this->session->get($this->lang), $this->languages)) {
      		$this->set($this->session->get($this->lang));
    	} elseif (array_key_exists($this->request->get($this->lang, 'cookie'), $this->languages)) {
      		$this->set($this->request->get($this->lang, 'cookie'));
    	} elseif ($browser = $this->detect()) {
	    	$this->set($browser);
	  	} else {
        	$this->set($this->config->get('config_language'));
		}
		$this->load($this->languages[$this->code]['filename']);
  	}
	
	function set($language) {
    	$this->code = $language;
		
    	if ((!$this->session->has($this->lang)) || ($this->session->get($this->lang) != $language)) {
      		$this->session->set($this->lang, $language);
    	}

    	if ((!$this->request->get($this->lang, 'cookie')) || ($this->request->get($this->lang, 'cookie') != $language)) {	  
	  		setcookie($this->lang, $language, time() + $this->expire, '/', $_SERVER['HTTP_HOST']);
    	}
  	}
    
	function load($filename, $directory = DIR_LANGUAGE) {
		$_ = array();

		// Get the corrent default filename (eg: english.php or cart.php)
		$dfn = ($filename == $this->languages[$this->code]['directory'].'.php')?D_LANG.'.php':$filename;

		// Include the default language
		$dfile = $directory.D_LANG.DIRECTORY_SEPARATOR.$dfn;
		if (file_exists($dfile)) { include($dfile); }

		// Include the specified language
		$file = $directory.$this->languages[$this->code]['directory'].DIRECTORY_SEPARATOR.$filename;
		// Check it's not the same as the default, and it exists, then include
		if (($dfile != $file) && file_exists($file)) { include($file); }

		// We have no languages, exit
		if (empty($_)) { exit(E_LANG); }

        $this->data = array_merge($this->data, $_);

		$this->setCharset($this->get('charset'));
		$this->setLocale($this->get('locale'));
    }
  
  	function get($key) {
    	$args = func_get_args();
 
    	if (count($args) > 1) {
      		return vsprintf($this->get(array_shift($args)), $args);
    	} else {
      		return (isset($this->data[$key]) ? $this->data[$key] : $key);
    	}
  	}

  	function detect() {
    	if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) { return; }
		$browser_languages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
  
		$languages = array(
			'ar' => 'ar([-_][[:alpha:]]{2})?|arabic',
			'bg' => 'bg|bulgarian',
			'br' => 'pt[-_]br|brazilian portuguese',
			'ca' => 'ca|catalan',
			'cs' => 'cs|czech',
			'da' => 'da|danish',
			'de' => 'de([-_][[:alpha:]]{2})?|german',
			'el' => 'el|greek',
			'en' => 'en([-_][[:alpha:]]{2})?|english',
			'es' => 'es([-_][[:alpha:]]{2})?|spanish',
			'et' => 'et|estonian',
			'fi' => 'fi|finnish',
			'fr' => 'fr([-_][[:alpha:]]{2})?|french',
			'gl' => 'gl|galician',
			'he' => 'he|hebrew',
			'hu' => 'hu|hungarian',
			'id' => 'id|indonesian',
			'it' => 'it|italian',
			'ja' => 'ja|japanese',
			'ko' => 'ko|korean',
			'ka' => 'ka|georgian',
			'lt' => 'lt|lithuanian',
			'lv' => 'lv|latvian',
			'nl' => 'nl([-_][[:alpha:]]{2})?|dutch',
			'no' => 'no|norwegian',
			'pl' => 'pl|polish',
			'pt' => 'pt([-_][[:alpha:]]{2})?|portuguese',
			'ro' => 'ro|romanian',
			'ru' => 'ru|russian',
			'sk' => 'sk|slovak',
			'sr' => 'sr|serbian',
			'sv' => 'sv|swedish',
			'th' => 'th|thai',
			'tr' => 'tr|turkish',
			'uk' => 'uk|ukrainian',
			'tw' => 'zh[-_]tw|chinese traditional',
			'zh' => 'zh|chinese simplified'
		);

		foreach ($browser_languages as $browser_language) {
			foreach ($languages as $key => $value) {
				if (preg_match(sprintf(R_LANG,$value), $browser_language)) {
					if (isset($this->languages[$key])) {
					  return $key;
					}
				}
			}
		}

    	return FALSE;
	}

  	function getId() {
    	return $this->languages[$this->code]['language_id'];
  	}

  	function getCode() {
    	return $this->code;
  	}

	function setLocale($locale=0) {
		if ($locale && !is_array($locale) && strstr($locale,',')) $locale=explode(',',$locale);
		return setlocale(LC_ALL,$locale);
	}

	function setCharset($charset='UTF-8') {
		$charset=strtoupper($charset);
		if (function_exists('mb_language')) { //see http://www.php.net/mb_language
			if ($charset == 'ISO-2022-JP') { mb_language('ja'); }
			elseif ($charset == 'ISO-8859-1') { mb_language('en'); }
			else { mb_language('uni'); }
		}
		if (function_exists('mb_internal_encoding')) { mb_internal_encoding($charset); }
	}

	function formatDate($format,$time=false) {
		if (strstr($format,'%')) return ($time)?strftime($format,$time):strftime($format);
		return ($time)?date($format,$time):date($format);
	}

}
?>
