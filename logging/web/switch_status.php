<?php
include 'includes.php';
include 'menu.php';

// GET FORM START
$get_filter="";
if (isset($_GET["filter"])) {
  $get_filter=$_GET["filter"];
}
// FORM END

//POST FORM START
if (count($_POST) >= 3){
  $device=$_POST["device"];
  $port=intval($_POST["port"]);
  $new_state=$_POST["old_state"];

  if ($new_state == "on" || empty($new_state)) {
    $new_state=0;
  }elseif ($new_state == "off") {
    $new_state=1;
  }

 #$file = $sensors_settings_path."/".$key."/$file_name";
 #write_file($file,$value);

 # update the relays
 $command = "/bin/bash $sensors_settings_path/switch_set.sh $device $port $new_state";
 exec ($command, $output);
}
//FORM END



$used_switches = array();

$sensors_pathes = glob($sensors_settings_path . "/*");

foreach ($sensors_pathes as $sensor_dir) {
  if ( is_dir($sensor_dir) ) {
    $sensor_name = substr($sensor_dir, strrpos($sensor_dir, "/")+1);
    
    //SWITCH
    $switch = read_file($sensor_dir."/switch");
    if ($switch == "") {
        $switch="";
    }
    $switch = explode("\n", $switch);
    if (sizeof($switch) < 2){
      continue;
    }
    if ( !isset($used_switches[$switch[0]]) ){
      $used_switches[$switch[0]] = array();
    }
    if ($switch[1] != "") {
      $used_switches[$switch[0]][$switch[1]] = $sensor_name;
    }
  }
}

$switches = glob($sensors_path . "29-*");

echo "       <form method=\"get\">";
echo "         <select name=\"filter\">";
$selected = "";
if ($get_filter == "") {
  $selected = "selected=\"selected\"";
}

echo "           <option value=\"\" $selected>all</option>";
foreach($switches as $switch_id)
{
  $switch_id_name=substr($switch_id, strrpos($switch_id, "/")+1);

  $selected = "";
  if ($get_filter == $switch_id_name) {
    $selected = "selected=\"selected\"";
  }
  echo "           <option value=\"$switch_id_name\" $selected>$switch_id_name</option>";
}

echo "         </select>";
echo "         <input type=\"submit\" value=\"Filter\">";
echo "       </form>";




echo "<center>";
echo "<table>";
echo "  <thead>";
echo "  <tr>";

echo "     <th>$lang[1]</th>";
echo "     <th>Port</th>";
echo "     <th>Who use it?</th>";
echo "     <th>current status</th>";
echo "  </tr>";
echo "  </thead>";

echo "  <tbody>";
//echo "  <tr>";
foreach($switches as $switch_id)
{
  $switch_id_name=substr($switch_id, strrpos($switch_id, "/")+1);

  $read_command="$sensors_settings_path/switch_read.sh $switch_id_name";
  exec ($read_command, $switch_output);

  if ( $get_filter != "" && $get_filter != $switch_id_name ) {
    continue;
  }
  for($i=1; $i<9; $i++){
    echo "  <tr>";
    if ( $i == 1) {
      echo "  <td rowspan=\"8\">$switch_id_name</td>";
    }
    echo "  <td>$i</td>";
    $who_use_it=NULL;
    if ( array_key_exists("$i", $used_switches[$switch_id_name]) ) {
      $who_use_it = $used_switches[$switch_id_name][$i];
    }

    // ALIAS
    $alias = read_file($sensors_settings_path."/".$who_use_it."/alias");
    $alias = trim($alias, " \n.");
    if ( $alias != "") {
      echo "  <td>$alias</td>";
    } else {
      echo "  <td>$who_use_it</td>";
    }

    $checked="";
    $disabled="";
    if ($who_use_it != ""){
      $disabled="disabled";
      // ON/OFF
      $onoff = read_file($sensors_settings_path."/".$who_use_it."/onoff");
      $onoff = trim($onoff, " \n.");

      if ($onoff == "on"){      
        $checked="checked";
      }
    } else {
      $onoff= substr($switch_output[0],7-$i,1);
      if ($onoff == "0"){
        $onoff="on";
        $checked="checked";
      }
      if ($onoff == "1"){
        $onoff="off";
      }
    }
    echo "  <td>";
    echo "    <form method=\"post\">";
    echo "      <input type=\"hidden\" name=\"device\" value=\"$switch_id_name\">";
    echo "      <input type=\"hidden\" name=\"port\" value=\"$i\">";
    echo "      <input type=\"hidden\" name=\"old_state\" value=\"$onoff\">";

    echo "    <div class=\"slideThree\">";
    $id = $switch_id."_".$i;
    echo "      <input type=\"checkbox\" value=\"None\" id=\"$id\" name=\"check\" $checked $disabled>";
    echo "      <input type=\"submit\" value=\" \">";
    #echo "      <label for=\"$id\"></label>";
    echo "    </div>";
    echo "    </form>";
    echo "  </td>";
  }
  echo "  </tr>";
}

//echo "  </tr>";
echo "  </tbody>";

echo "</table>";
echo "</center>";

?>
