<?php

$servername = "localhost";
	$username = "admin";
	$password = "admin";
	$dbname = "bibtex_db";

	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) 
	{
		die("Connection failed: " . $conn->connect_error);
	}
	
	if(!$conn->ping())echo "NOT CONNECTED";
	if(!$conn->ping())echo "---ERROR--- not ping";
	
?>