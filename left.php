<?php 
$file_handle = fopen("/dev/ttyACM0", "w");
$line = "ctrl3";
fwrite($file_handle, $line);
fclose($file_handle);
?>
