<?php
require_once('constants.php');


$command = "/usr/bin/sudo $sensors_settings_path/api.sh";

// GET FORM START
$get_filter="";
if (isset($_GET["filter"])) {
  $get_filter=$_GET["filter"];
}
if ( $get_filter == "N/A" ) {

}

// FORM END

exec ($command, $output);
echo $output[0];

?>
