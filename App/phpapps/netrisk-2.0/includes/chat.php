<?php

//Get config file for Session and common functions
require_once(dirname(__FILE__) . '/config.php');
//include('./config.php');

$_G = array(
	'mysql_prefix' => $mysql_prefix,
    'empty' => 'future_use');

// The number of comments that should show up in one viewing.
$number_of_main_comments = 35;
$number_of_game_comments = 30;

// Register globals - Thanks Karan et Etienne
$jal_lastID    = isset($_GET['jal_lastID']) ? $_GET['jal_lastID'] : "";
$jal_user_name = isset($_POST['n']) ? $_POST['n'] : ""; 
$jal_user_url  = isset($_POST['u']) ? $_POST['u'] : "";
$jal_user_text = isset($_POST['c']) ? $_POST['c'] : "";
$jalGetChat    = isset($_GET['jalGetChat']) ? $_GET['jalGetChat'] : "";
$jalSendChat   = isset($_GET['jalSendChat']) ? $_GET['jalSendChat'] : "";

// function to print the external javascript and css links
function jal_add_to_head () {
	//Will Alter this to use either a main or game chat css!
	$add_to_head = '<!-- Added By chat.php -->
    		<link rel="stylesheet" href="./css/gamechat_css.php" type="text/css" />
    		<script type="text/javascript" src="./includes/fatAjax.php"></script>
		';
	if(!isset($_SESSION['gid'])){  
	$add_to_head = '<!-- Added By chat.php -->
    		<link rel="stylesheet" href="./css/mainchat_css.php" type="text/css" />
    		<script type="text/javascript" src="./includes/fatAjax.php"></script>
		';
	}	
    
	return $add_to_head;
}


////////////////////////////////////////////////////////////
// Functions Below are for getting comments from the database
////////////////////////////////////////////////////////////

// Never cache this page
if ($jalGetChat == "yes" || $jalSendChat == "yes") {
	header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); 
	header( "Last-Modified: ".gmdate( "D, d M Y H:i:s" )."GMT" ); 
	header( "Cache-Control: no-cache, must-revalidate" ); 
	header( "Pragma: no-cache" );
	header("Content-Type: text/html; charset=utf-8");
	
	//if the request does not provide the id of the last know message the id is set to 0
	if (!$jal_lastID) $jal_lastID = 0;
}

if ($jalGetChat == "yes") {
	jal_getData($jal_lastID);
}

// Where the shoutbox receives information
function jal_getData ($jal_lastID) {
	
	global $_G;
	
	$chatid = $_SESSION['gid'];
	$gname = $_SESSION['gname'];  
	if(!isset($_SESSION['gid'])){  
		$chatid = 0;
		$gname = 'main_chat';
	}
	
	$results = mysql_query("SELECT * FROM ". $_G['mysql_prefix'] ."game_chat WHERE gid = $chatid AND id > ".$jal_lastID." ORDER BY id DESC");

	while ($row = mysql_fetch_array($results)) {

		$id   	= $row[0];
		$time	= $row[1];
		$gid 	= $row[2];
		$gname 	= $row[3];
		$name 	= $row[4];
		$text 	= $row[5];
		$url  	= $row[6];
		
		// append the new id's to the beginning of $loop
		$loop = $id."---".stripslashes($name)."---".stripslashes($text)."---".time_since($time)." ago---".stripslashes($url)."---" . $loop; // --- is being used to separate the fields in the output
	}
	echo $loop;
	
	// if there's no new data, send one byte. Fixes a bug where safari gives up w/ no data
	if (empty($loop)) { echo "0"; }
}

function jal_special_chars ($s) {
  $s = htmlspecialchars($s, ENT_COMPAT,'UTF-8');
  return str_replace("---","&minus;-&minus;",$s);
}

////////////////////////////////////////////////////////////
// Functions Below are for submitting comments to the database
////////////////////////////////////////////////////////////

