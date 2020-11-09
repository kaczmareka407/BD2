<!DOCTYPE HTML>
<html>
    <head>
        <title>Bibtex database</title>
    </head>
    <body>
        <?php
        include 'simple_html_dom.php';
        
        

        function displayResults($title,$pageNumber)
        {
            $html = file_get_html('https://pp-hip.pfsl.poznan.pl/ipac20/ipac.jsp?index=.GW&term='.$title.'&page='.$pageNumber);
            $temp = $html -> find('center[xmlns:strings]')[0]
                ->children(2)
                ->children(0)
                ->children(0)
                ->children(1)
                ->find('table table[style=0]');
            for($i=0;$i<count($temp);$i++)
            {
                echo $i+1+(10*($pageNumber-1)).$temp[$i].'<br>';
            }
        }
        
        function numberOfPages($title)
        {
            $results = strip_tags(file_get_html('https://pp-hip.pfsl.poznan.pl/ipac20/ipac.jsp?index=.GW&term='.$title.'&page=1')
                                 ->find('body table.tableBackground a.normalBlackFont2')[0]
                                 ->children(0));
            echo 'Results: '.$results.'<br>';
            $number = $results%10 > 0 ? ($results/10)+1 : $results/10;
            settype($number,"integer");
            echo 'Pages: '.$number.'<br>';

            return $number;
        }

        $title = "włodarski";
        echo '<h2>Searching: '.$title.'</h2><br>';
        $number_of_pages = numberOfPages($title);

        for($i=1;$i<=$number_of_pages;$i++)
        {
            displayResults($title,$i);
        }
        
        ?>
        
    </body>
</html>