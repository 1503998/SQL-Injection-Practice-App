#Version Setting
SET @ver='1.2.1';
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `type` = 'global' and `group` = 'version' and `key` = 'version';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'version', 'version', @ver) ON DUPLICATE KEY UPDATE value=@ver;

# Add the extension details to the database

SET @lid=1;
SELECT @lid:=language_id FROM language WHERE `code` = 'en';

# Extension Authorize Net
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'payment_authnetaim';
INSERT INTO `extension` (`extension_id`, `code`, `type`, `directory`, `filename`, `controller`) VALUES (@id, 'authnetaim', 'payment', 'payment', 'authnetaim.php', 'payment_authnetaim') ON DUPLICATE KEY UPDATE extension_id=extension_id;
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'payment_authnetaim';
INSERT INTO `extension_description` (`extension_id`, `language_id`, `name`, `description`) VALUES (@id, @lid, 'Authorize.Net (AIM)', 'Authorize.Net (AIM)') ON DUPLICATE KEY UPDATE extension_id=extension_id;

#Settings AuthorizeNet
REPLACE INTO `order_status` (`order_status_id`, `language_id`, `name`) VALUES ('99', @lid, 'Paid Unconfirmed');
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'authnetaim' and `key` = 'authnetaim_status';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'authnetaim', 'authnetaim_status', '0') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'authnetaim' and `key` = 'authnetaim_geo_zone_id';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'authnetaim', 'authnetaim_geo_zone_id', '0') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'authnetaim' and `key` = 'authnetaim_sendemail';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'authnetaim', 'authnetaim_sendemail', 'FALSE') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'authnetaim' and `key` = 'authnetaim_test';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'authnetaim', 'authnetaim_test', '1') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'authnetaim' and `key` = 'authnetaim_prod_login';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'authnetaim', 'authnetaim_prod_login', '') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'authnetaim' and `key` = 'authnetaim_prod_txnkey';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'authnetaim', 'authnetaim_prod_txnkey', '') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'authnetaim' and `key` = 'authnetaim_test_login';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'authnetaim', 'authnetaim_test_login', '') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'authnetaim' and `key` = 'authnetaim_test_txnkey';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'authnetaim', 'authnetaim_test_txnkey', '') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'authnetaim' and `key` = 'authnetaim_authtype';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'authnetaim', 'authnetaim_authtype', 'auth_capture') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'authnetaim' and `key` = 'authnetaim_sort_order';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'authnetaim', 'authnetaim_sort_order', '1') ON DUPLICATE KEY UPDATE setting_id=setting_id;

# Add the extension details to the database

# Extension Google Checkout
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'payment_google';
INSERT INTO `extension` (`extension_id`, `code`, `type`, `directory`, `filename`, `controller`) VALUES (@id, 'google', 'payment', 'payment', 'google.php', 'payment_google') ON DUPLICATE KEY UPDATE extension_id=extension_id;
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'payment_google';
INSERT INTO `extension_description` (`extension_id`, `language_id`, `name`, `description`) VALUES (@id, @lid, 'Google Checkout', 'Google Checkout Payment Gateway') ON DUPLICATE KEY UPDATE extension_id=extension_id;

CREATE TABLE IF NOT EXISTS `order_google` (
  `order_reference` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `order_number` varchar(30) collate utf8_unicode_ci NOT NULL default '',
  `total` decimal(14,6) NOT NULL,
  PRIMARY KEY  (`order_reference`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

# Settings Google Checkout

SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'google' and `key` = 'google_status';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'google', 'google_status', '0') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'google' and `key` = 'google_geo_zone_id';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'google', 'google_geo_zone_id', '0') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'google' and `key` = 'google_merchantid';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'google', 'google_merchantid', '') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'google' and `key` = 'google_merchantkey';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'google', 'google_merchantkey', '') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'google' and `key` = 'google_test';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'google', 'google_test', '0') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'google' and `key` = 'google_currency';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'google', 'google_currency', 'USD') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'google' and `key` = 'google_sort_order';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'google', 'google_sort_order', '1') ON DUPLICATE KEY UPDATE setting_id=setting_id;

# Add Flash Size to Homepage Description
ALTER TABLE `home_description`
ADD `flash_width` int(11) After `flash`,
ADD `flash_height` int(11) After `flash`;
ALTER TABLE `home_description` CHANGE `welcome` `welcome` VARCHAR( 510 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;

#Add Model_Number Column
ALTER TABLE `order_product` DROP `model`;
ALTER TABLE `order_product`
ADD `model_number` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL AFTER `name`;
ALTER TABLE `product_description`
ADD `model_number` varchar( 32 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL AFTER `model`;

# Settings for Catalog Options Display type select or radio
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'product_options_select';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'product_options_select', 'select') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'category_options_select';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'category_options_select', 'select') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'search_options_select';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'search_options_select', 'select') ON DUPLICATE KEY UPDATE setting_id=setting_id;
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'manufacturer' and `key` = 'manufacturer_options_select';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'manufacturer', 'manufacturer_options_select', 'select') ON DUPLICATE KEY UPDATE setting_id=setting_id;

# Start of version 1.2
# Setting for STYLES
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_styles';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'config_styles', 'default') ON DUPLICATE KEY UPDATE setting_id=setting_id;
# Setting for AutoUpdate SEO
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_seo';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'config_seo', '0') ON DUPLICATE KEY UPDATE setting_id=setting_id;
# Change URL Alias to Global
SET @ver='global';
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_url_alias';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'global', 'config', 'config_url_alias', '0') ON DUPLICATE KEY UPDATE type=@ver;
# Change vreview module to extra
set @ver='module_extra_review';
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `code` = 'review' and `type` ='module';
INSERT INTO `extension` (`extension_id`, `code`, `type`, `directory`, `filename`, `controller`) VALUES (@id, 'review', 'module', 'module', 'review.php', 'module_extra_review') ON DUPLICATE KEY UPDATE controller=@ver;
SET @id=NULL;
SELECT @id:=extension_id FROM extension WHERE `controller` = 'module_extra_review';
INSERT INTO `extension_description` (`extension_id`, `language_id`, `name`, `description`) VALUES (@id, @lid, 'Catalog Review', 'Catalog Review') ON DUPLICATE KEY UPDATE extension_id=extension_id;

# Start of version 1.2.1
# Setting for COLORS
SET @id=NULL;
SELECT @id:=setting_id FROM setting WHERE `group` = 'config' and `key` = 'config_colors';
INSERT INTO `setting` (`setting_id`, `type`, `group`, `key`, `value`) VALUES (@id, 'catalog', 'config', 'config_colors', 'neutral.css') ON DUPLICATE KEY UPDATE setting_id=setting_id;
# Add Run Times and Meta tags to Home Page
ALTER TABLE `home_description`
ADD `run_times` int(11) default '1' After `image_id`,
ADD `meta_keywords` varchar(255) CHARACTER SET utf8 collate utf8_unicode_ci DEFAULT NULL AFTER `welcome`,
ADD `meta_description` varchar(512) CHARACTER SET utf8 collate utf8_unicode_ci DEFAULT NULL AFTER `welcome`,
ADD `meta_title` varchar(255) CHARACTER SET utf8 collate utf8_unicode_ci DEFAULT NULL AFTER `welcome`;