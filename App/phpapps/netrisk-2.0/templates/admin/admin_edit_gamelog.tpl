<div id="main">
	<div id="browser">
		<div id="games">
			<div id="create_title">View Game Log for {GID}: {GName}</div>
			<div class="right">	
					<select name="edit_game" onchange="window.open(this.options[this.selectedIndex].value,'_top')">
						<option value="0">Game Options</option>
						<option value="index.php?p=edit&amp;mode=gamedata&amp;gid={GID}">Edit Game Settings</option>
						<option value="index.php?p=edit&amp;mode=players&amp;gid={GID}">Edit Players</option>
						<option value="index.php?p=edit&amp;mode=countries&amp;gid={GID}">Edit Countries</option>
						<option value="index.php?p=edit&amp;mode=gamelog&amp;gid={GID}">Edit Game Log</option>
					</select>
			</div>
			<div id="logheight">
				<table>
					<tr>
						<th>Game Log</th>
					</tr>
					<!-- START BLOCK : GameLog -->
					<tr>
						<td>{Time}: {Text}</td>
					</tr>
					<!-- END BLOCK : GameLog -->
				</table>
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