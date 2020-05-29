<?PHP

include 'db.php';
$res = mysql_query("select mochi_id,site_url from pp_config") or die(mysql_error());
$row = mysql_fetch_assoc($res);
$mochi_id = $row['mochi_id'];
$site_url = $row['site_url'];


$urlrequest = "http://www.mochiads.com/feeds/games/".$mochi_id."/".$_REQUEST['game_tag']."/?format=json";
$gamearr = json_decode(file_get_contents($urlrequest),true);
$game = $gamearr['games'][0];

$name = mysql_escape_string($game['name']);
$tag = $game['tag'];
$author = mysql_escape_string($game['author']);
$author_link = mysql_escape_string($game['author_link']);
$description = mysql_escape_string($game['description']);
$slug = $game['slug'];
$width = $game['width'];
$height = $game['height'];
$keywords = mysql_escape_string($game['keywords']);
$swfaddr = "swf/".$slug.".swf";
$swf = fopen($swfaddr, "w");
fwrite($swf, file_get_contents($game['swf_url']));
$thumbaddr = "icon/".$slug.".gif";
$thumb = fopen($thumbaddr, "w");
fwrite($thumb, file_get_contents($game['thumbnail_url']));
$game_url = "$site_url/$swfaddr";
$thumb_url = "$site_url/$thumbaddr";
$date = time();
$query = "INSERT INTO pp_files (id,name,url_creator,creator,description,date,file,file2,approved,feature,ip,picture,category,reject,views,width,height) VALUES ('NULL','$name','$author_link','$author','$description',CURRENT_DATE(),'$game_url','$slug','0','0','0','$thumb_url','0','0','0',$width,$height)";
mysql_query($query);



?>