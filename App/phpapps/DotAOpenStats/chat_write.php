<?php

    include('config.php');
    if ( file_exists('chat_func.php') )
	include ('chat_func.php');
	
	/* spam keywords */
	$spam[] = "nigger";
	$spam[] = "idiot";
	$spam[] = "retard";

	/* IP's to block */
	$blockip[] = "1.2.3.4";

	/* spam, if message IS exactly that string */	
	$espam[] = "viagra";
	
	$fn = $chat_file;
	$msg = $_REQUEST["m"];
	$msg = htmlspecialchars(stripslashes($msg));
	
	$n = $_REQUEST["n"];
	$n = htmlspecialchars(stripslashes($n));
    if (strlen($n) >= $nick_length) {
		$n = substr($n, 0, $nick_length); 
	} else { 
		for ($i=strlen($n); $i<$nick_length; $i++) $n .= "";
	}

	if ($waittime_sec > 0) {
		$lastvisit = $_COOKIE["lachatlv"];
		setcookie("lachatlv", time());
 
		if ($lastvisit != "") {
			$diff = time() - $lastvisit;
			if ($diff < $waittime_sec) { die;}
		}
	}

	if ($msg != "")  {
		if (strlen($msg) < 2) { die(); }
		if (strlen($msg) >= 3) {
			/* Smilies are ok */
			if (strtoupper($msg) == $msg) { die(); }
		}
		if (strlen($msg) > 300) { die(); }
		if (strlen($msg) > 160) {
			if (substr_count($msg, substr($msg, 6, 8)) > 1) { die(); }
		}

		foreach ($blockip as $a) {
			if ($_SERVER["REMOTE_ADDR"] == $a) { die(); }
		}
		
		$mystring = strtoupper($msg);
		foreach ($spam as $a) {	
			 if (strpos($mystring, strtoupper($a)) === false) {
			 	/* Everything Ok Here */
			 } else {
			 	$msg = str_replace($a,"***",$msg);
			 }
		}		

		foreach ($espam as $a) {
			if (strtoupper($msg) == strtoupper($a)) { die(); }		
		}
				
		$handle = fopen ($fn, 'r'); 
		$chattext = fread($handle, filesize($fn)); fclose($handle);
		
		$arr1 = explode("\n", $chattext);

		if (count($arr1) > $maxlines) {
			/* Pruning */
			$arr1 = array_reverse($arr1);
			for ($i=0; $i<$maxlines; $i++) { $arr2[$i] = $arr1[$i]; }
			$arr2 = array_reverse($arr2);			
		} else {
			$arr2 = $arr1;
		}
		$ip = $_SERVER["REMOTE_ADDR"];
		$chattext = implode("\n", $arr2);
		if (substr_count($chattext, $msg) > 2) { die(); }
		$date = date($date_format);
		if ($chat_show_date == 1)
		$date = "<td width='150px'>(".date($date_format).")</td>"; else $date = "";
		
		if (function_exists('smilies'))
	    $msg = smilies($msg, 'smilies/');
		
		//if (function_exists('refineUrls'))
	    //$msg = refineUrls($msg);

		if (function_exists('clickable_link'))
	    $msg = clickable_link($msg);
		
		if (function_exists('shortURLS'))
		$msg = shortURLS($msg);
			
		$out = "$chattext<table><tr class='row'>$date<td valign='middle' align='left'><b>$n</b>: $msg</td></tr></table>\n";
		$out = str_replace("\'", "'", $out);
		$out = str_replace("\\\"", "\"", $out);
		$handle = fopen ($fn, 'w'); fwrite ($handle, $out); fclose($handle);				
	}

?>