<!-- BEGIN: MYGAMES -->
<div class="create_title">My Games</div>
	<table>
		<tr>
   			<th>ID</th>
   			<th>Name</th>
		</tr>
		<!-- START BLOCK : MyGames -->
		<tr>
   			<td class="state-{pstate}">{GID}</td>
   			<td><a href="./login.php?id={GID}"><span class="state-{pstate}">{GName}</span></a></td>
		</tr>
		<!-- END BLOCK : MyGames -->		
	</table>
<div class="create_title">Legend</div>
<div>
	<span class="state-waiting">Waiting</span> <br />
	<span class="state-initial">Initial</span> <br />
	<span class="state-trading">Trading</span> <br />
	<span class="state-placing">Placing</span> <br />
	<span class="state-attacking">Attacking</span> <br />
	<span class="state-fortifying">Fortifying</span> <br />
	<span class="state-dead">Dead</span> <br />
	<span class="state-winner">Winner</span> <br />
</div>
<!-- END: MYGAMES -->