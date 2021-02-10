<?php
session_start();
require("functions.php");
	

	if($_GET["resource_id"] == null)
	{
		
	}
	else delete_resource($_GET["resource_id"]);
	header('Location: book.php?book_id='.$_GET['book_id']);
?>
