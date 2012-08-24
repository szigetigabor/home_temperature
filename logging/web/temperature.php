<?php
//path to 1-wire sensors to scan
$sensors_path = "/sys/bus/w1/devices/";

//path to stored 1-wire sensors settings
$sensors_settings_path = "/home/pi/logging";

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

echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\" />";
include 'menu.php';


$ip=$_SERVER['SERVER_ADDR'];
//echo "<b>Server IP Address= $ip</b>";
 
$ip=$_SERVER['REMOTE_ADDR'];
//echo "<p><b>Your IP Address= $ip</b>"; 

//POST FORM START
foreach($_POST as $key=>$value)
{
 $file = $sensors_settings_path."/".$key."/alarm";
 write_file($file,$value);

 // update the relays
 $command = "$sensors_settings_path/alarm_checking.sh";
 exec ($command, $output);
 //$output = system($command, $retval);
}
//FORM END


// GET FORM START
$get_realtime="";
if (isset($_GET["realtime"])) {
  $get_realtime=$_GET["realtime"];
}

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


 
//get all sensors files.
$devices = glob($sensors_path . "*");

?>
<center>
<table>
  <thead>
  <tr>
     <th>device</th>
     <th>current value</th>
     <th>required <br>temperature</th>
     <th>switch</th>
     <th>on/off</th>
  </tr>
  </thead>

<?php
//print each sensor device
foreach($devices as $device)
{
  $device_name=substr($device, strrpos($device, "/")+1);
  $device_id=$device_name;
  $settings_path=$sensors_settings_path."/".$device_name;
  $filename=$settings_path."/value";
  if ($get_realtime == "1") {
      $filename = $device."/w1_slave";
  }
  $value = read_file($filename);
  $pos = strpos($value, "t=");
  if ($pos === false && $get_realtime == "1") {
    // BAD query
    echo "<br>Bad query by <b> $device_name</b>.";
  } else {
    if ( $get_realtime == "1" ) {
        $value = substr($value, $pos+2);
    }
    $value = $value/1000;
  }

  //ALIAS
  $alias = read_file($settings_path."/alias");
  $alias = trim($alias, " \n.");
  if ($alias != "") {
      $device_name=$alias;
  }

  //ALARM
  $alarm = read_file($settings_path."/alarm");
  $alarm = trim($alarm, " \n.");
  $disabled="";
  if ($alarm == "") {
      $disabled = "disabled=\"disabled\"";
      $alarm=0;
  }

  //SWITCH
  $switch = read_file($settings_path."/switch");
  $switch = trim($switch, " \n.");
  if ($switch == "") {
      $switch="";
  }

  // ON/OFF
  $onoff = read_file($settings_path."/onoff");
  $onoff = trim($onoff, " \n.");
  if ($onoff == "") {
      $onoff="on/off";
  }


  echo "  <tbody>";
  echo "  <tr>";
  echo "     <td>$device_name</td>";
  if ( $value != "" ) {
      echo "     <td id=\"temperature\">$value C</td>";
  } else {
      echo "     <td> </td>";
  }
  if ( $disabled == "" || substr($device_id, 0, 2) == "28" ){
      echo "  <td>";
      echo "      <form method=\"post\">";
//      echo "      <input type=\"number\" name=\"$device_id\" min=\"16\" max=\"30\" step=\"0.1\" value=\"$alarm\" $disabled>";
      echo "      <input type=\"number\" name=\"$device_id\" min=\"16\" max=\"30\" step=\"0.1\" value=\"$alarm\" >";
      echo "      <input type=\"submit\" value=\"Set\">";
      echo "</form>";
      echo "  </td>";
  } else {
      echo "     <td><input type=\"number\" name=\"$device_id\" min=\"16\" max=\"30\" step=\"0.1\" value=\"$alarm\" $disabled></td>";
  }
  echo "     <td>$switch</td>";
  echo "     <td id=$onoff>$onoff</td>";
  echo "  </tr>";
  echo "  </tbody>";
}

?>
</table>
</center>
<progress id="m1" value="0.33">test 1</progress>
