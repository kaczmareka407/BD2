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
	
?>