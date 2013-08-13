<?php
require_once('includes.php');
include 'menu.php';

echo "<script src=\"jquery.min.js\"></script>";
echo "<script src=\"jquery.knob.js\"></script>";

//POST FORM START
foreach($_POST as $key=>$value)
{
 $file = $sensors_settings_path."/".$key."/alarm";
 write_file($file,$value);

 // update the relays
 $command = "/bin/bash $sensors_settings_path/alarm_checking.sh $key";
 exec ($command, $output_post);
 //$output = system($command, $retval);
}
//FORM END



echo "<p>";
//GET FORM START
$get_filter="28";
if (isset($_GET["filter"])) {
  $get_filter=$_GET["filter"];
}
// FORM END

//GLOBAL MODE
$global_mode = read_file($sensors_settings_path."/current_mode");
$global_mode = trim($global_mode, " \n.");
if ($global_mode == "") {
    $global_mode="Manual";
}
echo "Mode: $global_mode";


$global_disabled = "disabled=\"disabled\"";
if ($global_mode == "Manual") {
    $global_disabled = "";
}

 
echo "       <form method=\"get\">";
echo "         <select name=\"filter\">";
$selected = "";
if ($get_filter == "") {
  $selected = "selected=\"selected\"";
}

echo "           <option value=\"\" $selected>all</option>";
$selected = "";
if ($get_filter == "28") {
  $selected = "selected=\"selected\"";
}
echo "           <option value=\"28\" $selected>Temperature</option>";
$selected = "";
if ($get_filter == "-") {
  $selected = "selected=\"selected\"";
}

echo "           <option value=\"29\" $selected>Switch</option>";
$selected = "";
if ($get_filter == "w1") {
  $selected = "selected=\"selected\"";
}

echo "           <option value=\"w1\" $selected>1-wire master</option>";
echo "         </select>";
echo "         <input type=\"submit\" value=\"Filter\">";
echo "       </form>";

echo "<center>";
echo "<table>";
echo "  <thead>";
echo "  <tr>";

echo "     <th>$lang[1]</th>";
echo "     <th>current value</th>";
echo "     <th>required <br>temperature</th>";

if (!in_array($global_mode, $off_modes)){
  echo "     <th>Mode</th>";
}
?>
     <th>heating <br>on/off</th>
  </tr>
  </thead>

<?php
// Filter option
$filter = "28";

//print each sensor device
foreach($devices as $device)
{
  $device_name=substr($device, strrpos($device, "/")+1);
  if ( $get_filter != "" && $get_filter != substr($device_name,0,2) ) {
    continue;
  }
  $device_id=$device_name;
  $settings_path=$sensors_settings_path."/".$device_name;

  $output_temp=NULL;
  $command="rrdtool lastupdate $sensors_settings_path/temperature5004_$device_name.rrd |tail -1|cut -c 13-";
  exec ($command, $output_temp);
  $value=$output_temp[0];

  //ALIAS
  $alias = read_file($settings_path."/alias");
  $alias = trim($alias, " \n.");
  if ($alias != "") {
      $device_name=$alias;
  }

  //ALARM
  $output=NULL;
  $command = "/bin/bash $sensors_settings_path/get_alarm.sh $device_id";
  exec ($command, $output);
  $alarm=$output[0]*10;
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


  // ON/OFF
  $onoff = read_file($settings_path."/onoff");
  $onoff = trim($onoff, " \n.");
  if ($alarm!=50 && $value < ($alarm/10)) {
      $onoff="on";
  } else {
      if ($onoff == "") {
          $onoff="on/off";
      } else {
          $onoff="off";
      }
  }


  echo "  <tbody>";
  echo "  <tr>";
  echo "     <td><a href=\"alarm.php?id=$device_id\" target=\"_blank\">$device_name</a></td>";
  if ( $value != "" ) {
      echo "     <td id=\"temperature\">$value C</td>";
  } else {
      echo "     <td> </td>";
  }
  if ( $disabled == "" || substr($device_id, 0, 2) == "28" ){
      echo "  <td>";
      echo "       <img src=\"\" width=\"1\" height=\"15\">";
      echo "       <form method=\"post\">";
      echo "      <input type=\"test\" class=\"dial\" name=\"$device_id\" data-min=\"160\" data-max=\"300\" value=\"$alarm\" data-width=\"200\" data-fgColor=\"#888888\" data-cursor=true data-angleOffset=-125 data-angleArc=250 data-displayPrevious=true $global_disabled>";
      echo "      <input type=\"submit\" value=\"Set\" class=\"buttonclass\" $global_disabled>";
      echo "</form>";
      echo "  </td>";
  } else {
      echo "     <td><input type=\"number\" name=\"$device_id\" min=\"16\" max=\"30\" step=\"0.1\" value=\"$alarm\" $disabled></td>";
  }
  if (!in_array($global_mode, $off_modes)){
    if ($global_mode != "" && $mode == ""){
      $mode = $global_mode;
    }
    echo "     <td><a href=\"add_mode.php?mode=$mode\" class=\"buttonclass\">$mode</a></td>";
  }
  echo "     <td id=$onoff>";
  $checked="";
  if ($onoff=="on"){
    $checked="checked";
  }
  echo "<div class=\"roundedOne\">";
  echo "  <input type=\"checkbox\" value=\"None\" id=\"roundedOne\" name=\"check\" $checked disabled/>";
  echo "  <label for=\"roundedOne\"></label>";
  echo "</div>";

  echo "<div class=\"slideThree\">";
  echo "  <input type=\"checkbox\" value=\"None\" id=\"slideThree\" name=\"check\" $checked/ disabled>";
  echo "  <label for=\"slideThree\"></label>";
  echo "</div>";

  echo "  </tr>";
  echo "  </tbody>";
}

?>
</table>
</center>

<script>
$(function() {
    $(".dial").knob();
});
</script>
