#!/bin/bash
sensor_settings_path="/home/pi/logging"

cd $sensor_settings_path

# Get temperature devices
sensors=`ls /tmp/1wire;`

for line in $sensors
do
   if [ `echo $line|cut -c1-3` != "28." ]; then
      continue
   fi
   if [ ! -d $line ]; then
      mkdir $line
      chmod 777 $line
   fi

  # Update database
  if [ ! -e temperature5004_$line.rrd ]; then
     ./make_temps.sh
  fi

  alias=$line
  if [ -e $line/alias ]; then
    alias=`cat $line/alias`
  fi
  # Create graphs
  rrdtool graph /var/www/temp_graphs/$line/temp_h.png --start -1h --title "Hourly graph" \
       --watermark "`date`" \
       --vertical-label "Temperature (C)" \
       DEF:temp=temperature5004_$line.rrd:temp:AVERAGE LINE1:temp#0000FF:"$alias" GPRINT:temp:MIN:"min %2.2lf%sC" GPRINT:temp:LAST:"last %2.2lf%sC" GPRINT:temp:MAX:"max %2.2lf%sC"
  rrdtool graph /var/www/temp_graphs/$line/temp_d.png --start -1d --title "Daily graph" \
       --watermark "`date`" \
       --vertical-label "Temperature (C)" \
       DEF:temp=temperature5004_$line.rrd:temp:AVERAGE LINE1:temp#0000FF:"$alias" GPRINT:temp:MIN:"min %2.2lf%sC" GPRINT:temp:LAST:"last %2.2lf%sC" GPRINT:temp:MAX:"max %2.2lf%sC"
  rrdtool graph /var/www/temp_graphs/$line/temp_w.png --start -1w --title "Weekly graph" \
       --watermark "`date`" \
       --vertical-label "Temperature (C)" \
       DEF:temp=temperature5004_$line.rrd:temp:AVERAGE LINE1:temp#0000FF:"$alias" GPRINT:temp:MIN:"min %2.2lf%sC" GPRINT:temp:LAST:"last %2.2lf%sC" GPRINT:temp:MAX:"max %2.2lf%sC"
  rrdtool graph /var/www/temp_graphs/$line/temp_m.png --start -1m --title "Monthly graph" \
       --watermark "`date`" \
       --vertical-label "Temperature (C)" \
       DEF:temp=temperature5004_$line.rrd:temp:AVERAGE LINE1:temp#0000FF:"$alias" GPRINT:temp:MIN:"min %2.2lf%sC" GPRINT:temp:LAST:"last %2.2lf%sC" GPRINT:temp:MAX:"max %2.2lf%sC"
  rrdtool graph /var/www/temp_graphs/$line/temp_y.png --start -1y --title "Yearly graph" \
       --watermark "`date`" \
       --vertical-label "Temperature (C)" \
       DEF:temp=temperature5004_$line.rrd:temp:AVERAGE LINE1:temp#0000FF:"$alias" GPRINT:temp:MIN:"min %2.2lf%sC" GPRINT:temp:LAST:"last %2.2lf%sC" GPRINT:temp:MAX:"max %2.2lf%sC"
  #0000FF means blue trace color in the graphs.
done


