<?php
require_once('includes.php');

function alert($msg)
{
   echo "\n<script language=\"javascript\">";
   echo "\nalert(\"$msg\");";
   echo "\n</script>";
}


//POST FORM START
if ( isset($_POST) && count($_POST) > 0 ){
  $mode_name=$_POST["mode_name"];
  $file = $mode_settings_path."/$mode_name";
  if(!isset($_POST["confirm"])) {
    echo "Do you want to realy delete this (<b>$mode_name</b>) mode settings?";
    echo "<form method=\"post\" action=\"delete_mode.php\">";
    echo "   <input type=\"hidden\" name=\"mode_name\" value=\"$mode_name\">";
    echo "   <input type=\"hidden\" name=\"confirm\" value=\"yes\">";
    echo "   <input type=\"submit\" value=\"Yes\" class=\"buttonclass\">";
    echo "</form>";
    echo "<a href=\"delete_mode.php?mode=$mode_name\" class=\"buttonclass\">No</a>";
    break;
  }
  if(isset($_POST["confirm"]) && $_POST["confirm"] == "yes") {
    unlink($file);
    alert("$mode_name mode removed!");
    // reread the modes
    $modes = glob($mode_settings_path . "/*");
  }
}
//FORM END


// GET FORM START
$get_mode="";
if (isset($_GET["mode"])) {
   $get_mode=$_GET["mode"];
}
//FORM END

include 'menu.php';

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
  echo "<a href=\"delete_mode.php?mode=$mode_name\" class=\"$class\">$mode_name</a>";
  echo "</td>";
  $i++;
  if ($i%7 == 0){
    echo "</tr><tr id=\"mode\">";
  }
}

echo"</tr>";
echo "</table>";


echo "<center>";
echo "       <form method=\"post\">";
echo "         Mode name: <input type=\"text\" name=\"mode_name\" value=\"$get_mode\">";
echo "<p>";
echo "         <input type=\"submit\" value=\"Delete\" class=\"buttonclass\">";
echo "       </form>";
echo "</center>";

?>

