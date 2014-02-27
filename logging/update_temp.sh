#!/bin/bash
prefix=$(dirname $0)
source $prefix/config_temp.sh

cd $sensor_settings_path

# Read temperature from sensors
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
  n=0

  while [ $crc = "NO" -a $n -lt 10 ]
  do
    sensor_output=$(cat /sys/bus/w1/devices/$line/w1_slave | cut -c 30-)
    crc=$(echo $sensor_output | cut -c 7-10 )
    #echo $crc

    temp_raw=$(echo $sensor_output | cut -c 11-)
    temp=$(echo "0.001 * $temp_raw" | bc)

    if [ $temp = 85.000 ]; then
      temp=0.000
      crc="NO"
      n=$(echo "1 + $n" | bc)
    fi
  done
  echo $temp
 
  # update store values
  #echo $temp > $line/value;


  # Update database
  DB=$db_prefix"_"$line.rrd
  if [ ! -e $DB ]; then
     ./make_temps.sh
  fi
  rrdtool update $DB N:$temp

  ./update_temp_sql.py $line $temp
#  alias=$line
#  if [ -e $line/alias ]; then
#    alias=`cat $line/alias`
#  fi
  # Create graphs
#  rrdtool graph /var/www/temp_graphs/$line/temp_h.png --start -1h --title "Hourly graph" \
#       --watermark "`date`" \
#       --vertical-label "Temperature (C)" \
#       DEF:temp=temperature5004_$line.rrd:temp:AVERAGE LINE1:temp#0000FF:"$alias" GPRINT:temp:MIN:"min %2.2lf%sC" GPRINT:temp:LAST:"last %2.2lf%sC" GPRINT:temp:MAX:"max %2.2lf%sC"
#  rrdtool graph /var/www/temp_graphs/$line/temp_d.png --start -1d --title "Daily graph" \
#       --watermark "`date`" \
#       --vertical-label "Temperature (C)" \
#       DEF:temp=temperature5004_$line.rrd:temp:AVERAGE LINE1:temp#0000FF:"$alias" GPRINT:temp:MIN:"min %2.2lf%sC" GPRINT:temp:LAST:"last %2.2lf%sC" GPRINT:temp:MAX:"max %2.2lf%sC"
#  rrdtool graph /var/www/temp_graphs/$line/temp_w.png --start -1w --title "Weekly graph" \
#       --watermark "`date`" \
#       --vertical-label "Temperature (C)" \
#       DEF:temp=temperature5004_$line.rrd:temp:AVERAGE LINE1:temp#0000FF:"$alias" GPRINT:temp:MIN:"min %2.2lf%sC" GPRINT:temp:LAST:"last %2.2lf%sC" GPRINT:temp:MAX:"max %2.2lf%sC"
#  rrdtool graph /var/www/temp_graphs/$line/temp_m.png --start -1m --title "Monthly graph" \
#       --watermark "`date`" \
#       --vertical-label "Temperature (C)" \
#       DEF:temp=temperature5004_$line.rrd:temp:AVERAGE LINE1:temp#0000FF:"$alias" GPRINT:temp:MIN:"min %2.2lf%sC" GPRINT:temp:LAST:"last %2.2lf%sC" GPRINT:temp:MAX:"max %2.2lf%sC"
#  rrdtool graph /var/www/temp_graphs/$line/temp_y.png --start -1y --title "Yearly graph" \
#       --watermark "`date`" \
#       --vertical-label "Temperature (C)" \
#       DEF:temp=temperature5004_$line.rrd:temp:AVERAGE LINE1:temp#0000FF:"$alias" GPRINT:temp:MIN:"min %2.2lf%sC" GPRINT:temp:LAST:"last %2.2lf%sC" GPRINT:temp:MAX:"max %2.2lf%sC"
#  #0000FF means blue trace color in the graphs.
  sleep 1
done

./alarm_checking.sh

