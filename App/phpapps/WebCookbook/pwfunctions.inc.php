<?php
/*
These are the related password funtions from my little forum. I've extracted them because of side effects, when including the whole './forum/includes/functions.inc.php'.
The existence of this file still will be proofed by Webcookbook but use './pwfunctions.inc.php' instead.
*/
function generate_pw_hash($pw) {
	$salt = random_string(10,'0123456789abcdef');
	$salted_hash = sha1($pw.$salt);
	$hash_with_salt = $salted_hash.$salt;
	return $hash_with_salt;
}

/**
 * checks password comparing it with the hash
 *
 * @param string $pw
 * @param string $hash
 * @return bool
 */
function is_pw_correct($pw,$hash) {
	if(strlen($hash)==50) { // salted sha1 hash with salt
		$salted_hash = substr($hash,0,40);
		$salt = substr($hash,40,10);
		if(sha1($pw.$salt)==$salted_hash) {
			return true;
		}
		else {
			return false;
		}
	}
	elseif(strlen($hash)==32) { // md5 hash generated in an older version
		if($hash == md5($pw)) {
			return true;
		}
		else {
			return false;
		}
	}
	else {
		return false;
	}
}
?>