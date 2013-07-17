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
source $prefix/config_temp.sh
cd $sensor_settings_path

# Read temperature from sensors
parameters="graph $www_path/aggr_temp_${1}.png --start -1${1} --title ${title[$1]}_graph"
i=0
for line in $sensors_all
do
    if [ `echo $line|cut -c1-2` != "28" ]; then
       continue
    fi

    alias=$line
    if [ -e $line/alias ]; then
      alias=`cat $line/alias`
    else
      if [ `echo $line|cut -c1-3` = "28." ]; then
        alias=`echo  $line | tr '.' '-'`
      fi
    fi
    # Create rrdtool parameters
    parameters="${parameters} DEF:temp${alias}=${db_prefix}_${line}.rrd:temp:AVERAGE LINE1:temp${alias}${colors[$i]}:$alias \
              GPRINT:temp${alias}:MIN:min\:%2.2lf%sC GPRINT:temp${alias}:LAST:last\:%2.2lf%sC GPRINT:temp${alias}:MAX:max\:%2.2lf%sC\n "
    let "i=i+1"
done

# Create graphs
rrdtool $parameters --watermark "`date`" --vertical-label "Temperature (C)" --width 650 --height 300 --font DEFAULT:10: #--slope-mode

