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
	<script>
				function showAddButton(book_id) 
				{
					var x = document.getElementById("bookResourcesAddButton"+book_id );
					var y = document.getElementsByClassName("bookResourcesForm"+book_id);
					if (x.style.display === "none") 
					{
						x.style.display = "inline";
					} 
					else 
					{
						x.style.display = "none";
					}
					if (x.style.display === "none") 
					{
						for(var i=0;i<y.length;i++)
						{
							y[i].style.display = "inline";
						}
						y.nextSibling.style.display = "inline";
					} 
					else 
					{
						for(var i=0;i<y.length;i++)
						{
							y[i].style.display = "none";
						}
						y.nextSibling.style.display = "none";
					}
				}
	</script>
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
                
                function echojujdupe(){
                    console.log("dupa");
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
        //<button action="getFromDatabase.php" onclick="echojujdupe()">Pobierz bibtexa</button>
          //  <form method="POST" action="getFromDatabase.php?">
               
        //<br>
		echo '
        <div id="menu">To jest kontener na menu<br>
            
            <button id="menuSelectButton" onclick="switchAdd()" value="0">Single</button>
        <br>
            <button class="addMultiple" onclick="countSelected()">Dodaj zaznaczone</button>
        <br>
            <span id="insertResult"></span>
        <br></div>
        ';
        echo '<form action="getFromDatabase.php">
                <input type="submit" value="Pobierz zawartość bazy do formatu bibtex" name="btn">
            </form>';

        function multiexplode ($delimiters,$string) {
            $ready = str_replace($delimiters, $delimiters[0], $string);
            $launch = explode($delimiters[0], $ready);
            return  $launch;
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

		function displayResources($book_id)
		{
			include("connect_to_database.php");
			//wypisuje dostępne zasoby książki
			if (!($stmt = $conn->prepare('SELECT * FROM book_resources WHERE bookID = ?')))
			{
                echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
            }
                
			if (!$stmt->bind_param("i", $book_id)) 
			{
                echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            }
                
			if (!$stmt->execute()) 
			{
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            }
                
                
            $result = $stmt->get_result();
			echo '<div style="padding-left: 2em">';
			
			/*Load resurce and its category*/
			while($row = $result->fetch_assoc()) 
			{
				
				if (!($stmt2 = $conn->prepare('SELECT * FROM resource_category WHERE ID = ?')))
				{
					echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
				}
                
				if (!$stmt2->bind_param("i", $row['tagID'])) 
				{
					echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
				}
                
				if (!$stmt2->execute()) 
				{
					echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
				}
				$result2 = $stmt2->get_result();
				$tagname = $result2->fetch_assoc()['tagName'];
				

				echo '<i>' . $tagname."  <a href=". $row['link'].">" . $row['link']. "</a></i><br>";
			}
			
			/*Get all categories*/
			if (!($stmt3 = $conn->prepare('SELECT tagName FROM resource_category')))
				{
					echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
				}
				
				if (!$stmt3->execute()) 
				{
					echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
				}
				$result3 = $stmt3->get_result();
				
			$categories = array();	
			while($row = $result3->fetch_assoc())
			{
				array_push($categories,$row['tagName']);
			}						
			
			/*Save button, styled as link*/
			echo '<button type="button" onclick="showAddButton('.$book_id.')" id="bookResourcesAddButton'.$book_id.'"
			style="
			
			
			align-items: normal;
			background-color: rgba(0,0,0,0);
			border-color: rgb(0, 0, 238);
			border-style: none;
			box-sizing: content-box;
			color: rgb(0, 0, 238); 
			cursor: pointer;
			display: inline;
			font: inherit;
			height: auto;
			padding: 0;
			perspective-origin: 0 0;
			text-align: start;
			text-decoration: underline;
			transform-origin: 0 0;
			width: auto;
			-moz-appearance: none;
			-webkit-logical-height: 1em; /* Chrome ignores auto, so we have to use this hack to set the correct height  */
			-webkit-logical-width: auto; /* Chrome ignores auto, but here for completeness */
			 font-style: italic;
			
			"
			>Add...</button>';
			
			/*new resource form*/
			echo 
			'
			
			<form name="addRes" action="add_resource.php" method="post" class="bookResourcesForm'.$book_id.'" style="display:none">
				<input name="book_id" type="hidden" value="'.$book_id.'" class="bookResourcesForm'.$book_id.'" style="display:none">
				<input name="rlink" type="text" placeholder="Resource link" class="bookResourcesForm'.$book_id.'" style="display:none">
				<select name="category" class="bookResourcesForm'.$book_id.'" style="display:none">
				';
				
				echo ' <option value = "'.$categories[0].'" selected="selected">'.$categories[0].'</option> ';
				for($i =1 ; $i < count($categories); $i++)
				{
					echo ' <option value = "'.$categories[$i].'">'.$categories[$i].'</option> ';
				}	
				
			echo '
				</select>
				<input type="submit" value="Save" class="bookResourcesForm'.$book_id.'" style="display:none">
			</form>
			<button type="button" onclick="showAddButton('.$book_id.')" class="bookResourcesForm'.$book_id.'" style="display:none">Cancel</button>
			';
			
			echo '</div>';
            $stmt->close();
            $conn->close();
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
                //echo($author);
				
				include("connect_to_database.php");
				

                
                $title = html_entity_decode($title);
                // echo $title;


                $test = "Projektowanie algorytmów grafowych / Krystyna Balińska, Krzysztof T. Zwierzyński. ";

                
				
                $wydawca_cale_te = explode(', ', strip_tags($elem[3]));
                $title = html_entity_decode(strip_tags($elem[1]));
                //echo (@convert2bibtex($title, $author, $wydawca_cale_te[0], substr($wydawca_cale_te[1], 0, -1)));
                
                $yr = @substr($wydawca_cale_te[1], 0, -1);
				// echo 'TITLE---'.$title."---TITLE<br>";

                //echo (convert2bibtex(strip_tags($elem[1]), $author, $wydawca_cale_te[0], substr($wydawca_cale_te[1], 0, -1)));
                //convert2bibtexFile(array(array(strip_tags($elem[1]), $author, $wydawca_cale_te[0], substr($wydawca_cale_te[1], 0, -1))));

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
				$in_base = false;
                if ($result->num_rows > 0)
				{
					$book_id = $result->fetch_assoc()["ID"];
					$in_base = true;
					echo '<form method="POST" action="delete.php">'./*USUWANIE Z BAZY DODAĆ ! ! ! */
					'<div class="result'.($i+1).'" style="border: 4px; border-color: green; border-style: solid;">
					</form>
					';
		
				}
				else echo '<form method="POST" action="addToDataBase.php?title='.$_GET['title'].'"><div class="result'.($i+1).'" style="border: 4px; border-color: red; border-style: solid;">';
                //else echo '<form method="POST" action="getFromDatabase.php?title='.$_GET['title'].'"><div class="result'.($i+1).'" style="border: 4px; border-color: red; border-style: solid;">';
                //else  echo '<form action="getFromDatabase.php">
                ///<input type="submit" value="Open Script" name="btn">
            //</form>';
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

                if (!$in_base)
                {
                    echo '<span>Chose category: 
                    <input name="category" type="text" list="categories">
                    <br>
                    <input type="submit" class="addSingle" value="Add to database">';
                }
                else
                {
					echo '<br>';
					echo 'Resources:';
					echo '<br>';
					
					displayResources($book_id);
					
					/*TODO JĘDRZEJ*/
                    /*echo '<span>Resource name:
                    <input name="tagName" type="text">

                    Link: <input name="link" type="text">
                    <br>
                    <input type="submit" value="Add book resource">
                    <input type="submit" class="delete_record" value="Delete from database">';*/
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
        
        //used by show_resources()
	


        display();
        ?>
        
    </body>
</html>