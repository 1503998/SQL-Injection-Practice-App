<script type="text/javascript">

function validate(){
	if (document.getElementById('create_game').style.display != 'none'){
		if (document.forms[0].gname.value==''){
			alert("Please include a Game Name before submitting.");
			document.forms[0].gname.focus();
		} else if (document.forms[0].map_type.value=='blank'){
			alert("Please Select a Map Type.");
			document.forms[0].map_type.focus();
		} else {
			document.getElementById('create_game').style.display = 'none';
			document.getElementById('joingame').style.display = 'block';
		}	
	} else if (document.getElementById('joingame').style.display != 'none'){
		if (document.forms[0].player.value==''){
			alert("Please fill out the Player Name field before submitting.");
			document.forms[0].player.focus();
		} else if (!(document.forms[0].playerpassword1.value == document.forms[0].playerpassword2.value)){
			alert("Password fields do not match, please Re-Enter your password.");
			document.forms[0].playerpassword1.focus();
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

<!--
var Path='images/maps/';

function CngMap(obj){
index=obj.selectedIndex;
if (index<1){ return; }
document.getElementById('Img1').src=Path+obj.options[index].value+'.jpg';
}
//-->
</script>
	<div id="main">
		<div id="browser">
			<div id="games">
				<form name="newgame" id="newgame" action="./functions/function_create.php" method="post">
					<div id="create_game">
						<div id="create_title">Create A New Game</div>
						<div>
							<div id="create_left">
								<br />
								Game Name: <input name="gname" type="text" size="30" maxlength="32" />
								<br />
								 <span class="note">- the name used to identify your game to other players (32 char max)</span>
								<br />
								Password: <input name="gpass" type="text" size="16" maxlength="16" />
								<br />
								<span class="note">- (16 char max) Leave this field blank if you wish to allow anyone to join</span>
								<br />
								<div class="left">
								Game Style
								<select name="map_type" onchange="CngMap(this);">
									<option value="blank">Select Map</option>
									<!-- START BLOCK : style -->
									<option value="{mapvalue}">{mapname}</option>
									<!-- END BLOCK : style -->
								</select>
								</div>
								<div class="left">
								<img id="Img1" src="images/misc/blank.gif" alt="map" width="100" height="70" />
								</div>
								<br />
							</div>
							<div id="create_right">
								<br />
								Player Time Limit:
								<select name="time_limit">
									<option value="0">none</option>
									<option value="1800">30 min</option>
									<option value="3600">1 hr</option>
									<option value="10800">3 hr</option>
									<option value="21600">6 hr</option>
									<option value="43200" selected="selected">12 hrs</option>
									<option value="86400">1 day</option>
									<option value="172800">2 days</option>
									<option value="604800">1 week</option>
								</select>
								<br />
								Card Rules:
								<select name="card_rules">
									<option value="Random">Random</option>
									<option value="UK">UK</option>
									<option value="US">US</option>
								</select>
								<br />
								# of Players:
								<select name="num_players">
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">6</option>
									<option value="7">7</option>
									<option value="8">8</option>
								</select>
								<br />
							</div>
						</div>
						
						<div id="create_notify">
							<span class="right top">
								Unit Type:
								<input type="radio" name="unit_type" value="1" checked="checked" /><img src="images/units/1/infantry1_red.gif" alt="infantry1" /><img src="images/units/1/cavalry_red.gif" alt="cavlary1" /><img src="images/units/1/cannon_red.gif" alt="cannon1" />
								<br/>
								Unit Type:
								<input type="radio" name="unit_type" value="3" /><img src="images/units/3/infantry1_red.gif" alt="infantry3" /><img src="images/units/3/cavalry_red.gif" alt="cavalry3" /><img src="images/units/3/cannon_red.gif" alt="cannon3" />
							</span>
							
							<input name="kibitz" type="checkbox" value="1" checked="checked" />Allow Kibitzing? <span class="note">- allows non-players to view the game from the browser.</span>
							<br />
							<input name="mailupdates" type="checkbox" value="1" checked="checked" />Would you like to recieve emails on your turn?
							<br /><br />
							Notify Players <span class="note">- An email will be sent to the addresses below informing them to join this game</span><br />
								<input name="player1" type="text" size="40" />
								<input name="player2" type="text" size="40" />
								<br />
								<input name="player3" type="text" size="40" />
								<input name="player4" type="text" size="40" />
								<br />
								<input name="player5" type="text" size="40" />
								<input name="player6" type="text" size="40" />
								<br />
								<input name="player7" type="text" size="40" />
								<input name="player8" type="text" size="40" />
								
								<a class="button_grey" style="font-size:.8em;" href="javascript:validate()">Next >></a>
								<br /><br />
						</div>
					</div>
					
					
					<div id="joingame" style="display:none">
						<!-- begin of join.html -->
						<div id="joinform">
						<span>Choose A Color</span>
						<span class="right top">
						<a class="button_grey" style="font-size:.8em;" href="javascript:previous()"> &lt;&lt; Previous</a>
						<a class="button_grey" style="font-size:.8em;" href="javascript:validate()">Create Game >></a>
						</span>
						</div>
						<!-- START BLOCK : colors -->
							<br />
								<span class="colors">
  								<input type="radio" name="pcolor" value="{color}" />
  								<img src="images/units/{type}/infantry1_{color}.gif" alt="infantry" />
  								<img src="images/units/{type}/cavalry_{color}.gif" alt="cavalry" />
  								<img src="images/units/{type}/cannon_{color}.gif" alt="cannon" />
  								</span>
						<!-- END BLOCK : colors -->
						<input name="player" type="hidden" size="30" maxlength="32" value="{Player}"/>
						<input name="playerpassword1" type="hidden" size="16" maxlength="16" value="{Password1}"/>
						<input name="playerpassword2" type="hidden" size="16" maxlength="16" value="{Password2}"/>
					</div>
					<div class="creating" id="creating" style="display:none">
						<span class="loadtitle"><br />Creating Game ... Please Wait</span>
					</div>
				</form>
			</div>
		</div>
		<div id="sidebar">
			
			<div id="topten">
				<!-- INCLUDESCRIPT BLOCK : top_ten.php -->
			</div>
			<div id="mygames">
				<!-- INCLUDESCRIPT BLOCK : my_games.php -->
			</div>
			<div id="usersonline">
				<!-- INCLUDESCRIPT BLOCK : users_online.php -->
			</div>
		</div>
	</div>