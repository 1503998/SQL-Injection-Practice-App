<?php header('Content-type: text/xml');
include 'db.php';
$sql="SELECT * FROM pp_config";
$req=mysql_query($sql);
$row = mysql_fetch_assoc($req);
 ?>

<rss version="2.0">
<channel>
<title><?php echo $row['name']; ?></title>
<description>Play flash game for free</description>
<link><?php echo $row['site_url']; ?></link>
<copyright><?php echo $row['name']; ?></copyright>

<?php
$q="SELECT * FROM pp_files WHERE approved = '1' ORDER BY id DESC LIMIT 15";
$doGet=mysql_query($q)or die (mysql_error());

while($result = mysql_fetch_assoc($doGet)){
?>
     <item>
        <title> <?=htmlentities(strip_tags($result['name'])); ?></title>
        <description> <?=htmlentities(strip_tags($result['description'],'ENT_QUOTES'));?></description>
        <link><?php echo $row['site_url']; ?>/games.php?id=<?=$result['id'];?></link>
        <pubDate> <?=$result['date']; ?></pubDate>
     </item>  
<?php } ?>  

</channel>
</rss>
