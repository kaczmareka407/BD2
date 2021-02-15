<?php
$starttime = microtime(true);
while(true){
    usleep(10 * 1000);
    $endtime = microtime(true);
    $timediff = $endtime - $starttime;
    //console.log("Elapsed time ".$timediff);
    $file_string = "Elapsed time ".$timediff;
    $file = "elapsed_time.txt";
    $txt = fopen($file, "w") or die("Unable to open file!");
    fwrite($txt, $file_string);
    fclose($txt);
}
?>