#!/bin/bash
# Read temperature from sensors
sensors=`cat /sys/bus/w1/devices/w1_bus_master1/w1_master_slaves;`

if [ ! -e /var/www/temp_graphs ]; then
    sudo mkdir /var/www/temp_graphs
fi

for line in $sensors
do
  if [ ! -e /var/www/temp_graphs/$line ]; then
      sudo mkdir /var/www/temp_graphs/$line
  fi
  if [ -e  temperature5004_$line.rrd ]; then
      continue
  fi
  rrdtool create temperature5004_$line.rrd --start N --step 60 \
  DS:temp:GAUGE:600:U:U \
  RRA:AVERAGE:0.5:1:12 \
  RRA:AVERAGE:0.5:1:288 \
  RRA:AVERAGE:0.5:12:168 \
  RRA:AVERAGE:0.5:12:720 \
  RRA:AVERAGE:0.5:288:365
done

