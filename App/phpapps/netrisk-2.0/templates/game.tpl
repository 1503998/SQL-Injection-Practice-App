<div id="main1">
	<div id="main2">
		<div id="left">
			<div class="column-in">
			<br />
				<div id="status">
					<a href="./index.php?p=game&amp;id={GNumber}&amp;display=status">Status</a> | <a href="./index.php?p=game&amp;id={GNumber}&amp;display=options">Options</a> | <a href="./index.php?p=browser">Browser</a>
				</div>
			</div>
			<div class="column-in">
				<div id="usercontrols">
					<!-- INCLUDESCRIPT BLOCK : usercontrols.php -->
				</div>
			</div>
			<div class="column-in">
				<div id="players">
					<!-- INCLUDESCRIPT BLOCK : players.php -->
				</div>
			</div>
		</div>
		<div id="right">
			<div class="column-in">
					<br /><br /><br />
					<!-- INCLUDESCRIPT BLOCK : dice.php -->
			</div>
			<div class="column-in">
				<div id="gamechat">
					<!-- INCLUDESCRIPT BLOCK : chat.php -->
				</div>
			</div>
		</div>
		<div id="middle">
			<div class="column-in">
				<div id="gameinfo">
					<span class="ginfo">GID:</span> {GNumber} | 
        			<span class="ginfo">Name:</span> {GName} | 
        			<span class="ginfo">Card Rules:</span> {CardRules} |
        			<span class="ginfo">Blind Version:</span> {BlindType} |
        			<span class="ginfo">Time Limit:</span> {TimeLimit} |
        			<span class="ginfo">Last Move:</span> {LastMove} 
        		</div>
        	</div>
        	<div class="column-in">
				<ul class="map">
					<li><img src="images/maps/worldmap_{MapType}_{CSS}.jpg" border="0" height="568" width="825" alt="map" /></li>
					<!-- START BLOCK : states -->
							<li id="{Country}" class="{Color}"><a href="javascript:select{JSelect}({SelectID},{Attackable},{TID})" class="{UnitCss}"><span class="army unit_{Color}">{Army}</span></a></li>
					<!-- END BLOCK : states -->
				</ul>
			</div>
			<div class="column-in">
				<div id="gamelog" class="gamelog">				
					<!-- INCLUDESCRIPT BLOCK : gamelog.php -->
				</div>
			</div>
		</div>
		<div class="cleaner">&nbsp;</div>
	</div>
</div>