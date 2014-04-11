#!/bin/bash

#
# for this is require the adafruit DHT binary
#
type=$1
port=$2

prefix=$(dirname $0)
source $prefix/config.sh

for i in {1..10}; do
  output=`$sensor_settings_path/Adafruit_DHT $1 $2`

  i=$((${#output}-1))

  if [ "${output:$i:1}" == "%" ]; then
    i=$((${#output}-4))
    len=2
    if [ $type == 22 ]; then
      i=$((${#output}-6))
      len=4
    fi
    echo ${output:$i:len}

    exit
  fi
  sleep 3
done
