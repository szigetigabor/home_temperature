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
$levels=8;
for($i=$levels; $i>0; $i--){
      $status= substr($switch_output[0], $levels-$i,1);
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
    $value=$i/$levels*100;
  }
  if ( !$top_of_water && $status == "off" && $i == 1 ){
    $status_font = "empty";
    $value = 0;
  }
  echo "     <td class=$status><font class=$status_font>$value %</font></td>";
  echo "   </tr>";
}
echo " </tbody>";

?>
