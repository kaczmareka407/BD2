<?php
session_start();

require 'bibtecxfunctions.php';

    //session_start();
    include("connect_to_database.php");
	if(!$conn->ping())echo "NOT CONNECTED";
	foreach($_POST as $x)
	{
		echo $x.'<br>';
	}

	$ajdidowyplucia=$_GET["value2"];
	if(!($stmt = $conn->prepare("SELECT * FROM books WHERE ID like $ajdidowyplucia")))
    {
		printf("Error: %s.\n", $stmt->error);
    }
    if (!$stmt->execute()) 
	{
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    $result = $stmt->get_result();
    $piwo_array = array();
    while($assocyjacyje = $result->fetch_assoc()){
    $title = $assocyjacyje['title'];
    $author = $assocyjacyje['author'];
    $publisher = $assocyjacyje['publisher'];
    $year = $assocyjacyje['year'];
    $tmp_array = array($title, $author, $publisher, $year);
    array_push($piwo_array, $tmp_array);
    }

    convert2bibtexFile($piwo_array);

    $stmt->close();
    $conn->close();

    //echo
	//'<script>history.go(-1);</script>'
	//;
	//header("Location: index.php?title=".$_GET['title']);
?>