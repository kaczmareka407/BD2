<!--	
	Agata:
	TODO: Przycisk "zaznacz wiele" - zmienia widoczność checkboxa i przycisku zapisz
	TODO: po zaznaczeniu checkboxa pojawia się "choose category"
	
	ZAZNACZ WIELE -> Pokazuje Checkboxy
				  -> Pokazuje Zapisz wiele
				  -> Ukrywa zapisz (pojedynczy)
				  -> Zmienia nazwę "zaznacz wiele" na "zaznacz jeden"

-->

<style><!-- Agata to potem przeniesie do style.css -->
	.ptak
	{
			/* visibility: hidden; */
			width:60px;
			height:60px;
	}
	
	#menu
	{
		width: 100%;
		top: 0;
		background-color:white;
		border: 4px;
		border-style: solid;
		border-color: aqua;
		position:sticky;
	}
</style>

<?php
    session_start();
    mb_internal_encoding("UTF-8");
    mb_http_output( "UTF-8" );
?>


<!DOCTYPE HTML>
<html>
    <head>
    <meta http-equiv="Content-Language" content="text/html; charset=UTF-8" >
    <meta charset="UTF-8" >
        <title>Bibtex database</title>
        <script>
                function countSelected()
                {
                    var tab = [];

                    var x = document.getElementsByClassName('addMultiple');
                    console.log(x.length);
                    for(var i = 1;i < x.length;i++)
                    {
                        if(x[i].checked==true)
                        {
                            tab.push(parseInt(i));
                        }
                        console.log(x[i].checked);
                    }
                    console.log(tab);
                    document.getElementById('insertResult').innerHTML = tab;
                    console.log(x[1]);
                }

                function setView()
                {
                    var all = document.getElementsByClassName('addMultiple');
                    for (var i = 0; i < all.length; i++) 
                    {
                        all[i].style.visibility = "hidden";
                    }
                }

                function switchAdd()
                {
                    var x = document.getElementById('menuSelectButton');
                    y = parseInt(x.value)+1;
                    x.value = y%2;
                    switch(parseInt(x.value))
                    {
                        case 0:
                        x.innerText = "single";
                        console.log("single");

                        var multiple = document.getElementsByClassName('addMultiple');
                        var single = document.getElementsByClassName('addSingle');

                        for (var i = 0; i < multiple.length; i++) 
                        {
                            multiple[i].style.visibility = "hidden";
                        }
                        for (var i = 0; i < single.length; i++) 
                        {
                            single[i].style.visibility = "visible";
                        }
                        break;

                        case 1:
                        x.innerText = "multiple";
                        console.log("multiple");

                        var single = document.getElementsByClassName('addSingle');
                        var multiple = document.getElementsByClassName('addMultiple');

                        for (var i = 0; i < multiple.length; i++) 
                        {
                            multiple[i].style.visibility = "visible";
                        }
                        for (var i = 0; i < single.length; i++) 
                        {
                            single[i].style.visibility = "hidden";
                        }
                        break;
                    }
                }
        </script>
    </head>
    <body onload="setView()">
        <?php
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
mb_http_input('UTF-8');
mb_regex_encoding('UTF-8'); 

        //biblioteka do zczytywania stron
        include 'simple_html_dom.php';
        
		//wyrysuj nieruchome menu
		echo '
        <div id="menu">To jest kontener na menu<br>
            <button id="menuSelectButton" onclick="switchAdd()" value="0">Single</button>
        <br>
            <button class="addMultiple" onclick="countSelected()">Dodaj zaznaczone</button>
        <br>
            <span id="insertResult"></span>
        <br></div>
        ';

        function multiexplode ($delimiters,$string) {
            $ready = str_replace($delimiters, $delimiters[0], $string);
            $launch = explode($delimiters[0], $ready);
            return  $launch;
        }

        class Book {
            private $title;
            private $author;
            private $publisher;
            private $year;
            private $citekey;

            function __construct($title_arg, $author_arg, $publisher_arg, $year_arg, $citekey_arg){
                $title = $title_arg; 
                $author = $author_arg;
                $publisher = $publisher_arg;
                $year = $year_arg;
                $citekey = $citekey_arg;
            }

            function get_title() { 
                return $title; 
            } 
            function get_author() { 
                return $author; 
            } 
            function get_publisher() { 
                return $publisher; 
            } 
            function get_year() { 
                return $year; 
            } 
            function get_citekey() { 
                return $citekey; 
            } 

            function get_bibtex() {
                $bibtex = "@Book{".$citekey.", title = \"".$title."\", "
                    ."author = \"".$author."\", publisher = \"".$publisher."\", "
                    ."year = ".$year."}";
                return $bibtex;
            }

            function set_title($title_arg) { 
                $title = $title_arg; 
            } 
            function set_author($author_arg) { 
                $author = $author_arg;
            } 
            function set_publisher($publisher_arg) { 
                $publisher = $publisher_arg;
            } 
            function set_year($year_arg) { 
                $year = $year_arg;
            } 
            function set_citekey($citekey_arg) { 
                $citekey = $citekey_arg;
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
            $author_cale_te = multiexplode(array(" ", ","), $author);
            $citekey = $author_cale_te[1].$year;
            $book = new Book($title, $author, $publiher, $year, $citekey);
            return $book;
        }

        function convert2bibtexFile($books){ //books = array2D([0]-title, [1]-author, [2]-publisher, [3]-year)
            $file_string = "";
            foreach($books as &$value){
                $book = convert2Book($value[0], $value[1], $value[2], $value[3]);
                $file_string .= $book->get_bibtex();
                $file_string .= "\n";
            }
            $myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
            fwrite($myfile, $file_string);
            
            //return '<span style="display:none">'.$bibtex.'</span>';
        }

        function convert2form($title, $author, $publisher, $year)
        {
            $author_cale_te = multiexplode(array(" ", ","), $author);
            $citekey = $author_cale_te[1].$year;
            $bibtex = '<span name ="'.$citekey.'"></span>
                <span name="title">'.$title.'</span><br>
                <span name="author">'.$author.'</span><br>
                <span name="publisher">'.$publisher.'</span>
                <span name="year">'.$year.'</span><br>
				<input type="hidden" name="title" value="'.$title.'">
				<input type="hidden" name="author" value="'.$author.'">
				<input type="hidden" name="publisher" value="'.$publisher.'">
				<input type="hidden" name="year" value="'.$year.'">
				'
				;
            return $bibtex;
        }

        function convert2insert($title, $author, $publisher, $year)
        {
            $author_cale_te = multiexplode(array(" ", ","), $author);
            $citekey = $author_cale_te[1].$year;
            /*$title = str_replace("&nbsp;"," ",$title);
            $author = str_replace("&nbsp;"," ",$author);
            $publihser = str_replace("&nbsp;"," ",$publisher);
            $year = str_replace("&nbsp;"," ",$year);*/

            /* $bibtex = '<span name ="'.$citekey.'"></span>
                <span name="title">'.str_replace("&nbsp;"," ",$title).'</span><br>
                <span name="author">'.str_replace("&nbsp;"," ",$author).'</span><br>
                <span name="publisher">'.str_replace("&nbsp;"," ",$publisher).'</span>
                <span name="year">'.str_replace("&nbsp;"," ",$year).'</span><br>
				<input type="hidden" name="title" value="'.str_replace("&nbsp;"," ",$title).'">
				<input type="hidden" name="author" value="'.str_replace("&nbsp;"," ",$author).'">
				<input type="hidden" name="publisher" value="'.str_replace("&nbsp;"," ",$publisher).'">
				<input type="hidden" name="year" value="'.str_replace("&nbsp;"," ",$year).'">
				'
                ; */
            $bibtex = '<span name ="'.$citekey.'"></span>
            <span name="title">'.$title.'</span><br>
            <span name="author">'.$author.'</span><br>
            <span name="publisher">'.$publisher.'</span>
            <span name="year">'.$year.'</span><br>
            <input type="hidden" name="title" value="'.$title.'">
            <input type="hidden" name="author" value="'.$author.'">
            <input type="hidden" name="publisher" value="'.$publisher.'">
            <input type="hidden" name="year" value="'.$year.'">
            '
            ;
            return $bibtex;
            /*for($i = 0,$i<4, $i++)
            {

            }
            for($array as $element)
            {
                $element = str_replace("&nbsp"," ",$element);
            }*/
            
        }

        function displayResults($title,$pageNumber)
        {
            /*
                pobieramy zawartość strony biblioteki
                $title          tytuł który chcemy wyszukać
                $page_number    numer strony z wyszukiwania
            */
            $html = file_get_html('https://pp-hip.pfsl.poznan.pl/ipac20/ipac.jsp?index=.GW&term='.$title.'&page='.$pageNumber);
            /*
                znacznik center posiada pozycje ze znalezionymi książkami
                $temp           jest tablicą która posiada wszystkie pozycje dlatego potem iterujemy po kolejnych elementach
            */
            $temp = $html -> find('center[xmlns:strings]')[0]
                ->children(2)
                ->children(0)
                ->children(0)
                ->children(1)
                ->find('table table[style=0]');
            /*
                iterujemy po każdym elemencie tablicy $temp
                wyświetlamy kolejne rzędy <tr>, usuwamy znaczniki i dajemy swoje <span>
            */
            for($i=0;$i<count($temp);$i++)
            {
                //echo $i+1+(10*($pageNumber-1)).$temp[$i].'<br>';
                $elem = $temp[$i]->find('tr');
				
				/*
						
							--TODO: SPRAWDZIĆ CZY JEST W BAZIE
							j=1 - tytuł
							j=2 - autor
                            j=3 - wydawca
                            
                            j=? - wydanie
						
				*/
				//check for books in database
				$author = str_replace("Autor: ","",strip_tags($elem[2]));
				
				include("connect_to_database.php");
				if(!$conn->ping())echo "NOT CONNECTED";
				if(!$conn->ping())echo "---ERROR--- not ping";

                
                $title = html_entity_decode($title);
                // echo $title;


                $test = "Projektowanie algorytmów grafowych / Krystyna Balińska, Krzysztof T. Zwierzyński. ";

                
				
                $wydawca_cale_te = explode(', ', strip_tags($elem[3]));
                $title = html_entity_decode(strip_tags($elem[1]));
                echo (@convert2bibtex($title, $author, $wydawca_cale_te[0], substr($wydawca_cale_te[1], 0, -1)));
                
                $yr = @substr($wydawca_cale_te[1], 0, -1);
				// echo 'TITLE---'.$title."---TITLE<br>";

                //echo (convert2bibtex(strip_tags($elem[1]), $author, $wydawca_cale_te[0], substr($wydawca_cale_te[1], 0, -1)));
                
				if (!($stmt = $conn->prepare('SELECT * FROM books WHERE `title` LIKE ? AND `year` LIKE ?')))
				{
                    echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
                }
                
				if (!$stmt->bind_param("si", $title,$yr)) 
				{
                    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
                }
                
				if (!$stmt->execute()) 
				{
                    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                }
                
                
                $result = $stmt->get_result();
                
                $stmt->close();
                $conn->close();
				//select color (red or green)usu
                //wyswietlanie do formularza
                if ($result->num_rows > 0)echo '<form method="POST" action="delete.php">'./*                                                                                USUWANIE Z BAZY DODAĆ ! ! ! */'
                
                <div class="result'.($i+1).'" style="border: 4px; border-color: green; border-style: solid;">';
				else echo '<form method="POST" action="addToDataBase.php"><div class="result'.($i+1).'" style="border: 4px; border-color: red; border-style: solid;">';
                echo (($i+1)+(10*($pageNumber-1))).'  ';
                
                
                echo(@convert2insert(strip_tags($elem[1]), $author, $wydawca_cale_te[0], substr($wydawca_cale_te[1], 0, -1)));
                


                for($j=1;$j<4;$j++)
                {
                    $temp_res = strip_tags($elem[$j]);
                    if(strlen($temp_res)>5)
                    {
						
                        // echo '<span id="'.$j.'" quantity="'.strlen($temp_res).'">'.$temp_res.'</span><br>';

                    }
                }

                if ($result->num_rows == 0)
                {
                    echo '<span>Chose category: 
                    <input name="category" type="text" list="categories">
                    <br>
                    <input type="submit" class="addSingle" value="Add to database">';
                }
                else
                {
                    echo '<span>Resource name:
                    <input name="tagName" type="text">

                    Link: <input name="link" type="text">
                    <br>
                    <input type="submit" value="Add book resource">
                    <input type="submit" class="delete_record" value="Delete from database">';
                }
                // echo '<span>
                // Chose category: <input name="category" type="text" list="categories"><br><input class="addMultiple" type="checkbox" class="ptak">';                
                // if ($result->num_rows == 0) echo '<input type="submit" class="addSingle" value="Add to database">';
                // else echo '<input type="submit" class="delete_record" value="Delete from database">';
                echo '</span><br></div>';
                echo '</form>';

            }
			include("connect_to_database.php");
                if($queryResult = $conn->query('SELECT `name` FROM `categories` ORDER BY 1;'))
                {
                    echo'<datalist id="categories">';
                    while($row = $queryResult->fetch_assoc())
                    {
                        echo '<option value="'.$row["name"].'">';
                    }
                    echo '</datalist>';
                }
                else
                {
                    echo 'Database connection error!<br>';
                }
			$conn->close();
                
        }
        
        function numberOfPages($title)
        {
            /* 
                otwieramy pierwszą stronę z wyszukiwaną frazą
                pobieramy element, w którym zwrócona jest liczba otrzymanych wyników wyszukiwania
                $results        liczba wyników
            */
            $results = strip_tags(file_get_html('https://pp-hip.pfsl.poznan.pl/ipac20/ipac.jsp?index=.GW&term='.$title.'&page=1')
                                 ->find('body table.tableBackground a.normalBlackFont2')[0]
                                 ->children(0));
            echo '<p class="results">Results: '.$results.'</p>';
            /*
                jako, że przypada po 10 pozycji na strone, na podstawie liczby wyników obliczamy liczbę stron
                $number         luczba stron
            */
            $number = $results%10 > 0 ? ($results/10)+1 : $results/10;
            settype($number,"integer");
            echo '<p class="results">Pages: '.$number.'</p>';

            return $number;
        }

        function display()
        {
            $number_of_pages = 0;
            echo '<div id="mainPanel">';
            if(!empty(@$_GET['title'])) 
            {
                echo '<h2 class="searching">Searching: '.@$_GET['title'].'</h2>';
                $number_of_pages = numberOfPages(str_replace(" ","+",$_GET['title']));
            }
            else
            {
                echo '<h2 class="searching">Brak wyszukiwania</h2>';
            }

            echo '<hr></div>';
            $title = @$_GET['title'];
            
            if(!empty($title))
            {
                // echo '<h2>Searching: '.$title.'</h2><br>';
                
                
                //wyświetlamy pozycje z każdej strony wyszukiwania
                for($i=1;$i<=$number_of_pages;$i++)
                {
                    displayResults(str_replace(" ","+",$title),$i);
                }
            }
        }
        
        


        display();
        ?>
        
    </body>
</html>