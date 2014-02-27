#!/bin/bash
prefix=$(dirname $0)
source $prefix/config_temp.sh

cd $sensor_settings_path

# Read temperature from sensors
for line in $sensors_owfs
do
   if [ `echo $line|cut -c1-3` != "28." ]; then
      continue
   fi
   #line=${line//./-}
   if [ ! -d $line ]; then
      mkdir $line
      chmod 777 $line
      chown pi:pi $line
   fi

  crc="NO" 

 # while [ $crc = "NO" ]
 # do
    sensor_output=$(cat $sensors_owfs_path/$line/temperature | cut -c 5-)
#    crc=$(cat $sensor_path/$line/crc8 )
    #echo $crc
 # done
  temp=$(echo "$sensor_output" | bc )
  temp_raw=$(echo "1000 * $temp" | bc )

  # update store values
#  echo $temp_raw > $line/value;


  # Update database
  DB=$db_prefix"_"$line.rrd
  if [ ! -e $DB ]; then
     ./make_temps_owfs.sh
  fi
  rrdtool update $DB N:$temp
  ./update_temp_sql.py $line $temp

  sleep 1
done

./alarm_checking.sh all owfs

