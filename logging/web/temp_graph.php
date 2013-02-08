<?php
require_once('includes.php');
include 'menu.php';

// GET FORM START
$get_filter="";
if (isset($_GET["filter"])) {
  $get_filter=$_GET["filter"];
}
if ( $get_filter == "aggregated" ) {
 $command = "/bin/bash $sensors_settings_path/aggregate_graph.sh h";
 exec ($command, $output);
 $command = "/bin/bash $sensors_settings_path/aggregate_graph.sh d";
 exec ($command, $output);
 $command = "/bin/bash $sensors_settings_path/aggregate_graph.sh w";
 exec ($command, $output);
 $command = "/bin/bash $sensors_settings_path/aggregate_graph.sh m";
 exec ($command, $output);
 $command = "/bin/bash $sensors_settings_path/aggregate_graph.sh y";
 exec ($command, $output);
}
// FORM END



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

$switches = glob($sensors_path . "28-*");

echo "       <form method=\"get\">";
echo "         <select name=\"filter\">";
$selected = "";
if ($get_filter == "") {
  $selected = "selected=\"selected\"";
}

echo "           <option value=\"\" $selected>all</option>";
$selected = "";
if ($get_filter == "aggregated") {
  $selected = "selected=\"selected\"";
}
echo "           <option value=\"aggregated\" $selected>aggregated</option>";
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
if ( $get_filter != "aggregated" ) {
  echo "     <th>$lang[1]</th>";
}
echo "     <th>Graphs</th>";
echo "  </tr>";
echo "  </thead>";

echo "  <tbody>";

foreach($switches as $switch_id)
{
  $switch_id_name=substr($switch_id, strrpos($switch_id, "/")+1);
  if ( $get_filter != "" && $get_filter != $switch_id_name ) {
    continue;
  }

  echo "  <tr>";
  echo "    <td rowspan=\"6\">$switch_id_name</td>";
  echo "      <tr><td><img class=\"graph\" src=\"temp_graphs/$switch_id_name/temp_h.png\" alt=\"hourly graph\" ></td></tr>";
  echo "      <tr><td><img class=\"graph\" src=\"temp_graphs/$switch_id_name/temp_d.png\" alt=\"daily graph\" /></td></tr>";
  echo "      <tr><td><img class=\"graph\" src=\"temp_graphs/$switch_id_name/temp_w.png\" alt=\"weekly graph\" /></td></tr>";
  echo "      <tr><td><img class=\"graph\" src=\"temp_graphs/$switch_id_name/temp_m.png\" alt=\"monthly graph\" /></td></tr>";
  echo "      <tr><td><img class=\"graph\" src=\"temp_graphs/$switch_id_name/temp_y.png\" alt=\"yearly graph\" /></td></tr>";
  echo "  </tr>";
}

if ( $get_filter == "aggregated" ) {
  echo "  <tr>";
  echo "      <tr><td><img class=\"graph\" src=\"temp_graphs/aggr_temp_h.png\" alt=\"hourly graph\" ></td></tr>";
  echo "      <tr><td><img class=\"graph\" src=\"temp_graphs/aggr_temp_d.png\" alt=\"daily graph\" /></td></tr>";
  echo "      <tr><td><img class=\"graph\" src=\"temp_graphs/aggr_temp_w.png\" alt=\"weekly graph\" /></td></tr>";
  echo "      <tr><td><img class=\"graph\" src=\"temp_graphs/aggr_temp_m.png\" alt=\"monthly graph\" /></td></tr>";
  echo "      <tr><td><img class=\"graph\" src=\"temp_graphs/aggr_temp_y.png\" alt=\"yearly graph\" /></td></tr>";
  echo "  </tr>";

}

echo "  </tbody>";

echo "</table>";
echo "</center>";

?>
