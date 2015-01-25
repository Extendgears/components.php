<?php

// get data of a specific user
//
// @param array $dataArray e.g. array("name" => $name, "password" => $password [, "databaseCol" => $variable])
//
// @return array first row of user table with matching information from $dataArray
function getUserData($dataArray) {

	$dataArray = secureArray($dataArray);;

	$query = "SELECT * FROM user WHERE ";

	$count = count($dataArray);
	$i = 0;
	foreach ($dataArray as $col => $var) {

		if ($i >= $count-1) {
			$query = $query . $col . "='" . $var . "';";
		} else {
			$query = $query . $col . "='" . $var . "' AND ";
		}

		$i++;
	}

	$result = queryMySQLData($query);

	return $result;
}

// set data of a specific user
//
// @param string $userID id of the user you want to change
// @param array $dataArray e.g. array("name" => $name, "password" => $password [, "databaseCol" => $newValue]), these values will be changed to the given value
//
// @return bool true, if everything went right
function setUserData($userID, $dataArray) {

	$dataArray = secureArray($dataArray);;

	$query = "UPDATE user SET ";

	$count = count($dataArray);
	$i = 0;
	foreach ($dataArray as $col => $var) {

		if ($i >= $count-1) {
			$query = $query . $col . "='" . $var . "' WHERE id='" . $userID . "';";
		} else {
			$query = $query . $col . "='" . $var . "', ";
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

	if (isStringEmpty($name) || isStringEmpty($password)) {
		return 3;
	}

	$name = secureString($name);
	$salt = uniqid();
	$passwordHash = hashPassword(secureString($password), $salt);

	$query = "SELECT // FROM user WHERE LOWER(name)='" . strtolower($name) . "';";
	$nameOccupied = queryMySQLData($query);

	if (!$nameOccupied) {

		$query = "INSERT INTO user (id, name, password, salt) VALUES (NULL, '" . $name . "', '" . $passwordHash . "', '" . $salt . "');";

		if (queryMySQLData($query)) {

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
	$userdata = getUserData(array("id" => getLogState()));

	if (hashPassword(secureString($oldpassword), $userdata["salt"]) == $userdata["password"]) {
		$newpassword = hashPassword(secureString($newpassword), $userdata["salt"]);
		$result = setUserData(getLogState(), array("password" => $newpassword));
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
		return getUserData(array("id" => getLogState()))[$col];
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

?>
