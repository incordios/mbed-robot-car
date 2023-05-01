<?php 
session_start();
$file_handle = fopen("/dev/ttyACM0", "r");
if (!$file_handle) {
   echo "Mbed connection error<br>";
}
$line = fgets($file_handle);
if (preg_match('/Rear Distance:/', $line)){
   $_SESSION["ultrasonic"] = $line;
   echo "$line";
} else {
   echo $_SESSION["ultrasonic"];
} 
   
fclose($file_handle);
?>
