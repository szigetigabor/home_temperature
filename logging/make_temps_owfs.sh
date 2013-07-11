#!/bin/bash
# Read temperature from sensors
sensors=`ls  /tmp/1wire;`

if [ ! -e /var/www/temp_graphs ]; then
    sudo mkdir /var/www/temp_graphs
    sudo chmod 777 /var/www/temp_graphs/
fi

for line in $sensors
do
  if [ `echo $line|cut -c1-3` != "28." ]; then
     continue
  fi
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

