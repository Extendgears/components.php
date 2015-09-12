<?php

// get data of a specific user
//
// @param array $dataArray e.g. array('name' => $name, 'password' => $password [, 'databaseCol' => $variable])
//
// @return array first row of user table with matching information from $dataArray
function getUserData($dataArray) {
	initTable(DB_PREFIX.DB_USERS, SQL_USERS);

	$dataArray = secureArray($dataArray);;

	$query = 'SELECT * FROM '.DB_PREFIX.DB_USERS.' WHERE ';

	$count = count($dataArray);
	$i = 0;
	foreach ($dataArray as $col => $var) {
		
		$col = secureString($col);
		$var = secureString($var);

		if ($i >= $count-1) {
			$query = $query . $col . '=\'' . $var . '\';';
		} else {
			$query = $query . $col . '=\'' . $var . '\' AND ';
		}

		$i++;
	}

	$result = queryMySQLData($query);

	return $result->fetch_array();
}

// set data of a specific user
//
// @param string $userID id of the user you want to change
// @param array $dataArray e.g. array('name' => $name, 'password' => $password [, 'databaseCol' => $newValue]), these values will be changed to the given value
//
// @return bool true, if everything went right
function setUserData($userID, $dataArray) {
	initTable(DB_PREFIX.DB_USERS, SQL_USERS);

	$dataArray = secureArray($dataArray);;

	$query = 'UPDATE '.DB_PREFIX.DB_USERS.' SET ';

	$count = count($dataArray);
	$i = 0;
	foreach ($dataArray as $col => $var) {
		
		$col = secureString($col);
		$var = secureString($var);

		if ($i >= $count-1) {
			$query = $query . $col . '=\'' . $var . '\' WHERE id=\'' . $userID . '\';';
		} else {
			$query = $query . $col . '=\'' . $var . '\', ';
		}

		$i++;
	}

	$success = queryMySQLData($query);

	return $success;
}

// register a new user
//
// @param string $name
// @param string $password
//
// @return int 0 if something went wrong, 1 if user is registered, 2 if name exists already, 3 if $name or $password is empty
function registerUser($name, $password) {
	initTable(DB_PREFIX.DB_USERS, SQL_USERS);

	if (isStringEmpty($name) || isStringEmpty($password)) {
		return 3;
	}

	$name = secureString($name);
	$salt = uniqid();
	$passwordHash = hashPassword(secureString($password), $salt);

	$query = 'SELECT id FROM '.DB_PREFIX.DB_USERS.' WHERE LOWER(name)=\'' . strtolower($name) . '\';';
	$nameOccupied = queryMySQLData($query)->fetch_array();

	if (!$nameOccupied) {

		$query = 'INSERT INTO '.DB_PREFIX.DB_USERS.' (name, password, salt) VALUES (\'' . $name . '\', \'' . $passwordHash . '\', \'' . $salt . '\');';
		$result = queryMySQLData($query);


		if ($result) {
			logUserIn($name, $password);
			return 1;
		}

		return 0;

	} else {
		return 2;
	}
}

// change password of a user who is logged in
//
// @param string $oldpassword
// @param string $newpassword
//
// @return bool true if new password has been set, false if not
function resetPassword($oldpassword, $newpassword) {
	initTable(DB_PREFIX.DB_USERS, SQL_USERS);

	$userdata = getUserData(array('id' => getLogState()));


	if (hashPassword(secureString($oldpassword), $userdata['salt']) == $userdata['password']) {
		$newpassword = hashPassword(secureString($newpassword), $userdata['salt']);
		$result = setUserData(getLogState(), array('password' => $newpassword));
		return $result;
	}

	return false;
}

// get a single information of a user who is logged in
//
// @param string $col name of column in database
//
// @return mixed value of requested item
function getSingleUserData($col) {
	if (!isStringEmpty($col)){
		return getUserData(array('id' => getLogState()))[$col];
	}
}

// set a single information of a user who is logged in
//
// @param string $col name of column in database
// @param string $value value for column
//
// @return bool true if value has been set
function setSingleUserData($col, $value) {
	if (!isStringEmpty($col) && !isStringEmpty($value)) {
		return setUserData(getLogState(), array($col => $value));
	}
}

// add custom columns to users table
//
// @param array $fieldarray e.g. ['initials' => 'VARCHAR(60)']
//
// @return bool true if everything worked
function addCustomFields($fieldarray) {
	foreach ($fieldarray as $name => $type) {
		$name = secureString(strtolower($name));
		$type = secureString($type);
		$query = 'ALTER TABLE ' . DB_PREFIX . DB_USERS . ' ADD ' . $name . ' ' . $type;
		queryMySQLData($query);
	}
}

?>
