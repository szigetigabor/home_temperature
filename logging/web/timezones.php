<?php
include 'includes.php';
include 'menu.php';

//POST FORM START
foreach($_POST as $key=>$value)
{
 $file = $sensors_settings_path."/timezone";
 write_file($file,$value);
}
//FORM END

static $regions = array(
    'Africa' => DateTimeZone::AFRICA,
    'America' => DateTimeZone::AMERICA,
    'Antarctica' => DateTimeZone::ANTARCTICA,
    'Asia' => DateTimeZone::ASIA,
    'Atlantic' => DateTimeZone::ATLANTIC,
    'Europe' => DateTimeZone::EUROPE,
    'Indian' => DateTimeZone::INDIAN,
    'Pacific' => DateTimeZone::PACIFIC
);

foreach ($regions as $name => $mask) {
    $tzlist[] = DateTimeZone::listIdentifiers($mask);
}

//var_dump($tzlist);

$current = read_file($sensors_settings_path."/timezone");
$current = trim($current, " \n.");

if ( $current == "" ) {
  $current=date_default_timezone_get();
}

echo "<center>";
echo " <form method=\"post\">";
echo "   <select name=\"timezone\">";
$timezones=timezone_identifiers_list();
foreach ($timezones as $zone) {
    $selected="";
    if ( $current == $zone) {
      $selected="selected=\"selected\"";
    }
    echo "<option value=\"$zone\" $selected>$zone</option>";
}
echo "   </select>";
echo "   <input type=\"submit\" value=\"Set\">";
echo " </form>";
echo "</center>";
?>
