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
   if ( substr($key,0,5) == "temp_") {
     write_file_extra($file,"$key=$value",$mode);
   } else {
     write_file_extra($file,$key,$mode);
   }
   $mode = 'a';
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

$time_values = array("0:30","1","1:30","2","2:30","3","3:30","4","4:30","5","5:30","6",
"6:30","7","7:30","8","8:30","9","9:30","10","10:30","11","11:30","12",
"12:30","13","13:30","14","14:30","15","15:30","16","16:30","17","17:30","18",
"18:30","19","19:30","20","20:30","21","21:30","22","22:30","23","23:30","24");

$split_times = array("6:30", "12:30", "18:30");

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
  echo "       <form method=\"post\">";
  echo "         name: <input type=\"text\" name=\"mode_name\" value=\"$get_mode\">";
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

