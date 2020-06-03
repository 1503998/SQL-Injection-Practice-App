<?php
	##################################
	## CREATE ANTIBOT FOR EVILBOARD ##
	##   BY ARNE CHRISTIAN BLYSTAD  ##
	##################################
	Header("Content-type: image/png");
	include("functions/crypt.class.php");
	$antibot = new antibot;
	$antibot->make();
	
	$file = "antibot/antibot.crypt";
	$open = fopen($file,"r");
	$passwd = fread($open, 8);
	fclose($open);
	
	$randn = rand(1, 3);
	$im = imagecreatefrompng("Themes/Default/Images/gd_img.png");
	$gray = ImageColorAllocate($im, 166, 166, 166);
	$txt = ImageColorAllocate($im, 85, 85, 85);
	
	if ( $randn == "1" ) {
		ImageLine($im, 1, 10, 99, 34, $gray); 
		ImageLine($im, 1, 23, 99, 12, $gray); 
		ImageLine($im, 25, 1, 180, 90, $gray);
	}
	elseif ( $randn == "2" ) {
		ImageLine($im, 1, 20, 99, 12, $gray); 
		ImageLine($im, 1, 27, 99, 38, $gray); 
		ImageLine($im, 1, 25, 99, 82, $gray); 
		ImageLine($im, 1, 38, 99, 12, $gray);
		ImageLine($im, 30, 1, 99, 30, $gray); 
	}
	elseif ( $randn == "3" ) {
		ImageLine($im, 1, 52, 99, 25, $gray); 
		ImageLine($im, 21, 1, 99, 64, $gray); 
		ImageLine($im, 1, 21, 99, 13, $gray); 
		ImageLine($im, 31, 1, 99, 43, $gray);
		ImageLine($im, 12, 1, 99, 13, $gray); 
	}
	ImageString ($im, 38, 15, 10,  $passwd, $txt);

	ImagePNG($im); 
?>