<?php
	require("functions.php");
	deleteBook($_GET["book_id"]);
	header('Location: index.php');
?>