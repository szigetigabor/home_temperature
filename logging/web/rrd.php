<?php
$device="28-0000040009a1";
$alias=$device;
create_graph($device, $alias, "/var/www/login-hour.gif", "-1h", "Hourly temperature graph");
create_graph($device, $alias, "/var/www/login-day.gif", "-1d", "Daily temperature graph");
create_graph($device, $alias, "/var/www/login-week.gif", "-1w", "Weekly temperature graph");
create_graph($device, $alias, "/var/www/login-month.gif", "-1m", "Monthly temperature graph");
create_graph($device, $alias, "/var/www/login-year.gif", "-1y", "Yearly temperature graph");

echo "<table>";
echo "<tr><td>";
echo "<img src='login-hour.gif' alt='Generated RRD image'>";
echo "</td><tr>";
echo "<tr><td>";
echo "<img src='login-day.gif' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='login-week.gif' alt='Generated RRD image'>";
echo "</td></tr>";
echo "<tr><td>";
echo "<img src='login-month.gif' alt='Generated RRD image'>";
echo "</td><td>";
echo "<img src='login-year.gif' alt='Generated RRD image'>";
echo "</td></tr>";
echo "</table>";
exit;

function create_graph($deviceID,$alias, $output, $start, $title) {
  $options = array(
    "--slope-mode",
    "--start", $start,
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
  }
}

?>
