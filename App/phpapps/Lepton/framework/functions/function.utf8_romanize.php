<?php

/**
 * This file is part of LEPTON Core, released under the GNU GPL
 *
 * @function		utf8_romanize
 * @author          Website Baker Project, LEPTON Project
 * @copyright       2004-2010 Website Baker Project
 * @copyright       2010-2016 LEPTON Project
 * @link            http://www.LEPTON-cms.org
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see LICENSE and COPYING files in your package
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if ( defined( 'LEPTON_PATH' ) )
{
	include( LEPTON_PATH . '/framework/class.secure.php' );
} //defined( 'LEPTON_PATH' )
else
{
	$oneback = "../";
	$root    = $oneback;
	$level   = 1;
	while ( ( $level < 10 ) && ( !file_exists( $root . '/framework/class.secure.php' ) ) )
	{
		$root .= $oneback;
		$level += 1;
	} //( $level < 10 ) && ( !file_exists( $root . '/framework/class.secure.php' ) )
	if ( file_exists( $root . '/framework/class.secure.php' ) )
	{
		include( $root . '/framework/class.secure.php' );
	} //file_exists( $root . '/framework/class.secure.php' )
	else
	{
		trigger_error( sprintf( "[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER[ 'SCRIPT_NAME' ] ), E_USER_ERROR );
	}
}
// end include class.secure.php

/*
 * Romanize a non-latin string
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 *
 */
function utf8_romanize( $string )
{
	if(utf8_isASCII($string)) return $string; //nothing to do

	global $UTF8_ROMANIZATION;
	
	// see: http://php.net/manual/de/function.strtr.php
	return strtr($string, $UTF8_ROMANIZATION);
}

/**
 * Romanization lookup table
 *
 * This lookup tables provides a way to transform strings written in a language
 * different from the ones based upon latin letters into plain ASCII.
 *
 * Please note: this is not a scientific transliteration table. It only works
 * oneway from nonlatin to ASCII and it works by simple character replacement
 * only. Specialities of each language are not supported.
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 * @author Vitaly Blokhin <vitinfo@vitn.com>
 * @link   http://www.uconv.com/translit.htm
 * @author Bisqwit <bisqwit@iki.fi>
 * @link   http://kanjidict.stc.cx/hiragana.php?src=2
 * @link   http://www.translatum.gr/converter/greek-transliteration.htm
 * @link   http://en.wikipedia.org/wiki/Royal_Thai_General_System_of_Transcription
 * @link   http://www.btranslations.com/resources/romanization/korean.asp
 * @author Arthit Suriyawongkul <arthit@gmail.com>
 * @author thorn <thorn@nettest.thekk.de>
 */
