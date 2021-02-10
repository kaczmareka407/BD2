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
	<style>
	.add
	{
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
			font-style: italic;
			-moz-appearance: none;
			-webkit-logical-height: 1em; /* Chrome ignores auto, so we have to use this hack to set the correct height  */
			-webkit-logical-width: auto; /* Chrome ignores auto, but here for completeness */
			 
	}
	</style>
<?php


function multiexplode ($delimiters,$string) 
		{
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
					echo "Binding parameters failed: (" . $stmt2->errno . ") " . $stmt2->error;
				}
                
				if (!$stmt2->execute()) 
				{
					echo "Execute failed: (" . $stmt2->errno . ") " . $stmt2->error;
				}
				$result2 = $stmt2->get_result();
				$tagname = $result2->fetch_assoc()['tagName'];
				
				
				echo '<div style="white-space: nowrap; font-style: italic; display:inline-block; width: 200px;"><div style="width:75px; max-width:75px; display:inline-block;">' . $tagname.'  </div><div style="display:inline-block;overflow: hidden;  text-overflow: ellipsis; width:150px; max-width:150px; "><a href="'. $row['link'].'">'.$row['link'].'</a></div></div>';
				if(basename($_SERVER['PHP_SELF']) == "book.php")
				{
					//TODO usuwanie zasobów
					echo '
					<div style="margin-left: 20px;display: inline-block;" ><a class="add" style="display: inline; color:red; text-align: center;" href="delete_resource.php?book_id='.$_GET["book_id"].'&resource_id='.$row["ID"].'">Delete</a></div>
					
					';
				}
				echo '<br>';
				//echo basename($_SERVER["PHP_SELF"]);
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
			echo '<button class="add" type="button" onclick="showAddButton('.$book_id.')" id="bookResourcesAddButton'.$book_id.'">Add...</button>';
			
			/*new resource form*/
			echo 
			'
			
			<form name="addRes" action="add_resource.php" method="post" class="bookResourcesForm'.$book_id.'" style="display:none">
				<input name="book_id" type="hidden" value="'.$book_id.'" class="bookResourcesForm'.$book_id.'" style="display:none">
				<input name="rlink" type="text" placeholder="Resource link" class="bookResourcesForm'.$book_id.'" style="display:none" required>
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
				
                
				//select color (red or green)usu
                //wyswietlanie do formularza
				$in_base = false;
                if ($result->num_rows > 0)
				{
					$book_id = $result->fetch_assoc()["ID"];
					
					$in_base = true;
					echo '<form method="POST" action="delete_book?book_id='.$book_id.'.php">'./*USUWANIE Z BAZY DODAĆ ! ! ! */
					'<div class="result'.($i+1).'" style="border: 4px; border-color: green; border-style: solid;">
					</form>
					';
		
				}
				else echo '<form method="POST" action="addToDataBase.php?title='.$_GET['title'].'"><div class="result'.($i+1).'" style="border: 4px; border-color: red; border-style: solid;">';

			$stmt->close();
            $conn->close();
				
			
			if($in_base == true)echo '<a href="book.php?book_id='.$book_id.'">Przejdź do książki</a><br><br> ';	
            
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
			if($results=="") $results = 0;
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
            
            $title = @$_GET['title'];
            
            if(!empty($title))
            {
                // echo '<h2>Searching: '.$title.'</h2><br>';
                
                if(@$_GET['base']=="remote")
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
						echo '<hr></div>';
						echo '<h2 class="searching">Brak wyszukiwania</h2>';
						return;
					}
					echo '<hr></div>';
					//wyświetlamy pozycje z każdej strony wyszukiwania
					if($number_of_pages==0)
					{
						echo '<h2 class="searching">Brak wyszukiwania</h2>';
						return;
					}
					for($i=1;$i<=$number_of_pages;$i++)
					{
						displayResults(str_replace(" ","+",$title),$i);
					}
				}
				else 
				{
					echo '<hr>';
					//wyświetlanie pozycji z lokalnej bazy
					include("connect_to_database.php");
					$result = $conn->query('SELECT * FROM `books` WHERE `title` LIKE "%'.@$_GET['title'].'%";');
					if($result->num_rows==0)
					{
						echo '<h2 class="searching">Brak wyszukiwania</h2>';
						$conn->close();
						return;
					}
					while($row = $result->fetch_assoc())
					{
						echo '<div style="border: 4px; border-color: green; border-style: solid;">';
						echo '<a href="book.php?book_id='.$row['ID'].'">Przejdź do książki</a><br> ';
						echo'<br>'.$row['title'].'<br>'.$row['author'].'<br>'.$row['publisher'].' '.$row['year'].'<br>';
						displayResources($row['ID']);
						echo '</div><br>';
					}

					$conn->close();
				}
                
            }
			else
            {
				if(@$_GET['base']!="remote")
				{
					include("connect_to_database.php");
					$result = $conn->query('SELECT * FROM `books` WHERE `title` LIKE "%'.@$_GET['title'].'%";');
					while($row = $result->fetch_assoc())
					{
						echo '<div style="border: 4px; border-color: green; border-style: solid;">';
						echo '<a href="book.php?book_id='.$row['ID'].'">Przejdź do książki</a><br> ';
						echo'<br>'.$row['title'].'<br>'.$row['author'].'<br>'.$row['publisher'].' '.$row['year'].'<br>';
						displayResources($row['ID']);
						echo '<br></div><br>';
					}
					$conn->close();
				}
            }
        }
		
