<?php
    session_start();
    include("connect_to_database.php");
	if(!$conn->ping())echo "NOT CONNECTED";

	foreach($_POST as $x)
	{
		echo $x.'<br>';
	}
	$title = $_POST['title'];
	$author = $_POST['author'];
	$publisher = $_POST['publisher'];
	$year = $_POST['year'];
	$category = 3;
	// $category = $_POST['category'];


			if($stmt = $conn->prepare("INSERT INTO books (title, author, publisher, year, category) VALUES (?, ?, ?, ?, ?)"))
			{
				//sssii znaczy string*3 int*2 - inne: d-double, b-BLOB
				$stmt->bind_param("sssis", $title, $author, $publisher, $year, $category);
				$stmt->execute();
			}
			else
			{
				printf("Error: %s.\n", $stmt->error);
			}
    echo
	'<script>history.go(-1);</script>'
	;
	header("Location: index.php?title=".$_GET['title']);
?>