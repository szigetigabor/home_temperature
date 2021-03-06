<?php

require_once('constants.php');

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

function remove_file($path){
    $retval = unlink($path);
    return $retval;
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


$heating_or_cooling=array("Heating", "Cooling");
$off_modes = array("Manual", "OFF");
$off_modes_select = array("Manual", "Auto", "OFF");


$time_values = array("0:30","1","1:30","2","2:30","3","3:30","4","4:30","5","5:30","6",
"6:30","7","7:30","8","8:30","9","9:30","10","10:30","11","11:30","12",
"12:30","13","13:30","14","14:30","15","15:30","16","16:30","17","17:30","18",
"18:30","19","19:30","20","20:30","21","21:30","22","22:30","23","23:30","24");

$split_times = array("6:30", "12:30", "18:30");

// TimeZone settings
$current = read_file($sensors_settings_path."/timezone");
$current = trim($current, " \n.");
if ( $current == "" ) {
  $current=date_default_timezone_get();
}
date_default_timezone_set($current);

// IP masking, sequrity function
function isIPIn($ip, $net, $mask) {
    //doesn't check for the return value of ip2long
    $ip = ip2long($ip);
    $rede = ip2long($net);
    $mask = ip2long($mask);

    //AND
    $res = $ip & $mask;
    return ($res == $rede);
}

$mask="0.0.0.0";
$ip=$_SERVER["REMOTE_ADDR"];
$net="0.0.0.0";


?>
