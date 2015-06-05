<?php
	sec_session_start(); // Our custom secure way of starting a php session. 

	define("HOST", "localhost"); // The host you want to connect to.
	define("USER", "root"); // The database username.
	define("PASSWORD", ""); // The database password. 
	define("DATABASE", "celiaca"); // The database name.
	 
	$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
	$mysqli->set_charset("utf8"); // Dead serious sentence for json_encode - DO NOT DELETE x_X
?>