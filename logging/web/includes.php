<?php

//path to 1-wire sensors to scan
$sensors_path = "/sys/bus/w1/devices/";

//get all sensors files.
$devices = glob($sensors_path . "*");

//path to stored 1-wire sensors settings
$sensors_settings_path = "/home/pi/logging";


//path to the modes settings
$mode_settings_path = "/home/pi/logging/modes";

//get all sensors files.
$modes = glob($mode_settings_path . "/*");


function read_file($path){
    if (!file_exists($path)) {
      return "";
    }
    $fn = fopen($path, "r");
    $retval = fread($fn,filesize($path));
    fclose($fn);
    return $retval;
}

function write_file($path, $data){
    $fn = fopen($path, 'w');
    fwrite($fn, "$data\n");
    fclose($fn);
}

function write_file_extra($path, $data, $mode){
    $fn = fopen($path, $mode);
    fwrite($fn, "$data\n");
    fclose($fn);
}

echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\" />";

$ip=$_SERVER['SERVER_ADDR'];
//echo "<b>Server IP Address= $ip</b>";
 
$ip=$_SERVER['REMOTE_ADDR'];
//echo "<p><b>Your IP Address= $ip</b>"; 


$off_modes = array("Auto", "OFF");
$off_modes_select = array("Auto", "Manual", "OFF");
?>
