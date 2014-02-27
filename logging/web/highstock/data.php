<?php

$db = new SQLite3('/home/pi/logging/templog.db');

$WHERE="";
if (isset($_GET['sensorid'])) {
  $id=$_GET['sensorid'];
  $WHERE="WHERE id='$id'";
}
$limitNR=120;
$results = $db->query("SELECT * FROM (SELECT * FROM temps ".$WHERE."ORDER BY timestamp DESC LIMIT ".$limitNR.") ORDER BY timestamp ASC" );

echo "[";
$start="";
while ($row = $results->fetchArray()) {
  if ( $start == "") {
    $start="1";
  }else {
    echo ",\n";
  }
  echo "[".$row['temp']. "]";
 // echo $row['timestamp'] . "\t" . $row['temp']. "\n";
}
echo "]";

?>