// When user submits and javascript fails
if (isset($_POST['shout_no_js'])) {
	if ($_POST['shoutboxname'] != '' && $_POST['chatbarText'] != '') {
		jal_addData($_POST['shoutboxname'], $_POST['chatbarText'], $_POST['shoutboxurl']);
		
		//jal_deleteOld(); //some database maintenance
    	
    	setcookie("jalUserName",$_POST['shoutboxname'],time()+60*60*24*30*3,'/');
    	setcookie("jalUrl",$_POST['shoutboxurl'],time()+60*60*24*30*3,'/');
        //take them right back where they left off
		header('location: '.$_SERVER['HTTP_REFERER']);
	} else echo "You must have a name and a comment";
}

	//only if a name and a message have been provides the information is added to the db
if ($jal_user_name != '' && $jal_user_text != '' && $jalSendChat == "yes") {
		jal_addData($jal_user_name,$jal_user_text,$jal_user_url); //adds new data to the database
		//jal_deleteOld(); //some database maintenance
		echo "0";
}

function jal_addData($jal_user_name,$jal_user_text,$jal_user_url) {
	
	global $_G;
	
	//the message is cut of after 500 letters
	$jal_user_text = substr($jal_user_text,0,500); 
	
	$jal_user_name = substr(trim($jal_user_name), 0,18);

	///// The code below can mess up multibyte strings

	// If there isn't a url, truncate the words to 25 chars each
	//	if (!preg_match("`(http|ftp)+(s)?:(//)((\w|\.|\-|_)+)(/)?(\S+)?`i", $jal_user_text, $matches))
	//		$jal_user_text = preg_replace("/([^\s]{25})/","$1 ",$jal_user_text);

	// CENSORS .. default is off. To turn it on, uncomment the line below. Add new lines with new censors as needed.	
	//$jal_user_text = str_replace("fuck", "****", $jal_user_text);

	$jal_user_text = jal_special_chars(trim($jal_user_text));
	$jal_user_name = (empty($jal_user_name)) ? "Anonymous" : jal_special_chars($jal_user_name);
	$jal_user_url = ($jal_user_url == "http://") ? "" : jal_special_chars($jal_user_url);
	
    $chatid = $_SESSION['gid'];
	$gname = $_SESSION['gname'];  
	if(!isset($_SESSION['gid'])){  
		$chatid = 0;
		$gname = 'main_chat';
	}
    
	mysql_query("INSERT INTO ". $_G['mysql_prefix'] ."game_chat (time,gid,gname,name,text,url) VALUES ('".time()."',$chatid,'".mysql_real_escape_string($gname)."','".mysql_real_escape_string($jal_user_name)."','".mysql_real_escape_string($jal_user_text)."','".mysql_real_escape_string($jal_user_url)."')");
}

function jal_deleteOld() {
	global $_G;
	
    $chatid = $_SESSION['gid'];
	$gname = $_SESSION['gname'];  
	if(!isset($_SESSION['gid'])){  
		$chatid = 0;
		$gname = 'main_chat';
	}
	
	$results = mysql_query("SELECT * FROM ". $_G['mysql_prefix'] ."game_chat WHERE gid = $chatid ORDER BY id DESC LIMIT 30");	

	while ($row = mysql_fetch_array($results)) { $id = $row[0]; }

	if ($id) mysql_query("DELETE FROM ". $_G['mysql_prefix'] ."game_chat WHERE id < ".$id);
}


