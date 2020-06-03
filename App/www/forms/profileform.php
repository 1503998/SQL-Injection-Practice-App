<?php

if($_SESSION['sr'] == '2')
{
    $infoquery = mysql_query("SELECT * FROM " . $prefix . "members WHERE memberid = '{$_SESSION['memberid']}'") OR DIE("Gravity Board X was unable to retrieve your profile information: " . mysql_error());
    while($info = mysql_fetch_assoc($infoquery))
    {
        //SET TEMPORARY VARIABLE VALUES FOR THIS FORM
        global $tdisplayname;
        $tmemberid = $info['memberid'];
        $tdateregistered = $info['dateregistered'];
        $trealname = $info['realname'];
        $tdisplayname = $info['displayname'];
        $ticon_url = $info['icon_url'];
        $temail = $info['email'];
        $taim_id = $info['aim_id'];
        $tmsn_id = $info['msn_id'];
        $tyahoo_id = $info['yahoo_id'];
        $ticq_id = $info['icq_id'];
        $thomepage_link = $info['homepage_link'];
        $tlocation = $info['location'];
        $ttimediff = $info['timediff'];
        $tsignature = $info['signature'];
        $totherinfo = $info['otherinfo'];
        $ttperpage = $info['tperpage'];
        $tmperpage = $info['mperpage'];
        $tusecookie = $info['usecookie'];
        $tmessageeditor = $info['messageeditor'];
        
        //SET TEMPORARY ERROR VARIABLES
        $displayname_error = '';
        $tperpage_error = '';
        $mperpage_error = '';
    }

?>

<script type="text/javascript" language="JavaScript">
var rules=new Array();
rules[0]='formdisplayname|required|Please enter a display name.';
rules[1]='formdisplayname|maxlength|14|Display Name must be 14 characters or less.';
rules[2]='tperpage|numrange|10-100|Threads per page must be between 10 and 100.';
rules[3]='mperpage|numrange|10-50|Messages per page must be between 10 and 50.';
</script>

<div class="headermid">

<div class="header">
  <span class="headerfont">Edit Profile</span>
</div>

<div class="content">

<div id="ajaxStatus"></div>
<div id="errorsDiv"></div>

<table>
<form name="profileform">
  <tr>
  
    <td colspan=4>
    <p align="left">

</p>
</td>
</tr>
  <tr>
    
    <td width="35%">
      <p align="right">Member ID
    </td>
    
    <td width="65%">
      <p align="left"><?php echo $tmemberid; ?>
    </td>
    
  </tr>
  <tr>
    
    <td width="35%">
      <p align="right">Date Registered
    </td>
    
    <td width="65%">
      <p align="left"><?php echo date("M d, Y h:i:s A",$tdateregistered + $timeadjust); ?>
    </td>
    
  </tr>
  <tr>
    
    <td width="35%">
      <p align="right">Real Name
    </td>
    
    <td width="65%">
      <input type="text" class="textbox" id="realname" size="30" value="<?php echo $trealname; ?>">
    </td>
    
  </tr>
  <tr>

    <td width="35%">
      <p align="right">Display Name
    </td>
    
    <td width="65%">
      <input type="text" class="textbox" id="formdisplayname" size="30" maxlength="14" value="<?php if(!isset($_POST['formsent'])){ echo $tdisplayname; }elseif(isset($_POST['formsent'])){ echo $_POST['formdisplayname']; } ?>"<?php echo $displayname_error; ?>>
    </td>
    
  </tr>
  <tr>
    
    <td width="35%">
      <p align="right">Password
    </td>
    
    <td width="65%">
      <input type="password" class="textbox" id="formpw" size="30" maxlength="20">
    </td>
    
  </tr>
  <tr>

    <td width="35%">
      <p align="right">Confirm Password
    </td>

    <td width="65%">
      <input type="password" class="textbox" id="formpwconfirm" size="30" maxlength="20">
    </td>

  </tr>
  <tr>
    
    <td width="35%">
      <p align="right">User Icon URL
    </td>
    
    <td width="65%">
      <input type="text" class="textbox" id="icon_url" size="30" value="<?php echo $ticon_url; ?>">
    </td>
    
  </tr>
  <tr>
    
    <td width="35%">
      <p align="right">E-Mail
    </td>
    
    <td width="65%">
      <p><?php echo $temail; ?> <a href="?action=changeemail">(change)</a></p>
    </td>
    
  </tr>
  <tr>
    
    <td width="35%">
      <p align="right">AIM
    </td>
    
    <td width="65%">
      <input type="text" class="textbox" id="aim_id" size="30" value="<?php echo $taim_id; ?>"><img src="images/aimsmall.gif" alt="AIM" />
    </td>
    
  </tr>
  <tr>

    <td width="35%">
      <p align="right">MSN
    </td>
    
    <td width="65%">
      <input type="text" class="textbox" id="msn_id" size="30" value="<?php echo $tmsn_id; ?>"><img src="images/msnsmall.gif" alt="MSN Messenger" />
    </td>
    
  </tr>
  <tr>
    
    <td width="35%">
      <p align="right">Yahoo!
    </td>
    
    <td width="65%">
      <input type="text" class="textbox" id="yahoo_id" size="30" value="<?php echo $tyahoo_id; ?>"><img src="images/yahoosmall.gif" alt="Yahoo! Instant Messenger" />
    </td>
    
  </tr>
  <tr>
    
    <td width="35%">
      <p align="right">ICQ
    </td>
    
    <td width="65%">
      <input type="text" class="textbox" id="icq_id" size="30" value="<?php echo $ticq_id; ?>"><img src="images/icqsmall.gif" alt="ICQ" />
    </td>
    
  </tr>
  <tr>
  
    <td width="35%">
      <p align="right">Personal Website URL
    </td>
    
    <td width="65%">
      <input type="text" class="textbox" id="homepage_link" size="30" value="<?php echo $thomepage_link; ?>"><img src="images/www.gif" alt="Homepage" />
    </td>
    
  </tr>
  <tr>
  
    <td width="35%">
      <p align="right">Location
    </td>
    
    <td width="65%">
      <input type="text" class="textbox" id="location" size="30" value="<?php echo $tlocation; ?>">
    </td>
    
  </tr>
  <tr>
  
    <td width="35%">
      <p align="right">Time Difference
    </td>
    
    <td width="65%">
    <select id="timediff" class="textbox">
      <option value="-12" <?php if($ttimediff == '-12'){ echo 'selected'; } ?>>(GMT -12:00) Eniwetok, Kwajalein</option>
      <option value="-11" <?php if($ttimediff == '-11'){ echo 'selected'; } ?>>(GMT -11:00) Midway Island, Samoa</option>
      <option value="-10" <?php if($ttimediff == '-10'){ echo 'selected'; } ?>>(GMT -10:00) Hawaii</option>
      <option value="-9" <?php if($ttimediff == '-9'){ echo 'selected'; } ?>>(GMT -9:00) Alaska</option>
      <option value="-8" <?php if($ttimediff == '-8'){ echo 'selected'; } ?>>(GMT -8:00) Pacific Time (US & Canada), Tijuana</option>
      <option value="-7" <?php if($ttimediff == '-7'){ echo 'selected'; } ?>>(GMT -7:00) Mountain Time (US & Canada)</option>
      <option value="-6" <?php if($ttimediff == '-6'){ echo 'selected'; } ?>>(GMT -6:00) Central Time (US & Canada)</option>
      <option value="-5" <?php if($ttimediff == '-5'){ echo 'selected'; } ?>>(GMT -5:00) Eastern Time (US & Canada)</option>
      <option value="-4" <?php if($ttimediff == '-4'){ echo 'selected'; } ?>>(GMT -4:00) Atlantic Time (Canada)</option>
      <option value="-3" <?php if($ttimediff == '-3'){ echo 'selected'; } ?>>(GMT -3:00) Buenos Aires, Georgetown</option>
      <option value="-2" <?php if($ttimediff == '-2'){ echo 'selected'; } ?>>(GMT -2:00) Mid-Atlantic</option>
      <option value="-1" <?php if($ttimediff == '-1'){ echo 'selected'; } ?>>(GMT -1:00) Azores</option>
      <option value="0" <?php if($ttimediff == '0'){ echo 'selected'; } ?>>(GMT) Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London</option>
      <option value="1" <?php if($ttimediff == '1'){ echo 'selected'; } ?>>(GMT +1:00) Berlin, Paris</option>
      <option value="2" <?php if($ttimediff == '2'){ echo 'selected'; } ?>>(GMT +2:00) Athens, Cairo</option>
      <option value="3" <?php if($ttimediff == '3'){ echo 'selected'; } ?>>(GMT +3:00) Baghdad, Moscow</option>
      <option value="4" <?php if($ttimediff == '4'){ echo 'selected'; } ?>>(GMT +4:00) Abu Dhabi, Muscat</option>
      <option value="5" <?php if($timediff == '5'){ echo 'selected'; } ?>>(GMT +5:00) Islamabad, Karachi</option>
      <option value="6" <?php if($timediff == '6'){ echo 'selected'; } ?>>(GMT +6:00) Almaty, Novosibirsk</option>
      <option value="7" <?php if($ttimediff == '7'){ echo 'selected'; } ?>>(GMT +7:00) Bangkok, Hanoi</option>
      <option value="8" <?php if($ttimediff == '8'){ echo 'selected'; } ?>>(GMT +8:00) Beiijing, Hong Kong</option>
      <option value="9" <?php if($ttimediff == '9'){ echo 'selected'; } ?>>(GMT +9:00) Seoul, Tokyo</option>
      <option value="10" <?php if($ttimediff == '10'){ echo 'selected'; } ?>>(GMT +10:00) Guam, Sydney</option>
      <option value="11" <?php if($ttimediff == '11'){ echo 'selected'; } ?>>(GMT +11:00) Magadan, Solomon Is., New Caledonia</option>
      <option value="12" <?php if($ttimediff == '12'){ echo 'selected'; } ?>>(GMT +12:00) Fiji, Kamchatka</option>
      <option value="13" <?php if($ttimediff == '13'){ echo 'selected'; } ?>>(GMT +13:00) Nuku'alofa</option>
    </select>
    </td>
    
  </tr>
  <tr>
    
    <td width="35%">
      <p align="right">Signature
    </td>
    
    <td width="65%">
      <textarea id="signature" class="textbox" rows="6" cols="55"><?php echo stripslashes($tsignature); ?></textarea>
    </td>
    
  </tr>
  <tr>
  
    <td width="35%">
      <p align="right">Other Information
    </td>
    
    <td width="65%">
      <textarea id="otherinfo" class="textbox" rows="10" cols="55"><?php echo stripslashes($totherinfo); ?></textarea>
    </td>
    
  </tr>
  <tr>

    <td width="35%">
      <p align="right">Keep Me Logged In (Uses Cookies)
    </td>

<?php

    if($tusecookie == 1 || isset($_POST['usecookie']))
    {
        $checked = 'checked';
    }else{
        $checked = '';
    }

?>

    <td width="65%">
      <input type="checkbox" id="usecookie"  <?php echo $checked; ?>>
    </td>

  </tr>
  <tr>

    <td width="35%">
      <p align="right">Message Editor
    </td>

    <td width="65%">
      <select id="messageeditor" class="textbox">
      <option value="1" <?php if($tmessageeditor == '1'){ echo 'selected'; } ?>>WYSIWYG Graphical Editor</option>
      <option value="0" <?php if($tmessageeditor == '0'){ echo 'selected'; } ?>>Plain text editor</option>
      </select>
    </td>

  </tr>
  <tr>

    <td width="35%">
      <p align="right">Threads per page
    </td>

    <td width="65%">
      <input type="text" class="textbox" id="tperpage" size="5" value="<?php if(!isset($_POST['formsent'])){ echo $ttperpage; }elseif(isset($_POST['formsent'])){ echo $_POST['tperpage']; } ?>"<?php echo $tperpage_error; ?>>
    </td>

  </tr>
  <tr>

    <td width="35%">
      <p align="right">Messages per page
    </td>

    <td width="65%">
      <input type="text" class="textbox" id="mperpage" size="5" value="<?php if(!isset($_POST['formsent'])){ echo $tmperpage; }elseif(isset($_POST['formsent'])){ echo $_POST['mperpage']; } ?>"<?php echo $mperpage_error; ?>>
    </td>

  </tr>
  <tr>
    
    <td width="35%">
    </td>
    
    <td width="65%">
      <p align="center"><button type="button" class="button" name="submit" onClick="gbxAjaxReq('profileform', true, 'forms/ajax/updateprofile.php', 'realname=' + document.getElementById('realname').value + '&formdisplayname=' + document.getElementById('formdisplayname').value + '&formpw=' + document.getElementById('formpw').value + '&formpwconfirm=' + document.getElementById('formpwconfirm').value + '&icon_url=' + document.getElementById('icon_url').value + '&aim_id=' + document.getElementById('aim_id').value + '&msn_id=' + document.getElementById('msn_id').value + '&yahoo_id=' + document.getElementById('yahoo_id').value + '&icq_id=' + document.getElementById('icq_id').value + '&homepage_link=' + document.getElementById('homepage_link').value + '&location=' + document.getElementById('location').value + '&timediff=' + document.getElementById('timediff').value + '&signature=' + document.getElementById('signature').value + '&otherinfo=' + document.getElementById('otherinfo').value + '&usecookie=' + document.getElementById('usecookie').checked + '&messageeditor=' + document.getElementById('messageeditor').value + '&tperpage=' + document.getElementById('tperpage').value + '&mperpage=' + document.getElementById('mperpage').value);">Save Changes</button>
    </td>
    
  </tr>
</form>
</table>

  </div>

  <div class="headerbot">
  </div>

</div>

<?php

}else
{
    echo '<font color="#FF0000">Access denied. You must be logged in to edit your profile. If you do not have an account, you may <a href="index.php?action=register">register here</a>.</font>';
}

?>
