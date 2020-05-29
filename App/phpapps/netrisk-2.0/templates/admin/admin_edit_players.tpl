<div id="main">
	<div id="browser">
		<div id="games">
			<div class="create_title">Edit Game Players for {GID}: {GName}</div>
			<div class="right">	
				<select name="edit_game" onchange="window.open(this.options[this.selectedIndex].value,'_top')">
					<option value="0">Game Options</option>
					<option value="index.php?p=edit&amp;mode=gamedata&amp;gid={GID}">Edit Game Settings</option>
					<option value="index.php?p=edit&amp;mode=players&amp;gid={GID}">Edit Players</option>
					<option value="index.php?p=edit&amp;mode=countries&amp;gid={GID}">Edit Countries</option>
					<option value="index.php?p=edit&amp;mode=gamelog&amp;gid={GID}">Edit Game Log</option>
				</select>
			</div>
			<div>
				<form action="./admin/functions/function_admin_update_players.php" method="post">
				<br/>
					<table>
						<tr>
							<td class="create_title">PID</td>
							<td class="create_title">Player</td>
							<td class="create_title">Color</td>
							<td class="create_title">State</td>
							<td class="create_title">Attack<br />Card</td>
							<td class="create_title"># of<br />Armies</td>
							<td class="create_title">Mission</td>
							<td class="create_title">Original<br />Capital</td>
							<td class="create_title">E-Mail</td>
							<td class="create_title">Vote</td>
							<td class="create_title">Kick</td>
							<td class="create_title">Kills</td>
							<td class="create_title">Points</td>
						</tr>
						<!-- START BLOCK : Players -->
						<tr>
						 	<td><input name="pid[]" type="hidden" value="{PID}" />{PID}</td>
						 	<td><input name="pname[]" type="text" value="{PName}" size="15" maxlength="32" /></td>
						 	<td><input name="pcolor[]" type="text" value="{PColor}" size="15" maxlength="32" /></td>
						 	<td>
						 		<select name="pstate[]">
									<option value="{PState}">{PState}</option>
									<option value="waiting">waiting</option>
									<option value="initial">initial</option>
									<option value="capital">capital</option>
									<option value="inactive">inactive</option>
									<option value="trading">trading</option>
									<option value="placing">placing</option>
									<option value="attacking">attacking</option>
									<option value="occupy">occupy</option>
									<option value="fortifying">fortifying</option>
									<option value="forceplace">forceplace</option>
									<option value="forcetrade">forcetrade</option>
									<option value="dead">dead</option>
									<option value="winner">winner</option>
								</select>
						 	</td>
						 	<td>
						 		<select name="pattackcard[]">
									<option value="{PAttackCard}">{PAttackCard}</option>
									<option value="0">0</option>
									<option value="1">1</option>
								</select>
						 	</td>
						 	<td><input name="pnumarmy[]" type="text" value="{PNumArmy}" size="2" maxlength="3" /></td>
						 	<td><input name="pmission[]" type="text" value="{PMission}" size="1" maxlength="2" /></td>
						 	<td><input name="pcaporg[]" type="text" value="{PCapOrg}" size="1" maxlength="2" /></td>
						 	<td>
						 		<select name="pmail[]">
									<option value="{PMail}">{PMail}</option>
									<option value="0">0</option>
									<option value="1">1</option>
								</select>
						 	</td>
						 	<td>
						 		<select name="pvote[]">
									<option value="{PVote}">{PVote}</option>
									<option value="0">0</option>
									<option value="1">1</option>
								</select>
						 	</td>
						 	<td>
						 		<select name="pkick[]">
									<option value="{PKick}">{PKick}</option>
									<option value="0">0</option>
									<option value="1">1</option>
								</select>
						 	</td>
						 	<td><input name="pkills[]" type="text" value="{PKills}" size="1" maxlength="2" /></td>
						 	<td><input name="ppoints[]" type="text" value="{PPoints}" size="1" maxlength="2" /></td>
						 </tr>
						<!-- END BLOCK : Players -->
					</table>
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