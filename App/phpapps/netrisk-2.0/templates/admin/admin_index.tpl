<div id="main">
	<div id="browser">
		<div id="games">
			<table>
				<tr>
					<th>Delete</th>
					<th>Game ID</th>
					<th>Game Name</th>
					<th>Options</th>
				</tr>
				<!-- START BLOCK : GameList -->
   				<tr>
   					<td><a style="color:red;font-weight:800;text-align:center;" href="admin/edit.php?mode=delete&amp;gid={GID}" onclick="return confirm('Are you sure to delete this game {GID}?')" ><img src="./images/misc/delete.png" alt="delete" border="0" /></a></td>
     				<td>{GID}</td>
     				<td>{GName}</td>
     				<td>
     					<select name="edit_game" onchange="window.open(this.options[this.selectedIndex].value,'_top')">
							<option value="0">Game Options</option>
							<option value="index.php?p=edit&amp;mode=gamedata&amp;gid={GID}">Edit Game Settings</option>
							<option value="index.php?p=edit&amp;mode=players&amp;gid={GID}">Edit Players</option>
							<option value="index.php?p=edit&amp;mode=countries&amp;gid={GID}">Edit Countries</option>
							<option value="index.php?p=edit&amp;mode=gamelog&amp;gid={GID}">Edit Game Log</option>
						</select>
     				</td>
   				</tr>
  				<!-- END BLOCK : GameList -->
  			</table>
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