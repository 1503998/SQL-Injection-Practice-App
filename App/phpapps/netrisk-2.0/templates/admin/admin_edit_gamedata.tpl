<div id="main">
	<div id="browser">
		<div id="games">
			<div id="create_title">Edit Game Settings for {GID}: {GName}</div>
			<div>
				<div class="right">	
					<select name="edit_game" onchange="window.open(this.options[this.selectedIndex].value,'_top')">
						<option value="0">Game Options</option>
						<option value="index.php?p=edit&amp;mode=gamedata&amp;gid={GID}">Edit Game Settings</option>
						<option value="index.php?p=edit&amp;mode=players&amp;gid={GID}">Edit Players</option>
						<option value="index.php?p=edit&amp;mode=countries&amp;gid={GID}">Edit Countries</option>
						<option value="index.php?p=edit&amp;mode=gamelog&amp;gid={GID}">Edit Game Log</option>
					</select>
				</div>
			<form action="./admin/functions/function_admin_update_gamedata.php" method="post">
				Change Game Name: <input name="gname" type="text" value="{GName}" size="30" maxlength="32" /> 
				<br />
				Change Game State: 
					<select name="gstate">
						<option value="{GState}">{GState}</option>
						<option value="Initial">Initial</option>
						<option value="Playing">Playing</option>
						<option value="Finished">Finished</option>
					</select>
				<br />
				Change Game Type: 
					<select name="gtype">
						<option value="{GType}">{GType}</option>
						<option value="domination">dominaation</option>
					</select>
				<br />
				Change Unit Type: 
					<select name="utype">
						<option value="{UnitType">{UnitType}</option>
						<option value="0">0</option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
					</select>
				<br />
				Change Blind Man: 
					<select name="bman">
						<option value="{BlindMan">{BlindMan}</option>
						<option value="0">0</option>
						<option value="1">1</option>
					</select>
				<br />
				Change Number of Players: <input name="players" type="text" value="{Players}" size="1" maxlength="2" /> <br />
				Change Player Capacity: <input name="capacity" type="text" value="{Capacity}" size="1" maxlength="2" /> <br />
				Change Kibitz Lock: 
					<select name="kibitz">
						<option value="{Kibitz">{Kibitz}</option>
						<option value="0">0</option>
						<option value="1">1</option>
					</select>
				<br />
				Change Card Rules: 
					<select name="cardrules">
						<option value="{CardRules}">{CardRules}</option>
						<option value="Random">Random</option>
						<option value="UK">UK</option>
						<option value="US">US</option>
					</select>
				<br />
				Change Next Trade Value (Applies to U.S. Rules Only): <input name="tradevalue" type="text" value="{TradeValue}" size="1" maxlength="2" /> <br />
				Change Time Limit: 
					<select name="timelimit">
						<option value="{TimeLimit}">{TimeHMS}</option>
						<option value="0">None</option>
						<option value="60">1 min</option>
						<option value="1800">30 min</option>
						<option value="3600">1 hr</option>
						<option value="10800">3 hr</option>
						<option value="21600">6 hr</option>
						<option value="43200">12 hrs</option>
						<option value="86400">1 day</option>
						<option value="172800">2 days</option>
						<option value="604800">1 week</option>
					</select>
				<br />
				Change Custom Rules: <textarea id="customrules" name="customrules" cols="100" rows="5">{CustomRules}</textarea>
				<br />
						 <input name="gid" type="hidden" value="{GID}" />
				Submit:  <input type="submit" name="updategame" value="Submit Changes" />
						 <input type="reset" value="Reset Values" />
			</form>
			</div>
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