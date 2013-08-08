<?php
require_once('includes.php');
#include 'menu.php';

//POST FORM START
#$file_name="alarm";
if (count($_POST) > 0 && isset($_POST["group"])){
  $file_name=$_POST["group"];
  $groups="";
  foreach($_POST as $key=>$value)
  {
    if($key == "group"){
      continue;
    }
    $groups .= $value.", ";
  }
  $file = $group_settings_path."/$file_name";
  write_file($file,$groups);
}
//FORM END

// GET FORM START
$get_group="";
$get_selected="";
$setting=false;
if (isset($_GET["group"])) {
   $get_group=$_GET["group"];
   $file = $group_settings_path."/".$get_group;
   $get_selected = read_file($file);
   //$get_selected = trim($get_mode, " ,\n.");
   $get_selected = explode(', ', $get_selected);

   $setting=true;
}
//FORM END

//if ($setting) {
  echo "<table id=\"mode\">";
  echo "<tr id=\"mode\">";
  echo "<a href=\"group.php\" class=\"buttonclass\">New group</a>";

  //print each groups
  $i=0;
  foreach($groups as $group)
  {
    $group_name = substr($group, strrpos($group, "/")+1);
    $class="buttonclass";
    if ( $get_group == $group_name ) {
      $class = "active$class";
    }
    echo "<td id=\"mode\">";
    echo "<a href=\"group.php?group=$group_name\" class=\"$class\">$group_name</a>";
    echo "</td>";
    $i++;
    if ($i%7 == 0){
      echo "</tr><tr id=\"mode\">";
    }
  }

  echo"</tr>";
  echo "</table>";
//}




function off_mode($var){
  global $off_modes_select;
  return(!in_array($var, $off_modes_select));
}
$modes = array_filter($modes, "off_mode");



?>
<center>
<form method="post">

<?php
echo "<table>";
echo "  <tr><td><input type=\"text\" name=\"group\" value=\"$get_group\"></td></tr>";

echo "  <tr><td align=\"left\">";
// Filter option
$filter = "28";

//print each sensor device
foreach($devices as $device)
{
  $device_name=substr($device, strrpos($device, "/")+1);
  if ( $filter != substr($device_name,0,2) ) {
    continue;
  }
  $device_id=$device_name;
  $settings_path=$sensors_settings_path."/".$device_name;

  //ALIAS
  $alias = read_file($settings_path."/alias");
  $alias = trim($alias, " \n.");
  if ($alias != "") {
      $device_name=$alias;
  }
  $selected="";
  if (in_array($device_name,$get_selected)) {
    $selected="checked";
  }
  echo "     <input type=\"checkbox\" name=\"$device_id\" value=\"$device_name\" $selected> $device_name<br>";
}

?>
    </td></tr>
    <tr><td><input type="submit" value="Set" class="buttonclass"></td></tr>
  </table>
</form>
</center>
