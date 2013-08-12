<?php
require_once('includes.php');
include 'menu.php';

// GET FORM START
$get_filter="lux";
if (isset($_GET["filter"])) {
  $get_filter=$_GET["filter"];
}
if ( $get_filter == "lux" ) {
 $command = "/bin/bash $sensors_settings_path/create_lux_graph.sh h";
 exec ($command, $output);
 $command = "/bin/bash $sensors_settings_path/create_lux_graph.sh d";
 exec ($command, $output);
 $command = "/bin/bash $sensors_settings_path/create_lux_graph.sh w";
 exec ($command, $output);
 $command = "/bin/bash $sensors_settings_path/create_lux_graph.sh m";
 exec ($command, $output);
 $command = "/bin/bash $sensors_settings_path/create_lux_graph.sh y";
 exec ($command, $output);
}
// FORM END



echo "<center>";
echo "<table>";
echo "  <thead>";
echo "  <tr>";
if ( $get_filter != "lux" ) {
  echo "     <th>$lang[1]</th>";
}
echo "     <th>Graphs</th>";
echo "  </tr>";
echo "  </thead>";

echo "  <tbody>";

$prefix = "";

if ( $get_filter == "lux" ) {
  echo "  <tr>";
  echo "      <tr><td><img class=\"graph\" src=\"$prefix/temp_graphs/lux_h.png\" alt=\"hourly graph\" ></td></tr>";
  echo "      <tr><td><img class=\"graph\" src=\"$prefix/temp_graphs/lux_d.png\" alt=\"daily graph\" /></td></tr>";
  echo "      <tr><td><img class=\"graph\" src=\"$prefix/temp_graphs/lux_w.png\" alt=\"weekly graph\" /></td></tr>";
  echo "      <tr><td><img class=\"graph\" src=\"$prefix/temp_graphs/lux_m.png\" alt=\"monthly graph\" /></td></tr>";
  echo "      <tr><td><img class=\"graph\" src=\"$prefix/temp_graphs/lux_y.png\" alt=\"yearly graph\" /></td></tr>";
  echo "  </tr>";

}

echo "  </tbody>";

echo "</table>";
echo "</center>";

?>
