<?php
include 'includes.php';

//POST FORM START
if ( isset($_POST) && count($_POST) > 0 ){
  $value=$_POST["mode"];
  $file = $sensors_settings_path."/current_mode";
  write_file_extra($file,$value,'w');
}
//FORM END


include 'menu.php';

$current_mode = read_file($sensors_settings_path."/current_mode");
$current_mode = trim($current_mode, " \n.");

echo "<table id=\"mode\">";
echo "<tr id=\"mode\">";

//print each mode settings
$i=0;
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
  $i++;
  if ($i%7 == 0){
    echo "</tr><tr id=\"mode\">";
  }

}

echo"</tr>";
echo "</table>";
?>

<p><a href="add_mode.php" class="buttonclass">Add new mode</a>
<?php
echo "<a href=\"add_mode.php?mode=$current_mode\" class=\"buttonclass\">Set modes</a>";
?>

