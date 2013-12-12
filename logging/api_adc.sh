#!/bin/bash
prefix=$(dirname $0)
source $prefix/config_temp.sh

cd $sensor_settings_path

JSON=""

#temperatures
IDS=`ls -d */|grep 2`
last=`ls -d */|grep 2|tail -1`
# remove last character from the ID
last=`echo "${last%?}"`

for pin in 0 1 2 3 4 5 6 7
do

  value=`./mcp3008_read.py $pin 2>adc_error;`

  JSON=`echo $JSON"{"`
  # Fill the JSON
  JSON=`echo $JSON"port:"$pin","`
  JSON=`echo $JSON"value:"$value`
  JSON=`echo $JSON"}"`

  if [[ "$pin" != "7" ]]
  then
     JSON=`echo $JSON","`
  fi
done

echo $JSON
