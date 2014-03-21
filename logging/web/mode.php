<?php
require_once('includes.php');

//POST FORM START
if ( isset($_POST) && count($_POST) > 0 ){
  if ( isset($_POST["mode"]) ) {
    $value=$_POST["mode"];
    $file = $sensors_settings_path."/current_mode";
    write_file_extra($file,$value,'w');
  } else {
    if ( isset($_POST["heating_mode"]) && $_POST["heating_mode"] == "Cooling" ) {
      write_file($heating_mode_path,"");
    } else {
      remove_file($heating_mode_path); 
    }
  }
}
//FORM END


include 'menu.php';

$current_mode = read_file($sensors_settings_path."/current_mode");
$current_mode = trim($current_mode, " \n.");

echo "<table id=\"mode\">";
echo "<tr id=\"mode\">";

//$heating_or_cooling
//print heating or cooling options
$heating_mode="Heating";
if (file_exists($heating_mode_path)) {
  $heating_mode="Cooling";
}
foreach($heating_or_cooling as $mode)
{
  echo "<td id=\"mode\">";
  echo "       <form method=\"post\">";
  echo "         <input type=\"hidden\" name=\"heating_mode\" value=\"$mode\" >";
  $disable = ""; 
  $class="buttonclass";
  if ( $heating_mode == $mode ) {
    $disable = "disabled=true"; 
    $class = "active$class";
  } 
  echo "         <input type=\"image\" src=\"images/$mode.png\" alt=\"$mode\" class=\"$class\" $disable >";
  echo "       </form>";
  echo "</td>";
} 
echo "</tr><tr id=\"mode\">";


global $off_modes_select;
//print main modes
foreach($off_modes_select as $mode)
{
  echo "<td id=\"mode\">";
  echo "       <form method=\"post\">";
  echo "         <input type=\"hidden\" name=\"mode\" value=\"$mode\" >";
  $disable = "";
  $class="buttonclass";
  if ( $current_mode == $mode ) {
    $disable = "disabled=true";
    $class = "active$class";
  }
  echo "         <input type=\"image\" src=\"images/$mode.png\" alt=\"$mode\" class=\"$class\" $disable >";
  echo "       </form>";
  echo "</td>";
}
echo "</tr><tr id=\"mode\">";

//print each mode settings by Auto main mode
if ( $current_mode == "Auto" ) {
  $i=0;
  foreach($modes as $mode)
  {
    $mode_name = substr($mode, strrpos($mode, "/")+1);
    if (in_array($mode_name, $off_modes_select)) {
      continue;
    }
    //$mode_description = read_file($mode);
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
    $i++;
    if ($i%7 == 0){
      echo "</tr><tr id=\"mode\">";
    }
  }
}
echo"</tr>";
echo "</table>";
?>

<p><a href="add_mode.php" class="buttonclass">Add new mode</a>
<?php
echo "<a href=\"add_mode.php?mode=$current_mode\" class=\"buttonclass\">Set modes</a>";
?>

