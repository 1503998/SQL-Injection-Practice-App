<?php

include("ajaxinclude.php");

	//
        //START DATA VALIDATION AND SUBMISSION
        //
        //$pattern = '/.*@.*\..*/';
        $pattern = '/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i';

            //VALIDATE ENTERED FORM DATA
            $iconquery = mysql_query("SELECT * FROM " . $prefix . "settings");
            while($icon = mysql_fetch_assoc($iconquery))
            {
		global $dbiconwidth;
		global $dbiconheight;
                $dbiconwidth = $icon['maxiconwidth'];
                $dbiconheight = $icon['maxiconheight'];
            }

            //VALIDATE USER ICON WIDTH
            if($_POST['icon_width'] > $dbiconwidth)
            {
                echo '<font color="#FF0000"><b>Your User Icon Width must not exceed ' . $dbiconwidth . '.</b></font><br>';
                $icon_width_error = 'style="border: 2px solid #FF0000"';
            }
            //VALIDATE USER ICON HEIGHT
            elseif($_POST['icon_height'] > $dbiconheight)
            {
                echo '<font color="#FF0000"><b>Your User Icon Height must not exceed ' . $dbiconheight . '.</b></font><br>';
                $icon_height_error = 'style="border: 2px solid #FF0000"';
            }
            //ALL DATA IS OK, SAVE TO DATABASE
            else
            {
		//RESIZE USER ICON TO FIT MAX SPECIFICATIONS
		if($_POST['icon_url'] != '')
		{
			$srcimg = imagecreatefromjpeg($_POST['icon_url']);
			$iconwidth = imagesx($srcimg);
			$iconheight = imagesy($srcimg);
			$ratio = $iconwidth / $iconheight;
			$desratio = $dbiconwidth / $dbiconheight;

			if($desratio > $ratio)
			{
				$iconheight = $dbiconheight;
				$iconwidth = $dbiconheight * $ratio;
			}else
			{
				$iconwidth = $dbiconwidth;
				$iconheight = $dbiconwidth / $ratio;
			}
		}else
		{
			$iconwidth = '';
			$iconheight = '';
		}
                
                //SAVE VALIDATED INFORMATION TO THE DATABASE
                if($_SESSION['sr'] == '2')
                {
                    //MAKE CHANGES IN DATABASE TO MEMBER'S PROFILE
                    if($_POST['usecookie'] == 'true'){ $setcookie = 1; }else{ $setcookie = 0; }

			//FILTER USER INPUT
			$realname = htmlspecialchars($_POST['realname']);
			$displayname = htmlspecialchars($_POST['formdisplayname']);
			$icon_url = htmlspecialchars($_POST['icon_url']);
			$aim_id = htmlspecialchars($_POST['aim_id']);
			$msn_id = htmlspecialchars($_POST['msn_id']);
			$yahoo_id = htmlspecialchars($_POST['yahoo_id']);
			$icq_id = htmlspecialchars($_POST['icq_id']);
			$homepage = htmlspecialchars($_POST['homepage']);
			$homepage_link = htmlspecialchars($_POST['homepage_link']);
			$location = htmlspecialchars($_POST['location']);
			$signature = htmlspecialchars($_POST['signature']);
			$otherinfo = htmlspecialchars($_POST['otherinfo']);
			$tperpage = (int)$_POST['tperpage'];
			$mperpage = (int)$_POST['mperpage'];

                    mysql_query("UPDATE " . $prefix . "members SET realname = '$realname', displayname = '$displayname', icon_url = '$icon_url', aim_id = '$aim_id', msn_id = '$msn_id', yahoo_id = '$yahoo_id', icq_id = '$icq_id', homepage = '$homepage', homepage_link = '$homepage_link', location = '$location', timediff = '{$_POST['timediff']}', signature = '$signature', usecookie = '$setcookie', otherinfo = '$otherinfo', icon_width = '$iconwidth', icon_height = '$iconheight', messageeditor = '{$_POST['messageeditor']}', tperpage = '$tperpage', mperpage = '$mperpage' WHERE memberid='{$_SESSION['memberid']}'");

                    if($_POST['formpw'] != '' && $_POST['formpw'] == $_POST['formpwconfirm'])
                    {
			$newuserpass = MD5($_POST['formpw']);
                        mysql_query("UPDATE " . $prefix . "members SET pw = '$newuserpass' WHERE memberid = '{$_SESSION['memberid']}'") OR DIE("Gravity Board X was unable to change your password: " . mysql_error());
                    }

                    $pw = $_POST['formpw'];
                    if(isset($_COOKIE['gbx']))
                    {
                        $_COOKIE['gbx']['pw'] = $pwform;
                    }
                    	echo '<span class="errortext">Profile changes saved successfully.</span>';
                    }else
                    {
                        echo '<span class="errortext">Access Denied. You must be logged in to edit your profile.</span>';
                    }
                }
            //
            //END DATA VALIDATION AND SUBMISSION
            //

mysql_close();

?>