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
	require("functions.php");
	
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
		echo'
		<div id="menu">
			<form action="" target="_self">
				<label for="title">Tytuł:</label><br>
				<input type="text" id="title" name="title">
				<select id="base" name="base">
                <option value="remote">Baza zdalna</option>
                <option value="local"';
                    if(@$_GET['base']=="local") echo ' selected';
                    echo '>Baza lokalna</option>
				</select>
				<input type="submit" value="Szukaj">
			</form>
			
		
		<script>
			var parameterValue = decodeURIComponent(window.location.search.match(/(\?|&)title\=([^&]*)/)[2]);

			document.getElementById("title").value = parameterValue.replace(/\+/g, " ");
		</script>
		
		
		</div>';

        echo '<form action="getFromDatabase.php">
                <input type="submit" value="Pobierz zawartość bazy do formatu bibtex" name="btn">
            </form>';
			
			
			//do value zamiast "2" wrzuc ten ID z bazy co ciebie intereere i ten sie wypluje dla jego
		 echo '<form action="getFromDatabaseID.php">
				<input type="hidden" value="2" name="value2">
                <input type="submit" value="Pobierz wybrany element bazy do formatu bibtex" name="btn">
            </form>';
		
		//do value wrzucamy tablice (pol.stół) z ID które chcemy w pliku wyplutym. elemetna tablycy odzielone winny byc przecinkamy lub spacyią
		 echo '<form action="getFromDatabaseStol.php">
				<input type="hidden" value="2,1,3,7" name="value2">
                <input type="submit" value="Pobierz wybrane elementy bazy do formatu bibtex" name="btn">
            </form>';


        


        display();
        ?>
        
    </body>
</html>