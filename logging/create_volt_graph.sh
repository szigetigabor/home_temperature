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
source $prefix/config_volt.sh

cd $sensor_settings_path

# Read Volt from sensors
file=$www_path/volt_${1}.png
parameters="graph $file --start -1${1} --title ${title[$1]}"
i=0
line=""
# Create rrdtool parameters
now=`date +%s`
mode_time=`stat -c %Y $file`
if [ $(echo "$now - $mode_time" | bc) -gt ${delay[$1]} ]; then
  parameters="${parameters} DEF:lux${line}=${db_prefix}${line}.rrd:lux:AVERAGE LINE1:lux${line}${colors[$i]}:$alias \
              GPRINT:lux${line}:MIN:min\:%5.2lf%sV GPRINT:lux${line}:LAST:last\:%2.2lf%sV GPRINT:lux${line}:MAX:max\:%2.2lf%sV\n "

  # Create graphs
  rrdtool $parameters --watermark "`date`" --vertical-label "Volt" --width 650 --height 300 --font DEFAULT:10: #--slope-mode
fi
