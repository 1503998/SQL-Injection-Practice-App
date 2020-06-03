<?php if($_SESSION['sr'] == '2')

{

?>

<div id="recent_msg">
  <div class="station">

<div class="boxheader">
    <span class="headerfont">Recent Messages</span>
</div>

<div class="content">

<?php

$imquery = mysql_query("SELECT * FROM " . $prefix . "ims WHERE imto = '{$_SESSION['memberid']}' ORDER BY imdate DESC LIMIT 10") OR DIE("Gravity Board X was unable to retrieve your personal messages from the database: " . mysql_error());
if(mysql_num_rows($imquery) == '0')
{
    echo '<font class=small><b>You have no messages.</b></font>';
}

while($imstat = mysql_fetch_assoc($imquery))
{
	rank($imstat['imfrom']);
    $dnquery = mysql_query("SELECT displayname FROM " . $prefix . "members WHERE memberid = '{$imstat['imfrom']}'") OR DIE("Gravity Board X was unable to identify the senders of your personal messages: " . mysql_error());

    while($imfrom = mysql_fetch_row($dnquery))
    {

?>

<?php if($imstat['imread'] == '0') { echo '<b>'; } ?>

<span><a href="#" onMouseOver="window.status='View This Message'; return true" onMouseOut="window.status=''; return true" onClick="PMs=window.open('viewpm.php?imid=<?php echo $imstat['imid']; ?>','PMs','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=600,height=400,left=100,top=100'); return false;"><?php echo $imstat['imsubject']; ?></a>

<?php if($imstat['imread'] == '0') { echo '</b>'; } ?>

<br>

<span class="small">From: </span><a href="index.php?action=viewprofile&member_id=<?php echo $imstat['imfrom']; ?>"><span class="membername" style="color: <?php echo $userRank['color']; ?>"><?php echo $imfrom[0]; ?></a></span></span><br/>

<?php

    }
}

?>

<a href="#" onMouseOver="window.status='View All Messages'; return true" onMouseOut="window.status=''; return true" onClick="PMs=window.open('index.php?action=inbox','PMs','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=600,height=400,left=100,top=100'); return false;"><center><font class=small><b>&#60;&#60;View All Messages&#62;&#62;</b></font></center></a>

</div>

<div class="boxfooter"
</div>

  </div>
</div>

<?php

}

?>