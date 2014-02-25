#!/bin/bash

#
# for this is require the adafruit DHT binary
#
type=$1
port=$2

while true; do
  output=`./Adafruit_DHT $1 $2`

  i=$((${#output}-1))

  if [ "${output:$i:1}" == "%" ]; then
    i=$((${#output}-4))
    echo ${output:$i:2}

    exit
  fi
  sleep 3
  exit
done