// Prints the html structure for the shoutbox
function jal_get_shoutbox( ) {
	global $_G;
	
	require('./includes/chat_config.php');	
	
	$user_nickname = $_SESSION['username'];
	
	$chatid = $_SESSION['gid'];
	$gname = $_SESSION['gname'];
	if(!isset($_SESSION['gid'])){  
		$chatid = 0;
		$gname = 'main_chat';
	}
	
	$results = get_results("SELECT * FROM ". $_G['mysql_prefix'] ."game_chat WHERE gid = $chatid ORDER BY id DESC LIMIT 30");								
	                       	
	// Will only add the last message div if it is looping for the first time
	$jal_first_time = true; 
	
	//Start of chat box
	$chat .= "<div id=\"chatoutput\">";
								
	// Loops the messages into a list
	if($results) {
    	foreach($results as $r ) { 
			// Add links								
			$r['text'] = preg_replace( "`(http|ftp)+(s)?:(//)((\w|\.|\-|_)+)(/)?(\S+)?`i", "<a href=\"\\0\">&laquo;link&raquo;</a>", $r['text']);

			if ($jal_first_time == true){ 
				$chat .= "<div id=\"lastMessage\"><span>Latest Message</span> <em id=\"responseTime\">".time_since( $r['time'] )." ago</em></div>
 						<ul id=\"outputList\">"; 
 			}
 						
 			if ($jal_first_time == true) $lastID = $r['id'];
 						
 			$url = (empty($r['url']) && $r['url'] = "http://") ? $r['name'] : '<a href="'.$r['url'].'">'.$r['name'].'</a>';

 			if(isset($_SESSION['gid'])){
	 			$bgcolor = 'black';
	 			/*
 				foreach($_SESSION['PLAYERS'] as $aplayer) {
            		if($aplayer['name'] == stripslashes($url))
                		$bgcolor = $aplayer['pcolor'];
				}*/
			} else {
				$bgcolor = 'yellow';
			}
			
			if (!empty($user_nickname)) { /* If they are logged in */
				$chat .= "<li><span title=\"".time_since( $r['time'] )."\" style=\"color: ".$bgcolor."; font-weight: bold;\">".stripslashes($url)."</span>: ".convert_smilies(" ".stripslashes($r['text']))."</li>"; 
			} else {
				$chat .= "<li>You Must log into see and read the chat</li>";
				break;
			}
				
							        
			$jal_first_time = false; } 
					
			// If there is less than one entry in the box
			$chat .= "</ul></div>";	
	} else {
		$chat .= "No messages in chat yet. <b>Be the first!</b>";
		$chat .= "</ul></div>";	
	}
					
	$use_url = (get_option('shoutbox_use_url') == "true") ? TRUE : FALSE;
	$use_textarea = (get_option('shoutbox_use_textarea') == "true") ? TRUE : FALSE;
	$registered_only = (get_option('shoutbox_registered_only') == "1") ? TRUE : FALSE;
				
	
	if (!empty($user_nickname)) { /* If they are logged in */
		$chat .= "<br />";
		$chat .= "<form id=\"chatForm\" method=\"post\" action=\"./includes/chat.php\">";
		$chat .= "<input type=\"hidden\" name=\"shoutboxname\" id=\"shoutboxname\" value=\"{$user_nickname}\" />";
		$chat .= "<input type=\"hidden\" name=\"shoutboxurl\" id=\"shoutboxurl\" value=\"{$user_url}\" />";
		
		if ($use_textarea){
			$chat .= '<textarea rows="2" cols="14" name="chatbarText" id="chatbarText" onkeypress="return pressedEnter(this,event);"></textarea>';
		} else {
			$chat .= "<input type=\"text\" name=\"chatbarText\" id=\"chatbarText\" />";
		}
		
		$NextID = $lastID + 1;
		$chat .= "<input type=\"hidden\" id=\"jal_lastID\" value=\"{$NextID}\" name=\"jal_lastID\" />";
		$chat .= "<input type=\"hidden\" name=\"shout_no_js\" value=\"true\" />";
		$chat .= "<input type=\"submit\" id=\"submitchat\" name=\"submit\" value=\"Send\" />";
		$chat .= "</form>";
			
	} else {
		$chat .= "You need to be logged in post a chat message";
	}
	
	
	return $chat;
}


 ?>
