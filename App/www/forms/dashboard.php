<?php

if($_SESSION['sr'] == '2')
{

?>

<div id="your_info">
  <div class="station">

<div class="boxheader">
    <span class="headerfont">Your Information</span>
</div>

<div class="content">

<?php

    $pcquery = mysql_query("SELECT COUNT(*) FROM " . $prefix . "posts WHERE memberid = '{$_SESSION['memberid']}'") OR DIE("Gravity Board X was unable to locate your post count: " . mysql_error());
    list($pc) = mysql_fetch_row($pcquery);
    $miquery = mysql_query("SELECT * FROM " . $prefix . "members WHERE memberid = '{$_SESSION['memberid']}'") OR DIE ("Gravity Board X was unable to locate your member info: " . mysql_error());

    while($mi = mysql_fetch_assoc($miquery))
    {
        $mgquery = mysql_query("SELECT * FROM " . $prefix . "membergroups WHERE group_id = '$mi[memberGroup]'") OR DIE("Gravity Board X was unable to verify your member group: " . mysql_error());
        while($mg = mysql_fetch_assoc($mgquery))
        {

rank($_SESSION['memberid']);

?>

    <span><b><span class="post_username" style="color: <?php echo $userRank['color']; ?>;"><?php echo $mi['displayname']; ?></span></b>
    <br/>
    <?php echo $mg['group_name']; ?>
    <br/>
    <?php echo $userRank['rank']; ?>
    <br/>
    <font class="small">
    Member #:</b> <?php echo $_SESSION['memberid']; ?>
    <br/>
    Posts: <?php echo $pc; ?>
    <br/>
    Registered: <?php echo date("m/d/Y h:i:s A",$mi['dateregistered'] + $timeadjust); ?>
    <br/>
    Time Zone: <?php
    
            if($_SESSION['timediff'] == '-12'){ echo '(GMT -12:00)';
            }elseif($_SESSION['timediff'] == '-11'){ echo '(GMT -11:00)';
            }elseif($_SESSION['timediff'] == '-10'){ echo '(GMT -10:00)';
            }elseif($_SESSION['timediff'] == '-9'){ echo '(GMT -9:00)';
            }elseif($_SESSION['timediff'] == '-8'){ echo '(GMT -8:00)';
            }elseif($_SESSION['timediff'] == '-7'){ echo '(GMT -7:00)';
            }elseif($_SESSION['timediff'] == '-6'){ echo '(GMT -6:00)';
            }elseif($_SESSION['timediff'] == '-5'){ echo '(GMT -5:00)';
            }elseif($_SESSION['timediff'] == '-4'){ echo '(GMT -4:00)';
            }elseif($_SESSION['timediff'] == '-3'){ echo '(GMT -3:00)';
            }elseif($_SESSION['timediff'] == '-2'){ echo '(GMT -2:00)';
            }elseif($_SESSION['timediff'] == '-1'){ echo '(GMT -1:00)';
            }elseif($_SESSION['timediff'] == '0'){ echo '(GMT)';
            }elseif($_SESSION['timediff'] == '1'){ echo '(GMT 1:00)';
            }elseif($_SESSION['timediff'] == '2'){ echo '(GMT 2:00)';
            }elseif($_SESSION['timediff'] == '3'){ echo '(GMT 3:00)';
            }elseif($_SESSION['timediff'] == '4'){ echo '(GMT 4:00)';
            }elseif($_SESSION['timediff'] == '5'){ echo '(GMT 5:00)';
            }elseif($_SESSION['timediff'] == '6'){ echo '(GMT 6:00)';
            }elseif($_SESSION['timediff'] == '7'){ echo '(GMT 7:00)';
            }elseif($_SESSION['timediff'] == '8'){ echo '(GMT 8:00)';
            }elseif($_SESSION['timediff'] == '9'){ echo '(GMT 9:00)';
            }elseif($_SESSION['timediff'] == '10'){ echo '(GMT 10:00)';
            }elseif($_SESSION['timediff'] == '11'){ echo '(GMT 11:00)';
            }elseif($_SESSION['timediff'] == '12'){ echo '(GMT 12:00)';
            }elseif($_SESSION['timediff'] == '13'){ echo '(GMT 13:00)';
            }
            
?>

    <br/>
    Permissions:
    
<?php

            if($_SESSION['perm'] == '1') { echo 'Administrator'; }elseif($_SESSION['perm'] == '2') { echo 'Moderator'; }else{ echo 'Regular Member'; }

?>
    <br/>
    PMs Sent: <?php echo $mi['pmssent']; ?>
    <br/>
    Boards Viewed: <?php echo $mi['boardviews']; ?>
    <br/>
    Threads Read: <?php echo $mi['threadviews']; ?>
    <br/>
    Messages Read: <?php echo $mi['messageviews']; ?>
    <br/>
    PMs Read: <?php echo $mi['pmsread']; ?>
    <br/>
    Total Clicks: <?php echo $mi['totalclicks']; ?>
    </font>
    </span>

<?php

        }
    }

?>

</div>

<div class="boxfooter"
</div>

  </div>
</div>

<?php

}

?>
