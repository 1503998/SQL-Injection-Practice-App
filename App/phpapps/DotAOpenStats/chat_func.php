<?php
function clickable_link($text)
{
$text = preg_replace('#(script|about|applet|activex|chrome):#is', "\\1:", $text);
$ret = ' ' . $text;
$ret = preg_replace("#(^|[\n ])([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $ret);
$ret = preg_replace("#(^|[\n ])((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $ret);
$ret = preg_replace("#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $ret);
$ret = substr($ret, 1);
return $ret;
}
function shortURLS($text){
$text = preg_replace("/((<a[^>]+>)(https?:\/\/)?[a-z0-9]+[a-z0-9\.\-]+?\.([a-z]{2,4}([a-z]{2})?)\/)(.*?)(.{5}<\/a>)/im", "$1...$7", $text);
return $text;
}
function refineUrls($inText){
    // define an url regular exression pattern:
    $urlPattern = "/(https?:\/\/|www.)(www.)?([-a-z0-9]*[a-z0-9]\.)(\bcom\b|\bbiz\b|\bgov\b|\bmil\b|\bnet\b|\borg\  b|[a-z][a-z]|[a-z][a-z]\.[a-z][a-z])\/?([a-zA-Z0-9]*)?([a-zA-Z0-9_\-\.\?=\/&%#]*)?/";
    // get all matches
    preg_match_all($urlPattern, $inText, $temp);
    $temp = $temp[0]; // an array of all urls

    $urlTag = array();
    foreach ($temp as &$url){
        $urlShort = str_replace("http://", "",$url);
        while (strlen($urlShort) > 35){ // limit is set to 35
            if(is_bool(strpos($urlShort,"/"))){ // are there any natural places to cut the link?
                $urlShort = substr($urlShort,0,30);
            } else { // find a good place to cut the link
                $newLength = max(strrpos($urlShort,"/"),strrpos($urlShort,"?"),strrpos($urlShort,"#"));
                $urlShort = substr($urlShort, 0, $newLength);
            }
            $urlShort = $urlShort."...";
        }
        $urlTag[] = "<a title=\"$url\" href=\"$url\" target=\"_blank\">$urlShort</a>";
    }
    if(0 < sizeof($temp)){
        // if there are any urls in the text, replace them with the new code
        return strtr($inText, array_combine($temp, $urlTag));
    } else {
        return $inText;
    }
} 

function smilies($text, $url_address_to_images_folder) // with '/' at the end
{
$array = array(
':)' =>  'smile.gif', // happy 
':-)' =>  'smile.gif', // happy 
':-D' => 'D.gif', // very happy 
':D' =>  'D.gif', // very happy
':O' => 'eek.gif', // surprised / o, no   
':-P' => 'razz.gif', // tongue sticking out
':P' =>  'razz.gif', // tongue sticking out 
':p' =>  'razz.gif', // tongue sticking out 
';)' => 'wink.gif', // wink 
';D' =>  'wink.gif', // wink
':-(' => 'sad.gif', // sad 
':(' =>  'sad.gif', // sad
':angry:' => 'twisted.gif', // icon_twisted 
':bravo:' => 'aplauz.gif', // icon_aplauz 
':gamer:' => 'gamer.gif', // gamer 
':rambo:' => 'rambo.gif', // rambo 
':love:'=> 'wub.gif', // heart 
':hi:' => 'hi.gif', // hi
':evil:' => 'evil.gif', // evil
':heh:'=> 'kreza.gif', // kreza
':unworthy:' => 'unworthy.gif', // unworthy
':applause:' => 'icon_tapsh.gif', // icon_tapsh
':zzz:' => 'zzz.gif', // sleeping
':facepalm:' => 'isuse.gif', // facepalm
':rofl:' => 'biglaugh.gif', // rofl
':study:' => 'icon_study.gif', // icon_study
':omg:' => 'icon_boodala.gif', // icon_boodala.gif
':cool:' => 'qliranje.gif' // icon_qliranje
); 


foreach($array as $s => $xc)
{
$text = str_replace($s, "<img alt='' style='vertical-align:middle;' border=0 src='".$url_address_to_images_folder.$xc."' />", $text);
}
return $text;		
}
 
?>