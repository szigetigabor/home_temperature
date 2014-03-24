<?php
require_once('includes.php');
include 'menu.php';

echo "<center>";
echo "<table class=water>";
echo " <thead>";
echo " <tr>";

echo " <th>Water tank level</th>";
echo " </tr>";
echo " </thead>";
echo "</table>";

echo "<font size=1 color=white>.</font>";
echo "<br>";
echo "<table class=water>";
echo " <tbody>";

$switches = glob($sensors_path . "29-*");
$switch_id_name=substr($switches[0], strrpos($switches[0], "/")+1);

$read_command="$sensors_settings_path/switch_read.sh $switch_id_name";
exec ($read_command, $switch_output);

$top_of_water=false;
for($i=1; $i<9; $i++){
      $status= substr($switch_output[0],8-$i,1);
      if ($status == "0"){
        $status="on";
        $checked="checked";
      }
      if ($status == "1"){
        $status="off";
      }
  echo "   <tr>";
  $status_font=$status;
  if ( !$top_of_water && $status == "on" ){
    $top_of_water = true;
    $status_font = "top";
  }
  $value=(9-$i)/8*100;
  echo "     <td class=$status><font class=$status_font>$value %</font></td>";
  echo "   </tr>";
}
echo " </tbody>";

?>
