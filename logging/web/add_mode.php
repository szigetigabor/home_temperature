<?php
$mode_settings_path = "/home/pi/logging/modes";

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
  $mode_name=$_POST["mode_name"];
  if ( !file_exists($mode_settings_path."/") ) {
    mkdir($mode_settings_path, 0777);
  }
  $file = $mode_settings_path."/$mode_name";
  $mode = 'w';
  foreach($_POST as $key=>$value)
  {
   if ( $key == "mode_name" ) {
     continue;
   }
   write_file($file,$key,$mode);
   $mode = 'a';
  }
}
//FORM END


// GET FORM START
$get_mode="";
if (isset($_GET["mode"])) {
   $get_mode=$_GET["mode"];
}

//FORM END

$time_values = array("0:30","1","1:30","2","2:30","3","3:30","4","4:30","5","5:30","6",
"6:30","7","7:30","8","8:30","9","9:30","10","10:30","11","11:30","12",
"12:30","13","13:30","14","14:30","15","15:30","16","16:30","17","17:30","18",
"18:30","19","19:30","20","20:30","21","21:30","22","22:30","23","23:30","24");

//get all mode files.
$modes = glob($mode_settings_path . "*");

echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\" />";

include 'menu.php';

  $mode_description = read_file($mode_settings_path."/$mode");
  echo "       <form method=\"post\">";
  echo "         name: <input type=\"text\" name=\"mode_name\" value=\"$get_mode\">";
  echo "         <table id=\"mode\">";
  echo "           <tr id=\"mode\">";
  foreach ($time_values as $key=>$value) {
      echo "             <td id=\"mode\">$value</td>";
  }
  echo "           </tr>";
  echo "           <tr id=\"mode\">";
  foreach ($time_values as $key=>$value){
      echo "             <td id=\"mode\">";
      echo "               <input type=\"checkbox\" name=\"$value\">";
      echo "             </td>";
  }
  echo "           </tr>";
  echo "         </table>";
  echo "         <input type=\"submit\" value=\"Create\" class=\"buttonclass\">";
  echo "       </form>";

?>

