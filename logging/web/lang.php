<?php
include 'includes.php';

//POST FORM START
if ( isset($_POST) && count($_POST) > 0 ){
  $value=$_POST["lang"];
  $file = $sensors_settings_path."/lang";
  write_file_extra($file,$value,'w');
}
//FORM END


include 'menu.php';

//get all language files.
$langs = glob("langs/*");

echo "<center>";
echo "<form method=\"post\">";
echo "<table id=\"mode\">";

//print each lang settings
foreach($langs as $lang)
{
  $lang_name = substr($lang, strrpos($lang, "/")+1);
  $lang_name = substr($lang_name,0, strrpos($lang_name, "."));

  echo "<tr id=\"mode\">";
  echo "<td>";
  $selected="";
  $current_lang = substr(language(), strrpos(language(), "/")+1);
  if ( $current_lang == $lang_name) {
    $selected="checked";
  }
  echo "<div class=\"langs\">";
  echo "  <input type=\"radio\" id=\"$lang\" name=\"lang\" value=\"$lang_name\" $selected background=\"images/$lang_name.gif\">";
  echo "  <label for=\"$lang\">";//</label>";
  echo "  <img src=\"images/$lang_name.gif\" for=\"$lang\" alt=\"$lang_name\" height=\"31\" width=\"48\"/>";
  echo "  </label>";
 // echo "  <img src=\"images/$lang_name.gif\" for=\"$lang\" alt=\"$lang_name\" height=\"31\" width=\"48\"/>";
  echo "</div>";

  echo "</td>";
  echo "</tr>";
}

echo "</table>";
echo "  <input type=\"submit\" value=\"Set\" class=\"buttonclass\" >";
echo "</form>";
echo "</center>";

?>


