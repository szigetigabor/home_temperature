#!/bin/bash
cd /home/pi/logging

# Read temperature from sensors
sensors=`cat /sys/bus/w1/devices/w1_bus_master1/w1_master_slaves;`

for line in $sensors
do
  tempread=`cat /sys/bus/w1/devices/w1_bus_master1/$line/w1_slave|tail -1|cut -d"=" -f2;`  
  l=`expr length $tempread`
  up=`echo $tempread|cut -c1-$(($l-3));`
  down=`echo $tempread|cut -c $(($l-2))-;`
  temp=$up.$down

  # Update database
  rrdtool update temperature5004_$line.rrd N:$temp

  # Create graphs
  rrdtool graph /var/www/temp_graphs/$line/temp_h.png --start -1h DEF:temp=temperature5004_$line.rrd:temp:AVERAGE LINE1:temp#0000FF:"Last temperature [deg C]" GPRINT:temp:LAST:"%2.2lf%sC"
  rrdtool graph /var/www/temp_graphs/$line/temp_d.png --start -1d DEF:temp=temperature5004_$line.rrd:temp:AVERAGE LINE1:temp#0000FF:"Temperature [deg C]"
  rrdtool graph /var/www/temp_graphs/$line/temp_w.png --start -1w DEF:temp=temperature5004_$line.rrd:temp:AVERAGE LINE1:temp#0000FF:"Temperature [deg C]"
  rrdtool graph /var/www/temp_graphs/$line/temp_m.png --start -1m DEF:temp=temperature5004_$line.rrd:temp:AVERAGE LINE1:temp#0000FF:"Temperature [deg C]"
  rrdtool graph /var/www/temp_graphs/$line/temp_y.png --start -1y DEF:temp=temperature5004_$line.rrd:temp:AVERAGE LINE1:temp#0000FF:"Temperature [deg C]"
  #0000FF means blue trace color in the graphs.

done

