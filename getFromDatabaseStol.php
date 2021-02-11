<?php
session_start();
//TODO funkcja arguemnty wejciowe: yyyajdi (naszobazowe)
//utwarzam plik z jednom ksiomszkom

//charset zeby polskie całe te (124 powinna działać ale nie dziaa)

//zmienic nazwe plika na costam lalala bibtex

//TUTU funkcja masz zamienic na nazwe funkcji jak ona sie bedzie nazywała
//TOOD funkcja wejsciowe; tablica ajdików książek do zwrócenia
//gdzie w jabvie werjsi skrypt autopobieradło
//
require 'bibtecxfunctions.php';


    //session_start();
    include("connect_to_database.php");
	if(!$conn->ping())echo "NOT CONNECTED";
	foreach($_POST as $x)
	{
		echo $x.'<br>';
	}
	//$title = $_POST['title'];
	//$author = $_POST['author'];
	//$publisher = $_POST['publisher'];
	//$year = $_POST['year'];
	//$category = 3;
	// $category = $_POST['category'];


			//if($stmt = $conn->prepare("INSERT INTO books (title, author, publisher, year, category) VALUES (?, ?, ?, ?, ?)"))
    //if(isset($argc)){
		//$ajdidowyplucia = $argv[0];
	$zwr=$_GET["value2"]; //pobrany stol
	$stol=multiexplode(array(" ", ","), $zwr);//stol na elementa poszczegolne podzielon
	 $piwo_array = array();
	foreach($stol as $elem){
	if(!($stmt = $conn->prepare("SELECT * FROM books WHERE ID like $elem")))
    {
		printf("Error: %s.\n", $stmt->error);
    }
    if (!$stmt->execute()) 
	{
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    $result = $stmt->get_result();
   
    while($assocyjacyje = $result->fetch_assoc()){
    $title = $assocyjacyje['title'];
    $author = $assocyjacyje['author'];
    $publisher = $assocyjacyje['publisher'];
    $year = $assocyjacyje['year'];
    $tmp_array = array($title, $author, $publisher, $year);
    array_push($piwo_array, $tmp_array);
    }
	}
    //console.log($title);
    //convert2bibtexFile(array(array($title, $author, $publisher, $year)));
    convert2bibtexFile($piwo_array);
	//}else{
		//echo "Kurde belka żeś coś skopał bo ja nie wiem co za ID ty chcesz tej";
	//}
    $stmt->close();
    $conn->close();



    //echo
	//'<script>history.go(-1);</script>'
	//;
	//header("Location: index.php?title=".$_GET['title']);
?>