<?php
//console.log("Test");
echo "dupa";
$starttime = microtime(true);
for ($x = 0; $x <= 1000000000000000; $x++) {
    usleep(10 * 1000);
    $endtime = microtime(true);
    $timediff = $endtime - $starttime;
    //console.log("Elapsed time ".$timediff);
    $file = "bibtex".$x.".ktz";
    $txt = fopen($file, "w") or die("Unable to open file!");
    fwrite($txt, $file_string);
    fclose($txt);
    echo "dupa1.5";
}
echo "dupa2";
?>