<?php
require_once('includes.php');

//POST FORM START
if ( isset($_POST) && count($_POST) > 0 ){
  $sensor_id=$_POST["temp_sensor_id"];
  $file = $sensors_settings_path."/".$sensor_id."/switch";
  $mode = 'w';
  if ( isset($_POST["switch_name"]) && $_POST["switch_name"] == "" ) {
    write_file_extra($file,"",$mode);
  } else {
    foreach($_POST as $key=>$value)
    {
      if ( $key == "temp_sensor_id" ) {
        continue;
      }
      write_file_extra($file,$value,$mode);
      $mode = 'a';
    }
  }
}
//FORM END

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
$switch_ids = glob($sensors_path . "29-*");

//print each sensor device
foreach($switch_devices as $device)
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
  echo "           <option value=\"\"></option>";

  foreach( $switch_ids as $id ){
    $id=substr($id, strrpos($id, "/")+1);
    $selected = "";
    if ($id == $switch_id) {
      $selected = "selected=\"selected\"";
    }
    // ALIAS
    $alias = read_file($sensors_settings_path."/".$id."/alias");
    $alias = trim($alias, " \n.");
    if ( $alias != "") {
      echo "           <option value=\"$id\" $selected>$alias</option>";
    } else {
      echo "           <option value=\"$id\" $selected>$id</option>";
    }
  }
  echo "         </select>";

  if ( $port == 0 ) {
    $selected = "selected=\"selected\"";
  }
  echo "         <select name=\"switch_port\">";
  echo "           <option value=\"\" $selected></option>";
  for ($i=1; $i<9; $i++) {
   $value = $i;
   $selected = "";
   if ( $i == $port && strlen($port) > 0) {
     $selected = "selected=\"selected\"";
   }
   echo "           <option value=\"$i\" $selected>$value</option>";
  }
  echo "         </select>";
  echo "         <input type=\"submit\" value=\"Set\" class=\"buttonclass\">";
  echo "       </form>";
  echo "      </td>";
  echo "  </tr>";
}

?>
</table>
</center>

<a href="add_switch.php" class="buttonclass">Add new switch</a>

