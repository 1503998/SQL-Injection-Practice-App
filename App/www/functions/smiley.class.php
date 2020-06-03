<?
############################################
## SMILEY CLASS BY ARNE CHRISTIAN BLYSTAD ##
## 		  COPYRIGHT 2006 EVILBOARD 		  ##
############################################
class smiley
{
	function show($txt) 
	{
		$id = 0;
		while ( $id < count($R) ) {
			$rss = str_replace($S[$id] , $R[$id], $txt);
			$rx = preg_replace("cool",$S[0],$msg);
			$id++;
		}
		return $rx;
	}
	///////////////////////////////////////////
	// Function: click_smileys();            //
	// Description: Add all smileys in a box //
	///////////////////////////////////////////
	function click_smileys()
	{
	$ret = '<div class="eb_menu1">
              <div align="center"><span style="font-weight: bold">Clickable Smileys</span>:</div>
            </div><div align="center">';
	    ########################################
 		## Add in the smilies box             ##
 		########################################
		
		$connect = mysql_query("SELECT * FROM eb_smiley");
		$ret .= '<div class="forum_footer" style="border-top-width: 0; padding-left: 5px;">';
		while ($emo = mysql_fetch_object( $connect ) )
		{
		$img = $emo -> img;
		$cmd = $emo -> cmd;

			$ret .= "<a href=\"javascript:insertEmotion('{$img}','" . $cmd . "')\"><img src=\"Emoticons/1/" .$img. "\" alt='smilie' border='0'></a>&nbsp;\n";
			
		
		}
		$ret .= "</div></div>";
		return $ret;
	}
	/////////////////////////////////////
	// Function: LoadSmiley();         //
	// Description: Preloading Smileys //
    /////////////////////////////////////
	function LoadSmiley($msg)
	{
		$connect = mysql_query("SELECT * FROM eb_smiley");
		$cmd = array();
		$img = array();
		$id_emo = 1;
		while( $emo = mysql_fetch_object($connect) )
		{
			$id = $emo->id;
			$cmd[$id] = $emo->cmd;
			$img[$id] = $emo->img;
		}
		$rep = array();
		foreach ( $cmd as $emoshow )
		{
			$rep[] = str_replace($cmd[$id], "<img src='Emoticons/1/" . $img[$id] . "'>", $msg);
			#return $rep;
		}
			 foreach( $rep as $q )
        {
           $q = preg_replace("/:D(\S+?)([\s\.,]|$)/", "happy.gif" , $q);
		   
        }
		return $q;
	}
}
?>