function delete_resource($resource_id)
	{
		include("connect_to_database.php");
		
		if (!($stmt = $conn->prepare('DELETE FROM book_resources WHERE ID = ?')))
			{
                echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
            }
                
			if (!$stmt->bind_param("i", $resource_id)) 
			{
                echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            }
                
			if (!$stmt->execute()) 
			{
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            }
			$stmt->close();
            $conn->close();
	}

function displayBook($book_id)
	{
		include("connect_to_database.php");
		
		
		if (!($stmt = $conn->prepare('SELECT * FROM books WHERE ID = ?')))
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
			
			$row = $result->fetch_assoc();
			$stmt->close();
			
			echo '<div style="margin: 20px; padding: 10px 20px 20px 20px; outline: 2px solid blue;">';
			echo(
			'<div style="padding: 10px 5px 10px; outline: 2px solid green;"><b>Tytuł:</b> '.$row['title'].'</div><hr>'
			.'<b>Autor:</b> '.$row['author'].'<hr>'
			.'<b>Wydawnictwo:</b> '.$row['publisher'].'<hr>'
			.'<b>Rok:</b> '.$row['year'].'<hr>');
			
			if (!($stmt = $conn->prepare('SELECT * FROM categories WHERE categoryID = ?')))
			{
                echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
            }
                
			if (!$stmt->bind_param("i", $row['category'])) 
			{
                echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            }
                
			if (!$stmt->execute()) 
			{
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            }
                
                
            $result = $stmt->get_result();
			$row = $result->fetch_assoc();
			echo '<b>Kategoria:</b> '.$row['name'].'<hr>';
			
			displayResources($book_id);
			
			
			echo '
			<br><br><hr>
			<form method="get" action="delete_book.php?book_id='.$book_id.'">
				<input type="hidden" name="book_id" value="'.$book_id.'">
				<input type="submit" value="Usuń książkę">
				<input type="checkbox" id="check" required>
				<label for="check">Potwierdź usunięcie</label>
			</form>
			';
			
			echo '</div>';
			
			$stmt->close();
			
			
			

            $conn->close();
			
	}
	
function deleteBook($book_id)
{
	echo '<script>console.log('.$book_id.');</script>';
	echo '<script>console.log(dupa123);</script>';
	include("connect_to_database.php");
	if (!($stmt = $conn->prepare('DELETE FROM books WHERE ID = ?')))
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
			
			$stmt->close();		
			$conn->close();		
}	
	
?>