#!/bin/bash
sensor_settings_path="/home/pi/logging"

cd $sensor_settings_path

# Read temperature from sensors
sensor_path="/tmp/1wire";
sensors=`ls $sensor_path;`

for line in $sensors
do
   if [ `echo $line|cut -c1-3` != "28." ]; then
      continue
   fi
   if [ ! -d $line ]; then
      mkdir $line
      chmod 777 $line
   fi

  crc="NO" 

 # while [ $crc = "NO" ]
 # do
    sensor_output=$(cat $sensor_path/$line/temperature | cut -c 5-)
#    crc=$(cat $sensor_path/$line/crc8 )
    #echo $crc
 # done
  temp=$(echo "$sensor_output" | bc )
  temp_raw=$(echo "1000 * $temp" | bc )

  # update store values
  echo $temp_raw > $line/value;


  # Update database
  if [ ! -e temperature5004_$line.rrd ]; then
     ./make_temps_owfs.sh
  fi
  rrdtool update temperature5004_$line.rrd N:$temp

  sleep 1
done

#./alarm_checking.sh

