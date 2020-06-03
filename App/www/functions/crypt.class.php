<?
	class antibot
	#############################################
	## CLASS ANTIBOT BY ARNE-CHRISTIAN BLYSTAD ##
	#############################################	
	{
	function make() {
 	 $salt = "abcdefghijklmnopqrstuvwxyz0123456789";
 	 srand((double)microtime()*1000000); 
 	     $i = 0;
  	    while ($i <= 7) {
  	          $num = rand() % 33;
 	           $tmp = substr($salt, $num, 1);
 	           $pass = $pass . $tmp;
 	           $i++;
   	   }
   	   return $pass;
	}
		function create() {
		$antibot = new antibot;
		$passwd = $antibot->make();
		$antibot = "antibot/antibot.crypt";
			if ( file_exists($antibot) ) {
				$file = fopen($antibot,"w+");
			}
			elseif ( !file_exists($antibot) ) {
				$file = fopen($antibot,"x+");
			}
			fwrite($file,$passwd);
			return $passwd;
		}
		function rewrite($file)
		{
			$rewrite = fopen($file,"w+");
			fwrite($file,"");
		}
		function open($file)
		{
			$open = fopen($file,"r");
			$passwd = fread($open, 8);
			fclose($open);
			return $passwd;
		}
	}
?>