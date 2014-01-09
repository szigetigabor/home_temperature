<?php
include 'calendar.php';
if (empty($_GET)) {
  exit;
}
echo "<center>";

$device="28-0000040009a1";
$alias=$device;
$prefix="..";

$start="-1h";
if (isset($_GET["start_date"])) {
  $start=$_GET["start_date"];
  $start=strtotime($start);
}
if (isset($_GET["end_date"])) {
  $end=$_GET["end_date"];
  $end=strtotime($end);
  create_graph($device, $alias, "$prefix/login-hour.gif", "Hourly temperature graph", $start, $end);
} else {
  create_graph($device, $alias, "$prefix/login-hour.gif", "Hourly temperature graph", $start);
  //create_graph($device, $alias, "$prefix/login-day.gif", "Daily temperature graph", "-1d");
  //create_graph($device, $alias, "$prefix/login-week.gif", "Weekly temperature graph", "-1w");
  //create_graph($device, $alias, "$prefix/login-month.gif", "Monthly temperature graph", "-1m");
  //create_graph($device, $alias, "$prefix/login-year.gif", "Yearly temperature graph", "-1y");
}

echo "<table>";
echo "<tr><td>";
echo "<img src='$prefix/login-hour.gif' alt='Generated RRD image'>";
echo "</td><tr>";
//echo "<tr><td>";
//echo "<img src='$prefix/login-day.gif' alt='Generated RRD image'>"; 
//echo "</td><td>";
//echo "<img src='$prefix/login-week.gif' alt='Generated RRD image'>";
//echo "</td></tr>";
//echo "<tr><td>";
//echo "<img src='$prefix/login-month.gif' alt='Generated RRD image'>";
//echo "</td><td>";
//echo "<img src='$prefix/login-year.gif' alt='Generated RRD image'>";
//echo "</td></tr>";
echo "</table>";

echo "</center>";
exit;

function create_graph($deviceID,$alias, $output, $title, $start, $end) {
  if (empty($end)) {
    $end=now;
  }
  if ( $end > $start ) {
  //  $tmp=$end;
  //  $end=$start;
  //  $start=$tmp;
  }
  $options = array(
    "--slope-mode",
    "--start", $start,
    "--end", $end,
    "--title=$title",
    "--vertical-label=Termperature (C)",
//    "--lower=0",
    "DEF:temp=/home/pi/logging/temperature5004_$deviceID.rrd:temp:AVERAGE",
    "LINE1:temp#0000FF:$alias",
    "GPRINT:temp:MIN:min %2.2lf%sC",
    "GPRINT:temp:LAST:last %2.2lf%sC",
    "GPRINT:temp:MAX:max %2.2lf%sC",
  );
//  $options = array(
//    "--slope-mode",
//    "--start", $start,
//    "--title=$title",
//    "--vertical-label=User login attempts",
//    "--lower=0",
//    "DEF:success=login.rrd:success:AVERAGE",
//    "DEF:failure=login.rrd:failure:AVERAGE",
//    "CDEF:tsuccess=success,300,*",
//    "CDEF:tfailure=failure,300,*",
//    "AREA:tsuccess#00FF00:Successful attempts",
//    "STACK:tfailure#FF0000:Failed attempts",
//    "COMMENT:\\n",
//    "GPRINT:tsuccess:AVERAGE:successful attempts %6.2lf",
//    "COMMENT: ",
//    "GPRINT:tfailure:AVERAGE:failure attempts %6.2lf",
//  );

  $ret = rrd_graph($output, $options);//, count($options));
//var_dump($output);
//var_dump($ret);
  if (! $ret) {
    echo "<b>Graph error: </b>".rrd_error()."\n";
    exit;
  }
}

?>
