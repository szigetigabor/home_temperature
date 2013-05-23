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
db_prefix="volt"

cd $sensor_settings_path

# Read lux from sensors

parameters="graph /var/www/temp_graphs/volt_${1}.png --start -1${1} --title ${title[$1]}_graph"
#graph colors
colors=( "#0000FF" "#CC0000" "#00FF00" )
i=0

line=""
    # Create rrdtool parameters
    parameters="${parameters} DEF:lux${line}=${db_prefix}${line}.rrd:lux:AVERAGE LINE1:lux${line}${colors[$i]}:$alias \
              GPRINT:lux${line}:MIN:min\:%5.2lf%sV GPRINT:lux${line}:LAST:last\:%2.2lf%sV GPRINT:lux${line}:MAX:max\:%2.2lf%sV\n "

# Create graphs
rrdtool $parameters --watermark "`date`" --vertical-label "Volt" --width 650 --height 300 --font DEFAULT:10: #--slope-mode

