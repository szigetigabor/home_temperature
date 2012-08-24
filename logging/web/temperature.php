<?php

function read_file($path){
    //$retval;
    $fn = fopen($path, "r");
    $retval = fread($fn,filesize($path));
    fclose($fn);
    return $retval;
}

$ip=$_SERVER['SERVER_ADDR'];
echo "<b>Server IP Address= $ip</b>";
 
$ip=$_SERVER['REMOTE_ADDR'];
echo "<p><b>Your IP Address= $ip</b>"; 

// FORM START
$get_realtime=$_GET["realtime"];

echo "<p>";
$button_label = "RealTime values!";
$get_param="?realtime=1";
if ($get_realtime == "1") {
    $button_label = "Stored values!";
    $get_param="";
}  
$own_name = substr(getenv('SCRIPT_NAME'),1);
echo "<a href=\"".$own_name.$get_param."\"><button type=\"button\">Move to $button_label</button></a>";
echo "<p>";
// FORM END


//path to 1-wire sensors to scan
$sensors_path = "/sys/bus/w1/devices/";
 
//get all sensors files.
$devices = glob($sensors_path . "*");

//path to stored 1-wire sensors settings
$sensors_settings_path = "/home/pi/logging";

?>
<center>
<table border=1>
  <tr>
     <td><b>device</b></td>
     <td><b>value</b></td>
     <td><b>alarm</b></td>
     <td><b>switch</b></td>
     <td><b>on/off</b></td>
  </tr>

<?php
//print each sensor device
foreach($devices as $device)
{
  $device_name=substr($device, strrpos($device, "/")+1);
  $filename=$sensors_settings_path."/".$device_name;
  if ($get_realtime == "1") {
      $filename = $device."/w1_slave";
  }
  $value = read_file($filename);
  $pos = strpos($value, "t=");
  if ($pos === false) {
    // BAD query
    echo "<br>Bad query by <b> $device_name</b>.";
  } else {
    $value = substr($value, $pos+2);
    $value = $value/1000;
  }
    $alarm=0;
    $switch="";
    
    echo "  <tr>";
    echo "     <td>$device_name</td>";
    echo "     <td>$value C</td>";
    echo "     <td><input type=\"number\" name=\"...\" min=\"16\" max=\"30\" step=\"0.1\" value=\"$alarm\" required></td>";
    echo "     <td>switch</td>";
    echo "     <td>on/off</td>";
    echo "  </tr>";
}

?>
</table>
</center>
<progress id="m1" value="0.33">test 1</progress>
