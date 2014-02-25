#!/bin/bash
prefix=$(dirname $0)
source $prefix/config_humidity.sh

cd $sensor_settings_path

# Read hum from DHT11 sensor
hum=`./dht11_hum.sh 11 22;`

# Update database
if [ ! -e $DB ]; then
   ./create_humidity_db.sh
fi

rrdtool update $DB N:$hum


