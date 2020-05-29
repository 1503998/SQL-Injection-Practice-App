<div id="main">
	<div id="browser">
		<div id="games">
			<div class="create_title">Edit Game Settings for {GID}: {GName}</div>
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
			<form action="./admin/functions/function_admin_update_countries.php" method="post">
				<table>
					<tr>
						<td class="create_title">PID</td>
						<td class="create_title">Player</td>
						<td class="create_title">CID</td>
						<td class="create_title">Country</td>
						<td class="create_title">Armies</td>
					</tr>
					<!-- START BLOCK : Countries -->
					<tr>
						<td>{CPID}</td>
						<td>{CPName}</td>
						<td><input name="ctid[]" type="hidden" value="{CTID}" />{CTID}</td>
						<td><input name="cname[]" type="hidden" value="{CName}" />{CName}</td>
						<td><input name="pterritory[]" type="text" value="{CPArmies}" size="1" maxlength="3" /></td>
					</tr>
					<!-- END BLOCK : Countries -->
				</table>
						
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