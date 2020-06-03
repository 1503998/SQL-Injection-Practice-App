<?php

//Takes care of security vulnerability
if(file_exists("../global.php")){ include_once("../global.php"); }

if($_SESSION['perm'] == '1')
{

?>

<div class="headermid">

<div class="header">
  <span class="headerfont">Control Panel</span>
</div>

<div class="content">

<div class="row">
	<span class="headerfont">General Information</span>
	<br/>
	<span class="small"><b>Gravity Board X Version:</b> <?php echo $board_version; ?>
	<br/>
	<b>PHP Version:</b> <?php echo phpversion(); ?>
	<br/>
	<b>Server IP Address:</b> <?php echo $_SERVER['SERVER_ADDR']; ?>
	<br/>
	<b>Server Name:</b> <?php echo $_SERVER['SERVER_NAME']; ?>
	<br/>
	<b>Your IP Address:</b> <?php echo $_SERVER['REMOTE_ADDR']; ?></span>
</div>

<div class="row">

  <div style="width: 313px; float: left;">
    <p align="center"><span class="xlarge">General Settings</span><br/>
    <span class="categoryfont"><a href="index.php?action=boardsettings">Board Settings</a><br/>
    <a href="index.php?action=configure">Modify Connection Info</a><br/>
    <a href="index.php?action=announcements">Edit Announcements</a><br/>
    <a href="index.php?action=censor">Edit Censored Words</a><br/>
    <a href="index.php?action=editcss">Modify Board Skin</a></span></p>
  </div>
    
  <div style="width: 313px; float: left;">
    <p align="center"><span class="xlarge">Board Setup</span><br/>
    <span class="categoryfont"><a href="index.php?action=movecategories">Create/Edit/Move Categories</a><br/>
    <a href="index.php?action=moveboards">Create/Edit/Move Boards</a></span></p>
  </div>

  <div style="width: 314px; float: left;">
    <p align="center"><span class="xlarge">Member Settings</span><br/>
    <span class="categoryfont"><a href="index.php?action=viewban">User Bans</a><br/>
    <a href="index.php?action=editmembergroup">Add/Edit Membergroups</a><br/>
    <a href="index.php?action=editranks">Add/Edit Ranks</a></span></p>
  </div>

</div>

<div style="clear:both;">
</div>

</div>

<div class="headerbot">
</div>

</div>

<?php

}else
{
    accessdenied();
}

?>