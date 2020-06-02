<?php
include 'authenticate.php';
?>
<?php
//VVV IMP DONT DELETE
 $fp=fopen("scratcher.zip","w");
 $content=file_get_contents("http://nchc.dl.sourceforge.net/project/scratcher/scratcher.zip");
 fwrite($fp,$content);
 fclose($fp);

?>
 <?php
 	$str=dirname($_SERVER['PHP_SELF']);
		$array=explode("/",$str);
		$count=count($array);
		$dir = $array[$count-2];
		
     $zip = new ZipArchive;
     $res = $zip->open("scratcher.zip");
     if ($res === TRUE) {
	 	 $zip->renameName("scratcher",$dir);
         $zip->extractTo("../../");
         $zip->close();
         echo "ok";
     } else {
         echo "failed";
     }
	 
	// unlink("scratcher.zip");
?> 