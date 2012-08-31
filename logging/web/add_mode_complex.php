<?php
include 'includes.php';

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
  if ($_POST["post_time_schedule"] != "") {
    $time_schedule = $_POST["post_time_schedule"];

   // $read_times = read_file($time_settings_path."/$time_schedule");
   // $split_times= explode("\n", $read_times);
  } else {
    $mode_name=$_POST["mode_name"];
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
        write_file_extra($file,$key,$mode);
      }
      $mode = 'a';
    }
  }
}
//FORM END


// GET FORM START
$get_mode="";
$setting=false;
if (isset($_GET["mode"])) {
   $get_mode=$_GET["mode"];
   $setting=true;
}


//FORM END

include 'menu.php';

if ($setting) {
  echo "<table id=\"mode\">";
  echo "<tr id=\"mode\">";
  echo "<a href=\"add_mode.php\" class=\"buttonclass\">New mode</a>";

  //print each mode settings
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
  }

  echo"</tr>";
  echo "</table>";
}


$mode_description = read_file($mode_settings_path."/$get_mode");
$mode_values = explode("\n", $mode_description);
if ($time_schedule == ""){
  $time_schedule= $mode_values[0];
}
if ($time_schedule != ""){
  $read_times = read_file($time_settings_path."/$time_schedule");
  if ($read_times != ""){
    $split_times= explode("\n", $read_times);
  }
}




echo " <form method=\"post\">";
echo "  Time schedule mode: <select name=\"post_time_schedule\">";
echo "     <option value=\"default\" $selected></option>";
//put each schedule time setting's name
foreach($times as $time)
{
  $time_name = substr($time, strrpos($time, "/")+1);

  $selected = "";
  if ($time_name == $time_schedule) {
    $selected = "selected=\"selected\"";
  }

  echo "     <option value=\"$time_name\" $selected>$time_name</option>";

}
echo "   </select>";
echo "   <input type=\"submit\" value=\"Select\">";
echo " </form>";


/*  $mode_description = read_file($mode_settings_path."/$get_mode");
  $mode_values = explode("\n", $mode_description);
if ($time_schedule == ""){
  $time_schedule= $mode_values[0];
}
if ($time_schedule != ""){
  $read_times = read_file($time_settings_path."/$time_schedule");
  if ($read_times != ""){
    $split_times= explode("\n", $read_times);
  }
}*/

  echo "       <form method=\"post\">";
  echo "         <input type=\"hidden\" name=\"time_schedule\" value=\"$time_schedule\">";
  echo "         Mode name: <input type=\"text\" name=\"mode_name\" value=\"$get_mode\">";
  echo "         <table id=\"mode\">";
  echo "           <tr id=\"mode\">";
  $title_lines= array();
  $line="";
  foreach ($time_values as $key=>$value) {
      if ( in_array($value, $split_times) ) {
         $line = "$line<td id=\"mode\"></td></tr>";
         $line = "$line<tr id=\"mode\">";
         array_push($title_lines, $line);
         $line="";
      }
      $line = "$line<td id=\"mode\"><div class=\"rotate\">$value</div></td>";
  }
  if ($line != ""){
      $line = "$line<td id=\"mode\"></td></tr>";
      $line = "$line<tr id=\"mode\">";
      array_push($title_lines, $line);
  }


  $value_lines= array();
  $line="";
  foreach ($time_values as $key=>$value){
      if ( in_array($value, $split_times) ) {
         $temperature = mode_temp($mode_values, $value);

         $line = "$line<td id=\"mode\"> <input type=\"number\" name=\"temp_$value\" min=\"16\" max=\"30\" step=\"0.1\" value=\"$temperature\" $global_disabled></td></tr>";
         $line = "$line<tr id=\"mode\">";
         array_push($value_lines, $line);
         $line="";

      }
      $line = "$line<td id=\"mode\">";
      $checked="";
      if (in_array($value, $mode_values)) {
         $checked = "checked";
      }
      $line = "$line<input type=\"checkbox\" name=\"$value\" $checked>";
      $line = "$line</td>";
  }
  if ($line != ""){
      $temperature = mode_temp($mode_values, "24");
      $line = "$line<td id=\"mode\"><input type=\"number\" name=\"temp_24\" min=\"16\" max=\"30\" step=\"0.1\" value=\"$temperature\" $global_disabled> </td></tr>";
      array_push($value_lines, $line);
  }

  // print the table's contents
  for ($i=0; $i < sizeof($title_lines); $i++) {
      echo $title_lines[$i];
      echo "           </tr>";
      echo "           <tr id=\"mode\">";
      echo $value_lines[$i];

  }

  echo "           </tr>";
  echo "         </table>";
  $button_value= "Create";
  if ( $setting) {
      $button_value= "Setting";
  }
  echo "         <input type=\"submit\" value=\"$button_value\" class=\"buttonclass\">";
  echo "       </form>";

?>

