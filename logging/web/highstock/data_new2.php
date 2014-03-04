<?php

$db = new SQLite3('/home/pi/logging/templog.db');

$WHERE="";
if (isset($_GET['sensorid'])) {
  $id=$_GET['sensorid'];
  $WHERE="WHERE id='$id'";
}
$limitNR=600;
$result=array();

$results = $db->query("SELECT strftime('%s',timestamp)*1000,temp FROM (SELECT * FROM temps ".$WHERE."ORDER BY timestamp DESC LIMIT ".$limitNR.") ORDER BY timestamp ASC" );
//$results = $db->query("SELECT strftime('%s',timestamp)*1000,temp FROM temps ".$WHERE );

try {
  if (($results instanceof Sqlite3Result)) {
    while ($row = $results->fetchArray(SQLITE3_NUM)) {
      $result[]= $row;
    }
  }
} catch(Exception $e) {
}

echo json_encode($result,JSON_NUMERIC_CHECK);
?>
