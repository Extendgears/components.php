<?php

// Open a database
//
// @param string $domain
// @param string $username
// @param string $password
// @param string $sheet
//
// @return bool|object false if an error occured, database if everything was ok
function openDatabase($domain, $username, $password, $sheet) {
	$database = new mysqli($domain, $username, $password, $sheet);

	if ($database->connect_error) {
		echo 'Error while connecting: '.mysqli_connect_error();
		return false;
	}

	if (!$database->set_charset('utf8')) {
		echo 'Error while loading UTF-8 for MySQLi: ' . $database->error;
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
	$database = openDatabase('localhost', 'root', 'password', 'database');

	if (strpos($query, 'UPDATE') || strpos($query, 'INSERT')) {
		$database->query($query);
		closeDatabase($database);
		return true;
	}

	$ergebnis = $database->query($query);

	closeDatabase($database);

	if (is_object($ergebnis)) {
		return $ergebnis->fetch_array();
	} elseif ($ergebnis == true) {
		return true;
	}

	return false;
}

?>
