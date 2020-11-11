<!--


	TODO: Przycisk "zaznacz wiele" - zmienia widoczność checkboxa i przycisku zapisz
	TODO: po zaznaczeniu checkboxa pojawia się "choose category"
	
	ZAZNACZ WIELE -> Pokazuje Checkboxy
				  -> Pokazuje Zapisz wiele
				  -> Ukrywa zapisz (pojedynczy)
				  -> Zmienia nazwę "zaznacz wiele" na "zaznacz jeden"


-->

<style>
	.ptak
	{
			/* visibility: hidden; */
			width:60px;
			height:60px;
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
	// echo "Connected successfully<br>";
	// echo '<a href="https://www.youtube.com/watch?v=73T5NVNb7lE">TU, OBOWIĄZKOWO SPRAWDZIĆ</a>';
	// echo '<img src="https://naukawpolsce.pap.pl/sites/default/files/styles/strona_glowna_slider_750x420/public/202005/portretProboscis_monkey_%28Nasalis_larvatus%29_male_head_0.jpg?itok=4nPIZ3jj" style="float:none;"> ';
    
    
    ///Przykład użycia SELECT
	/*$sql = "SELECT * FROM books";

	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) 
	{
		while($row = $result->fetch_assoc()) 
		{
			echo "id: " . $row["ID"]. " - title: " . $row["title"]. " author: " . $row["author"]. "<br>";
		}
	} 
	else
	{
		echo "0 results";
	}

	$conn->close();*/
	
	$_SESSION["baza"] = $conn; // z tego korzystajcie bo to jest baza ZA DARMO!
?>


<!DOCTYPE HTML>
<html>
    <head>
        <title>Bibtex database</title>
    </head>
    <body>
        <?php
        //biblioteka do zczytywania stron
        include 'simple_html_dom.php';
        

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
                echo (($i+1)+(10*($pageNumber-1))).'  ';
                echo '<div class="result'.$i.'">';
                for($j=1;$j<4;$j++)
                {
                    $temp_res = strip_tags($elem[$j]);
                    if(strlen($temp_res)>5)
                    {
                        echo '<span id="'.$j.'" quantity="'.strlen($temp_res).'">'.$temp_res.'</span><br>';
						/*
						
							TODO: SPRAWDZIĆ CZY JEST W BAZIE
							j=1 - tytuł
							j=2 - autor
							j=3 - wydawca
						
						*/
                    }
                }
                echo '<span>
                <button onclick="">Check</button>
                <button onclick="">Add to database</button>
                Chose category: <input type="text" list="categories"><br><input type="checkbox" class="ptak">                
                </span><br>';
                echo '</div><hr>';
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
        
        
        
        ?>
        
    </body>
</html>