#!/bin/bash
#parameter checking
containsElement () {
  local e
  for e in "${@:2}"; do [[ "$e" == "$1" ]] && return 0; done
  return 1
}

option=( "h" "d" "w" "m" "y" )
typeset -A title
title['h']="Hourly"
title['d']="Daily"
title['w']="Weekly"
title['m']="Monthly"
title['y']="Yearly"
containsElement $1 "${option[@]}"
if [ `echo $?` == "1" ]; then
  echo "Wrong parameter! [h, w, d, m, y]"
  exit 1
fi

sensor_settings_path="/home/pi/logging"
db_prefix="temperature5004_"

cd $sensor_settings_path

# Read temperature from sensors
sensors=`cat /sys/bus/w1/devices/w1_bus_master1/w1_master_slaves;`

parameters="graph /var/www/temp_graphs/aggr_temp_${1}.png --start -1${1} --title ${title[$1]}_graph"
#graph colors
colors=( "#0000FF" "#CCCCCC" "#00FF00"  "#FF0000" "#8800FF" "#00FFFF" "#888800" "#008888")
i=0
for line in $sensors
do
    if [ `echo $line|cut -c1-3` != "28-" ]; then
       continue
    fi

    alias=$line
    if [ -e $line/alias ]; then
      alias=`cat $line/alias`
    fi
    # Create rrdtool parameters
    parameters="${parameters} DEF:temp${line}=${db_prefix}${line}.rrd:temp:AVERAGE LINE1:temp${line}${colors[$i]}:$alias \
              GPRINT:temp${line}:MIN:min\:%2.2lf%sC GPRINT:temp${line}:LAST:last\:%2.2lf%sC GPRINT:temp${line}:MAX:max\:%2.2lf%sC\n "
    let "i=i+1"
done

# Create graphs
rrdtool $parameters --watermark "`date`" --vertical-label "Temperature (C)" --width 650 --height 300 --font DEFAULT:10: #--slope-mode

