<?php

function initLogSys() {
	useSession();

	// save keepLog-cookie? if yes, save unique if to cookie and database
	if (getSessionVar('saveKeepLog') && getLogState()) {
		saveKeepLog();
		setSessionVar('saveKeepLog', false);
	}

	// keepLog-cookie equal to keepLog-value in database?
	if (isset($_COOKIE['keepLog']) && !getLogState()) {
		$userData = getUserData(array('keepLog' => $_COOKIE['keepLog']));
		if ($userData) {
			setSessionVar('login', true);
			setSessionVar('userID', $userData['id']);
			saveKeepLog();
		}
	}
	return true;
}

function saveKeepLog() {
	// Save keepLog-cookie and store the value in the database
	$keepLogKey = uniqid();
	setCookie('keepLog', $keepLogKey, time() + 3600, '/', 'localhost', false, true);
	setUserData(getLogState(), array('keepLog' => $keepLogKey));
}

function getLogState() {
	// if user is logged in, return the userID
	if (getSessionVar('login')) {
		return getSessionVar('userID');
	} else {
		return false;
	}
}

function logUserIn($name, $password, $keepLog=false) {
	$name = secureString($name);
	$password = hashPassword(secureString($password), getUserData(array('name' => $name))['salt']);
	$userData = getUserData(array('name' => $name, 'password' => $password));
	if ($userData) {
		setSessionVar('login', true);
		setSessionVar('userID', $userData['id']);
		if ($keepLog) {
			setSessionVar('saveKeepLog', true);
		}
		return true;
	} else {
		return false;
	}
}

function logUserOut() {
	setSessionVar('login', false);
	setCookie('keepLog', '0', time() - 3600, '/', 'localhost', false, true);
	unset($_COOKIE['keepLog']);
	quitSession();
}

?>
