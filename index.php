<!--

	--TODO: Wczytywanie wierszy z bazy dla piela
	--TODO: Sprawdzenie czy jest w bazie książka
	--TODO: Nieruchome menu
	
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
	
	$servername = "localhost";
	$username = "admin";
	$password = "admin";
	$dbname = "bibtex_db";

	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) 
	{
		die("Connection failed: " . $conn->connect_error);
	}
    
    ///Przykład użycia SELECT dla Magosa Ioannesa Pielusa
	/*
	
	$sql = "SELECT * FROM books"; //zapytanie

	$result = $conn->query($sql);//wczytanie wyniku zapytania
	
	if ($result->num_rows > 0) //jeżeli zapytanie zwróciło więcej niż 0 wierszy
	{
		while($row = $result->fetch_assoc()) //do $row przypisujemy kolejny wiersz, po prostu to sobie skopiujcie
		{
			echo "id: " . $row["ID"]. " - title: " . $row["title"]. " author: " . $row["author"]. "<br>";
			
			//$row["nazwa_kolumny"] - pod tym siedzi to co jest w wierszu pod danym polem
			//$row traktujemy jak mapę
		}
	} 
	else
	{
		echo "0 results";
	}

	 //zakończ połączenie z bazą
	
	*/
	
	///INSERT do bazy dla Magosa Ballusa
	/*
		$conn = $_SESSION["baza"];
				if($stmt = $conn->prepare("INSERT INTO books (title, author, publisher, year, category) VALUES (?, ?, ?, ?, ?)"))
				{
					//sssii znaczy string*3 int*2 - inne: d-double, b-BLOB
					$stmt->bind_param("sssii", $title, $author, $publisher, $year, $cat);
					$title = "grafy";
					$author = "zwierz";
					$publisher = "poli";
					$year = 2137;
					$cat = 3;
					$stmt->execute();
				}
				else
				{
					echo "nie można dodać do bazy bo tak";
				}
				
	*/
	
	
	$_SESSION["baza"] = $conn; //tutaj jest baza i jest dostępna cały czas w trakcie działania sesji
?>


<!DOCTYPE HTML>
<html>
    <head>
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

        function convert2bibtex($title, $author, $publisher, $year){
            //$author_cale_te = explode(" ", $author);
            $author_cale_te = multiexplode(array(" ", ","), $author);
            $citekey = $author_cale_te[1].$year;
            $bibtex = "@Book{".$citekey.", title = \"".$title."\", "
                ."author = \"".$author."\", publisher = \"".$publisher."\", "
                ."year = ".$year."}";
            return $bibtex;
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
				$sql = 'SELECT * FROM books WHERE title LIKE "'.strip_tags($elem[1]).'" AND author LIKE "'.$author.'" AND publisher LIKE "'.strip_tags($elem[3]).'"';
                
                
                $wydawca_cale_te = explode(', ', strip_tags($elem[3]));
                echo(convert2bibtex(strip_tags($elem[1]), $author, $wydawca_cale_te[0], substr($wydawca_cale_te[1], 0, -1)));
                

                $result = $_SESSION["baza"]->query($sql);//wczytanie wyniku zapytania
				
				//select color (red or green)
				if ($result->num_rows > 0)echo '<div class="result'.($i+1).'" style="border: 4px; border-color: green; border-style: solid;">';
				else echo '<div class="result'.($i+1).'" style="border: 4px; border-color: red; border-style: solid;">';
				echo (($i+1)+(10*($pageNumber-1))).'  ';
				
                for($j=1;$j<4;$j++)
                {
                    $temp_res = strip_tags($elem[$j]);
                    if(strlen($temp_res)>5)
                    {
						
                        echo '<span id="'.$j.'" quantity="'.strlen($temp_res).'">'.$temp_res.'</span><br>';

                    }
                }
                echo '<span>
                <button onclick="">Check</button>
                <button class="addSingle" onclick="">Add to database</button>
                Chose category: <input type="text" list="categories"><br><input class="addMultiple" type="checkbox" class="ptak">                
                </span><br>';
                echo '</div>';
            }
                if($queryResult = $_SESSION["baza"]->query('SELECT `name` FROM `categories` ORDER BY 1;'))
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
        
        
        $conn->close();
        ?>
        
    </body>
</html>