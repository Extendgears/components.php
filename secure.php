<?php

// Validate a mailaddress
//
// @param string $mail
//
// @return bool false if address is not a real one
function validMail($mail) {
	if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
		return false;
	} else {
		return true;
	}
}

// Validate a path
//
// @param string $path
//
// @return bool false if path is not a real one
function validPath($path) {
	if (!filter_var($path, FILTER_VALIDATE_URL)) {
		return false;
	} else {
		return true;
	}
}

// Secures an array with htmlspecialchars()
//
// @param array $dataArray
//
// @return array secured array
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

// Secures a string with htmlspecialchars()
//
// @param string $string
//
// @return string secured string
function secureString($string) {
	return htmlspecialchars($string);
}

// Hash a password with given salt
//
// @param string $password
// @param string $salt
//
// @return string hash value
function hashPassword($password, $salt) {
	return hash_hmac('md5', $password, $salt);
}

// Check whether a string is empty
//
// @param string $string
//
// @return bool true if string is empty
function isStringEmpty($string) {
	if (str_replace(' ', '', $string) == '') {
		return true;
	}
	return false;
}

?>
