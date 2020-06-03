<?php
###############################
## EvilBoard Post Parser 	 ##
## by Arne-Christian Blystad ##
## Copyright 2006 EvilBoard  ##
###############################
class post_parser {
#####################################
## Function: parse_post($message)  ##
## Description: Parse a forum post ##
#####################################
	function parse_post($message,$PROTECT = FALSE)
	{
		# Connect to DB
		global $db;
		$db = new db;
		# str_replace
		if ( $PROTECT !== FALSE ) {
		$message	=	str_replace("<", "&lt;", $message);
		$message	=	str_replace(">", "&gt;", $message);
		$message	=	nl2br($message);
		}	
		#smileys start	
			$db_smiley	=	$db->query("SELECT * FROM eb_smiley");
			
			while($db_smile = mysql_fetch_object($db_smiley))
			{
				$message	=	str_replace(''.$db_smile->cmd.'','<img src="Emoticons/1/'.$db_smile->img.'"/>', $message);
			}
		$settings = new setting;
		$replace = $settings->badword_replace();
		$badword = $db->query("SELECT * FROM eb_badword");
		$badword = mysql_fetch_array($badword);
		if(!empty($badword)) {
			foreach ($badword as $curse_word) {
				if (stristr(trim($message),$curse_word)) {
					$length = strlen($curse_word);
					for ($i = 1; $i <= $length; $i++) {
						$stars .= $replace;
					}
					$message = eregi_replace($curse_word,$stars,trim($message));
					$stars = "";
				}
			}
		}
		return $message;
	}
}
?>