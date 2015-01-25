<?php

function validMail($mail) {
	if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
		return false;
	} else {
		return true;
	}
}

function validPath($path) {
	if (!filter_var($path, FILTER_VALIDATE_URL)) {
		return false;
	} else {
		return true;
	}
}

function secureArray($dataArray) {
	$secureArray = array();

	foreach ($dataArray as $key => $val) {
		$key = secureString((string) $key);
		if (is_string($val)) {
			$val = secureString($val);
		}
		$secureArray[$key] = $val;
	}
	return $secureArray;
}

function secureString($string) {
	$new = htmlspecialchars($string);
	return $new;
}

function hashPassword($password, $salt) {
	return hash_hmac('md5', $password, $salt);
}

function isStringEmpty($string) {
	if (str_replace(' ', '', $string) == '') {
		return true;
	}
	return false;
}

?>
