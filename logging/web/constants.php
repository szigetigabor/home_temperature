<?php

//path to 1-wire sensors to scan
$sensors_path = "/sys/bus/w1/devices/";
$sensors_owfs_path = "/mnt/1wire/";
//get all sensors files.
$devices = glob($sensors_path . "*");
$devices = array_merge($devices, glob($sensors_owfs_path . "28*"));

$switch_devices = array_merge(array("/main_pump"), $devices);

//path to stored 1-wire sensors settings
$sensors_settings_path = "/home/pi/logging";


//path to the modes settings
$heating_mode_path = "$sensors_settings_path/cooling";
$mode_settings_path = "$sensors_settings_path/modes";

//get all modes files.
$modes = glob($mode_settings_path . "/*");

//path to the groups settings
$group_settings_path = "$sensors_settings_path/groups";

//get all groups files.
$groups = glob($group_settings_path . "/*");

//path to the switch time schedule settings
$time_settings_path = "/home/pi/logging/times";

//get all switch time schedules files.
$times = glob($time_settings_path . "/*");


?>
