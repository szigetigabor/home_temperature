<?php
require_once('includes.php');
include 'menu.php';

echo "<center>";
echo "<table>";
echo "  <thead>";
echo "  <tr>";
echo "     <th>Channel</th>";
echo "     <th>Value</th>";
echo "  </tr>";
echo "  </thead>";

echo "  <tbody>";

$prefix_path = "../";

echo "  <tr>";
for($i=0;$i<8;$i++){
  $channel=$i+1;
  $output=NULL;
  $command = "/usr/bin/sudo $sensors_settings_path/mcp3008_read.py $i";
  exec ($command, $output);
  echo "      <tr><td>$channel</td><td><progress min=\"0\" max=\"1023\" value=\"$output[0]\"></progress> $output[0]</td></tr>";
}
echo "  </tr>";

echo "  </tbody>";
echo "</table>";
echo "</center>";

?>
