<?php
include('../includes/config.php');

function get_option($in_str) {
	if(!isset($_SESSION['gid'])){
	    $fade_to = '000000';	
	} else {
		$fade_to = 'FFF4D1';	
	}
	
  switch($in_str) {
	   case 'shoutbox_text_color'     : return '000000';
	   case 'shoutbox_fade_to'        : return $fade_to;
       case 'shoutbox_name_color'     : return $_SESSION['player_color'];
       case 'shoutbox_fade_from'      : return 'F1D16C';
       case 'shoutbox_fade_length'    : return 1500;
       case 'shoutbox_update_seconds' : return 4;
       case 'shoutbox_use_url'        : return false;
       case 'shoutbox_use_textarea'   : return true;
       case 'shoutbox_registered_only': return false;
       case 'use_smilies'             : return true;
  }
}

function get_currentuserinfo() {
  $user_nickname = $_SESSION['username'];
  return $user_nickname;
}

function get_results($string) {

  	$q = mysql_query($string) or die('Failed to get assoc_array: '.mysql_error());

  	while($row = mysql_fetch_assoc($q)) {
    	$result[] = $row; 
    }    
  return $result;
}

function smile_location($smile_file) {
  return "<img src=\"images/smilies/".$smile_file."\" alt=\"image\" />";
}

function convert_smilies($text) {
    $wp_smiliessearch = array(':)',
                              ':-)',
                              ':smile:',
                              ':D',
                              ':-D',
                              ':grin:',
                              ':(',
                              ':-(',
                              ':sad:',
                              ':o',
                              ':-o',
                              '8o',
                              '8-o',
                              ':eek:',
                              ':s',
                              ':-s',
                              ':lol:',
                              ':cool:',
                              '8)',
                              '8-)',
                              ':x',
                              ':-x',
                              ':mad:',
                              ':p',
                              ':-p',
                              ':razz:',
                              ':$',
                              ':-$',
                              ':\'(',
                              ':evil:',
                              ':twisted:',
                              ':cry:',
                              ':roll:',
                              ':wink:',
                              ';)',
                              ';-)',
                              ':!:',
                              ':?',
                              ':-?',
                              ':idea:',
                              ':arrow:',
                              ':|',
                              ':neutral:',
                              ':-|',
                              ':mrgreen:');

    $wp_smiliesreplace = array(smile_location('icon_smile.gif'),
                               smile_location('icon_smile.gif'), 
                               smile_location('icon_smile.gif'), 
                               smile_location('icon_biggrin.gif'),
                               smile_location('icon_biggrin.gif'),
                               smile_location('icon_biggrin.gif'),
                               smile_location('icon_sad.gif'),
                               smile_location('icon_sad.gif'),
                               smile_location('icon_sad.gif'),
                               smile_location('icon_surprised.gif'),
                               smile_location('icon_surprised.gif'),
                               smile_location('icon_eek.gif'),
                               smile_location('icon_eek.gif'),
                               smile_location('icon_eek.gif'),
                               smile_location('icon_confused.gif'),
                               smile_location('icon_confused.gif'),
                               smile_location('icon_lol.gif'),
                               smile_location('icon_cool.gif'),
                               smile_location('icon_cool.gif'),
                               smile_location('icon_cool.gif'),
                               smile_location('icon_mad.gif'),
                               smile_location('icon_mad.gif'),
                               smile_location('icon_mad.gif'),
                               smile_location('icon_razz.gif'),
                               smile_location('icon_razz.gif'),
                               smile_location('icon_razz.gif'),
                               smile_location('icon_redface.gif'),
                               smile_location('icon_redface.gif'),
                               smile_location('icon_cry.gif'),
                               smile_location('icon_evil.gif'),
                               smile_location('icon_twisted.gif'),
                               smile_location('icon_cry.gif'),
                               smile_location('icon_rolleyes.gif'),
                               smile_location('icon_wink.gif'),
                               smile_location('icon_wink.gif'),
                               smile_location('icon_wink.gif'),
                               smile_location('icon_exclaim.gif'),
                               smile_location('icon_question.gif'),
                               smile_location('icon_question.gif'),
                               smile_location('icon_idea.gif'),
                               smile_location('icon_arrow.gif'),
                               smile_location('icon_neutral.gif'),
                               smile_location('icon_neutral.gif'),
                               smile_location('icon_neutral.gif'),
                               smile_location('icon_mrgreen.gif'));

    $output = '';
    if (get_option('use_smilies')) {
        // HTML loop taken from texturize function, could possible be consolidated
        $textarr = preg_split("/(<.*>)/U", $text, -1, PREG_SPLIT_DELIM_CAPTURE); // capture the tags as well as in between
        //print_r($textarr);
        $stop = count($textarr);// loop stuff
        for ($i = 0; $i < $stop; $i++) {
            $content = $textarr[$i];
            if ((strlen($content) > 0) && ('<' != $content{0})) { // If it's not a tag
                $content = str_replace($wp_smiliessearch, $wp_smiliesreplace, $content);
            }
            $output .= $content;
        }
    } else {
        // return default text.
        $output = $text;
    }
    return $output;
}

?>