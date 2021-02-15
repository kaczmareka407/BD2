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

$desc = array(
	0 => array('pipe', 'r'), // 0 is STDIN for process
	1 => array('pipe', 'w'), // 1 is STDOUT for process
	2 => array('file', 'C:\xampp\htdocs\bd2\tmp\error-output.txt', 'a') // 2 is STDERR for process
  );
  $cmd = 'time_counter.php';

	//$pipe = popen('time_counter.php', 'rw');
	$pipe = proc_open($cmd, $desc, $pipes);
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

    if(!($stmt = $conn->prepare("SELECT * FROM books")))
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
	//pclose($pipe);
	sleep(1);
	proc_terminate($pipe);
	proc_close($pipe);
?>