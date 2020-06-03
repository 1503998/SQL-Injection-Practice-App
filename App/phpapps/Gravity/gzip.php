<?php

/*****************************************************************************/
/* gzip.php                                                                  */
/*****************************************************************************/
/* Gravity Board X                                                           */
/* Open-Source Project started by Jonathan Taft (admin@gravityboardx.com)    */
/* Software Version: GBX Version 2.0                                         */
/* ========================================================================= */
/* Copyright (c) 2002-2006 Gravity Board X Developers. All Rights Reserved   */
/* Software by: The Gravity Board X Development Team                         */
/*****************************************************************************/
/* This program is free software; you can redistribute it and/or modify it   */
/* under the terms of the GNU General Public License as published by the     */
/* Free Software Foundation; either version 2 of the License, or (at your    */
/* option) any later version.                                                */
/*                                                                           */
/* This program is distributed in the hope that it will be useful, but       */
/* WITHOUT ANY WARRANTY; without even the implied warranty of                */
/* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General */
/* Public License for more details.                                          */
/*                                                                           */
/* The GNU GPL can be found in gpl.txt, which came with your download of GBX */
/*****************************************************************************/

///////////////////////////////////////////////////////////////////////////////
//----------------------------SCRIPT INFORMATION-----------------------------//
//This script enables gzip or deflate for content encoding.  Content encoding//
//compresses the data of the page, making load times faster for users.       //
///////////////////////////////////////////////////////////////////////////////

$PREFER_DEFLATE = false;
$FORCE_COMPRESSION = false;

function compress_output_gzip($output) {
	return gzencode($output);
}

function compress_output_deflate($output) {
	return gzdeflate($output, 9);
}

if(isset($_SERVER['HTTP_ACCEPT_ENCODING'])) {
	$AE = $_SERVER['HTTP_ACCEPT_ENCODING'];
}else{
	if(isset($_SERVER['HTTP_TE'])) {
		$AE = $_SERVER['HTTP_TE'];
	}else{
		$AE = '';
	}
}

$support_gzip = (strpos($AE, 'gzip') !== FALSE) || $FORCE_COMPRESSION;
$support_deflate = (strpos($AE, 'deflate') !== FALSE) || $FORCE_COMPRESSION;

if($support_gzip && $support_deflate) {
	$support_deflate = $PREFER_DEFLATE;
}

if ($support_deflate) {
	header("Content-Encoding: deflate");
	ob_start("compress_output_deflate");
} else{
	if($support_gzip){
		header("Content-Encoding: gzip");
		ob_start("compress_output_gzip");
	} else {
		ob_start();
	}
}
?>
