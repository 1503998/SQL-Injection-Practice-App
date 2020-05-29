<script type="text/javascript">

function validate(){
	if (document.getElementById('joingame').style.display != 'none'){
		if (document.forms[0].player.value==''){
			alert("Please fill out the Player Name field before submitting.");
			document.forms[0].player.focus();
		} else if (!(document.forms[0].playerpassword1.value == document.forms[0].playerpassword2.value)){
			alert("Password fields do not match, please Re-Enter your password.");
			document.forms[0].playerpassword1.focus();
		} else if (!(document.forms[0].pcolor.value == '')){
			alert("You need to select a color.");
			document.forms[0].pcolor.focus();
		} else {
			document.getElementById('joingame').style.display = 'none';
			document.getElementById('creating').style.display = 'block';
			document.forms[0].submit();
		}	
	}
}

function previous(){
	document.getElementById('joingame').style.display = 'none';
	document.getElementById('create_game').style.display = 'block';
}
</script>

<div id="main">
	<div id="browser">
		<div id="games">
			<div id="join_game">
				<div id="join_title">
					<th> Join Game: {GameName}</th>
				</div>
				<p>
				<div>
					<div id="join_title">
						<th> Game Information</th>
					</div>
					<div id="join_left">
						<div>MapType: {MapType}</div>
						<div>Card Rules: {CardRules}</div>
						<div>Blind Man: {BlindMan}</div>
					</div>	
					<div id="join_right">
						<div>Custom Rules: {CustomRules}</div>
					</div>
				</div>
				<p>
				<form name="newgame" id="newgame" action="./functions/function_join.php" method="post">
				<div id="join_left">
					<div id="join_title">					
						<th> Current Players</th>
					</div>
					<br />
					<!-- START BLOCK : players -->
					<div class="left">{pname}:
							<img src="images/units/{type}/infantry1_{pcolor}.gif" alt="infantry" />
  							<img src="images/units/{type}/cavalry_{pcolor}.gif" alt="cavalry" />
  							<img src="images/units/{type}/cannon_{pcolor}.gif" alt="cannon" />
  					</div>
					<!-- END BLOCK : players -->
				</div>
				<div id="join_right">
					<div>					
						{Password}
						<br />
					</div>
					<div id="join_title">					
						<th> Receive Email Updates?</th>
					</div>
					<div>
						<span id="joinpassword">
						Would you like to receive an email when<br /> it is your turn to play?
						<input type="checkbox" value="1" name="mailupdates" checked/> - Yes !</span>
					</div>
					</br />
					<div id="join_title">					
						<th> Available Colors</th>
					</div>
					<div class="right">
						<input class="button" type="submit" value="Join Game" />
					</div>
					<div>
					<!-- START BLOCK : colors -->
						<span class="colors">
  							<input type="radio" name="pcolor" value="{color}" />
  							<img src="images/units/{type}/infantry1_{color}.gif" alt="infantry" />
  							<img src="images/units/{type}/cavalry_{color}.gif" alt="cavalry" />
  							<img src="images/units/{type}/cannon_{color}.gif" alt="cannon" />
  							<br />
  						</span>
					<!-- END BLOCK : colors -->
					</div>
					<input name="npid" type="hidden" value="{npid}"/>
					<input name="player" type="hidden" size="30" maxlength="32" value="{Player}"/></p>
				</form>
				</div>
			</div>
		</div>
	</div>
		
	<div id="sidebar">		
		<div id="topten">
			<!-- INCLUDESCRIPT BLOCK : top_ten.php -->
		</div>
		<p>
		<div id="mygames">
			<!-- INCLUDESCRIPT BLOCK : my_games.php -->
		</div>
		<p>
		<div id="usersonline">
			<!-- INCLUDESCRIPT BLOCK : users_online.php -->
		</div>
	</div>
</div>