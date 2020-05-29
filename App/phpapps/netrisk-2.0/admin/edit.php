<?php

	/**************************************************

	Project	NetRisk <http://netrisk.sourceforge.net>
	Author	PMuldoon <ptmuldoon@gmail@gmail.com>
	License	GPL

	**************************************************/

	//Admin Edit.php
	include('./../includes/config.php');

	//Get Admin Mode to Determine Template
	$mode = $_GET['mode'];


	if($mode == 'gamedata'){
		$tpl = new TemplatePower('./templates/admin/admin_edit_gamedata.tpl');
	}elseif($mode == 'players'){
		$tpl = new TemplatePower('./templates/admin/admin_edit_players.tpl');
	} elseif($mode == 'countries'){
		$tpl = new TemplatePower('./templates/admin/admin_edit_countries.tpl');
	} elseif($mode == 'gamelog'){
		$tpl = new TemplatePower('./templates/admin/admin_edit_gamelog.tpl');
	} elseif($mode == 'delete'){
		require('./functions/function_admin_delete.php');
	}
	$tpl->prepare();	

	$gid = $_GET['gid'];

	//Create the Game Date Info
	$query1 = mysql_query("SELECT * FROM ". $mysql_prefix ."game_info WHERE gid = $gid");
	while($gdata = mysql_fetch_assoc($query1)){
		$gname = stripslashes($gdata['gname']);
		$maptype = $gdata['map_type'];
		$TimeHMS = seconds_to_HMS($gdata['time_limit']);
	
		$tpl->assign( Array( GID  => $gdata['gid'],
                     GName => $gname,
                     GState => stripslashes($gdata['gstate']), 
                     GMode => stripslashes($gdata['gmode']), 
                     GType => stripslashes($gdata['gtype']), 
                     UnitType => $gdata['unit_type'],
                     BlindMan => $gdata['blind_man'],
                     Players => $gdata['players'],
                     Capacity => $gdata['capacity'],
                     Kibitz => $gdata['kibitz'],
                     CardRules => stripslashes($gdata['card_rules']),
                     TradeValue => $gdata['trade_value'],
                     MapType => $gdata['map_type'],
                     CssStyle => $gdata['css_style'],
                     LastMove => $gdata['last_move'],
                     TimeLimit => $gdata['time_limit'],
                     TimeHMS => $TimeHMS,
                     CustomRules => stripslashes($gdata['custom_rules'])
                     ));
	}

	//Get All the Game Players and their Info
	$query2 = mysql_query("SELECT * FROM ". $mysql_prefix ."game_players WHERE gid = $gid ORDER BY pid ");
	while($gplayers = mysql_fetch_assoc($query2)){
	
		$tpl -> newBlock("Players");
	
		$tpl->assign( Array( PID => stripslashes($gplayers['pid']),                  
					 PName => stripslashes($gplayers['pname']), 
                     PHost => stripslashes($gplayers['phost']),
                     PColor => stripslashes($gplayers['pcolor']),  
                     PState => stripslashes($gplayers['pstate']), 
                     PAttackCard => stripslashes($gplayers['pattackcard']),
                     PNumArmy => stripslashes($gplayers['pnumarmy']), 
                     PMission => stripslashes($gplayers['pmission']), 
                     PCapOrg => stripslashes($gplayers['pcaporg']),
                     PMail => stripslashes($gplayers['pmail']), 
                     PVote => stripslashes($gplayers['pvote']), 
                     PKick => stripslashes($gplayers['pkick']),
                     PKills => stripslashes($gplayers['pkills']), 
                     PPoints => stripslashes($gplayers['ppoints'])  
                     ));
	}

	//Get all Countries and their Armies
	$query3 = mysql_query("SELECT gd.gid, gd.pid, gd.pterritory, gd.pname, gd.parmies, cty.mtype, cty.id, cty.name FROM ". $mysql_prefix ."countries cty, ". $mysql_prefix ."game_data gd WHERE gd.gid = {$gid} AND cty.mtype = '{$maptype}' AND gd.pterritory = cty.id ORDER BY pid") or die(mysql_error());
	while($gcountries = mysql_fetch_assoc($query3)){
	
		$tpl -> newBlock("Countries");
	
		$tpl->assign( Array( CPID => stripslashes($gcountries['pid']),                  
					 CPName => stripslashes($gcountries['pname']), 
                     CName => stripslashes($gcountries['name']),
                     CTID => stripslashes($gcountries['id']),    
                     CTerritory => stripslashes($gcountries['pterritory']),  
                     CPArmies => stripslashes($gcountries['parmies'])
                     ));
	}

	//Get Game Log
	$query4 = mysql_query("SELECT * FROM ". $mysql_prefix ."game_log WHERE gid = {$gid} ORDER BY time DESC, tid DESC") or die(mysql_error());
	while($glog = mysql_fetch_assoc($query4)){
		$glog['date'] = date('M-d : h:i a', $glog['time']);
		$tpl -> newBlock("GameLog");
	
		$tpl->assign( Array( Time => $glog['date'],
						 Text => $glog['text']
                     ));
	
	}

	$tpl->printToScreen();
?>