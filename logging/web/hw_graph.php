<?php
require_once('includes.php');
include 'menu.php';

$command = "/bin/bash $sensors_settings_path/hwmonitor/create_graph.sh";
exec ($command, $output);


echo "<center>";
echo "<table>";
echo "  <thead>";
echo "  <tr>";
echo "     <th>Hardware Graphs</th>";
echo "  </tr>";
echo "  </thead>";

echo "  <tbody>";

echo "  <tr>";
echo "      <tr><td><img class=\"graph\" src=\"temp_graphs/health_of_system.png\" alt=\"hardware graph\" ></td></tr>";
echo "  </tr>";


echo "  </tbody>";

echo "</table>";
echo "</center>";

?>
