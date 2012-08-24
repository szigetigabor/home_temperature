<?php
//path to 1-wire sensors to scan
$sensors_path = "/sys/bus/w1/devices/";

//path to stored 1-wire sensors settings
$sensors_settings_path = "/home/pi/logging";

function read_file($path){
    $fn = fopen($path, "r");
    $retval = fread($fn,filesize($path));
    fclose($fn);
    return $retval;
}

function write_file($path, $data){
    $fn = fopen($path, 'w');
    fwrite($fn, "$data\n");
    fclose($fn);
}

$ip=$_SERVER['SERVER_ADDR'];
//echo "Server IP Address= $ip";
 
$ip=$_SERVER['REMOTE_ADDR'];
//echo "<br>Your IP Address= $ip"; 

//POST FORM START
foreach($_POST as $key=>$value)
{
 $file = $sensors_settings_path."/".$key."/switch";
 write_file($file,$value);
}
//FORM END


//get all sensors files.
$devices = glob($sensors_path . "*");

echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\" />";

include 'menu.php';
?>


<center>
<table>
  <tr>
     <td><b>device</b></td>
     <td><b>switch name</b></td>
  </tr>

<?php
//print each sensor device
foreach($devices as $device)
{
  $device_name=substr($device, strrpos($device, "/")+1);
  $settings_path=$sensors_settings_path."/".$device_name;

  //SWITCH
  $switch = read_file($settings_path."/switch");
  $switch = trim($switch, " \n.");

  echo "  <tr>";
  echo "     <td>$device_name</td>";
  echo "     <td>";
  echo "       <form method=\"post\">";
  echo "         <input type=\"text\" name=\"$device_name\" value=\"$switch\" >";
  echo "         <input type=\"submit\" value=\"Set\">";
  echo "       </form>";
  echo "      </td>";
  echo "  </tr>";
}

?>
</table>
</center>
