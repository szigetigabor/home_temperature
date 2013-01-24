#!/bin/bash
cd /home/pi/logging

# Read temperature from sensors
sensors=`cat /sys/bus/w1/devices/w1_bus_master1/w1_master_slaves;`

for line in $sensors
do
   if [ `echo $line|cut -c1-3` != "28-" ]; then
      continue
   fi
   if [ ! -d $line ]; then
      mkdir $line
      chmod 777 $line
   fi

  crc="NO" 

  while [ $crc = "NO" ]
  do
    sensor_output=$(cat /sys/bus/w1/devices/$line/w1_slave | cut -c 30-)
    crc=$(echo $sensor_output | cut -c 7-10 )
    #echo $crc
  done
  temp_raw=$(echo $sensor_output | cut -c 11-)
  temp=$(echo "0.001 * $temp_raw" | bc)
  
  # update store values
  echo $temp_raw > $line/value;


  # Update database
  if [ ! -e temperature5004_$line.rrd ]; then
     ./make_temps.sh
  fi
  rrdtool update temperature5004_$line.rrd N:$temp

  # Create graphs
  rrdtool graph /var/www/temp_graphs/$line/temp_h.png --start -1h --title "Hourly graph" DEF:temp=temperature5004_$line.rrd:temp:AVERAGE LINE1:temp#0000FF:"Temperature [deg C]" GPRINT:temp:MIN:"min %2.2lf%sC" GPRINT:temp:LAST:"last %2.2lf%sC" GPRINT:temp:MAX:"max %2.2lf%sC"
  rrdtool graph /var/www/temp_graphs/$line/temp_d.png --start -1d --title "Daily graph" DEF:temp=temperature5004_$line.rrd:temp:AVERAGE LINE1:temp#0000FF:"Temperature [deg C]" GPRINT:temp:MIN:"min %2.2lf%sC" GPRINT:temp:LAST:"last %2.2lf%sC" GPRINT:temp:MAX:"max %2.2lf%sC"
  rrdtool graph /var/www/temp_graphs/$line/temp_w.png --start -1w --title "Weekly graph" DEF:temp=temperature5004_$line.rrd:temp:AVERAGE LINE1:temp#0000FF:"Temperature [deg C]" GPRINT:temp:MIN:"min %2.2lf%sC" GPRINT:temp:LAST:"last %2.2lf%sC" GPRINT:temp:MAX:"max %2.2lf%sC"
  rrdtool graph /var/www/temp_graphs/$line/temp_m.png --start -1m --title "Monthly graph" DEF:temp=temperature5004_$line.rrd:temp:AVERAGE LINE1:temp#0000FF:"Temperature [deg C]" GPRINT:temp:MIN:"min %2.2lf%sC" GPRINT:temp:LAST:"last %2.2lf%sC" GPRINT:temp:MAX:"max %2.2lf%sC"
  rrdtool graph /var/www/temp_graphs/$line/temp_y.png --start -1y --title "Yearly graph" DEF:temp=temperature5004_$line.rrd:temp:AVERAGE LINE1:temp#0000FF:"Temperature [deg C]" GPRINT:temp:MIN:"min %2.2lf%sC" GPRINT:temp:LAST:"last %2.2lf%sC" GPRINT:temp:MAX:"max %2.2lf%sC"
  #0000FF means blue trace color in the graphs.
  sleep 1
done

./alarm_checking.sh

