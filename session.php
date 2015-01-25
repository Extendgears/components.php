<?php

function useSession() {
	session_start();
}

function quitSession() {
	setcookie(session_name(), "", time() - 42000, "/");
}

function getSessionVar($varName) {
	if (isset($_SESSION[$varName])) {
		return $_SESSION[$varName];
	} else {
		return false;	
	}
}

function setSessionVar($varName, $varVal) {
	if (is_string($varVal)) {
		$varVal = htmlspecialchars($varVal);
	} 
	$_SESSION[$varName] = $varVal;
	return $varVal;
}

?>
