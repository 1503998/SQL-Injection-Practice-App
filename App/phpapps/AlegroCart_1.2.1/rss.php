<?php

// Installed?
if (filesize('config.php') == 0) { exit; }
	require_once('library/application/string_modify.php');
define('APP','CATALOG');

// Include Config and Common
require('config.php');
require('common.php');

// Locator
require(DIR_LIBRARY . 'locator.php');
$locator = new Locator();

// Config
$config =& $locator->get('config');

// Database
$database =& $locator->get('database');
$database->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Settings
$sql="select * from setting where type = 'catalog' or type = 'global'";
$settings = $database->getRows($sql);

foreach ($settings as $setting) {
	$config->set($setting['key'], $setting['value']);
}

$image =& $locator->get('image'); // Image
$request =& $locator->get('request'); // Request
$url =& $locator->get('url'); // URL
$language =& $locator->get('language'); // Language
$currency =& $locator->get('currency'); //Currency
$tax      =& $locator->get('tax'); // Tax

// Base URL
$catalog_url = $request->isSecure()?HTTPS_SERVER:HTTP_SERVER;
$image_url = $request->isSecure()?HTTPS_IMAGE:HTTP_IMAGE;

// Product Data
$product_data = array();
$sql="select *, p.date_added as date_product_added from product p left join product_description pd on (p.product_id = pd.product_id) left join image i on (p.image_id = i.image_id) where p.status = '1' and pd.language_id = '%s' and p.date_available < now() and p.status = '1' order by date_product_added desc limit 20";
$sql=sprintf($sql,(int)$language->getId());
$results = $database->getRows($sql);
$unit = 'Pounds';
$products=array();
foreach ($results as $result) {
	$products[]=array(
	'name' => htmlentities(strip_tags($result['name'])),
	'url' => htmlentities($url->href('product', FALSE, array('product_id' => $result['product_id']))),
	'add_date' => date("D, d M Y H:i:s T", strtotime($result['date_product_added'])),
	'desc' => htmlentities(strip_tags(strippedstring($result['description'],256),'ENT_QUOTES')).htmlentities('<br>'),
	'price' => $currency->format($tax->calculate($result['price'], $result['tax_class_id'], $config->get('config_tax'))),
	'image' => htmlentities('<br><img width="100" height="100" src="' . $image->resize($result['filename'], 100, 100) . '">'),
	'weight' => $result['weight'] . ' ' . $unit
	);
}

header('Content-type: text/xml');
?>
<rss version="2.0">
<channel>
	<title><?php echo $config->get('config_store'); ?></title>
	<description><?php echo $config->get('config_store'); ?></description>
	<link><?php echo $catalog_url; ?></link>
	<copyright><?php echo $config->get('config_store'); ?></copyright>
<?php foreach ($products as $product) { ?>
	<item>
        <title><?php echo $product['name']; ?></title>
		<pubDate><?php echo $product['add_date']; ?></pubDate>
        <description><?php echo $product['desc'].$product['price']." ".$config->get('config_currency').$product['image']; ?></description>
        <link><?php echo $product['url']; ?></link>

        <guid isPermaLink="true"><?php echo $product['url']; ?></guid>
	</item>
<?php } ?>
</channel>
</rss>