#!/bin/bash

prefix=$(dirname $0)
source $prefix/config_temp.sh

for line in $sensors
do
   if [[ $# == 1 && $1 != $line ]]; then
      continue
   fi
   if [ -d $sensor_settings_path/$line ]; then
      # read last stored temperature from file
      #if [ ! -e $sensor_settings_path/$line/value ]; then
      #    continue
      #fi 
      #temp=`cat $sensor_settings_path/$line/value;`

      DB=$db_prefix"_"$line.rrd
      # read last stored temperature from DB
      if [ ! -e $sensor_settings_path/$DB ]; then
          continue
      fi
      temp_db=`rrdtool lastupdate $sensor_settings_path/$DB |tail -1|cut -c 13-;`
      # convert the temperature
      pos=`expr index "$temp_db" .`
      temp=${temp_db:0:$pos-1}${temp_db:$pos}

      if [ ! -e $sensor_settings_path/$line/alarm ]; then
          echo "No alarm set for the following device: $line"
          continue
      fi

      alarm=`cat $sensor_settings_path/$line/alarm;`
      #alarm=`$sensor_settings_path/get_alarm.sh $line;`

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

      #alias=`cat $sensor_settings_path/$line/alias;`
      switch_file=$sensor_settings_path/$line/switch;
      if [ ! -e $switch_file ]; then
          echo "No switch set for the following device: $line"
          continue
      fi

      switch=`cat $switch_file;`

      deviceID=${switch:0:15}
      port=${switch:16:1}
      heating_mode=""; 
      if [ $temp -lt $alarm ]; then
        heating_mode="on";
        $sensor_settings_path/switch_set.sh $deviceID $port 1
      else
        heating_mode="off";
        $sensor_settings_path/switch_set.sh $deviceID $port 0
      fi

      echo "$line: heating " `echo $heating_mode | tr '[:lower:]' '[:upper:]'`;
      echo $heating_mode > $sensor_settings_path/$line/onoff
      
  fi
done