global $UTF8_ROMANIZATION;
$UTF8_ROMANIZATION = array(
	// Latin
	"\xc3\x81"=>"A","\xc3\xa1"=>"a","\xc3\x82"=>"A",
	"\xc3\xa2"=>"a","\xc3\x86"=>"Ae","\xc3\xa6"=>"ae","\xc3\x80"=>"A",
	"\xc3\xa0"=>"a","\xc3\x85"=>"A","\xc3\xa5"=>"a","\xc3\x83"=>"A",
	"\xc3\xa3"=>"a","\xc3\x84"=>"Ae","\xc3\xa4"=>"ae","\xc3\x87"=>"C",
	"\xc3\xa7"=>"c","\xc3\x89"=>"E","\xc3\xa9"=>"e","\xc3\x8a"=>"E",
	"\xc3\xaa"=>"e","\xc3\x88"=>"E","\xc3\xa8"=>"e","\xc3\x8b"=>"E",
	"\xc3\xab"=>"e","\xc3\x8d"=>"I","\xc3\xad"=>"i","\xc3\x8e"=>"I",
	"\xc3\xae"=>"i","\xc3\x8c"=>"I","\xc3\xac"=>"i","\xc3\x8f"=>"I",
	"\xc3\xaf"=>"i","\xc3\x91"=>"N","\xc3\xb1"=>"n","\xc3\x93"=>"O",
	"\xc3\xb3"=>"o","\xc3\x94"=>"O","\xc3\xb4"=>"o","\xc5\x92"=>"Oe",
	"\xc5\x93"=>"oe","\xc3\x92"=>"O","\xc3\xb2"=>"o","\xc3\x95"=>"O",
	"\xc3\xb5"=>"o","\xc3\x96"=>"Oe","\xc3\xb6"=>"oe","\xc5\xa0"=>"S",
	"\xc5\xa1"=>"s","\xc3\x9f"=>"ss","\xc3\x9a"=>"U","\xc3\xba"=>"u",
	"\xc3\x9b"=>"U","\xc3\xbb"=>"u","\xc3\x99"=>"U","\xc3\xb9"=>"u",
	"\xc3\x9c"=>"Ue","\xc3\xbc"=>"ue","\xc3\x9d"=>"Y","\xc3\xbd"=>"y",
	"\xc5\xb8"=>"Y","\xc3\xbf"=>"y","\xc2\xa9"=>"(c)","\xc2\xae"=>"(r)",
	"\xc3\x90"=>"D","\xc3\x97"=>"x","\xc3\x98"=>"O","\xc3\x9e"=>"Th",
	"\xc3\xb0"=>"d","\xc3\xb8"=>"o","\xc3\xbe"=>"th","\x27"=>"-",
	"\x22"=>"-","\xc4\x80"=>"A","\xc4\x81"=>"a","\xc4\x82"=>"A",
	"\xc4\x83"=>"a","\xc4\x84"=>"A","\xc4\x85"=>"a","\xc4\x86"=>"C",
	"\xc4\x87"=>"c","\xc4\x88"=>"C","\xc4\x89"=>"c","\xc4\x8d"=>"c",
	"\xc4\x8c"=>"C","\xc4\x8b"=>"c","\xc4\x8a"=>"C","\xc4\x91"=>"d",
	"\xc4\x90"=>"D","\xc4\x8f"=>"d","\xc4\x8e"=>"D","\xc4\x93"=>"e",
	"\xc4\x92"=>"E","\xc4\x94"=>"E","\xc4\x95"=>"e","\xc4\x96"=>"E",
	"\xc4\x97"=>"e","\xc4\x98"=>"E","\xc4\x99"=>"e","\xc4\x9a"=>"E",
	"\xc4\x9b"=>"e","\xc4\x9c"=>"G","\xc4\x9d"=>"g","\xc4\x9e"=>"G",
	"\xc4\x9f"=>"g","\xc4\xa0"=>"G","\xc4\xa1"=>"g","\xc4\xa2"=>"G",
	"\xc4\xa3"=>"g","\xc4\xa4"=>"H","\xc4\xa5"=>"h","\xc4\xa6"=>"H",
	"\xc4\xa7"=>"h","\xc4\xa8"=>"I","\xc4\xa9"=>"i","\xc4\xaa"=>"I",
	"\xc4\xab"=>"i","\xc4\xac"=>"I","\xc4\xad"=>"i","\xc4\xae"=>"I",
	"\xc4\xaf"=>"i","\xc4\xb0"=>"I","\xc4\xb1"=>"i","\xc4\xb2"=>"IJ",
	"\xc4\xb3"=>"ij","\xc4\xb4"=>"J","\xc4\xb5"=>"j","\xc4\xb6"=>"K",
	"\xc4\xb7"=>"k","\xc4\xb8"=>"k","\xc4\xb9"=>"L","\xc4\xba"=>"l",
	"\xc4\xbb"=>"L","\xc4\xbc"=>"l","\xc4\xbd"=>"L","\xc4\xbe"=>"l",
	"\xc4\xbf"=>"L","\xc5\x80"=>"l","\xc5\x81"=>"L","\xc5\x82"=>"l",
	"\xc5\x83"=>"N","\xc5\x84"=>"n","\xc5\x85"=>"N","\xc5\x86"=>"n",
	"\xc5\x87"=>"N","\xc5\x88"=>"n","\xc5\x89"=>"n","\xc5\x8a"=>"N",
	"\xc5\x8b"=>"n","\xc5\x8c"=>"O","\xc5\x8d"=>"o","\xc5\x8e"=>"O",
	"\xc5\x8f"=>"o","\xc5\x90"=>"O","\xc5\x91"=>"o","\xc5\x94"=>"R",
	"\xc5\x95"=>"r","\xc5\x96"=>"R","\xc5\x97"=>"r","\xc5\x98"=>"R",
	"\xc5\x99"=>"r","\xc5\x9a"=>"S","\xc5\x9b"=>"s","\xc5\x9c"=>"S",
	"\xc5\x9d"=>"s","\xc5\x9e"=>"S","\xc5\x9f"=>"s","\xc5\xa2"=>"T",
	"\xc5\xa3"=>"t","\xc5\xa4"=>"T","\xc5\xa5"=>"t","\xc5\xa6"=>"T",
	"\xc5\xa7"=>"t","\xc5\xa8"=>"U","\xc5\xa9"=>"u","\xc5\xaa"=>"U",
	"\xc5\xab"=>"u","\xc5\xac"=>"U","\xc5\xad"=>"u","\xc5\xae"=>"U",
	"\xc5\xaf"=>"u","\xc5\xb0"=>"U","\xc5\xb1"=>"u","\xc5\xb2"=>"U",
	"\xc5\xb3"=>"u","\xc5\xb4"=>"W","\xc5\xb5"=>"w","\xc5\xb6"=>"Y",
	"\xc5\xb7"=>"y","\xc5\xb9"=>"Z","\xc5\xba"=>"z","\xc5\xbb"=>"Z",
	"\xc5\xbc"=>"z","\xc5\xbd"=>"Z","\xc5\xbe"=>"z","\xc5\xbf"=>"s",
	"\xef\xac\x80"=>"ff","\xef\xac\x81"=>"fi","\xef\xac\x82"=>"fl","\xef\xac\x83"=>"ffi",
	"\xef\xac\x84"=>"ffl","\xef\xac\x85"=>"st","\xef\xac\x86"=>"st","\xc6\x80"=>"b",
	"\xc6\x81"=>"B","\xc6\x82"=>"B","\xc6\x83"=>"b","\xc6\x84"=>"6",
	"\xc6\x85"=>"6","\xc6\x86"=>"O","\xc6\x87"=>"C","\xc6\x88"=>"c",
	"\xc6\x89"=>"D","\xc6\x8a"=>"D","\xc6\x8b"=>"D","\xc6\x8c"=>"d",
	"\xc6\x8d"=>"d","\xc6\x8e"=>"E","\xc6\x8f"=>"e","\xc6\x90"=>"E",
	"\xc6\x91"=>"F","\xc6\x92"=>"f","\xc6\x93"=>"G","\xc6\x94"=>"G",
	"\xc6\x95"=>"hw","\xc6\x96"=>"I","\xc6\x97"=>"I","\xc6\x98"=>"K",
	"\xc6\x99"=>"k","\xc6\x9a"=>"l","\xc6\x9b"=>"l","\xc6\x9c"=>"M",
	"\xc6\x9d"=>"N","\xc6\x9e"=>"n","\xc6\x9f"=>"O","\xc6\xa0"=>"O",
	"\xc6\xa1"=>"o","\xc6\xa2"=>"OI","\xc6\xa3"=>"oi","\xc6\xa4"=>"P",
	"\xc6\xa5"=>"p","\xc6\xa6"=>"YR","\xc6\xa7"=>"2","\xc6\xa8"=>"2",
	"\xc6\xa9"=>"ESH","\xc6\xaa"=>"esh","\xc6\xab"=>"t","\xc6\xac"=>"T",
	"\xc6\xad"=>"t","\xc6\xae"=>"T","\xc6\xaf"=>"U","\xc6\xb0"=>"u",
	"\xc6\xb1"=>"V","\xc6\xb2"=>"v","\xc6\xb3"=>"Y","\xc6\xb4"=>"y",
	"\xc6\xb5"=>"Z","\xc6\xb6"=>"z","\xc6\xb7"=>"EZH","\xc6\xb8"=>"EZH",
	"\xc6\xb9"=>"ezh","\xc6\xba"=>"ezh","\xc6\xbb"=>"2","\xc6\xbc"=>"5",
	"\xc6\xbd"=>"5","\xc6\xbe"=>"-","\xc6\xbf"=>"w","\xc7\x80"=>"-",
	"\xc7\x81"=>"-","\xc7\x82"=>"-","\xc7\x83"=>"-","\xc7\x84"=>"DZ",
	"\xc7\x85"=>"DZ","\xc7\x86"=>"dz","\xc7\x87"=>"LJ","\xc7\x88"=>"Lj",
	"\xc7\x89"=>"lj","\xc7\x8a"=>"NJ","\xc7\x8b"=>"Nj","\xc7\x8c"=>"nj",
	"\xc7\x8d"=>"A","\xc7\x8e"=>"a","\xc7\x8f"=>"I","\xc7\x90"=>"i",
	"\xc7\x91"=>"O","\xc7\x92"=>"o","\xc7\x93"=>"U","\xc7\x94"=>"u",
	"\xc7\x95"=>"U","\xc7\x96"=>"u","\xc7\x97"=>"U","\xc7\x98"=>"u",
	"\xc7\x99"=>"U","\xc7\x9a"=>"u","\xc7\x9b"=>"U","\xc7\x9c"=>"u",
	"\xc7\x9d"=>"e","\xc7\x9e"=>"A","\xc7\x9f"=>"a","\xc7\xa0"=>"A",
	"\xc7\xa1"=>"a","\xc7\xa2"=>"AE","\xc7\xa3"=>"ae","\xc7\xa4"=>"G",
	"\xc7\xa5"=>"g","\xc7\xa6"=>"G","\xc7\xa7"=>"g","\xc7\xa8"=>"K",
	"\xc7\xa9"=>"k","\xc7\xaa"=>"O","\xc7\xab"=>"o","\xc7\xac"=>"O",
	"\xc7\xad"=>"o","\xc7\xae"=>"EZH","\xc7\xaf"=>"ezh","\xc7\xb0"=>"j",
	"\xc7\xb1"=>"DZ","\xc7\xb2"=>"Dz","\xc7\xb3"=>"dz","\xc7\xb4"=>"G",
	"\xc7\xb5"=>"g","\xc7\xb6"=>"HW","\xc7\xb7"=>"W","\xc7\xb8"=>"N",
	"\xc7\xb9"=>"n","\xc7\xba"=>"A","\xc7\xbb"=>"a","\xc7\xbc"=>"AE",
	"\xc7\xbd"=>"ae","\xc7\xbe"=>"O","\xc7\xbf"=>"o","\xc8\x80"=>"A",
	"\xc8\x81"=>"a","\xc8\x82"=>"A","\xc8\x83"=>"a","\xc8\x84"=>"E",
	"\xc8\x85"=>"e","\xc8\x86"=>"E","\xc8\x87"=>"e","\xc8\x88"=>"I",
	"\xc8\x89"=>"i","\xc8\x8a"=>"I","\xc8\x8b"=>"i","\xc8\x8c"=>"O",
	"\xc8\x8d"=>"o","\xc8\x8e"=>"O","\xc8\x8f"=>"o","\xc8\x90"=>"R",
	"\xc8\x91"=>"r","\xc8\x92"=>"R","\xc8\x93"=>"r","\xc8\x94"=>"U",
	"\xc8\x95"=>"u","\xc8\x96"=>"U","\xc8\x97"=>"u","\xc8\x98"=>"S",
	"\xc8\x99"=>"s","\xc8\x9a"=>"T","\xc8\x9b"=>"t","\xc8\x9c"=>"Y",
	"\xc8\x9d"=>"y","\xc8\x9e"=>"H","\xc8\x9f"=>"h","\xc8\xa0"=>"n",
	"\xc8\xa1"=>"d","\xc8\xa2"=>"OU","\xc8\xa3"=>"ou","\xc8\xa4"=>"Z",
	"\xc8\xa5"=>"z","\xc8\xa6"=>"A","\xc8\xa7"=>"a","\xc8\xa8"=>"E",
	"\xc8\xa9"=>"e","\xc8\xaa"=>"O","\xc8\xab"=>"o","\xc8\xac"=>"O",
	"\xc8\xad"=>"o","\xc8\xae"=>"O","\xc8\xaf"=>"o","\xc8\xb0"=>"O",
	"\xc8\xb1"=>"o","\xc8\xb2"=>"Y","\xc8\xb3"=>"y","\xc8\xb4"=>"l",
	"\xc8\xb5"=>"n","\xc8\xb6"=>"t","\xc8\xb7"=>"j","\xc8\xb8"=>"db",
	"\xc8\xb9"=>"qp","\xc8\xba"=>"A","\xc8\xbb"=>"C","\xc8\xbc"=>"c",
	"\xc8\xbd"=>"L","\xc8\xbe"=>"T","\xc8\xbf"=>"s","\xc9\x80"=>"z",
	"\xc9\x81"=>"-","\xe1\xb8\x80"=>"A","\xe1\xb8\x81"=>"a","\xe1\xb8\x82"=>"B",
	"\xe1\xb8\x83"=>"b","\xe1\xb8\x84"=>"B","\xe1\xb8\x85"=>"b","\xe1\xb8\x86"=>"B",
	"\xe1\xb8\x87"=>"b","\xe1\xb8\x88"=>"C","\xe1\xb8\x89"=>"c","\xe1\xb8\x8a"=>"D",
	"\xe1\xb8\x8b"=>"d","\xe1\xb8\x8c"=>"D","\xe1\xb8\x8d"=>"d","\xe1\xb8\x8e"=>"D",
	"\xe1\xb8\x8f"=>"d","\xe1\xb8\x90"=>"D","\xe1\xb8\x91"=>"d","\xe1\xb8\x92"=>"D",
	"\xe1\xb8\x93"=>"d","\xe1\xb8\x94"=>"E","\xe1\xb8\x95"=>"e","\xe1\xb8\x96"=>"E",
	"\xe1\xb8\x97"=>"e","\xe1\xb8\x98"=>"E","\xe1\xb8\x99"=>"e","\xe1\xb8\x9a"=>"E",
	"\xe1\xb8\x9b"=>"e","\xe1\xb8\x9c"=>"E","\xe1\xb8\x9d"=>"e","\xe1\xb8\x9e"=>"F",
	"\xe1\xb8\x9f"=>"f","\xe1\xb8\xa0"=>"G","\xe1\xb8\xa1"=>"g","\xe1\xb8\xa2"=>"H",
	"\xe1\xb8\xa3"=>"h","\xe1\xb8\xa4"=>"H","\xe1\xb8\xa5"=>"h","\xe1\xb8\xa6"=>"H",
	"\xe1\xb8\xa7"=>"h","\xe1\xb8\xa8"=>"H","\xe1\xb8\xa9"=>"h","\xe1\xb8\xaa"=>"H",
	"\xe1\xb8\xab"=>"h","\xe1\xb8\xac"=>"I","\xe1\xb8\xad"=>"i","\xe1\xb8\xae"=>"I",
	"\xe1\xb8\xaf"=>"i","\xe1\xb8\xb0"=>"K","\xe1\xb8\xb1"=>"k","\xe1\xb8\xb2"=>"K",
	"\xe1\xb8\xb3"=>"k","\xe1\xb8\xb4"=>"K","\xe1\xb8\xb5"=>"k","\xe1\xb8\xb6"=>"L",
	"\xe1\xb8\xb7"=>"l","\xe1\xb8\xb8"=>"L","\xe1\xb8\xb9"=>"l","\xe1\xb8\xba"=>"L",
	"\xe1\xb8\xbb"=>"l","\xe1\xb8\xbc"=>"L","\xe1\xb8\xbd"=>"l","\xe1\xb8\xbe"=>"M",
	"\xe1\xb8\xbf"=>"m","\xe1\xb9\x80"=>"M","\xe1\xb9\x81"=>"m","\xe1\xb9\x82"=>"M",
	"\xe1\xb9\x83"=>"m","\xe1\xb9\x84"=>"N","\xe1\xb9\x85"=>"n","\xe1\xb9\x86"=>"N",
	"\xe1\xb9\x87"=>"n","\xe1\xb9\x88"=>"N","\xe1\xb9\x89"=>"n","\xe1\xb9\x8a"=>"N",
	"\xe1\xb9\x8b"=>"n","\xe1\xb9\x8c"=>"O","\xe1\xb9\x8d"=>"o","\xe1\xb9\x8e"=>"O",
	"\xe1\xb9\x8f"=>"o","\xe1\xb9\x90"=>"O","\xe1\xb9\x91"=>"o","\xe1\xb9\x92"=>"O",
	"\xe1\xb9\x93"=>"o","\xe1\xb9\x94"=>"P","\xe1\xb9\x95"=>"p","\xe1\xb9\x96"=>"P",
	"\xe1\xb9\x97"=>"p","\xe1\xb9\x98"=>"R","\xe1\xb9\x99"=>"r","\xe1\xb9\x9a"=>"R",
	"\xe1\xb9\x9b"=>"r","\xe1\xb9\x9c"=>"R","\xe1\xb9\x9d"=>"r","\xe1\xb9\x9e"=>"R",
	"\xe1\xb9\x9f"=>"r","\xe1\xb9\xa0"=>"S","\xe1\xb9\xa1"=>"s","\xe1\xb9\xa2"=>"S",
	"\xe1\xb9\xa3"=>"s","\xe1\xb9\xa4"=>"S","\xe1\xb9\xa5"=>"s","\xe1\xb9\xa6"=>"S",
	"\xe1\xb9\xa7"=>"s","\xe1\xb9\xa8"=>"S","\xe1\xb9\xa9"=>"s","\xe1\xb9\xaa"=>"T",
	"\xe1\xb9\xab"=>"t","\xe1\xb9\xac"=>"T","\xe1\xb9\xad"=>"t","\xe1\xb9\xae"=>"T",
	"\xe1\xb9\xaf"=>"t","\xe1\xb9\xb0"=>"T","\xe1\xb9\xb1"=>"t","\xe1\xb9\xb2"=>"U",
	"\xe1\xb9\xb3"=>"u","\xe1\xb9\xb4"=>"U","\xe1\xb9\xb5"=>"u","\xe1\xb9\xb6"=>"U",
	"\xe1\xb9\xb7"=>"u","\xe1\xb9\xb8"=>"U","\xe1\xb9\xb9"=>"u","\xe1\xb9\xba"=>"U",
	"\xe1\xb9\xbb"=>"u","\xe1\xb9\xbc"=>"V","\xe1\xb9\xbd"=>"v","\xe1\xb9\xbe"=>"V",
	"\xe1\xb9\xbf"=>"v","\xe1\xba\x80"=>"W","\xe1\xba\x81"=>"w","\xe1\xba\x82"=>"W",
	"\xe1\xba\x83"=>"w","\xe1\xba\x84"=>"W","\xe1\xba\x85"=>"w","\xe1\xba\x86"=>"W",
	"\xe1\xba\x87"=>"w","\xe1\xba\x88"=>"W","\xe1\xba\x89"=>"w","\xe1\xba\x8a"=>"X",
	"\xe1\xba\x8b"=>"x","\xe1\xba\x8c"=>"X","\xe1\xba\x8d"=>"x","\xe1\xba\x8e"=>"Y",
	"\xe1\xba\x8f"=>"y","\xe1\xba\x90"=>"Z","\xe1\xba\x91"=>"z","\xe1\xba\x92"=>"Z",
	"\xe1\xba\x93"=>"z","\xe1\xba\x94"=>"Z","\xe1\xba\x95"=>"z","\xe1\xba\x96"=>"h",
	"\xe1\xba\x97"=>"t","\xe1\xba\x98"=>"w","\xe1\xba\x99"=>"y","\xe1\xba\x9a"=>"a",
	"\xe1\xba\x9b"=>"s","\xe1\xba\xa0"=>"A","\xe1\xba\xa1"=>"a","\xe1\xba\xa2"=>"A",
	"\xe1\xba\xa3"=>"a","\xe1\xba\xa4"=>"A","\xe1\xba\xa5"=>"a","\xe1\xba\xa6"=>"A",
	"\xe1\xba\xa7"=>"a","\xe1\xba\xa8"=>"A","\xe1\xba\xa9"=>"a","\xe1\xba\xaa"=>"A",
	"\xe1\xba\xab"=>"a","\xe1\xba\xac"=>"A","\xe1\xba\xad"=>"a","\xe1\xba\xae"=>"A",
	"\xe1\xba\xaf"=>"a","\xe1\xba\xb0"=>"A","\xe1\xba\xb1"=>"a","\xe1\xba\xb2"=>"A",
	"\xe1\xba\xb3"=>"a","\xe1\xba\xb4"=>"A","\xe1\xba\xb5"=>"a","\xe1\xba\xb6"=>"A",
	"\xe1\xba\xb7"=>"a","\xe1\xba\xb8"=>"E","\xe1\xba\xb9"=>"e","\xe1\xba\xba"=>"E",
	"\xe1\xba\xbb"=>"e","\xe1\xba\xbc"=>"E","\xe1\xba\xbd"=>"e","\xe1\xba\xbe"=>"E",
	"\xe1\xba\xbf"=>"e","\xe1\xbb\x80"=>"E","\xe1\xbb\x81"=>"e","\xe1\xbb\x82"=>"E",
	"\xe1\xbb\x83"=>"e","\xe1\xbb\x84"=>"E","\xe1\xbb\x85"=>"e","\xe1\xbb\x86"=>"E",
	"\xe1\xbb\x87"=>"e","\xe1\xbb\x88"=>"I","\xe1\xbb\x89"=>"i","\xe1\xbb\x8a"=>"I",
	"\xe1\xbb\x8b"=>"i","\xe1\xbb\x8c"=>"O","\xe1\xbb\x8d"=>"o","\xe1\xbb\x8e"=>"O",
	"\xe1\xbb\x8f"=>"o","\xe1\xbb\x90"=>"O","\xe1\xbb\x91"=>"o","\xe1\xbb\x92"=>"O",
	"\xe1\xbb\x93"=>"o","\xe1\xbb\x94"=>"O","\xe1\xbb\x95"=>"o","\xe1\xbb\x96"=>"O",
	"\xe1\xbb\x97"=>"o","\xe1\xbb\x98"=>"O","\xe1\xbb\x99"=>"o","\xe1\xbb\x9a"=>"O",
	"\xe1\xbb\x9b"=>"o","\xe1\xbb\x9c"=>"O","\xe1\xbb\x9d"=>"o","\xe1\xbb\x9e"=>"O",
	"\xe1\xbb\x9f"=>"o","\xe1\xbb\xa0"=>"O","\xe1\xbb\xa1"=>"o","\xe1\xbb\xa2"=>"O",
	"\xe1\xbb\xa3"=>"o","\xe1\xbb\xa4"=>"U","\xe1\xbb\xa5"=>"u","\xe1\xbb\xa6"=>"U",
	"\xe1\xbb\xa7"=>"u","\xe1\xbb\xa8"=>"U","\xe1\xbb\xa9"=>"u","\xe1\xbb\xaa"=>"U",
	"\xe1\xbb\xab"=>"u","\xe1\xbb\xac"=>"U","\xe1\xbb\xad"=>"u","\xe1\xbb\xae"=>"U",
	"\xe1\xbb\xaf"=>"u","\xe1\xbb\xb0"=>"U","\xe1\xbb\xb1"=>"u","\xe1\xbb\xb2"=>"Y",
	"\xe1\xbb\xb3"=>"y","\xe1\xbb\xb4"=>"Y","\xe1\xbb\xb5"=>"y","\xe1\xbb\xb6"=>"Y",
	"\xe1\xbb\xb7"=>"y","\xe1\xbb\xb8"=>"Y","\xe1\xbb\xb9"=>"y",
	// Cyrilic
	"\xd0\x90"=>"A","\xd0\xb0"=>"a","\xd3\x90"=>"A","\xd3\x91"=>"a",
	"\xd3\x92"=>"A","\xd3\x93"=>"a","\xd3\x94"=>"A","\xd3\x95"=>"a",
	"\xd3\x98"=>"A","\xd3\x99"=>"a","\xd3\x9a"=>"A","\xd3\x9b"=>"a",
	"\xd0\x91"=>"B","\xd0\xb1"=>"b","\xd0\x92"=>"V","\xd0\xb2"=>"v",
	"\xd0\x93"=>"G","\xd0\xb3"=>"g","\xd2\x90"=>"Gh","\xd2\x91"=>"gh",
	"\xd2\x94"=>"G","\xd2\x95"=>"g","\xd2\x92"=>"G","\xd2\x93"=>"g",
	"\xd3\xb6"=>"G","\xd3\xb7"=>"g","\xd0\x94"=>"D","\xd0\xb4"=>"d",
	"\xd0\x82"=>"D","\xd1\x92"=>"d","\xd0\x83"=>"G","\xd1\x93"=>"g",
	"\xd0\x80"=>"E","\xd1\x90"=>"e","\xd0\x95"=>"E","\xd0\xb5"=>"e",
	"\xd0\x81"=>"Jo","\xd1\x91"=>"jo","\xd3\x96"=>"E","\xd3\x97"=>"e",
	"\xd0\x84"=>"Je","\xd1\x94"=>"je","\xd2\xbc"=>"C","\xd2\xbd"=>"c",
	"\xd2\xbe"=>"C","\xd2\xbf"=>"c","\xd0\x96"=>"Zh","\xd0\xb6"=>"zh",
	"\xd3\x81"=>"Z","\xd3\x82"=>"z","\xd3\x9c"=>"Z","\xd3\x9d"=>"z",
	"\xd2\x96"=>"Z","\xd2\x97"=>"z","\xd0\x97"=>"Z","\xd0\xb7"=>"z",
	"\xd3\x9e"=>"Z","\xd3\x9f"=>"z","\xd0\x85"=>"Z","\xd1\x95"=>"z",
	"\xd3\xa0"=>"Z","\xd3\xa1"=>"z","\xd0\x8d"=>"I","\xd1\x9d"=>"i",
	"\xd0\x98"=>"I","\xd0\xb8"=>"i","\xd3\xa2"=>"I","\xd3\xa3"=>"i",
	"\xd3\xa4"=>"I","\xd3\xa5"=>"i","\xd0\x86"=>"I","\xd1\x96"=>"i",
	"\xd0\x87"=>"Ji","\xd1\x97"=>"ji","\xd0\x99"=>"J","\xd0\xb9"=>"j",
	"\xd0\x88"=>"J","\xd1\x98"=>"j","\xd0\x9a"=>"K","\xd0\xba"=>"k",
	"\xd2\x9a"=>"K","\xd2\x9b"=>"k","\xd2\x9c"=>"K","\xd2\x9d"=>"k",
	"\xd2\x9e"=>"K","\xd2\x9f"=>"k","\xd2\xa0"=>"K","\xd2\xa1"=>"k",
	"\xd0\x9b"=>"L","\xd0\xbb"=>"l","\xd0\x89"=>"L","\xd1\x99"=>"l",
	"\xd0\x9c"=>"M","\xd0\xbc"=>"m","\xd0\x9d"=>"N","\xd0\xbd"=>"n",
	"\xd0\x8a"=>"N","\xd1\x9a"=>"n","\xd2\xa4"=>"N","\xd2\xa5"=>"n",
	"\xd2\xa2"=>"N","\xd2\xa3"=>"n","\xd0\x9e"=>"O","\xd0\xbe"=>"o",
	"\xd3\xa6"=>"O","\xd3\xa7"=>"o","\xd3\xa8"=>"O","\xd3\xa9"=>"o",
	"\xd3\xaa"=>"O","\xd3\xab"=>"o","\xd0\x9f"=>"P","\xd0\xbf"=>"p",
	"\xd2\xa6"=>"P","\xd2\xa7"=>"p","\xd0\xa0"=>"R","\xd1\x80"=>"r",
	"\xd0\xa1"=>"S","\xd1\x81"=>"s","\xd2\xaa"=>"C","\xd2\xab"=>"c",
	"\xd0\xa2"=>"T","\xd1\x82"=>"t","\xd2\xac"=>"T","\xd2\xad"=>"t",
	"\xd0\x8b"=>"C","\xd1\x9b"=>"c","\xd0\x8c"=>"K","\xd1\x9c"=>"k",
	"\xd0\xa3"=>"U","\xd1\x83"=>"u","\xd0\x8e"=>"U","\xd1\x9e"=>"u",
	"\xd3\xae"=>"U","\xd3\xaf"=>"u","\xd3\xb0"=>"U","\xd3\xb1"=>"u",
	"\xd3\xb2"=>"U","\xd3\xb3"=>"u","\xd2\xae"=>"U","\xd2\xaf"=>"u",
	"\xd2\xb0"=>"U","\xd2\xb1"=>"u","\xd0\xa4"=>"F","\xd1\x84"=>"f",
	"\xd0\xa5"=>"X","\xd1\x85"=>"x","\xd2\xb2"=>"H","\xd2\xb3"=>"h",
	"\xd2\xba"=>"H","\xd2\xbb"=>"h","\xd0\xa6"=>"C","\xd1\x86"=>"c",
	"\xd2\xb4"=>"C","\xd2\xb5"=>"c","\xd0\xa7"=>"Ch","\xd1\x87"=>"ch",
	"\xd3\xb4"=>"C","\xd3\xb5"=>"c","\xd2\xb6"=>"C","\xd2\xb7"=>"c",
	"\xd2\xb8"=>"C","\xd2\xb9"=>"c","\xd0\x8f"=>"D","\xd1\x9f"=>"d",
	"\xd0\xa8"=>"Sh","\xd1\x88"=>"sh","\xd0\xa9"=>"Sch","\xd1\x89"=>"sch",
	"\xd0\xab"=>"Y","\xd1\x8b"=>"y","\xd3\xb8"=>"Y","\xd3\xb9"=>"y",
	"\xd0\xad"=>"Eh","\xd1\x8d"=>"eh","\xd3\xac"=>"E","\xd3\xad"=>"e",
	"\xd0\xae"=>"Ju","\xd1\x8e"=>"ju","\xd0\xaf"=>"Ja","\xd1\x8f"=>"ja",
	"\xd1\xa2"=>"E","\xd1\xa3"=>"e","\xd1\xaa"=>"A","\xd1\xab"=>"a",
	"\xd1\xb2"=>"F","\xd1\xb3"=>"f","\xd1\xb4"=>"Y","\xd1\xb5"=>"y",
	"\xd1\xb6"=>"Y","\xd1\xb7"=>"y","\xd2\xa8"=>"O","\xd2\xa9"=>"o",
	"\xd1\xa0"=>"O","\xd1\xa1"=>"o","\xd1\xa4"=>"E","\xd1\xa5"=>"e",
	"\xd1\xa6"=>"U","\xd1\xa7"=>"u","\xd1\xa8"=>"U","\xd1\xa9"=>"u",
	"\xd1\xac"=>"U","\xd1\xad"=>"u","\xd1\xae"=>"K","\xd1\xaf"=>"k",
	"\xd1\xb0"=>"P","\xd1\xb1"=>"p","\xd1\xb8"=>"U","\xd1\xb9"=>"u",
	"\xd1\xba"=>"O","\xd1\xbb"=>"o","\xd1\xbc"=>"O","\xd1\xbd"=>"o",
	"\xd1\xbe"=>"O","\xd1\xbf"=>"o","\xd2\x80"=>"K","\xd2\x81"=>"k",
	"\xd2\x8a"=>"J","\xd2\x8b"=>"j","\xd2\x8e"=>"r","\xd2\x98"=>"Z",
	"\xd2\x99"=>"z","\xd3\x83"=>"K","\xd3\x84"=>"k","\xd3\x85"=>"L",
	"\xd3\x86"=>"l","\xd3\x87"=>"N","\xd3\x88"=>"n","\xd3\x89"=>"N",
	"\xd3\x8a"=>"n","\xd3\x8b"=>"C","\xd3\x8c"=>"c","\xd3\x8d"=>"M",
	"\xd3\x8e"=>"m","\xd1\x8a"=>"","\xd0\xaa"=>"","\xd0\xac"=>"",
	"\xd1\x8c"=>"","\xd2\x8c"=>"-","\xd3\x80"=>"-","\xcc\x81"=>"",
	// Greek
	"\xce\xb1\xce\xb9"=>"e","\xce\x91\xce\xb9"=>"E","\xce\xb5\xce\xb9"=>"i",
	"\xce\x95\xce\xb9"=>"I","\xce\xbf\xce\xb9"=>"i","\xce\x9f\xce\xb9"=>"I","\xce\xbf\xcf\x85"=>"ou",
	"\xce\x9f\xcf\x85"=>"Ou","\xce\xb1\xcf\x85"=>"av","\xce\x91\xcf\x85"=>"Av","\xce\xb5\xcf\x85"=>"ev",
	"\xce\x95\xcf\x85"=>"Ev","\xce\xb7\xcf\x85"=>"iv","\xce\x97\xcf\x85"=>"Iv","\xce\xbc\xcf\x80"=>"mp",
	"\xce\x9c\xcf\x80"=>"B","\xce\xbd\xcf\x84"=>"nt","\xce\x9d\xcf\x84"=>"D","\xcf\x84\xce\xb6"=>"tz",
	"\xce\xa4\xce\xb6"=>"Tz","\xce\xb3\xce\xba"=>"ng","\xce\x93\xce\xba"=>"G","\xce\xb3\xce\xb3"=>"ng",
	"\xce\x93\xce\xb3"=>"Ng","\xce\x86"=>"A","\xce\x88"=>"E","\xce\x89"=>"I",
	"\xce\x8a"=>"I","\xce\x8c"=>"O","\xce\x8e"=>"Y","\xce\x8f"=>"O",
	"\xce\x90"=>"i","\xce\x91"=>"A","\xce\x92"=>"V","\xce\x93"=>"G",
	"\xce\x94"=>"D","\xce\x95"=>"E","\xce\x96"=>"Z","\xce\x97"=>"I",
	"\xce\x98"=>"Th","\xce\x99"=>"I","\xce\x9a"=>"K","\xce\x9b"=>"L",
	"\xce\x9c"=>"M","\xce\x9d"=>"N","\xce\x9e"=>"X","\xce\x9f"=>"O",
	"\xce\xa0"=>"P","\xce\xa1"=>"R","\xce\xa3"=>"S","\xce\xa4"=>"T",
	"\xce\xa5"=>"Y","\xce\xa6"=>"F","\xce\xa7"=>"Ch","\xce\xa8"=>"Ps",
	"\xce\xa9"=>"O","\xce\xaa"=>"I","\xce\xab"=>"Y","\xce\xac"=>"a",
	"\xce\xad"=>"e","\xce\xae"=>"i","\xce\xaf"=>"i","\xce\xb0"=>"y",
	"\xce\xb1"=>"a","\xce\xb2"=>"v","\xce\xb3"=>"g","\xce\xb4"=>"d",
	"\xce\xb5"=>"e","\xce\xb6"=>"z","\xce\xb7"=>"i","\xce\xb8"=>"th",
	"\xce\xb9"=>"i","\xce\xba"=>"k","\xce\xbb"=>"l","\xce\xbc"=>"m",
	"\xce\xbd"=>"n","\xce\xbe"=>"x","\xce\xbf"=>"o","\xcf\x80"=>"p",
	"\xcf\x81"=>"r","\xcf\x82"=>"s","\xcf\x83"=>"s","\xcf\x84"=>"t",
	"\xcf\x85"=>"y","\xcf\x86"=>"f","\xcf\x87"=>"ch","\xcf\x88"=>"ps",
	"\xcf\x89"=>"o","\xcf\x8a"=>"i","\xcf\x8b"=>"y","\xcf\x8c"=>"o",
	"\xcf\x8d"=>"y","\xcf\x8e"=>"o","\xcf\x90"=>"b","\xcf\x91"=>"th",
	"\xcf\x92"=>"y","\xcf\x93"=>"y","\xcf\x94"=>"y",
	// Georgian
	"\xe1\x83\x90"=>"a","\xe1\x83\x91"=>"b","\xe1\x83\x92"=>"g","\xe1\x83\x93"=>"d",
	"\xe1\x83\x94"=>"e","\xe1\x83\x95"=>"v","\xe1\x83\x96"=>"z","\xe1\x83\x97"=>"th",
	"\xe1\x83\x98"=>"i","\xe1\x83\x99"=>"p","\xe1\x83\x9a"=>"l","\xe1\x83\x9b"=>"m",
	"\xe1\x83\x9c"=>"n","\xe1\x83\x9d"=>"o","\xe1\x83\x9e"=>"p","\xe1\x83\x9f"=>"zh",
	"\xe1\x83\xa0"=>"r","\xe1\x83\xa1"=>"s","\xe1\x83\xa2"=>"t","\xe1\x83\xa3"=>"u",
	"\xe1\x83\xa4"=>"ph","\xe1\x83\xa5"=>"kh","\xe1\x83\xa6"=>"gh","\xe1\x83\xa7"=>"q",
	"\xe1\x83\xa8"=>"sh","\xe1\x83\xa9"=>"ch","\xe1\x83\xaa"=>"c","\xe1\x83\xab"=>"dh",
	"\xe1\x83\xac"=>"w","\xe1\x83\xad"=>"j","\xe1\x83\xae"=>"x","\xe1\x83\xaf"=>"jh",
	"\xe1\x83\xb0"=>"xh",
	// Sanskrit
	"\xe0\xa4\x85\xe0\xa4\x82"=>"amh","\xe0\xa4\x85\xe0\xa4\x83"=>"aq","\xe0\xa4\x95"=>"k","\xe0\xa4\x96"=>"kh",
	"\xe0\xa4\x85"=>"a","\xe0\xa4\x86"=>"ah",
	"\xe0\xa4\x87"=>"i","\xe0\xa4\x88"=>"ih","\xe0\xa4\x89"=>"u","\xe0\xa4\x8a"=>"uh",
	"\xe0\xa4\x8b"=>"ry","\xe0\xa5\xa0"=>"ryh","\xe0\xa4\x8c"=>"ly","\xe0\xa5\xa1"=>"lyh",
	"\xe0\xa4\x8f"=>"e","\xe0\xa4\x90"=>"ay","\xe0\xa4\x93"=>"o","\xe0\xa4\x94"=>"aw",
	"\xe0\xa4\x97"=>"g","\xe0\xa4\x98"=>"gh","\xe0\xa4\x99"=>"nh","\xe0\xa4\x9a"=>"c",
	"\xe0\xa4\x9b"=>"ch","\xe0\xa4\x9c"=>"j","\xe0\xa4\x9d"=>"jh","\xe0\xa4\x9e"=>"ny",
	"\xe0\xa4\x9f"=>"tq","\xe0\xa4\xa0"=>"tqh","\xe0\xa4\xa1"=>"dq","\xe0\xa4\xa2"=>"dqh",
	"\xe0\xa4\xa3"=>"nq","\xe0\xa4\xa4"=>"t","\xe0\xa4\xa5"=>"th","\xe0\xa4\xa6"=>"d",
	"\xe0\xa4\xa7"=>"dh","\xe0\xa4\xa8"=>"n","\xe0\xa4\xaa"=>"p","\xe0\xa4\xab"=>"ph",
	"\xe0\xa4\xac"=>"b","\xe0\xa4\xad"=>"bh","\xe0\xa4\xae"=>"m","\xe0\xa4\xaf"=>"z",
	"\xe0\xa4\xb0"=>"r","\xe0\xa4\xb2"=>"l","\xe0\xa4\xb5"=>"v","\xe0\xa4\xb6"=>"sh",
	"\xe0\xa4\xb7"=>"sqh","\xe0\xa4\xb8"=>"s","\xe0\xa4\xb9"=>"x",
	// Hebrew - thanks to forum-member iti
	"\xd7\x90\xd7\x95"=>"ao", "\xd7\x91\xd7\x95"=>"bo",
   "\xd7\x92\xd7\x95"=>"go", "\xd7\x93\xd7\x95"=>"do",
   "\xd7\x94\xd7\x95"=>"ho", "\xd7\x95\xd7\x95"=>"v",
   "\xd7\x96\xd7\x95"=>"zo", "\xd7\x97\xd7\x95"=>"cho",
   "\xd7\x98\xd7\x95"=>"to", "\xd7\x95\xd7\x99\xd7\x99"=>"vyi",
   "\xd7\x99\xd7\x95"=>"io", "\xd7\x9a\xd7\x95"=>"kho",
   "\xd7\x9b\xd7\x95"=>"ko", "\xd7\x9c\xd7\x95"=>"lo",
   "\xd7\x9e\xd7\x95"=>"mo", "\xd7\xa0\xd7\x95"=>"no",
   "\xd7\xa1\xd7\x95"=>"so", "\xd7\xa2\xd7\x95"=>"ao",
   "\xd7\xa4\xd7\x95"=>"po", "\xd7\xa6\xd7\x95"=>"tzo",
   "\xd7\xa7\xd7\x95"=>"qo", "\xd7\xa8\xd7\x95"=>"ro",
   "\xd7\xa9\xd7\x95"=>"sho", "\xd7\xaa\xd7\x95"=>"to",
   "\xd7\x99\xd7\x99"=>"yi", "\xd7\x99\xd7\x95" =>"yo",
   "\xd7\x90"=>"a", "\xd7\x91"=>"b", "\xd7\x92"=>"g", "\xd7\x93"=>"d",
   "\xd7\x94"=>"h", "\xd7\x95"=>"v", "\xd7\x96"=>"z", "\xd7\x97"=>"ch",
   "\xd7\x98"=>"t", "\xd7\x99"=>"i", "\xd7\x9a"=>"kh", "\xd7\x9b"=>"k",
   "\xd7\x9c"=>"l", "\xd7\x9d"=>"m", "\xd7\x9e"=>"m", "\xd7\x9f"=>"n",
   "\xd7\xa0"=>"n", "\xd7\xa1"=>"s", "\xd7\xa2"=>"a", "\xd7\xa3"=>"f",
   "\xd7\xa4"=>"p", "\xd7\xa5"=>"tz", "\xd7\xa6"=>"tz", "\xd7\xa7"=>"q",
   "\xd7\xa8"=>"r", "\xd7\xa9"=>"sh", "\xd7\xaa"=>"t",
	// Arabic
	"\xd8\xa7"=>"a","\xd8\xa8"=>"b","\xd8\xaa"=>"t","\xd8\xab"=>"th",
	"\xd8\xac"=>"g","\xd8\xad"=>"xh","\xd8\xae"=>"x","\xd8\xaf"=>"d",
	"\xd8\xb0"=>"dh","\xd8\xb1"=>"r","\xd8\xb2"=>"z","\xd8\xb3"=>"s",
	"\xd8\xb4"=>"sh","\xd8\xb5"=>"s_","\xd8\xb6"=>"d_","\xd8\xb7"=>"t_",
	"\xd8\xb8"=>"z_","\xd8\xb9"=>"y","\xd8\xba"=>"gh","\xd9\x81"=>"f",
	"\xd9\x82"=>"q","\xd9\x83"=>"k","\xd9\x84"=>"l","\xd9\x85"=>"m",
	"\xd9\x86"=>"n","\xd9\x87"=>"x_","\xd9\x88"=>"u","\xd9\x8a"=>"i",
	// Japanese hiragana
	"\xe3\x81\xb3\xe3\x82\x83"=>"bya","\xe3\x81\xb3\xe3\x81\x87"=>"bye","\xe3\x81\xb3\xe3\x81\x83"=>"byi",
	"\xe3\x81\xb3\xe3\x82\x87"=>"byo","\xe3\x81\xb3\xe3\x82\x85"=>"byu","\xe3\x81\xa1\xe3\x82\x83"=>"tya","\xe3\x81\xa1\xe3\x81\x87"=>"tye",
	"\xe3\x81\xa1\xe3\x82\x87"=>"tyo","\xe3\x81\xa1\xe3\x82\x85"=>"tyu","\xe3\x81\xa1\xe3\x81\x83"=>"tyi",
	"\xe3\x81\xa7\xe3\x82\x83"=>"dha","\xe3\x81\xa7\xe3\x81\x87"=>"dhe","\xe3\x81\xa7\xe3\x81\x83"=>"dhi","\xe3\x81\xa7\xe3\x82\x87"=>"dho",
	"\xe3\x81\xa7\xe3\x82\x85"=>"dhu","\xe3\x81\xa9\xe3\x81\x81"=>"dwa","\xe3\x81\xa9\xe3\x81\x87"=>"dwe","\xe3\x81\xa9\xe3\x81\x83"=>"dwi",
	"\xe3\x81\xa9\xe3\x81\x89"=>"dwo","\xe3\x81\xa9\xe3\x81\x85"=>"dwu","\xe3\x81\xa2\xe3\x82\x83"=>"dya","\xe3\x81\xa2\xe3\x81\x87"=>"dye",
	"\xe3\x81\xa2\xe3\x81\x83"=>"dyi","\xe3\x81\xa2\xe3\x82\x87"=>"dyo","\xe3\x81\xa2\xe3\x82\x85"=>"dyu","\xe3\x81\xa2"=>"di",
	"\xe3\x81\xb5\xe3\x81\x81"=>"fa","\xe3\x81\xb5\xe3\x81\x87"=>"fe","\xe3\x81\xb5\xe3\x81\x83"=>"fi","\xe3\x81\xb5\xe3\x81\x89"=>"fo",
	"\xe3\x81\xb5\xe3\x81\x85"=>"fwu","\xe3\x81\xb5\xe3\x82\x83"=>"fya","\xe3\x81\xb5\xe3\x82\x87"=>"fyo","\xe3\x81\xb5\xe3\x82\x85"=>"fyu",
	"\xe3\x81\x8e\xe3\x82\x83"=>"gya","\xe3\x81\x8e\xe3\x81\x87"=>"gye","\xe3\x81\x8e\xe3\x81\x83"=>"gyi","\xe3\x81\x8e\xe3\x82\x87"=>"gyo",
	"\xe3\x81\x8e\xe3\x82\x85"=>"gyu","\xe3\x81\xb2\xe3\x82\x83"=>"hya","\xe3\x81\xb2\xe3\x81\x87"=>"hye","\xe3\x81\xb2\xe3\x81\x83"=>"hyi",
	"\xe3\x81\xb2\xe3\x82\x87"=>"hyo","\xe3\x81\xb2\xe3\x82\x85"=>"hyu","\xe3\x81\x98\xe3\x82\x83"=>"ja","\xe3\x81\x98\xe3\x81\x87"=>"je",
	"\xe3\x81\x98\xe3\x81\x83"=>"zyi","\xe3\x81\x98\xe3\x82\x87"=>"jo","\xe3\x81\x98\xe3\x82\x85"=>"ju","\xe3\x81\x8d\xe3\x82\x83"=>"kya",
	"\xe3\x81\x8d\xe3\x81\x87"=>"kye","\xe3\x81\x8d\xe3\x81\x83"=>"kyi","\xe3\x81\x8d\xe3\x82\x87"=>"kyo","\xe3\x81\x8d\xe3\x82\x85"=>"kyu",
	"\xe3\x82\x8a\xe3\x82\x83"=>"rya","\xe3\x82\x8a\xe3\x81\x87"=>"rye","\xe3\x82\x8a\xe3\x81\x83"=>"ryi","\xe3\x82\x8a\xe3\x82\x87"=>"ryo",
	"\xe3\x82\x8a\xe3\x82\x85"=>"ryu","\xe3\x81\xbf\xe3\x82\x83"=>"mya","\xe3\x81\xbf\xe3\x81\x87"=>"mye","\xe3\x81\xbf\xe3\x81\x83"=>"myi",
	"\xe3\x81\xbf\xe3\x82\x87"=>"myo","\xe3\x81\xbf\xe3\x82\x85"=>"myu","\xe3\x81\xab\xe3\x82\x83"=>"nya",
	"\xe3\x81\xab\xe3\x81\x87"=>"nye","\xe3\x81\xab\xe3\x81\x83"=>"nyi","\xe3\x81\xab\xe3\x82\x87"=>"nyo","\xe3\x81\xab\xe3\x82\x85"=>"nyu",
	"\xe3\x81\xb4\xe3\x82\x83"=>"pya","\xe3\x81\xb4\xe3\x81\x87"=>"pye","\xe3\x81\xb4\xe3\x81\x83"=>"pyi","\xe3\x81\xb4\xe3\x82\x87"=>"pyo",
	"\xe3\x81\xb4\xe3\x82\x85"=>"pyu","\xe3\x81\x97\xe3\x82\x83"=>"sya","\xe3\x81\x97\xe3\x81\x87"=>"sye","\xe3\x81\x97"=>"si",
	"\xe3\x81\x97\xe3\x82\x87"=>"syo","\xe3\x81\x97\xe3\x82\x85"=>"syu","\xe3\x81\x99\xe3\x81\x81"=>"swa","\xe3\x81\x99\xe3\x81\x87"=>"swe",
	"\xe3\x81\x99\xe3\x81\x83"=>"swi","\xe3\x81\x99\xe3\x81\x89"=>"swo","\xe3\x81\x99\xe3\x81\x85"=>"swu","\xe3\x81\x97\xe3\x81\x83"=>"syi",
	"\xe3\x81\xa6\xe3\x82\x83"=>"tha","\xe3\x81\xa6\xe3\x81\x87"=>"the","\xe3\x81\xa6\xe3\x81\x83"=>"thi","\xe3\x81\xa6\xe3\x82\x87"=>"tho",
	"\xe3\x81\xa6\xe3\x82\x85"=>"thu","\xe3\x81\xa4\xe3\x82\x83"=>"tsa","\xe3\x81\xa4\xe3\x81\x87"=>"tse","\xe3\x81\xa4\xe3\x81\x83"=>"tsi",
	"\xe3\x81\xa4\xe3\x82\x87"=>"tso","\xe3\x81\xa4"=>"tu","\xe3\x81\xa8\xe3\x81\x81"=>"twa","\xe3\x81\xa8\xe3\x81\x87"=>"twe",
	"\xe3\x81\xa8\xe3\x81\x83"=>"twi","\xe3\x81\xa8\xe3\x81\x89"=>"two","\xe3\x81\xa8\xe3\x81\x85"=>"twu","\xe3\x83\xb4\xe3\x82\x83"=>"vya",
	"\xe3\x83\xb4\xe3\x81\x87"=>"ve","\xe3\x83\xb4\xe3\x81\x83"=>"vi","\xe3\x83\xb4\xe3\x82\x87"=>"vyo","\xe3\x83\xb4\xe3\x82\x85"=>"vyu",
	"\xe3\x81\x86\xe3\x81\x81"=>"wha","\xe3\x81\x86\xe3\x81\x87"=>"we","\xe3\x81\x86\xe3\x81\x83"=>"wi","\xe3\x81\x86\xe3\x81\x89"=>"who",
	"\xe3\x81\x86\xe3\x81\x85"=>"whu","\xe3\x82\x91"=>"wye","\xe3\x82\x90"=>"wyi","\xe3\x81\x84\xe3\x81\x87"=>"ye",
	"\xe3\x82\x93"=>"n","\xe3\x81\x82"=>"a",
	"\xe3\x81\x88"=>"e","\xe3\x81\x84"=>"yi","\xe3\x81\x8a"=>"o","\xe3\x81\x86"=>"u",
	"\xe3\x81\xb0"=>"ba","\xe3\x81\xb9"=>"be","\xe3\x81\xb3"=>"bi","\xe3\x81\xbc"=>"bo",
	"\xe3\x81\xb6"=>"bu","\xe3\x81\xa0"=>"da","\xe3\x81\xa7"=>"de","\xe3\x81\xa9"=>"do",
	"\xe3\x81\xa5"=>"du","\xe3\x81\xb5"=>"hu","\xe3\x81\x8c"=>"ga","\xe3\x81\x92"=>"ge",
	"\xe3\x81\x8e"=>"gi","\xe3\x81\x94"=>"go","\xe3\x81\x90"=>"gu","\xe3\x81\xaf"=>"ha",
	"\xe3\x81\xb8"=>"he","\xe3\x81\xb2"=>"hi","\xe3\x81\xbb"=>"ho","\xe3\x81\x98"=>"zi",
	"\xe3\x81\x8b"=>"ka","\xe3\x81\x91"=>"ke","\xe3\x81\x8d"=>"ki","\xe3\x81\x93"=>"ko",
	"\xe3\x81\x8f"=>"ku","\xe3\x82\x89"=>"ra","\xe3\x82\x8c"=>"re","\xe3\x82\x8a"=>"ri",
	"\xe3\x82\x8d"=>"ro","\xe3\x82\x8b"=>"ru","\xe3\x81\xbe"=>"ma","\xe3\x82\x81"=>"me",
	"\xe3\x81\xbf"=>"mi","\xe3\x82\x82"=>"mo","\xe3\x82\x80"=>"mu","\xe3\x81\xaa"=>"na",
	"\xe3\x81\xad"=>"ne","\xe3\x81\xab"=>"ni","\xe3\x81\xae"=>"no","\xe3\x81\xac"=>"nu",
	"\xe3\x81\xb1"=>"pa","\xe3\x81\xba"=>"pe","\xe3\x81\xb4"=>"pi","\xe3\x81\xbd"=>"po",
	"\xe3\x81\xb7"=>"pu","\xe3\x81\x95"=>"sa","\xe3\x81\x9b"=>"se","\xe3\x81\x9d"=>"so",
	"\xe3\x81\x99"=>"su","\xe3\x81\x9f"=>"ta","\xe3\x81\xa6"=>"te","\xe3\x81\xa8"=>"to","\xe3\x81\xa1"=>"ti",
	"\xe3\x83\xb4\xe3\x81\x81"=>"va","\xe3\x83\xb4\xe3\x81\x89"=>"vo","\xe3\x83\xb4"=>"vu","\xe3\x82\x8f"=>"wa",
	"\xe3\x82\x92"=>"wo","\xe3\x82\x84"=>"ya","\xe3\x82\x88"=>"yo",
	"\xe3\x82\x86"=>"yu","\xe3\x81\x96"=>"za","\xe3\x81\x9c"=>"ze","\xe3\x81\x9e"=>"zo",
	"\xe3\x81\x9a"=>"zu",
	// Japanese katakana
	"\xe3\x83\x93\xe3\x83\xa3"=>"bya","\xe3\x83\x93\xe3\x82\xa7"=>"bye",
	"\xe3\x83\x93\xe3\x82\xa3"=>"byi","\xe3\x83\x93\xe3\x83\xa7"=>"byo","\xe3\x83\x93\xe3\x83\xa5"=>"byu","\xe3\x83\x81\xe3\x83\xa3"=>"tya",
	"\xe3\x83\x81\xe3\x82\xa7"=>"tye","\xe3\x83\x81"=>"ti","\xe3\x83\x81\xe3\x83\xa7"=>"tyo","\xe3\x83\x81\xe3\x83\xa5"=>"tyu",
	"\xe3\x83\x81\xe3\x82\xa3"=>"tyi","\xe3\x83\x87\xe3\x83\xa3"=>"dha","\xe3\x83\x87\xe3\x82\xa7"=>"dhe","\xe3\x83\x87\xe3\x82\xa3"=>"dhi",
	"\xe3\x83\x87\xe3\x83\xa7"=>"dho","\xe3\x83\x87\xe3\x83\xa5"=>"dhu","\xe3\x83\x89\xe3\x82\xa1"=>"dwa","\xe3\x83\x89\xe3\x82\xa7"=>"dwe",
	"\xe3\x83\x89\xe3\x82\xa3"=>"dwi","\xe3\x83\x89\xe3\x82\xa9"=>"dwo","\xe3\x83\x89\xe3\x82\xa5"=>"dwu","\xe3\x83\x82\xe3\x83\xa3"=>"dya",
	"\xe3\x83\x82\xe3\x82\xa7"=>"dye","\xe3\x83\x82\xe3\x82\xa3"=>"dyi","\xe3\x83\x82\xe3\x83\xa7"=>"dyo","\xe3\x83\x82\xe3\x83\xa5"=>"dyu",
	"\xe3\x83\x95\xe3\x82\xa1"=>"fa","\xe3\x83\x95\xe3\x82\xa7"=>"fe","\xe3\x83\x95\xe3\x82\xa3"=>"fi",
	"\xe3\x83\x95\xe3\x82\xa9"=>"fo","\xe3\x83\x95\xe3\x82\xa5"=>"fwu","\xe3\x83\x95\xe3\x83\xa3"=>"fya","\xe3\x83\x95\xe3\x83\xa7"=>"fyo",
	"\xe3\x83\x95\xe3\x83\xa5"=>"fyu","\xe3\x82\xae\xe3\x83\xa3"=>"gya","\xe3\x82\xae\xe3\x82\xa7"=>"gye","\xe3\x82\xae\xe3\x82\xa3"=>"gyi",
	"\xe3\x82\xae\xe3\x83\xa7"=>"gyo","\xe3\x82\xae\xe3\x83\xa5"=>"gyu","\xe3\x83\x92\xe3\x83\xa3"=>"hya","\xe3\x83\x92\xe3\x82\xa7"=>"hye",
	"\xe3\x83\x92\xe3\x82\xa3"=>"hyi","\xe3\x83\x92\xe3\x83\xa7"=>"hyo","\xe3\x83\x92\xe3\x83\xa5"=>"hyu","\xe3\x82\xb8\xe3\x83\xa3"=>"ja",
	"\xe3\x82\xb8\xe3\x82\xa7"=>"je","\xe3\x82\xb8\xe3\x82\xa3"=>"zyi","\xe3\x82\xb8\xe3\x83\xa7"=>"jo","\xe3\x82\xb8\xe3\x83\xa5"=>"ju",
	"\xe3\x82\xad\xe3\x83\xa3"=>"kya","\xe3\x82\xad\xe3\x82\xa7"=>"kye","\xe3\x82\xad\xe3\x82\xa3"=>"kyi","\xe3\x82\xad\xe3\x83\xa7"=>"kyo",
	"\xe3\x82\xad\xe3\x83\xa5"=>"kyu","\xe3\x83\xaa\xe3\x83\xa3"=>"rya","\xe3\x83\xaa\xe3\x82\xa7"=>"rye","\xe3\x83\xaa\xe3\x82\xa3"=>"ryi",
	"\xe3\x83\xaa\xe3\x83\xa7"=>"ryo","\xe3\x83\xaa\xe3\x83\xa5"=>"ryu","\xe3\x83\x9f\xe3\x83\xa3"=>"mya","\xe3\x83\x9f\xe3\x82\xa7"=>"mye",
	"\xe3\x83\x9f\xe3\x82\xa3"=>"myi","\xe3\x83\x9f\xe3\x83\xa7"=>"myo","\xe3\x83\x9f\xe3\x83\xa5"=>"myu","\xe3\x83\xb3"=>"n",
	"\xe3\x83\x8b\xe3\x83\xa3"=>"nya","\xe3\x83\x8b\xe3\x82\xa7"=>"nye","\xe3\x83\x8b\xe3\x82\xa3"=>"nyi","\xe3\x83\x8b\xe3\x83\xa7"=>"nyo",
	"\xe3\x83\x8b\xe3\x83\xa5"=>"nyu","\xe3\x83\x94\xe3\x83\xa3"=>"pya","\xe3\x83\x94\xe3\x82\xa7"=>"pye","\xe3\x83\x94\xe3\x82\xa3"=>"pyi",
	"\xe3\x83\x94\xe3\x83\xa7"=>"pyo","\xe3\x83\x94\xe3\x83\xa5"=>"pyu","\xe3\x82\xb7\xe3\x83\xa3"=>"sya","\xe3\x82\xb7\xe3\x82\xa7"=>"sye",
	"\xe3\x82\xb7\xe3\x83\xa7"=>"syo","\xe3\x82\xb7\xe3\x83\xa5"=>"syu","\xe3\x82\xb9\xe3\x82\xa1"=>"swa",
	"\xe3\x82\xb9\xe3\x82\xa7"=>"swe","\xe3\x82\xb9\xe3\x82\xa3"=>"swi","\xe3\x82\xb9\xe3\x82\xa9"=>"swo","\xe3\x82\xb9\xe3\x82\xa5"=>"swu",
	"\xe3\x82\xb7\xe3\x82\xa3"=>"syi","\xe3\x83\x86\xe3\x83\xa3"=>"tha","\xe3\x83\x86\xe3\x82\xa7"=>"the","\xe3\x83\x86\xe3\x82\xa3"=>"thi",
	"\xe3\x83\x86\xe3\x83\xa7"=>"tho","\xe3\x83\x86\xe3\x83\xa5"=>"thu","\xe3\x83\x84\xe3\x83\xa3"=>"tsa","\xe3\x83\x84\xe3\x82\xa7"=>"tse",
	"\xe3\x83\x84\xe3\x82\xa3"=>"tsi","\xe3\x83\x84\xe3\x83\xa7"=>"tso","\xe3\x83\x84"=>"tu","\xe3\x83\x88\xe3\x82\xa1"=>"twa",
	"\xe3\x83\x88\xe3\x82\xa7"=>"twe","\xe3\x83\x88\xe3\x82\xa3"=>"twi","\xe3\x83\x88\xe3\x82\xa9"=>"two","\xe3\x83\x88\xe3\x82\xa5"=>"twu",
	"\xe3\x83\xb4\xe3\x83\xa3"=>"vya","\xe3\x83\xb4\xe3\x82\xa7"=>"ve","\xe3\x83\xb4\xe3\x82\xa3"=>"vi","\xe3\x83\xb4\xe3\x83\xa7"=>"vyo",
	"\xe3\x83\xb4\xe3\x83\xa5"=>"vyu","\xe3\x82\xa6\xe3\x82\xa1"=>"wha","\xe3\x82\xa6\xe3\x82\xa7"=>"we","\xe3\x82\xa6\xe3\x82\xa3"=>"wi",
	"\xe3\x82\xa6\xe3\x82\xa9"=>"who","\xe3\x82\xa6\xe3\x82\xa5"=>"whu","\xe3\x83\xb1"=>"wye","\xe3\x83\xb0"=>"wyi",
	"\xe3\x83\x82"=>"di","\xe3\x82\xb7"=>"si",
	"\xe3\x82\xa2"=>"a","\xe3\x82\xa8"=>"e","\xe3\x82\xa4"=>"yi","\xe3\x82\xaa"=>"o",
	"\xe3\x82\xa6"=>"u","\xe3\x83\x90"=>"ba","\xe3\x83\x99"=>"be","\xe3\x83\x93"=>"bi",
	"\xe3\x83\x9c"=>"bo","\xe3\x83\x96"=>"bu","\xe3\x83\x80"=>"da","\xe3\x83\x87"=>"de",
	"\xe3\x83\x89"=>"do","\xe3\x83\x85"=>"du","\xe3\x83\x95"=>"hu","\xe3\x82\xac"=>"ga",
	"\xe3\x82\xb2"=>"ge","\xe3\x82\xae"=>"gi","\xe3\x82\xb4"=>"go","\xe3\x82\xb0"=>"gu",
	"\xe3\x83\x8f"=>"ha","\xe3\x83\x98"=>"he","\xe3\x83\x92"=>"hi","\xe3\x83\x9b"=>"ho",
	"\xe3\x82\xb8"=>"zi","\xe3\x82\xab"=>"ka","\xe3\x82\xb1"=>"ke","\xe3\x82\xad"=>"ki",
	"\xe3\x82\xb3"=>"ko","\xe3\x82\xaf"=>"ku","\xe3\x83\xa9"=>"ra","\xe3\x83\xac"=>"re",
	"\xe3\x83\xaa"=>"ri","\xe3\x83\xad"=>"ro","\xe3\x83\xab"=>"ru","\xe3\x83\x9e"=>"ma",
	"\xe3\x83\xa1"=>"me","\xe3\x83\x9f"=>"mi","\xe3\x83\xa2"=>"mo","\xe3\x83\xa0"=>"mu",
	"\xe3\x83\x8a"=>"na","\xe3\x83\x8d"=>"ne","\xe3\x83\x8b"=>"ni","\xe3\x83\x8e"=>"no",
	"\xe3\x83\x8c"=>"nu","\xe3\x83\x91"=>"pa","\xe3\x83\x9a"=>"pe","\xe3\x83\x94"=>"pi",
	"\xe3\x83\x9d"=>"po","\xe3\x83\x97"=>"pu","\xe3\x82\xb5"=>"sa","\xe3\x82\xbb"=>"se",
	"\xe3\x82\xbd"=>"so","\xe3\x82\xb9"=>"su","\xe3\x82\xbf"=>"ta","\xe3\x83\x86"=>"te",
	"\xe3\x83\x88"=>"to","\xe3\x83\xb4\xe3\x82\xa1"=>"va","\xe3\x83\xb4\xe3\x82\xa9"=>"vo","\xe3\x83\xaf"=>"wa",
	"\xe3\x83\xb2"=>"wo","\xe3\x83\xa4"=>"ya","\xe3\x82\xa4\xe3\x82\xa7"=>"ye","\xe3\x83\xa8"=>"yo",
	"\xe3\x83\xa6"=>"yu","\xe3\x82\xb6"=>"za","\xe3\x82\xbc"=>"ze","\xe3\x82\xbe"=>"zo",
	"\xe3\x82\xba"=>"zu","\xe3\x83\xbc"=>"-",
	// Thai
	"\xe0\xb8\xb5\xe0\xb8\xa2\xe0\xb8\xb0"=>"ia",
	"\xe0\xb8\xb5\xe0\xb8\xa2"=>"ia","\xe0\xb8\xb7\xe0\xb8\xad\xe0\xb8\xb0"=>"uea","\xe0\xb8\xb7\xe0\xb8\xad"=>"uea","\xe0\xb8\xb1\xe0\xb8\xa7\xe0\xb8\xb0"=>"ua",
	"\xe0\xb8\xb1\xe0\xb8\xa7"=>"ua","\xe0\xb8\xa3\xe0\xb8\xa3"=>"a","\xe0\xb8\xa6\xe0\xb9\x85"=>"lue","\xe0\xb9\x83"=>"ai",
	"\xe0\xb9\x84"=>"ai","\xe0\xb8\xb1\xe0\xb8\xa2"=>"ai","\xe0\xb8\xb2\xe0\xb8\xa2"=>"ai","\xe0\xb8\xb2\xe0\xb8\xa7"=>"ao",
	"\xe0\xb8\xb8\xe0\xb8\xa2"=>"ui","\xe0\xb8\xad\xe0\xb8\xa2"=>"oi","\xe0\xb8\xb7\xe0\xb8\xad\xe0\xb8\xa2"=>"ueai","\xe0\xb8\xa7\xe0\xb8\xa2"=>"uai",
	"\xe0\xb8\x81"=>"k","\xe0\xb8\x82"=>"kh","\xe0\xb8\x83"=>"kh","\xe0\xb8\x84"=>"kh",
	"\xe0\xb8\x85"=>"kh","\xe0\xb8\x86"=>"kh","\xe0\xb8\x87"=>"ng","\xe0\xb8\x88"=>"ch",
	"\xe0\xb8\x89"=>"ch","\xe0\xb8\x8a"=>"ch","\xe0\xb8\x8b"=>"s","\xe0\xb8\x8c"=>"ch",
	"\xe0\xb8\x8d"=>"y","\xe0\xb8\x8e"=>"d","\xe0\xb8\x8f"=>"t","\xe0\xb8\x90"=>"th",
	"\xe0\xb8\x91"=>"d","\xe0\xb8\x92"=>"th","\xe0\xb8\x93"=>"n","\xe0\xb8\x94"=>"d",
	"\xe0\xb8\x95"=>"t","\xe0\xb8\x96"=>"th","\xe0\xb8\x97"=>"th","\xe0\xb8\x98"=>"th",
	"\xe0\xb8\x99"=>"n","\xe0\xb8\x9a"=>"b","\xe0\xb8\x9b"=>"p","\xe0\xb8\x9c"=>"ph",
	"\xe0\xb8\x9d"=>"f","\xe0\xb8\x9e"=>"ph","\xe0\xb8\x9f"=>"f","\xe0\xb8\xa0"=>"ph",
	"\xe0\xb8\xa1"=>"m","\xe0\xb8\xa2"=>"y","\xe0\xb8\xa3"=>"r","\xe0\xb8\xa4"=>"rue",
	"\xe0\xb8\xa4\xe0\xb9\x85"=>"rue","\xe0\xb8\xa5"=>"l","\xe0\xb8\xa6"=>"lue","\xe0\xb8\xa7"=>"w",
	"\xe0\xb8\xa8"=>"s","\xe0\xb8\xa9"=>"s","\xe0\xb8\xaa"=>"s","\xe0\xb8\xab"=>"h",
	"\xe0\xb8\xac"=>"l","\xe0\xb8\xae"=>"h","\xe0\xb8\xb0"=>"a","\xe0\xb8\xb1"=>"a",
	"\xe0\xb8\xb2"=>"a","\xe0\xb9\x85"=>"a","\xe0\xb8\xb3"=>"am","\xe0\xb9\x8d\xe0\xb8\xb2"=>"am",
	"\xe0\xb8\xb4"=>"i","\xe0\xb8\xb5"=>"ue","\xe0\xb8\xb6"=>"ue","\xe0\xb8\xb8"=>"u",
	"\xe0\xb8\xb9"=>"u","\xe0\xb9\x80"=>"e","\xe0\xb9\x81"=>"ae","\xe0\xb9\x82"=>"o",
	"\xe0\xb8\xad"=>"o","\xe0\xb8\xb4\xe0\xb8\xa7"=>"io","\xe0\xb9\x87\xe0\xb8\xa7"=>"eo","\xe0\xb8\xb5\xe0\xb8\xa2\xe0\xb8\xa7"=>"iao",
	"\xe0\xb9\x88"=>"","\xe0\xb9\x89"=>"","\xe0\xb9\x8a"=>"","\xe0\xb9\x8b"=>"",
	"\xe0\xb9\x87"=>"","\xe0\xb9\x8c"=>"","\xe0\xb9\x8e"=>"","\xe0\xb9\x8d"=>"",
	"\xe0\xb8\xba"=>"","\xe0\xb9\x86"=>"2","\xe0\xb9\x8f"=>"o","\xe0\xb8\xaf"=>"-",
	"\xe0\xb9\x9a"=>"-","\xe0\xb9\x9b"=>"-","\xe0\xb9\x90"=>"0","\xe0\xb9\x91"=>"1",
	"\xe0\xb9\x92"=>"2","\xe0\xb9\x93"=>"3","\xe0\xb9\x94"=>"4","\xe0\xb9\x95"=>"5",
	"\xe0\xb9\x96"=>"6","\xe0\xb9\x97"=>"7","\xe0\xb9\x98"=>"8","\xe0\xb9\x99"=>"9",
	// Korean
	"\xe3\x84\xb1"=>"k","\xe3\x85\x8b"=>"kh","\xe3\x84\xb2"=>"kk",
	"\xe3\x84\xb7"=>"t","\xe3\x85\x8c"=>"th","\xe3\x84\xb8"=>"tt","\xe3\x85\x82"=>"p",
	"\xe3\x85\x8d"=>"ph","\xe3\x85\x83"=>"pp","\xe3\x85\x88"=>"c","\xe3\x85\x8a"=>"ch",
	"\xe3\x85\x89"=>"cc","\xe3\x85\x85"=>"s","\xe3\x85\x86"=>"ss","\xe3\x85\x8e"=>"h",
	"\xe3\x85\x87"=>"ng","\xe3\x84\xb4"=>"n","\xe3\x84\xb9"=>"l","\xe3\x85\x81"=>"m",
	"\xe3\x85\x8f"=>"a","\xe3\x85\x93"=>"e","\xe3\x85\x97"=>"o","\xe3\x85\x9c"=>"wu",
	"\xe3\x85\xa1"=>"u","\xe3\x85\xa3"=>"i","\xe3\x85\x90"=>"ay","\xe3\x85\x94"=>"ey",
	"\xe3\x85\x9a"=>"oy","\xe3\x85\x98"=>"wa","\xe3\x85\x9d"=>"we","\xe3\x85\x9f"=>"wi",
	"\xe3\x85\x99"=>"way","\xe3\x85\x9e"=>"wey","\xe3\x85\xa2"=>"uy","\xe3\x85\x91"=>"ya",
	"\xe3\x85\x95"=>"ye","\xe3\x85\x9b"=>"oy","\xe3\x85\xa0"=>"yu","\xe3\x85\x92"=>"yay",
	"\xe3\x85\x96"=>"yey"
);
?>