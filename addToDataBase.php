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
	$category = $_POST['category'];

	//echo '<h1>'.$_POST['category'].'</h1>';
	// $category = $_POST['category'];
	if($stmt = $conn->prepare("SELECT `categoryID` FROM `categories` WHERE `name` = ?"))
	{
		$stmt->bind_param("s", $category);
		$stmt->execute();

		$result = $stmt->get_result();

		if(mysqli_num_rows($result)==0)
		{
			$stmt = $conn->prepare("INSERT INTO categories (`name`) VALUES (?)");
			$stmt->bind_param("s",$category);
			$stmt->execute();
			echo "1";
		}
		if($stmt = $conn->prepare("INSERT INTO books (title, author, publisher, year, category) VALUES (?, ?, ?, ?,(SELECT `categoryID` FROM `categories` WHERE `name` = ?));"))
		{
			//sssii znaczy string*3 int*2 - inne: d-double, b-BLOB
			$stmt->bind_param("sssis", $title, $author, $publisher, $year, $category);
			$stmt->execute();
			echo "2";

		}
		else
		{
			printf("Error: %s.\n", $stmt->error);
		}

	}
	else
	{
		echo "HALO";
	}
	

	/* if($stmt = $conn->prepare("INSERT INTO books (title, author, publisher, year, category) VALUES (?, ?, ?, ?, ?)"))
	{
		//sssii znaczy string*3 int*2 - inne: d-double, b-BLOB
		$stmt->bind_param("sssis", $title, $author, $publisher, $year, $category);
		$stmt->execute();
	}
	else
	{
		printf("Error: %s.\n", $stmt->error);
	} */

    /* echo
	'<script>history.go(-1);</script>'
	; */

	header("Location: index.php?title=".$_GET['title']."&base=remote");
?>