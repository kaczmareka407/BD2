<?php

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
            ."year = ".$this->year."}\n";
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
/* //to to tu wgl jest tylko zeby ten
function download(filename, text) {
  var element = document.createElement('a');
  element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
  element.setAttribute('download', filename);

  element.style.display = 'none';
  document.body.appendChild(element);

  element.click();

  document.body.removeChild(element);
}*/
//Tutaj nie dziaa jeszcze prosze nie tykać
function convert2bibtexFileINPROGRESS($books){ //books = array(array([0]-title, [1]-author, [2]-publisher, [3]-year), ...)
    $file_string = "";
    //print_r($books);
    foreach($books as &$value){
        $book = convert2Book($value[0], $value[1], $value[2], $value[3]);
        $file_string .= $book->get_bibtex();
        $file_string .= "\n";
    }
    //echo($file_string);

    //$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
    //fwrite($myfile, $file_string);

    $file = "bibtex.txt";
    $txt = fopen($file, "w") or die("Unable to open file!");
    fwrite($txt, $file_string);
    fclose($txt);

    /*header('Content-Description: File Transfer');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    header("Content-Type: text/plain");*/
    //readfile($file);
    
    //return '<span style="display:none">'.$bibtex.'</span>';


		/*
		<!DOCTYPE html><head><meta charset="UTF-8"></head>
<script> document.location.href = 'yourfile.exe'; </script>
<meta http-equiv="refresh" content="0; url=yourfile.exe">
download("hello.txt","This is the content of my file :)");
		*/
    //echo '<script> document.location.href = "bibtex.txt"; </script>';
    echo '<!DOCTYPE html><head><meta charset="UTF-8"></head>';
	echo '<meta http-equiv="refresh" content="0; url=bibtex.txt">';
	
}
//stara werjsa funkcji
function convert2bibtexFile($books){ //books = array(array([0]-title, [1]-author, [2]-publisher, [3]-year), ...)
    $file_string = "";
    //print_r($books);
    foreach($books as &$value){
        $book = convert2Book($value[0], $value[1], $value[2], $value[3]);
        $file_string .= $book->get_bibtex();
        $file_string .= "\n";
    }
    //echo($file_string);

    //$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
    //fwrite($myfile, $file_string);

    $file = "bibtex.txt";
    $txt = fopen($file, "w") or die("Unable to open file!");
    fwrite($txt, $file_string);
    fclose($txt);

    /*header('Content-Description: File Transfer');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    header("Content-Type: text/plain");*/
    //readfile($file);
    
    //return '<span style="display:none">'.$bibtex.'</span>';

    //echo '<script> document.location.href = "bibtex.txt"; </script>';
    echo '<!DOCTYPE html><head><meta charset="UTF-8"></head>';
	echo '<meta http-equiv="refresh" content="0; url=bibtex.txt">';
	
}

?>