<?php
require_once('includes.php');
include 'menu.php';

// GET FORM START
$get_filter="";
if (isset($_GET["filter"])) {
  $get_filter=$_GET["filter"];
}
if ( $get_filter == "aggregated" ) {
  $now=time();
  
  $file_time=filemtime("../temp_graphs/aggr_temp_h.png");
  $minutes=5;
  if ( $file_time+($minutes *60) < $now){
    $command = "/bin/bash $sensors_settings_path/aggregate_graph.sh h";
    exec ($command, $output);
  }
  $file_time=filemtime("../temp_graphs/aggr_temp_d.png");
  $minutes=60;
  if ( $file_time+($minutes *60) < $now){
    $command = "/bin/bash $sensors_settings_path/aggregate_graph.sh d";
    exec ($command, $output);
  }
  $file_time=filemtime("../temp_graphs/aggr_temp_w.png");
  $minutes=24*$minutes;
  if ( $file_time+($minutes *60) < $now){
    $command = "/bin/bash $sensors_settings_path/aggregate_graph.sh w";
    exec ($command, $output);
  }
  $file_time=filemtime("../temp_graphs/aggr_temp_m.png");
  $minutes=7*$minutes;
  if ( $file_time+($minutes *60) < $now){
    $command = "/bin/bash $sensors_settings_path/aggregate_graph.sh m";
    exec ($command, $output);
  }
  $file_time=filemtime("../temp_graphs/aggr_temp_y.png");
  $minutes=30*$minutes;
  if ( $file_time+($minutes *60) < $now){
    $command = "/bin/bash $sensors_settings_path/aggregate_graph.sh y";
    exec ($command, $output);
  }
}

if ( $get_filter == "generate" ) {
  #TODO: use better file location
  $file_time=filemtime("../temp_graphs/28-000003d1da64/temp_h.png");
  $now=time();
  $minutes=10;
  if ( $file_time+($minutes *60) < $now){
    #Generate graphs when the last modification time was later the the minutes variable's value
    $command = "/bin/bash $sensors_settings_path/create_temp_graphs.sh";
    exec ($command, $output);
  }
  $get_filter = "";
}
// FORM END



$sensors_pathes = glob($sensors_settings_path . "/*");
$aliases = array();
$switches = glob($sensors_path . "28-*");
$switches = array_merge($switches, glob($sensors_owfs_path . "28*"));

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

  //ALIAS
  $device_name=$switch_id_name;
  $settings_path=$sensors_settings_path."/".$switch_id_name;
  $alias = read_file($settings_path."/alias");
  $alias = trim($alias, " \n.");
  if ($alias != "") {
      $device_name=$alias;
  }
  $aliases[$switch_id_name] = $device_name;
  echo "           <option value=\"$switch_id_name\" $selected>$device_name</option>";
}
echo "           <option value=\"generate\" $selected>generate all graphs</option>";
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

$prefix="";

foreach($switches as $switch_id)
{
  $switch_id_name=substr($switch_id, strrpos($switch_id, "/")+1);
  if ( $get_filter != "" && $get_filter != $switch_id_name ) {
    continue;
  }

  echo "  <tr>";
  echo "    <td rowspan=\"6\"><b>$aliases[$switch_id_name]</b> <br>($switch_id_name)</td>";
  echo "      <tr><td><img class=\"graph\" src=\"$prefix/temp_graphs/$switch_id_name/temp_h.png\" alt=\"hourly graph\" ></td></tr>";
  echo "      <tr><td><img class=\"graph\" src=\"$prefix/temp_graphs/$switch_id_name/temp_d.png\" alt=\"daily graph\" /></td></tr>";
  echo "      <tr><td><img class=\"graph\" src=\"$prefix/temp_graphs/$switch_id_name/temp_w.png\" alt=\"weekly graph\" /></td></tr>";
  echo "      <tr><td><img class=\"graph\" src=\"$prefix/temp_graphs/$switch_id_name/temp_m.png\" alt=\"monthly graph\" /></td></tr>";
  echo "      <tr><td><img class=\"graph\" src=\"$prefix/temp_graphs/$switch_id_name/temp_y.png\" alt=\"yearly graph\" /></td></tr>";
  echo "  </tr>";
}

if ( $get_filter == "aggregated" ) {
  echo "  <tr>";
  echo "      <tr><td><img class=\"graph\" src=\"$prefix/temp_graphs/aggr_temp_h.png\" alt=\"hourly graph\" ></td></tr>";
  echo "      <tr><td><img class=\"graph\" src=\"$prefix/temp_graphs/aggr_temp_d.png\" alt=\"daily graph\" /></td></tr>";
  echo "      <tr><td><img class=\"graph\" src=\"$prefix/temp_graphs/aggr_temp_w.png\" alt=\"weekly graph\" /></td></tr>";
  echo "      <tr><td><img class=\"graph\" src=\"$prefix/temp_graphs/aggr_temp_m.png\" alt=\"monthly graph\" /></td></tr>";
  echo "      <tr><td><img class=\"graph\" src=\"$prefix/temp_graphs/aggr_temp_y.png\" alt=\"yearly graph\" /></td></tr>";
  echo "  </tr>";

}

echo "  </tbody>";

echo "</table>";
echo "</center>";

?>
