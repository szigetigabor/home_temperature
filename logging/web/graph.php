<?php

function create_graph($type) {

  require_once('includes.php');
  include 'menu.php';

  $command = "/bin/bash $sensors_settings_path/create_".$type."_graph.sh h";
  exec ($command, $output);
  $command = "/bin/bash $sensors_settings_path/create_".$type."_graph.sh d";
  exec ($command, $output);
  $command = "/bin/bash $sensors_settings_path/create_".$type."_graph.sh w";
  exec ($command, $output);
  $command = "/bin/bash $sensors_settings_path/create_".$type."_graph.sh m";
  exec ($command, $output);
  $command = "/bin/bash $sensors_settings_path/create_".$type."_graph.sh y";
  exec ($command, $output);



  echo "<center>";
  echo "<table>";
  echo "  <thead>";
  echo "  <tr>";
  echo "     <th>Graphs</th>";
  echo "  </tr>";
  echo "  </thead>";

  echo "  <tbody>";

  $prefix_path = "../";

  echo "  <tr>";
  echo "      <tr><td><img class=\"graph\" src=\"$prefix_path/temp_graphs/".$type."_h.png\" alt=\"hourly graph\" ></td></tr>";
  echo "      <tr><td><img class=\"graph\" src=\"$prefix_path/temp_graphs/".$type."_d.png\" alt=\"daily graph\" /></td></tr>";
  echo "      <tr><td><img class=\"graph\" src=\"$prefix_path/temp_graphs/".$type."_w.png\" alt=\"weekly graph\" /></td></tr>";
  echo "      <tr><td><img class=\"graph\" src=\"$prefix_path/temp_graphs/".$type."_m.png\" alt=\"monthly graph\" /></td></tr>";
  echo "      <tr><td><img class=\"graph\" src=\"$prefix_path/temp_graphs/".$type."_y.png\" alt=\"yearly graph\" /></td></tr>";
  echo "  </tr>";

  echo "  </tbody>";

  echo "</table>";
  echo "</center>";

}
?>
