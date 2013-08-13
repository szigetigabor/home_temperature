<?php
require_once('includes.php');
require("color.inc.php");

echo "<script src=\"jquery.min.js\"></script>";
echo "<script src=\"jquery.knob.js\"></script>";

//POST FORM START
foreach($_POST as $key=>$value)
{
 $file = $sensors_settings_path."/".$key."/alarm";
 write_file($file,$value);

 // update the relays
 $command = "/bin/bash $sensors_settings_path/alarm_checking.sh $key";
 exec ($command, $output_post);
}
//FORM END



//GET FORM START
$get_id="";
if (isset($_GET["id"])) {
  $get_id=$_GET["id"];
}
// FORM END

 
echo "<center>";
echo "<table>";
echo "  <thead>";
echo "    <tr>";

echo "     <th>$lang[3] <br>$lang[4]</th>";

?>
    </tr>
  </thead>

<?php

//ALARM
$output=NULL;
$command = "/bin/bash $sensors_settings_path/get_alarm.sh $get_id";
exec ($command, $output);
$alarm=$output[0]*10;

$disabled="";
if ($alarm == "") {
    $disabled = "disabled=\"disabled\"";
    $alarm=0;
}

echo "  <tbody>";
echo "  <tr>";
if ( $disabled == "" ){
    echo "  <td>";
    echo "       <img src=\"\" width=\"1\" height=\"15\">";
    echo "       <form method=\"post\">";
    echo "      <input type=\"test\" class=\"dial\" name=\"$get_id\" data-min=\"160\" data-max=\"300\" value=\"$alarm\" data-width=\"200\" data-fgColor=\"#888888\" data-cursor=true data-angleOffset=-125 data-angleArc=250 data-displayPrevious=true $global_disabled>";
    echo "      <br><input type=\"submit\" value=$lang[9] class=\"buttonclass\" $global_disabled>";
    echo "</form>";
    echo "  </td>";
} else {
    echo "     <td><input type=\"number\" name=\"$get_id\" min=\"16\" max=\"30\" step=\"0.1\" value=\"$alarm\" $disabled></td>";
}

?>
    </tr>
  </tbody>
</table>
</center>

<script>
$(function() {
    $(".dial").knob();
});
</script>
