<?php
//index.php
//(C) Ben Hughes 2005 distributed under the GNU GPL
//Display page for unprivileged users
//lets do the authentication dance
function entry_theme($input, $comment_count) 
{
	//input is the theme file text
	//value will be global for now
	//comment_count is the number of comments attached to entry
	global $value;
	$input = str_replace("[DATE]", $value['date'], $input);
	$input = str_replace("[AUTHOR]", $value['author'], $input);
	$input = str_replace("[TITLE]", $value['title'], $input);
	$input = str_replace("[CONTENT]", $value['content'], $input);
	$input = str_replace("[CATEGORY]", $value['category'], $input);
	$input = str_replace("[COMMENT_LINK]", "<a href=\"index.php?comments=y&id=" . $value['id'] . "\">Comments($comment_count)</a>", $input);
	return($input); //kind of amusing, but I is lazy
}
function comment_theme($comment_theme, $comments, $entry_out)
{
	//comment_theme is the html for the individual comment cell
	//comments is the raw sql of the comments
	//entry_out is the preprocessed entry_theme
	//We will use the variable comment_work to denote the entire comment section as it is being worked
	global $theme;
	global $value;
	$top = fopen("themes/$theme/comment_top.html", "r");
	$comment_work = fread($top, filesize("themes/$theme/comment_top.html")); 
	while( $row = mysql_fetch_array($comments) ) {
		//again, if you don't like my var names, STFU
		$tmp = str_replace("[DATE]", $row['date'], $comment_theme);
		$tmp = str_replace("[AUTHOR]", $row['author'], $tmp);
		$tmp = str_replace("[TITLE]", $row['title'], $tmp);
		$tmp = str_replace("[EMAIL]", $row['email'], $tmp);
		$tmp = str_replace("[CONTENT]", $row['content'], $tmp);
		$comment_work .= "\n" . $tmp;
	}
	//add in permission checking reply-ness soon
	$bottom = fopen("themes/$theme/comment_bottom.html", "r");
	$comment_work .= fread($bottom, filesize("themes/$theme/comment_bottom.html"));
	$comment_work = str_replace("[COMMENT_LINK]", "<a href=\"new_comment.php?id={$value['id']}\">Post Comment</a>", $comment_work); 
	//merge comment_work and entry_out
	$entry_out = str_replace("[COMMENTS]", $comment_work, $entry_out);
	return($entry_out);
}//Boo Yah
session_start();
include("qdblog.php");
if(user_deny("index2.php", "admin", "mod")) {
global $conn;
global $theme;
theme();
//Ok lets output top theme part
include("themes/$theme/top.php");

//Lets apply our filters
if($_GET['cat']){ $cat = "category = '{$_GET['cat']}'"; } else { $cat = "fp = '1'"; }
if($_GET['author']){ $author = "AND author = '{$_GET['author']}'"; } else { $num = NULL; }
if($_GET['id']){ $id = "AND id = '".(int)$_GET['id']."'";} else { $num = NULL; }
if($_GET['num']) { $num = "LIMIT ".(int)$_GET['num']; } else { $num = "LIMIT 15"; } //Change limit to be user configurable through SQL
//Lets try and get some entries out here
$sql = "SELECT id, date, title, content, category, author FROM $prefix"."entries WHERE $cat $author $id ORDER BY date DESC $num;";
$query = mysql_query($sql, $conn) or die("Query Failed");
$file = fopen("themes/$theme/entry.html", "r");
$entry_theme = fread($file, filesize("themes/$theme/entry.html"));
fclose($file);
if($_GET['comments'] == 'y') {
	$file = fopen("themes/$theme/comment.html", "r");
	$comment_theme = fread($file, filesize("themes/$theme/comment.html"));
	fclose($file);
}
include("categories.php");
if( mysql_num_rows($query) == 0 )
	echo "<p>No entries matched your query</p>";
while( $value = mysql_fetch_array($query)) {
	//basic comment query now
	$comment_count = mysql_num_rows(mysql_query("SELECT id FROM $prefix"."comments WHERE entry_id = '" . $value['id'] . "';", $conn));
	global $value;
	$entry_out = entry_theme($entry_theme, $comment_count);
	//comment time
	if($_GET['comments'] == 'y') {
		$comments = mysql_query("SELECT * FROM $prefix"."comments WHERE entry_id = '" . $value['id'] . "';", $conn);
		$entry_out = comment_theme($comment_theme, $comments, $entry_out);
	} else { $entry_out = str_replace("[COMMENTS]", "", $entry_out); }
	echo $entry_out;
}
if(!$_SESSION['username']) { echo "<br/><br/><p><a href=\"login.php\">Log In</a></p>"; }else{ echo "<br/><br/><p><a href=\"logout.php\">Logout</a></p>"; }
include("themes/$theme/bottom.php");
}
?>

