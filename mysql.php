<?php

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

function closeDatabase($database) {
	$database->close();
}

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
