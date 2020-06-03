<div id="board_stats">
  <div class="station">

<div class="boxheader">
    <span class="headerfont">Live Board Statistics</span>
</div>

<div class="content">

<?php

if($_SESSION['sr'] == 2)
{
    $status = 'Member';
}else
{
    $status = 'Guest';
}

echo '<span class="small">Your Status: ' . $status . '<br/>';

$tpcquery = mysql_query("SELECT COUNT(*) FROM " . $prefix . "posts") OR DIE("Gravity Board X was unable to find the total amount of posts: " . mysql_error());
$ttcquery = mysql_query("SELECT COUNT(*) FROM " . $prefix . "threads") OR DIE("Gravity Board X was unable to find the total amount of threads: " . mysql_error());
$tccquery = mysql_query("SELECT COUNT(*) FROM " . $prefix . "categories") OR DIE("Gravity Board X was unable to find the total amount of categories: " . mysql_error());
$tbcquery = mysql_query("SELECT COUNT(*) FROM " . $prefix . "boards") OR DIE("Gravity Board X was unable to find the total amount of boards: " . mysql_error());
$tmcquery = mysql_query("SELECT COUNT(*) FROM " . $prefix . "members") OR DIE("Gravity Board X was unable to find the total amount of members: " . mysql_error());
$statquery = mysql_query("SELECT * FROM " . $prefix . "stats") OR DIE("Gravity Board X was unable to find the total amount of clicks: " . mysql_error());

list($tpc) = mysql_fetch_row($tpcquery);
list($ttc) = mysql_fetch_row($ttcquery);
list($tcc) = mysql_fetch_row($tccquery);
list($tbc) = mysql_fetch_row($tbcquery);
list($tmc) = mysql_fetch_row($tmcquery);

while ($stats = mysql_fetch_assoc($statquery))
{
	echo 'Categories: ' . $tcc . '<br/>';
	echo 'Boards: ' . $tbc . '<br/>';
	echo 'Threads: ' . number_format($ttc) . '<br/>';
	echo 'Posts: ' . number_format($tpc) . '<br/>';
	echo 'PMs Sent: ' . number_format($stats['pmssent']) . '<br/>';
	echo 'Members: ' . number_format($tmc) . '<br/>';
	echo 'Total Clicks: ' . number_format($stats['totalclicks']) . '<br/>';
	echo 'Boards Viewed: ' . number_format($stats['boardviews']) . '<br/>';
	echo 'Threads Read: ' . number_format($stats['threadviews']) . '<br/>';
	echo 'Messages Read: ' . number_format($stats['messageviews']) . '<br/>';
	echo 'PMs Read: ' . number_format($stats['pmsread']) . '<br/>';
	echo 'Newest Members:</span> ';

	$nmquery = mysql_query("SELECT memberid, displayname FROM " . $prefix . "members ORDER BY dateregistered DESC LIMIT 20") OR DIE("Gravity Board X was unable to locate the newest registered members: " . mysql_error());
	while ($nmw = mysql_fetch_assoc($nmquery))
	{
		rank($nmw['memberid']);
		echo '<a href="?action=viewprofile&amp;member_id=' . $nmw['memberid'] . '"><span class="membername" style="color: ' . $userRank['color'] . ';">' . $nmw['displayname'] . '</span></a>';
		echo ',&nbsp;';
	}
}

?>

</div>

<div class="boxfooter"
</div>

  </div>
</div>