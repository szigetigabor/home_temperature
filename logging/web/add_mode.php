<?php
include 'includes.php';

function alert($msg)
{
   echo "\n<script language=\"javascript\">";
   echo "\nalert(\"$msg\");";
   echo "\nhistory.back();";
   echo "\n</script>";
}


$time_stamp="";

function temp_value($var){
    global $time_stamp;
    $pos=strpos($var,"=");
    if (substr($var,0,$pos) == "temp_$time_stamp") {
        return true;
    }
    return false;
}

function mode_temp($values, $time){
    global $time_stamp;
    $time_stamp=$time;
    //$str= str_replace(":", "_", $time);
    $temperature = array_slice(array_filter($values, "temp_value"),0,1)[0];
    $pos = strpos($temperature, "=");
    return substr($temperature, $pos+1, strlen($temperature));
}

$time_schedule="";
//POST FORM START
if ( isset($_POST) && count($_POST) > 0 ){
  if ( array_key_exists("post_time_schedule", $_POST) 
       && $_POST["post_time_schedule"] != "") {
    $time_schedule = $_POST["post_time_schedule"];

   // $read_times = read_file($time_settings_path."/$time_schedule");
   // $split_times= explode("\n", $read_times);
  } else {
    $mode_name=$_POST["mode_name"];
    if ($mode_name == "" ) {
      alert("Empty mode name! Please set it.");
    }
    if ( !file_exists($mode_settings_path."/") ) {
      mkdir($mode_settings_path, 0777);
    }
    $file = $mode_settings_path."/$mode_name";
    $mode = 'w';
    foreach($_POST as $key=>$value)
    {
      if ( $key == "time_schedule" ) {
        $time_schedule=$value;
        write_file_extra($file,$value,$mode);
        $mode='a';
        continue;
      }
      if ( $key == "mode_name" ) {
        continue;
      }
      if ( substr($key,0,5) == "temp_") {
        write_file_extra($file,"$key=$value",$mode);
      } else {
        write_file_extra($file,$value,$mode);
      }
      $mode = 'a';
    }
    // reread modes
    $modes = glob($mode_settings_path . "/*");
  }
}
//FORM END


// GET FORM START
$get_mode="";
$setting=false;
if (isset($_GET["mode"])) {
   $get_mode=$_GET["mode"];
   if ($get_mode == "") {
     $file = $sensors_settings_path."/current_mode";
     $get_mode = read_file($file);
     $get_mode = trim($get_mode, " \n.");
   }
   $setting=true;
}
//FORM END

include 'menu.php';

if ($setting) {
  echo "<table id=\"mode\">";
  echo "<tr id=\"mode\">";
  echo "<a href=\"add_mode.php\" class=\"buttonclass\">New mode</a>";

  //print each mode settings
  $i=0;
  foreach($modes as $mode)
  {
    $mode_name = substr($mode, strrpos($mode, "/")+1);
    $class="buttonclass";
    if ( $get_mode == $mode_name ) {
      $class = "active$class";
    }
    echo "<td id=\"mode\">";
    echo "<a href=\"add_mode.php?mode=$mode_name\" class=\"$class\">$mode_name</a>";
    echo "</td>";
    $i++;
    if ($i%7 == 0){
      echo "</tr><tr id=\"mode\">";
    }
  }

  echo"</tr>";
  echo "</table>";
}


$mode_description = read_file($mode_settings_path."/$get_mode");
$mode_values = explode("\n", $mode_description);
if ($time_schedule == ""){
  $time_schedule= $mode_values[0];
}



echo "<center>";
echo " <form method=\"post\">";
echo "  Turn on per day: <select name=\"post_time_schedule\">";
echo "     <option value=\"0\"></option>";
//put each schedule time setting's name
for($i=1; $i<6; $i++)
{
  $selected = "";
  if ($i == $time_schedule) {
    $selected = "selected=\"selected\"";
  }

  echo "     <option value=\"$i\" $selected>$i</option>";

}
echo "   </select>";
echo "   <input type=\"submit\" value=\"Select\">";
echo " </form>";

echo "       <form method=\"post\">";
if ( $time_schedule != "" ) {
  echo "         <input type=\"hidden\" name=\"time_schedule\" value=\"$time_schedule\">";
  echo "         Mode name: <input type=\"text\" name=\"mode_name\" value=\"$get_mode\">";
  echo "         <br>";
  $min_temp = explode("=",$mode_values[1])[1];
  if ($min_temp == ""){
    $min_temp = "16";
  }
  echo "         OFF tempreature: <input type=\"number\" name=\"temp_OFF\" min=\"16\" max=\"30\" step=\"0.1\" value=\"$min_temp\">";
}

echo "<p>";
// print the table's contents
echo "         <table>";
echo "           <thead>";
echo "             <td>Turn ON <br>hh : mm</td>";
echo "             <td>Turn OFF <br>hh : mm</td>";
echo "             <td>Temperature </td>";
echo "           </thead>";
for ($i=0; $i < $time_schedule; $i++) {
    $index = 2+ ($i * 5);
    echo "           <tr><td>";
    $hour= "0";
    if (isset($mode_values[$index]) && $mode_values[$index] != "") {
      $hour = $mode_values[$index];
    }
    echo "               <input type=\"number\" name=\"11_$i\" min=\"0\" max=\"23\" step=\"1\" value=\"$hour\" >";
    echo ":";
    $minute="0";
    if (isset($mode_values[$index+1])) {
      $minute = $mode_values[$index+1];
    }
    echo "               <input type=\"number\" name=\"12_$i\" min=\"0\" max=\"59\" step=\"1\" value=\"$minute\" >";
    echo "</td><td>";
    $hour ="0";
    if (isset($mode_values[$index+2])) {
      $hour = $mode_values[$index+2];
    }
    echo "               <input type=\"number\" name=\"21_$i\" min=\"0\" max=\"23\" step=\"1\" value=\"$hour\" >";
    echo ":";
    $minute="0";
    if (isset($mode_values[$index+3])) {
      $minute = $mode_values[$index+3];
    }
    echo "               <input type=\"number\" name=\"22_$i\" min=\"0\" max=\"59\" step=\"1\" value=\"$minute\" >";

    echo "</td><td>";
    $temp= "";
    if (isset($mode_values[$index+4])) {
      $temp = explode("=",$mode_values[$index+4])[1];
    }
    echo "               <input type=\"number\" name=\"temp_$i\" min=\"16\" max=\"30\" step=\"0.1\" value=\"$temp\" >";
    echo "           </td></tr>";
}

echo "         </table>";
$button_value= "Create";
if ( $setting) {
    $button_value= "Setting";
}
echo "<br>";
echo "         <input type=\"submit\" value=\"$button_value\" class=\"buttonclass\">";
echo "       </form>";
echo "</center>";

?>

