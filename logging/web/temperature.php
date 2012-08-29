<?php
include 'includes.php';
include 'menu.php';

//POST FORM START
$file_name="alarm";
if (count($_POST) > 0 && isset($_POST["file"])){
  $file_name=$_POST["file"];
}
foreach($_POST as $key=>$value)
{
 if($key == "file"){
   continue;
 }
 $file = $sensors_settings_path."/".$key."/$file_name";
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

//get all mode's name.
for($i=0; $i<sizeof($modes); $i++){
  $modes[$i] = substr($modes[$i], strrpos($modes[$i],"/")+1, strlen($modes[$i]));
}

function off_mode($var){
  global $off_modes_select;
  return(!in_array($var, $off_modes_select));
}
$modes = array_filter($modes, "off_mode");


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

  if (in_array($global_mode, $off_modes)){
    $mode = $global_mode;
    echo "     <td><a href=\"add_mode.php?mode=$mode\">$mode</a>";
  } else {
    echo "     <td><a href=\"add_mode.php?mode=$mode\">$mode</a>";
    echo "        <form method=\"post\">";
    echo "         <input type=\"hidden\" name =\"file\" value=\"mode\">";
    echo "         <select name=\"$device_id\">";
    echo "           <option value=\"\"></option>";
    foreach($modes as &$mode_value){
      $selected="";
      if ($mode == $mode_value){
        $selected="selected";
      }
      echo "         <option value=\"$mode_value\" $selected>$mode_value</option>";
    }
    echo "         </select>";
    echo "         <input type=\"submit\" value=\"Set\" class=\"buttonclass\" $global_disabled>";
    echo "        </form>";
    echo "     </td>";
  }
  echo "     <td>$switch</td>";
  echo "     <td id=$onoff>$onoff</td>";
  echo "  </tr>";
  echo "  </tbody>";
}

?>
</table>
</center>
