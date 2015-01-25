<?php


// Check whether user should be logged in automatically, if yes do so
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
}

// Save keepLog-cookie and store the value in the database
function saveKeepLog() {
	$keepLogKey = uniqid();
	setCookie('keepLog', $keepLogKey, time() + 3600, '/', 'localhost', false, true);
	setUserData(getLogState(), array('keepLog' => $keepLogKey));
}

// get userID
//
// @return int|bool if user is not logged in, return false
function getLogState() {
	if (getSessionVar('login')) {
		return getSessionVar('userID');
	} else {
		return false;
	}
}


// log in a user
//
// @param string $name
// @param string $password
// @param bool $keepLog (optional)
//
// @return bool true if login was successful
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

// log out a user
function logUserOut() {
	setSessionVar('login', false);
	setCookie('keepLog', '0', time() - 3600, '/', 'localhost', false, true);
	unset($_COOKIE['keepLog']);
	quitSession();
}

?>
