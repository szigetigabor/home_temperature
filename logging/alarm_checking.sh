#!/bin/bash

sensor_settings_path="/home/pi/logging"

# Read temperature from sensors
sensors=`cat /sys/bus/w1/devices/w1_bus_master1/w1_master_slaves;`

for line in $sensors
do
   if [[ $# == 1 && $1 != $line ]]; then
      continue
   fi
   if [ -d $sensor_settings_path/$line ]; then
      if [ ! -e $sensor_settings_path/$line/value ]; then
          continue
      fi 
      temp=`cat $sensor_settings_path/$line/value;`

      if [ ! -e $sensor_settings_path/$line/alarm ]; then
          echo "No alarm set for the following device: $line"
          continue
      fi
      alarm=`cat $sensor_settings_path/$line/alarm;`

      # convert the alarm temperature
      pos=`expr index "$alarm" .`
      if [ $pos -ne 0 ]; then
        up=${alarm:0:$pos-1}
        down=${alarm:$pos}
        l=0
        if [ ${#down} -ne 0 ]; then
          l=`expr length $down`
        fi
        case $l in
          [0]*)
            down=000
          ;;
          [1]*)
            down+=00
          ;;
          [2]*)
            down+=0 
          ;;
        esac

        alarm=$up$down
      else
        alarm+=000
      fi

      #echo $temp
      #echo $alarm
      if [ $temp -lt $alarm ]; then
        echo "$line: heating ON";
        echo on > $sensor_settings_path/$line/onoff
      else
        echo "$line: heating OFF";
        echo off > $sensor_settings_path/$line/onoff
      fi
  fi
done
