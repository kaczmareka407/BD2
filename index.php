<!DOCTYPE HTML>
<html>
    <head>
        <title>Bibtex database</title>
    </head>
    <body>
        <?php
        //biblioteka do zczytywania stron
        include 'simple_html_dom.php';
        $categories = ["MAT","TEMATYKA DOWOLNA","TO BY NIC NIE DAŁO"];
        
        function insert()
        {
            
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
                echo (($i+1)+(10*($pageNumber-1))).'  ';
                echo '<div class="result'.$i.'">';
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
                <button onclick="">Add to database</button>
                Chose category: <input type="text" list="categories">                
                </span><br>';
                echo '</div><hr>';
            }
            echo'<datalist id="categories">';
                    foreach($GLOBALS['categories'] as $cat)
                    {
                        echo '<option value="'.$cat.'">';        
                    }
                echo '
                    <option value="IT">
                    <option value="MURARSTWO">
                    <option value="MATEMATYKA">
                    <option value="MALARSTWO">
                    <option value="GRAFY">
                </datalist>';
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
            echo 'Results: '.$results.'<br>';
            /*
                jako, że przypada po 10 pozycji na strone, na podstawie liczby wyników obliczamy liczbę stron
                $number         luczba stron
            */
            $number = $results%10 > 0 ? ($results/10)+1 : $results/10;
            settype($number,"integer");
            echo 'Pages: '.$number.'<br><br>';

            return $number;
        }

        function titleDetails()
        {
            
        }
        
        $title = @$_GET['title'];
        //$title = str_replace(" ","+",$title);
        //echo $title.'<br>';
        /*if(@$_GET['pages']!==null)
        {
            echo @$_GET['pages'].'<br>';
        }*/
        
        if(!empty($title))
        {
            echo '<h2>Searching: '.$title.'</h2><br>';
            $number_of_pages = numberOfPages(str_replace(" ","+",$title));
            
            //wyświetlamy pozycje z każdej strony wyszukiwania
            for($i=1;$i<=$number_of_pages;$i++)
            {
                displayResults(str_replace(" ","+",$title),$i);
            }
        }
        else
        {
            echo '<h2>Brak wyszukiwania</h2><br>';
        }
        
        
        ?>
        
    </body>
</html>