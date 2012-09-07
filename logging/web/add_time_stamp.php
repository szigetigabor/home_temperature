<?php
include 'includes.php';

//POST FORM START
if ( isset($_POST) && count($_POST) > 0 ){
  $mode_name=$_POST["mode_name"];
  if ( !file_exists($time_settings_path."/") ) {
    mkdir($time_settings_path, 0777);
  }
  $file = $time_settings_path."/$mode_name";
  $mode = 'w';
  foreach($_POST as $key=>$value)
  {
   if ( $key == "mode_name" ) {
     continue;
   }
   write_file_extra($file,$key,$mode);
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

include 'menu.php';

if ($setting) {
  echo "<table id=\"mode\">";
  echo "<tr id=\"mode\">";
  echo "<a href=\"add_time_stamp.php\" class=\"buttonclass\">New switch time schedule</a>";

  //print each schedule time settings
  foreach($times as $mode)
  {
    $mode_name = substr($mode, strrpos($mode, "/")+1);
    $class="buttonclass";
    if ( $get_mode == $mode_name ) {
      $class = "active$class";
    }
    echo "<td id=\"mode\">";
    echo "<a href=\"add_time_stamp.php?mode=$mode_name\" class=\"$class\">$mode_name</a>";
    echo "</td>";
  }

  echo"</tr>";
  echo "</table>";
}

  $mode_description = read_file($time_settings_path."/$get_mode");
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
         array_push($title_lines, $line);
         $line="";
      }
      $line = "$line<td id=\"mode\"><div class=\"rotate\">$value</div></td>";
  }
  if ($line != ""){
      $line = "$line<td id=\"mode\"></td></tr>";
      array_push($title_lines, $line);
  }


  $value_lines= array();
  $line="";
  foreach ($time_values as $key=>$value){
      if ( in_array($value, $split_times) ) {
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
      array_push($value_lines, $line);
  }

  // print the table's contents
  for ($i=0; $i < sizeof($title_lines); $i++) {
      echo $title_lines[$i];
      echo "           </tr>";
      echo "           <tr id=\"mode\">";
      echo $value_lines[$i];
      echo "           <tr id=\"mode\"></tr>";

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

