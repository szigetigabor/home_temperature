#!/bin/bash
prefix=$(dirname $0)
source $prefix/config.sh

dht11_sensors=(22)
dht22_sensors=()

#Humidity
DB="humidity.rrd"
db_prefix="humidity"
