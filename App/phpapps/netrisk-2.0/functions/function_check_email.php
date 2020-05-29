<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	include('./../config.php');

	function check_email($mail_address) {
    	$pattern = "/^[\w-]+(\.[\w-]+)*@";
    	$pattern .= "([0-9a-z][0-9a-z-]*[0-9a-z]\.)+([a-z]{2,4})$/i";
    	if (preg_match($pattern, $mail_address)) {
        	$parts = explode("@", $mail_address);
        	if (checkdnsrr($parts[1], "MX")){
            	echo "The e-mail address is valid.";
            	// return true;
        	} else {
            	echo "The e-mail host is not valid.";
            	// return false;
        	}
    	} else {
        	echo "The e-mail address contains invalid charcters.";
        	// return false;
    	}
	}
	//check_email("INFO@google.co.uk");
?>
