<div id="users_online">
  <div class="station">

<div class="boxheader">
    <span class="headerfont">Users Online</span>
</div>

<div class="content">

<?php

$exptime = time() - 600;
$guestquery = mysql_query("SELECT COUNT(*) FROM " . $prefix . "online WHERE lastactive >= $exptime AND memberid = '' GROUP BY ip_address") OR DIE("Gravity Board X was unable to count the number of guests online: " . mysql_error());
$memberquery = mysql_query("SELECT COUNT(*) FROM " . $prefix . "online WHERE lastactive >= $exptime AND memberid != '' GROUP BY ip_address") OR DIE("Gravity Board X was unable to count the number of members online: " . mysql_error());
$recentonline = mysql_query("SELECT * FROM " . $prefix . "online WHERE lastactive >= $exptime AND memberid != '' ORDER BY firstonline DESC LIMIT 30") OR DIE("Gravity Board X was unable to locate the most recent members online: " . mysql_error());

list($guestsonline) = mysql_fetch_row($guestquery);
list($membersonline) = mysql_fetch_row($memberquery);

$totalonline = $guestsonline + $membersonline;

echo '<font class="small">Users Online: ' . $totalonline . '<br/>';
echo 'Guests: ' . $guestsonline . '<br/>';
echo 'Members: ' . $membersonline . '<br/>';
echo 'Most Recent Online: <br/>';

while($recent = mysql_fetch_assoc($recentonline))
{
	$dnquery = mysql_query("SELECT displayname FROM " . $prefix . "members WHERE memberid = '{$recent['memberid']}'");
	list($displayname) = mysql_fetch_row($dnquery);
	rank($recent['memberid']);
	echo '<a href="?action=viewprofile&member_id=' . $recent['memberid'] . '"><span class="membername" style="color: ' . $userRank['color'] . ';">' . $displayname . '</span></a>&nbsp;&nbsp;&nbsp;';
}

?>

</font>

</div>

<div class="boxfooter"
</div>

  </div>
</div>