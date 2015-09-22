<?php

// Open a database
//
// @param string $domain
// @param string $username
// @param string $password
// @param string $sheet
//
// @return bool|object false if an error occured, database if everything was ok
function openDatabase($host, $username, $password, $sheet, $charset) {
	$database = new mysqli($host, $username, $password, $sheet);

	if ($database->connect_error) {
		echo 'Error while connecting: '.mysqli_connect_error();
		return false;
	}

	if (!$database->set_charset($charset)) {
		echo 'Error while loading ' . $charset . ' for MySQL: ' . $database->error;
		return false;
	}

	return $database;
}

// closes a databse
//
// @param object $database
function closeDatabase($database) {
	$database->close();
}

// query database for information
//
// @param string $query
//
// @return bool|object false if an error occured, true if result is not an object (e.g. for 'INSERT' or 'UPDATE' request), an object for everything else
function queryMySQLData($query) {
	$database = openDatabase(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_CHARSET);

	if (strpos($query, 'UPDATE') || strpos($query, 'INSERT')) {
		$database->query($query);
		closeDatabase($database);
		return true;
	}
	$ergebnis = $database->query($query);

	closeDatabase($database);

	if (is_object($ergebnis)) {
		return $ergebnis;
	} elseif ($ergebnis == true) {
		return true;
	}

	return false;
}

// create a table if there isn't one with given name
//
// @param string $name
// @param string $query
//
function initTable($name, $query) {
	$resultCheck = queryMySQLData('SELECT 1 FROM ' . $name . ' LIMIT 1');

	if($resultCheck == false) {
		queryMySQLData($query);
	}
}

?>
