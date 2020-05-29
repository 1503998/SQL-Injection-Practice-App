<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/
	
	if(isset($_GET['error'])){ ?>
		<div class="error" id="error" style="left: 235px; top: 250px;">
			Warning: &nbsp; <?= $_GET['error'] ?>
			<div class="close"><a href="javascript:OffError()">X</a></div>
		</div>
	
	<?php
	}
	
  	$tpl = new TemplatePower( "./templates/header.tpl" );
  	$tpl->prepare();
  
 	 $tpl->printToScreen(); 
?>