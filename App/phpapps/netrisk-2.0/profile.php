<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/
	
  	$tpl = new TemplatePower( "./templates/profile.tpl" );
  	$tpl->prepare();
  
  	$id = $_GET['id'];
  
  	//Get User Info
  	$query = mysql_query("SELECT * FROM ". $mysql_prefix . "users WHERE id = {$id} ") or die(mysql_error()); 
  	while($row = mysql_fetch_assoc($query)){
		$rank = compute_ranking($row['login']);
	  	$bio = stripslashes($row['bio']);
	  	$pid = $row['id'];
	 
	  	$tpl->assign( array( pid  => $row['id'],
	  				 UserName  => $row['login'],
	  				 win  => $row['win'],
                     loss => $row['loss'],
                     games_played => $row['games_played'],
                     total_players => $row['total_players'],
                     kills => $row['kills'],
                     points => $row['points'],
                     bio => stripslashes($row['bio']),
                     rank => $rank
                      ));
  	}
  	
  	if($pid == $_SESSION['userid']){
  		$tpl -> assign("Edit_Profile", '
	  		<div class="profile_title">Edit User Info</div>
				<br />
				<div class="profile_title">Change Password</div>
				<form action="./functions/function_updateprofile.php" method="post" enctype="multipart/form-data">
					Old: <input type="text" name="oldpass" maxlength="30" />
					<br />
					New: <input type="text" name="password1" maxlength="30" />
					<br />
					Rept:<input type="text" name="password2" maxlength="30" />
					<br /><br />
					<div class="profile_title">Upload Avatar</div>
					<div>
						<input type="hidden" id="avatar_type" name="avatar_type" value="upload" />
						<input id="userfile" name="userfile" type="file" size="15" />
					</div>
					<br />
					<div class="profile_title">Edit Bio</div>
						<textarea id="bio" name="bio" rows="5" cols="21" class="profile_bio"></textarea>
					<br /><br />
					<div class="profile_title">Change Email</div>
						Email: <input type="text" id="email" name="email" maxlength="30" />
					<br /><br />
					Submit:  <input type="submit" name="updateprofile" value="Submit Changes" />
				</form>
		');
	} else {
		$tpl -> assign("Edit_Profile", '
	  		<div class="profile_title">You can not edit another users profile</div>
		');
	}
  
	$tpl->printToScreen(); 
?>