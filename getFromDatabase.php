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

function multiexplode ($delimiters,$string) {
    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return  $launch;
}

class Book {
    private $title = "";
    private $author = "";
    private $publisher = "";
    private $year = "";
    private $citekey = "";

    function __construct($title_arg, $author_arg, $publisher_arg, $year_arg, $citekey_arg){
        $this->title = $title_arg; 
        $this->author = $author_arg;
        $this->publisher = $publisher_arg;
        $this->year = $year_arg;
        $this->citekey = $citekey_arg;
    }

    function get_title() { 
        return $this->title; 
    } 
    function get_author() { 
        return $this->author; 
    } 
    function get_publisher() { 
        return $this->publisher; 
    } 
    function get_year() { 
        return $this->year; 
    } 
    function get_citekey() { 
        return $this->citekey; 
    } 

    function get_bibtex() {
        $bibtex = "@Book{".$this->citekey.", title = \"".$this->title."\", "
            ."author = \"".$this->author."\", publisher = \"".$this->publisher."\", "
            ."year = ".$this->year."}";
        return $bibtex;
    }

    function set_title($title_arg) { 
        $this->title = $title_arg; 
    } 
    function set_author($author_arg) { 
        $this->author = $author_arg;
    } 
    function set_publisher($publisher_arg) { 
        $this->publisher = $publisher_arg;
    } 
    function set_year($year_arg) { 
        $this->year = $year_arg;
    } 
    function set_citekey($citekey_arg) { 
        $this->citekey = $citekey_arg;
    } 

};

function convert2bibtex($title, $author, $publisher, $year){
    //$author_cale_te = explode(" ", $author);
    $author_cale_te = multiexplode(array(" ", ","), $author);
    $citekey = $author_cale_te[1].$year;
    $bibtex = "@Book{".$citekey.", title = \"".$title."\", "
        ."author = \"".$author."\", publisher = \"".$publisher."\", "
        ."year = ".$year."}";
    return '<span style="display:none">'.$bibtex.'</span>';
}

function convert2Book($title, $author, $publisher, $year){
    //echo($author);
    $author_cale_te = multiexplode(array(" ", ","), $author);
    $citekey = $author_cale_te[1].$year;
    //echo($author_cale_te);
    //$citekey = "";
    $book = new Book($title, $author, $publisher, $year, $citekey);
    return $book;
}

function convert2bibtexFile($books){ //books = array(array([0]-title, [1]-author, [2]-publisher, [3]-year), ...)
    $file_string = "";
    //print_r($books);
    foreach($books as &$value){
        $book = convert2Book($value[0], $value[1], $value[2], $value[3]);
        $file_string .= $book->get_bibtex();
        $file_string .= "\n";
    }	
	
	echo '
	<iframe id="my_iframe" style="display:none;"></iframe>
	<script>
		var link = document.createElement("a");
    
		link.setAttribute("download", "");
		link.href = "bibtex.txt";
		document.body.appendChild(link);
		link.click();
		link.remove();
		window.history.back();
	</script>
	';
	
    echo '<!DOCTYPE html><head><meta charset="UTF-8"></head>';	
}



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
?>