<?
############################
## EVILBOARD :: SHOWFORUM ##
############################
include("include/header.php");
?>
<br>
<?
global $db;
$db = new db;
$db->connect();
$setting = new setting;
$setting->topic_per_page = $setting->num_topic();
// turns our url defined variable and turns it into $f.
$f = $_GET['f'];
// the query below selects the title from our selected forum.
$result = $db->query("select title from eb_forums where id = '$f'");
// the query below selects all threads with the selected forum id,
// then arranges the threads in descending order using the timestamp column.
$query = "SELECT * FROM eb_topic WHERE ForumID = '$f' ORDER BY `TopicID` DESC LIMIT ";
$page = $_GET['p'] ? (int)$_GET['p'] : 1; //If no page specified, use 1
$offset = ($page-1)*$setting->topic_per_page;
$query .= "$offset, $setting->topic_per_page";
$result2 = $db->query($query);
// $r below fetches the information from our result above.
$r = mysql_fetch_array($result);
// redefines our row variables.
$forum_title = $r['title'];
?>
<table cellpadding="5" cellspacing="0" border="0" style="width:100%;">
<tr>
<td class="eb_forum" colspan="2"><b><? echo $forum_title; ?></b></td>
</tr>
  <tr>
    <td width="68%" class="eb_forum_header" style="border-top-width:0;">Thread</td>
    <td width="16%" class="eb_forum_header" style="border-top-width:0; border-left-width:0;"><div align="center">Replies</div></td>
  </tr>
<?
// for each row in our $result2 (the threads query), we display  the info. below.
while($r2 = mysql_fetch_array($result2))
{
   // redefine our thread row values
   $thread_id = $r2['TopicID'];
   $thread_title = $r2['title'];
   $user_name = $r2['Username'];
   
   $replay = $db->query("SELECT * FROM eb_post WHERE TopicID = '{$thread_id}'");
   $replay = mysql_num_rows($replay);
   $rows->replay = $replay;
?>
<tr>
    <td  class="forum_footer">
    <a href="index.php?act=showtopic&t=<? echo $thread_id; ?>&forumid=<?=$f?>"><? echo $thread_title; ?></a><br><? echo $user_name; ?></b>
    </td>
    <td class="forum_footer" style="border-left-width:0;"><div align="center"><?=$rows->replay?></div></td>
  </tr>
<?
}
if (!$thread_id) {
?>
   <tr>
    <td colspan="2"  class="forum_footer"><div align="center"><em>No topics in current forum. </em></div></td>
  </tr>
<?  }
?>
</table>
<br class="eb_txt">
<table width="100%"  border="0" align="right" cellspacing="0">
  <tr>
    <td width="90%" valign="top"><span class="eb_txt"><? 
		$total_items = $db->query("SELECT * FROM `eb_topic` WHERE `ForumID` = '{$f}';");
	$total_items = mysql_num_rows($total_items);
	$number_pages = ceil($total_items/$setting->topic_per_page);
	echo "Page: ( ";
	for($i=1; $i<=$number_pages; $i++){
	   if($page !== $i){
	      echo "<a href='index.php?act=showforum&f={$f}&p=".$i."'>".$i."</a> ";
	   } else {
	      echo "<b>$i</b> ";
	   }
	}
	echo ") ";
	?></span></td>
    <td width="10%"><a href="index.php?act=posttopic&forumid=<?=$f?>"><img src="Themes/Default/Images/new-topic.gif" width="100" height="27" border="0"></a></td>
  </tr>
</table>
<br>
<br>
<? include("include/footer.php"); ?>
