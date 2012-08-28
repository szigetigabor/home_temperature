<?php
//path to stored 1-wire sensors settings
$mode_settings_path = "/home/pi/logging";

function read_file($path){
    if (!file_exists($path)) {
      return "";
    }
    $fn = fopen($path, "r");
    $retval = fread($fn,filesize($path));
    fclose($fn);
    return $retval;
}

function write_file($path, $data, $mode){
    $fn = fopen($path, $mode);
    fwrite($fn, "$data\n");
    fclose($fn);
}

$ip=$_SERVER['SERVER_ADDR'];
//echo "Server IP Address= $ip";
 
$ip=$_SERVER['REMOTE_ADDR'];
//echo "<br>Your IP Address= $ip"; 

//POST FORM START
if ( isset($_POST) && count($_POST) > 0 ){
  $value=$_POST["mode"];
  $file = $mode_settings_path."/current_mode";
  write_file($file,$value,'w');
}
//FORM END


//get all sensors files.
$modes = glob($mode_settings_path . "/modes/*");

echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\" />";

include 'menu.php';

$current_mode = read_file($mode_settings_path."/current_mode");
$current_mode = trim($current_mode, " \n.");

echo "<table id=\"mode\">";
echo "<tr id=\"mode\">";

//print each mode settings
foreach($modes as $mode)
{
  $mode_name = substr($mode, strrpos($mode, "/")+1);
  $mode_description = read_file($mode);
  echo "<td id=\"mode\">";
  echo "       <form method=\"post\">";
  echo "         <input type=\"hidden\" name=\"mode\" value=\"$mode_name\" >";
  $disable = "";
  $class="buttonclass";
  if ( $current_mode == $mode_name ) {
    $disable = "disabled=true";
    $class = "active$class";
  }
  echo "         <input type=\"submit\" value=\"$mode_name\" class=\"$class\" $disable >";
  echo "       </form>";
  echo "</td>";
}

echo"</tr>";
echo "</table>";
?>

<p><a href="add_mode.php" class="buttonclass">Add new mode</a>
<a href="add_mode.php" class="buttonclass">Set modes</a>

