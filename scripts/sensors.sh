#!/usr/bin/perl
@sensors = `cat /sys/bus/w1/devices/w1_bus_master1/w1_master_slaves`;
chomp(@sensors);

foreach $line(@sensors) {
  $output = `cat /sys/bus/w1/devices/$line/w1_slave`;
  $output =~ /t=(?<temp>\d+)/;
  $calc = $+{temp} / 1000;
  print "Sensor ID: $line, Temp: $calc\n";
}
