<?php
	require("functions.php");
	echo 'deleting';
	deleteBook($_GET["book_id"]);
	echo 'deleted';
	//header('Location: index.php');

?>