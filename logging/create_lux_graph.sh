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

prefix=$(dirname $0)
source $prefix/config_lux.sh

cd $sensor_settings_path

# Read lux from sensors

parameters="graph $www_path/lux_${1}.png --start -1${1} --title ${title[$1]}_graph"
i=0
line=""
# Create rrdtool parameters
parameters="${parameters} DEF:lux${line}=${db_prefix}${line}.rrd:lux:AVERAGE LINE1:lux${line}${colors[$i]}:$alias \
              GPRINT:lux${line}:MIN:min\:%5.2lf%slux GPRINT:lux${line}:LAST:last\:%2.2lf%slux GPRINT:lux${line}:MAX:max\:%2.2lf%slux\n "

# Create graphs
rrdtool $parameters --watermark "`date`" --vertical-label "LUX" --width 650 --height 300 --font DEFAULT:10: #--slope-mode

