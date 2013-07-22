#!/bin/bash
prefix=$(dirname $0)
source $prefix/config_temp.sh

cd $sensor_settings_path

# Get temperature devices
for line in $sensors_owfs
do
   if [ `echo $line|cut -c1-3` != "28." ]; then
      continue
   fi
   if [ ! -d $line ]; then
      mkdir $line
      chmod 777 $line
   fi

  # Update database
  DB=$db_prefix"_"$line.rrd
  if [ ! -e $DB ]; then
     ./make_temps_owfs.sh
  fi

  alias=$line
  if [ -e $line/alias ]; then
    alias=`cat $line/alias`
  fi
  # Create graphs
  now=`date +%s`
  file=$www_path/$line/temp_h.png
  mode_time=`stat -c %Y $file`
  delay=60
  if [ $(echo "$now - $mode_time" | bc) -gt $delay ]; then
    rrdtool graph $file --start -1h --title "Hourly graph" \
       --watermark "`date`" \
       --vertical-label "Temperature (C)" \
       DEF:temp=$DB:temp:AVERAGE LINE1:temp#0000FF:"$alias" GPRINT:temp:MIN:"min %2.2lf%sC" GPRINT:temp:LAST:"last %2.2lf%sC" GPRINT:temp:MAX:"max %2.2lf%sC"
  fi
  file=$www_path/$line/temp_d.png
  mode_time=`stat -c %Y $file`
  delay=$(echo "$delay * 60" | bc)
  if [ $(echo "$now - $mode_time" | bc) -gt $delay ]; then
    rrdtool graph $file --start -1d --title "Daily graph" \
       --watermark "`date`" \
       --vertical-label "Temperature (C)" \
       DEF:temp=$DB:temp:AVERAGE LINE1:temp#0000FF:"$alias" GPRINT:temp:MIN:"min %2.2lf%sC" GPRINT:temp:LAST:"last %2.2lf%sC" GPRINT:temp:MAX:"max %2.2lf%sC"
  fi
  file=$www_path/$line/temp_w.png
  mode_time=`stat -c %Y $file`
  delay=$(echo "$delay * 24" | bc)
  if [ $(echo "$now - $mode_time" | bc) -gt $delay ]; then
    rrdtool graph $file --start -1w --title "Weekly graph" \
       --watermark "`date`" \
       --vertical-label "Temperature (C)" \
       DEF:temp=$DB:temp:AVERAGE LINE1:temp#0000FF:"$alias" GPRINT:temp:MIN:"min %2.2lf%sC" GPRINT:temp:LAST:"last %2.2lf%sC" GPRINT:temp:MAX:"max %2.2lf%sC"
  fi
  file=$www_path/$line/temp_m.png
  mode_time=`stat -c %Y $file`
  delay=$(echo "$delay * 7" | bc)
  if [ $(echo "$now - $mode_time" | bc) -gt $delay ]; then
    rrdtool graph $file --start -1m --title "Monthly graph" \
       --watermark "`date`" \
       --vertical-label "Temperature (C)" \
       DEF:temp=$DB:temp:AVERAGE LINE1:temp#0000FF:"$alias" GPRINT:temp:MIN:"min %2.2lf%sC" GPRINT:temp:LAST:"last %2.2lf%sC" GPRINT:temp:MAX:"max %2.2lf%sC"
  fi
  file=$www_path/$line/temp_y.png
  mode_time=`stat -c %Y $file`
  delay=$(echo "$delay * 4" | bc)
  if [ $(echo "$now - $mode_time" | bc) -gt $delay ]; then
    rrdtool graph $file --start -1y --title "Yearly graph" \
       --watermark "`date`" \
       --vertical-label "Temperature (C)" \
       DEF:temp=$DB:temp:AVERAGE LINE1:temp#0000FF:"$alias" GPRINT:temp:MIN:"min %2.2lf%sC" GPRINT:temp:LAST:"last %2.2lf%sC" GPRINT:temp:MAX:"max %2.2lf%sC"
  fi
  #0000FF means blue trace color in the graphs.
done


