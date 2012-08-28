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
 $command = "$sensors_settings_path/alarm_checking.sh $key";
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
echo "<a href=\"".$own_name.$get_param."\" class=\"buttonclass\">Move to $button_label</a>";

echo "<p>";

$get_filter="28-";
if (isset($_GET["filter"])) {
  $get_filter=$_GET["filter"];
}

// FORM END

//GLBOAL MODE
$global_mode = read_file($sensors_settings_path."/current_mode");
$global_mode = trim($global_mode, " \n.");
if ($global_mode == "") {
    $global_mode="";
}
$global_disabled = "disabled=\"disabled\"";
if ($global_mode == "Manual") {
    $global_disabled = "";
}

 
//get all sensors files.
$devices = glob($sensors_path . "*");


echo "       <form method=\"get\">";
echo "         <select name=\"filter\">";
$selected = "";
if ($get_filter == "") {
  $selected = "selected=\"selected\"";
}

echo "           <option value=\"\" $selected>all</option>";
$selected = "";
if ($get_filter == "28-") {
  $selected = "selected=\"selected\"";
}
echo "           <option value=\"28-\" $selected>Temperature</option>";
$selected = "";
if ($get_filter == "-") {
  $selected = "selected=\"selected\"";
}

echo "           <option value=\"-\" $selected>Switch</option>";
$selected = "";
if ($get_filter == "w1_") {
  $selected = "selected=\"selected\"";
}

echo "           <option value=\"w1_\" $selected>1-wire master</option>";
echo "         </select>";
echo "         <input type=\"submit\" value=\"Filter\">";
echo "       </form>";


?>
<center>
<table>
  <thead>
  <tr>
     <th>device</th>
     <th>current value</th>
     <th>required <br>temperature</th>
     <th>Mode</th>
     <th>switch</th>
     <th>heating <br>on/off</th>
  </tr>
  </thead>

<?php
// Filter option
$filter = "28-";

//print each sensor device
foreach($devices as $device)
{
  $device_name=substr($device, strrpos($device, "/")+1);
  if ( $get_filter != "" && $get_filter != substr($device_name,0,3) ) {
    continue;
  }
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

  //MODE
  $mode = read_file($settings_path."/mode");
  $mode = trim($mode, " \n.");
  if ($mode == "") {
      $mode="";
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
      echo "       <img src=\"\" width=\"1\" height=\"15\">";
      echo "       <form method=\"post\">";
      echo "      <input type=\"number\" name=\"$device_id\" min=\"16\" max=\"30\" step=\"0.1\" value=\"$alarm\" $global_disabled>";
//      echo "      <input type=\"number\" name=\"$device_id\" min=\"16\" max=\"30\" step=\"0.1\" value=\"$alarm\" >";
      echo "      <input type=\"submit\" value=\"Set\" class=\"buttonclass\" $global_disabled>";
      echo "</form>";
      echo "  </td>";
  } else {
      echo "     <td><input type=\"number\" name=\"$device_id\" min=\"16\" max=\"30\" step=\"0.1\" value=\"$alarm\" $disabled></td>";
  }
  if ($global_mode != ""){
    $mode = $global_mode;
  }
  echo "     <td>$mode</td>";
  echo "     <td>$switch</td>";
  echo "     <td id=$onoff>$onoff</td>";
  echo "  </tr>";
  echo "  </tbody>";
}

?>
</table>
</center>
