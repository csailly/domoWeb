<?php

function startswith($hay, $needle) {
	return substr($hay, 0, strlen($needle)) === $needle;
}

function endswith($hay, $needle) {
	return substr($hay, -strlen($needle)) === $needle;
}

session_start ();

if (! (isset ( $_SESSION ['login'] ) && $_SESSION ['login'] != '') && ! isset($_COOKIE["logged"])) {
	if (!startswith($_SERVER['REMOTE_ADDR'], "192")){		
		header ( "Location: /login.php" );
	}
}
?> 