#!/bin/bash
prefix=$(dirname $0)
source $prefix/config_temp.sh

cd $sensor_settings_path

# Get temperature devices
for line in $sensors
do
   if [ `echo $line|cut -c1-3` != "28-" ]; then
      continue
   fi
   if [ ! -d $line ]; then
      mkdir $line
      chmod 777 $line
   fi

  # Update database
  DB=$db_prefix"_"$line.rrd
  if [ ! -e $DB ]; then
     ./make_temps.sh
  fi

  alias=$line
  if [ -e $line/alias ]; then
    alias=`cat $line/alias`
  fi
  # Create graphs
  rrdtool graph $www_path/$line/temp_h.png --start -1h --title "Hourly graph" \
       --watermark "`date`" \
       --vertical-label "Temperature (C)" \
       DEF:temp=$DB:temp:AVERAGE LINE1:temp#0000FF:"$alias" GPRINT:temp:MIN:"min %2.2lf%sC" GPRINT:temp:LAST:"last %2.2lf%sC" GPRINT:temp:MAX:"max %2.2lf%sC"
  rrdtool graph $www_path/$line/temp_d.png --start -1d --title "Daily graph" \
       --watermark "`date`" \
       --vertical-label "Temperature (C)" \
       DEF:temp=$DB:temp:AVERAGE LINE1:temp#0000FF:"$alias" GPRINT:temp:MIN:"min %2.2lf%sC" GPRINT:temp:LAST:"last %2.2lf%sC" GPRINT:temp:MAX:"max %2.2lf%sC"
  rrdtool graph $www_path/$line/temp_w.png --start -1w --title "Weekly graph" \
       --watermark "`date`" \
       --vertical-label "Temperature (C)" \
       DEF:temp=$DB:temp:AVERAGE LINE1:temp#0000FF:"$alias" GPRINT:temp:MIN:"min %2.2lf%sC" GPRINT:temp:LAST:"last %2.2lf%sC" GPRINT:temp:MAX:"max %2.2lf%sC"
  rrdtool graph $www_path/$line/temp_m.png --start -1m --title "Monthly graph" \
       --watermark "`date`" \
       --vertical-label "Temperature (C)" \
       DEF:temp=$DB:temp:AVERAGE LINE1:temp#0000FF:"$alias" GPRINT:temp:MIN:"min %2.2lf%sC" GPRINT:temp:LAST:"last %2.2lf%sC" GPRINT:temp:MAX:"max %2.2lf%sC"
  rrdtool graph $www_path/$line/temp_y.png --start -1y --title "Yearly graph" \
       --watermark "`date`" \
       --vertical-label "Temperature (C)" \
       DEF:temp=$DB:temp:AVERAGE LINE1:temp#0000FF:"$alias" GPRINT:temp:MIN:"min %2.2lf%sC" GPRINT:temp:LAST:"last %2.2lf%sC" GPRINT:temp:MAX:"max %2.2lf%sC"
  #0000FF means blue trace color in the graphs.
done


