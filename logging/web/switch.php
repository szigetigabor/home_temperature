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

function write_file($path, $data, $mode){
    $fn = fopen($path, $mode);
    fwrite($fn, "$data\n");
    fclose($fn);
}

$ip=$_SERVER['SERVER_ADDR'];
//echo "Server IP Address= $ip";
 
$ip=$_SERVER['REMOTE_ADDR'];
//echo "<br>Your IP Address= $ip"; 

//POST FORM START
if ( isset($_POST) ){
  $sensor_id=$_POST["temp_sensor_id"];
  $file = $sensors_settings_path."/".$sensor_id."/switch";
  $mode = 'w';
  if ( isset($_POST["switch_name"]) && $_POST["switch_name"] == "" ) {
    write_file($file,"",$mode);
  } else {
    foreach($_POST as $key=>$value)
    {
      if ( $key == "temp_sensor_id" ) {
        continue;
      }
      write_file($file,$value,$mode);
      $mode = 'a';
    }
  }
}
//FORM END


//get all sensors files.
$devices = glob($sensors_path . "*");

echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\" />";

include 'menu.php';
?>


<center>
<table>
  <thead>
  <tr>
     <th>device</th>
     <th>switch name</th>
  </tr>
  </thead>

<?php
//print each sensor device
foreach($devices as $device)
{
  $device_name=substr($device, strrpos($device, "/")+1);
  $settings_path=$sensors_settings_path."/".$device_name;
  $device_alias = read_file($settings_path."/alias");

  //SWITCH
  $switch = read_file($settings_path."/switch");
  list($switch_id, $port) = split("\n", $switch);

  echo "  <tr>";
  if ($device_alias != "") {
     $device_alias = "<br>$device_alias";
  }
  echo "     <td>$device_name$device_alias</td>";
  echo "     <td>";
  echo "       <img src=\"\" width=\"1\" height=\"15\">";
  echo "       <form method=\"post\">";
  echo "         <input type=\"hidden\" name=\"temp_sensor_id\" value=\"$device_name\" >";
  echo "         <select name=\"switch_name\">";
  echo "           <option value=\"\" $selected></option>";
  //TODO: read switch ids
  $switch_ids = array("swid1", "swid2");
  $value = 1;
  foreach( $switch_ids as &$id ){
   $selected = "";
   if ($id == $switch_id) {
     $selected = "selected=\"selected\"";
   }
   echo "           <option value=\"$id\" $selected>sw$value</option>";
   $value += 1;
  }
  echo "         </select>";

  if ( $port == 0 ) {
    $selected = "selected=\"selected\"";
  }
  echo "         <select name=\"switch_port\">";
  echo "           <option value=\"\" $selected></option>";
  for ($i=0; $i<8; $i++) {
   $value = $i+1;
   $selected = "";
   if ( $i == $port && strlen($port) > 0) {
     $selected = "selected=\"selected\"";
   }
   echo "           <option value=\"$i\" $selected>$value</option>";
  }
  echo "         </select>";
  echo "         <input type=\"submit\" value=\"Set\">";
  echo "       </form>";
  echo "      </td>";
  echo "  </tr>";
}

?>
</table>
</center>

<a href="add_switch.php" class="buttonclass">Add new switch</a>

