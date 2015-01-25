<?php

$pathArray = array(
	'mail'    => 'mail.php',
	'secure'  => 'secure.php',
	'session' => 'session.php',
	'user'    => 'user.php',
	'log'     => 'log.php',
	'mysql'   => 'mysql.php'
);

function getPath($module) {
	global $pathArray;
	foreach ($pathArray as $name => $path) {
		if ($name == strtolower($module)) {
			return realpath($path);
		}
	}
	return NULL;
}

function curPath() {
	$host = $_SERVER['HTTP_HOST'];
	$uri = rtrim(dirname(htmlspecialchars($_SERVER['PHP_SELF'])), '/\\');
	$extra = 'index.php';
	return 'http://' . $host . $uri . '/' . $extra;
}

function initComponents() {
	global $pathArray;
	foreach ($pathArray as $name => $path) {
		require(__DIR__ . "/" . $path);
	}
	return true;
}

initComponents();

?>
