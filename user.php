<?php

function getUserData($dataArray) {
	// $dataArray: Array of all data you want to check for and their name in the database.

	// EXAMPLE
	// $dataArray = array("name" => $name, "password" => $password [, "databaseCol" => $variable])

	// returns an array of the 1st row with matching values

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

function registerUser($name, $password) {

	if (isStringEmpty($name) || isStringEmpty($password)) {
		return 3;
	}

	$name = secureString($name);
	$salt = uniqid();
	$passwordHash = hashPassword(secureString($password), $salt);

	$query = "SELECT * FROM user WHERE LOWER(name)='" . strtolower($name) . "';";
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

function resetPassword($oldpassword, $newpassword) {
	$userdata = getUserData(array("id" => getLogState()));

	if (hashPassword(secureString($oldpassword), $userdata["salt"]) == $userdata["password"]) {
		$newpassword = hashPassword(secureString($newpassword), $userdata["salt"]);
		$result = setUserData(getLogState(), array("password" => $newpassword));
		return $result;
	}

	return false;
}

function getSingleUserData($col) {
	if (!isStringEmpty($col)){
		return getUserData(array("id" => getLogState()))[$col];
	}
}

function setSingleUserData($col, $value) {
	if (!isStringEmpty($col) && !isStringEmpty($value)) {
		return setUserData(getLogState(), array($col => $value));
	}
}

?>
