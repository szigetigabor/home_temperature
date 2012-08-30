<?php

//path to 1-wire sensors to scan
$sensors_path = "/sys/bus/w1/devices/";

//get all sensors files.
$devices = glob($sensors_path . "*");

//path to stored 1-wire sensors settings
$sensors_settings_path = "/home/pi/logging";


//path to the modes settings
$mode_settings_path = "/home/pi/logging/modes";

//get all modes files.
$modes = glob($mode_settings_path . "/*");


//path to the switch time schedule settings
$time_settings_path = "/home/pi/logging/times";

//get all switch time schedules files.
$times = glob($time_settings_path . "/*");


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


function language(){
    global $sensors_settings_path;
    $lang_file = $sensors_settings_path."/lang";
    $current_lang = read_file($lang_file);
    $current_lang = trim($current_lang, " \n.");
    if ($current_lang == ""){
      return "langs/eng";
    }
    return "langs/".$current_lang;
}

$lang_inc = language().".inc";
include $lang_inc;

echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\" />";

$ip=$_SERVER['SERVER_ADDR'];
//echo "<b>Server IP Address= $ip</b>";
 
$ip=$_SERVER['REMOTE_ADDR'];
//echo "<p><b>Your IP Address= $ip</b>"; 


$off_modes = array("Auto", "OFF");
$off_modes_select = array("Auto", "Manual", "OFF");


$time_values = array("0:30","1","1:30","2","2:30","3","3:30","4","4:30","5","5:30","6",
"6:30","7","7:30","8","8:30","9","9:30","10","10:30","11","11:30","12",
"12:30","13","13:30","14","14:30","15","15:30","16","16:30","17","17:30","18",
"18:30","19","19:30","20","20:30","21","21:30","22","22:30","23","23:30","24");

$split_times = array("6:30", "12:30", "18:30");

?>
