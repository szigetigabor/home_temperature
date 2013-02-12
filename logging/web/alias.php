<?php
require_once('includes.php');

if (!isIPIn($ip, $net, $mask)) {
  header('Location: home.php');
}

//POST FORM START
foreach($_POST as $key=>$value)
{
 $file = $sensors_settings_path."/".$key."/alias";
 write_file($file,$value);
}
//FORM END


include 'menu.php';
?>
<center>
<table>
  <thead>
  <tr>
     <th>device</th>
     <th>alias name</th>
  </tr>
  </thead>

<?php
//print each sensor device
foreach($devices as $device)
{
  $device_name=substr($device, strrpos($device, "/")+1);
  $settings_path=$sensors_settings_path."/".$device_name;

  //ALIAS
  $alias = read_file($settings_path."/alias");
  $alias = trim($alias, " \n.");

  echo "  <tr>";
  echo "     <td>$device_name</td>";
  echo "     <td>";
  echo "       <img src=\"\" width=\"1\" height=\"15\">";
  echo "       <form method=\"post\">";
  echo "         <input type=\"text\" name=\"$device_name\" value=\"$alias\" >";
  echo "         <input type=\"submit\" value=\"Set\" class=\"buttonclass\">";
  echo "       </form>";
  echo "     </td>";
  echo "  </tr>";
}

?>
</table>
</center>
