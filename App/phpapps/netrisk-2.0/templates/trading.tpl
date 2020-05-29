<!-- BEGIN: TRADING -->
<form action="./functions/function_trading.php" method="post" name="statusaction" id="addarmy">
{Message}<br /> 
<hr />
<div id="cards">
<table>
	<!-- START BLOCK : cards -->
	<tr>
		<td>
			<input name="cardandtype[]" type="checkbox" value="{ID},{Type}" />
		</td>
		<td class="cards_bg"><img src="./images/units/1/{Image}_{Color}.gif" alt="card" /></td>
		<td class="cards_bg">{Country}</td>
	</tr>
	<!-- START BLOCK : cards -->
</table>
</div>
	<input type="hidden" name="cards" value="Cards" />	
	<br />
	{TradeCards}
	<br />
		{StartSelect}
			<!-- START BLOCK : bonus -->
			<option value="{PTerritory}">{Name}</option>
			<!-- END BLOCK : bonus -->
		{EndSelect}
   	</form>
<hr />

next action...<br /><br/>
<a href="./functions/function_nextstatus.php" class="button_red">Placement >> </a>


<!-- END: TRADING -->


