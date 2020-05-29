<div id="main">
	<div id="browser">
		<div id="games">
			<div id="profile">
				<div class="create_title">User Profile for {UserName}</div>
				<div>
					<table align="left" border="0" cellspacing="0" cellpadding="10">
					<tr>
						<td>Username:</td>
						<td>{UserName}</td>
					</tr>
					<tr>
						<td>Current Avatar:</td>
						<td><img src="avatar.php?image_id={pid}" alt="avatar" /></td>
					</tr>
					<tr>
						<td>Rank:</td>
						<td colspan="5">{rank}  =  ((((points + kills) * wins * 0.1) / (games_played + .0001)) / 15)</td>
					</tr>
					<tr>
						<th>Wins:</th>
						<th>Losses</th>
						<th>Games Played</th>
						<th>Total Players</th>
						<th>Kills</th>
						<th>Points</th>
					</tr>
					<tr>
						<td class="center">{win}</td>
						<td class="center">{loss}</td>
						<td class="center">{games_played}</td>
						<td class="center">{total_players}</td>
						<td class="center">{kills}</td>
						<td class="center">{points}</td>
					</tr>
					<tr>
						<td>Bio:</td>
						<td colspan="5" align="left">{bio}</td>
					</tr>
					</table>
				</div>
			</div>
			<div id="profile_links">
				{Edit_Profile}
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


