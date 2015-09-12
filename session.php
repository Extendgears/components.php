<?php

// start a session
function useSession() {
	session_start();
}

// Quit the session
function quitSession() {
	setcookie(session_name(), '', time() - 42000, '/');
}

// get a session variable
//
// @param string $varName name of the variable
//
// @return mixed false if variable is not set or its value is false, otherwise the variables values
function getSessionVar($varName) {
	if (isset($_SESSION[$varName])) {
		return $_SESSION[$varName];
	} else {
		return false;
	}
}

// set a session variable
//
// @param string $varName name of the variable
// @param string $varVal value of the variable
//
// @return mixed value of the variable
function setSessionVar($varName, $varVal) {
	if (is_string($varVal)) {
		$varVal = htmlspecialchars($varVal);
	}
	$_SESSION[$varName] = $varVal;
	return $varVal;
}

?>
