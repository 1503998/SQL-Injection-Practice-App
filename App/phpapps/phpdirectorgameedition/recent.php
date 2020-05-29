<?php

ob_start( 'ob_gzhandler' );

require( "header.php" ) 

?>  
<ut_response status="ok">

    <video_list>

<?

$sql = "SELECT * FROM pp_files WHERE approved = '1' ORDER BY RAND() limit 20"; //Limitation 
$result = mysql_query($sql); 
while($row = mysql_fetch_assoc($result)){ 
$beingwatched[] = $row; 
?> 



         <video>
		<title><?=$row['name']; ?></title>
	                <run_time><?=$row['views']; ?></run_time>	
 

 	   	 <url>games.php?id=<?=$row['id']; ?></url>

		

		 <thumbnail_url><?=$row['picture']; ?></thumbnail_url>
		 

         </video>





	<?
} 
?> 
    </video_list>


</ut_response>

<?php
$smarty -> assign ( 'beingwatched' , $beingwatched ); 
$smarty -> display ( 'index.tpl' ); 
?> 