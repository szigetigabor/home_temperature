<?php
require_once('includes.php');

function alert($msg)
{
   echo "\n<script language=\"javascript\">";
   echo "\nalert(\"$msg\");";
   echo "\n</script>";
}


//GET FORM START
if ( isset($_GET) && isset($_GET["group_name"]) ){
  $group_name=$_GET["group_name"];
  $file = $group_settings_path."/$group_name";
  if(!isset($_GET["confirm"])) {
    echo "Do you want to realy delete this (<b>$group_name</b>) group settings?";
    echo "<form method=\"get\" action=\"delete_group.php\">";
    echo "   <input type=\"hidden\" name=\"group_name\" value=\"$group_name\">";
    echo "   <input type=\"hidden\" name=\"confirm\" value=\"yes\">";
    echo "   <input type=\"submit\" value=\"Yes\" class=\"buttonclass\">";
    echo "</form>";
    echo "<a href=\"delete_group.php?group=$group_name\" class=\"buttonclass\">No</a>";
    break;
  }
  if(isset($_GET["confirm"]) && $_GET["confirm"] == "yes") {
    unlink($file);
    alert("$group_name group removed!");
    // reread the groups
    $groups = glob($group_settings_path . "/*");
  }
}
//FORM END


// GET FORM START
$get_group="";
if (isset($_GET["group"])) {
   $get_group=$_GET["group"];
}
//FORM END

include 'menu.php';

echo "<table id=\"group\">";
echo "<tr id=\"group\">";
echo "<a href=\"group.php\" class=\"buttonclass\">New group</a>";

//print each group settings
$i=0;
foreach($groups as $group)
{
  $group_name = substr($group, strrpos($group, "/")+1);
  $class="buttonclass";
  if ( $get_group == $group_name ) {
    $class = "active$class";
  }
  echo "<td id=\"group\">";
  echo "<a href=\"delete_group.php?group=$group_name\" class=\"$class\">$group_name</a>";
  echo "</td>";
  $i++;
  if ($i%7 == 0){
    echo "</tr><tr id=\"group\">";
  }
}

echo"</tr>";
echo "</table>";


echo "<center>";
echo "       <form method=\"get\">";
echo "         Mode name: <input type=\"text\" name=\"group_name\" value=\"$get_group\">";
echo "<p>";
echo "         <input type=\"submit\" value=\"Delete\" class=\"buttonclass\">";
echo "       </form>";
echo "</center>";

?>

