#!/bin/bash
#parameter checking
containsElement () {
  local e
  for e in "${@:2}"; do [[ "$e" == "$1" ]] && return 0; done
  return 1
}

option=( "h" "d" "w" "m" "y" )
containsElement $1 "${option[@]}"
if [ `echo $?` == "1" ]; then
  echo "Wrong parameter! [h, w, d, m, y]"
  exit 1
fi

prefix=$(dirname $0)
source $prefix/config_humidity.sh

cd $sensor_settings_path

port=$2

# Read humidity from DB
file=$www_path/humidity_${port}_${1}.png
parameters="graph $file --start -1${1} --title ${title[$1]}"
i=0
line="_"$port
# Create rrdtool parameters
now=`date +%s`
mode_time=`last_mod $file`
if [ $(echo "$now - $mode_time" | bc) -gt ${delay[$1]} ]; then
  parameters="${parameters} DEF:hum${line}=${db_prefix}${line}.rrd:hum:AVERAGE LINE1:hum${line}${colors[$i]}:$alias \
              GPRINT:hum${line}:MIN:min\:%2.0lf%% GPRINT:hum${line}:LAST:last\:%2.0lf%% GPRINT:hum${line}:MAX:max\:%2.0lf%%\n "

  # Create graphs
  rrdtool $parameters --watermark "`date`" --vertical-label "Humidity" --width 650 --height 300 --font DEFAULT:10: #--slope-mode
fi
