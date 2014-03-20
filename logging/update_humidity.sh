#!/bin/bash
prefix=$(dirname $0)
source $prefix/config_humidity.sh

cd $sensor_settings_path

function update_humidity {
  type=$1
  _port=$2
  DB=$db_prefix"_"$_port".rrd"
  hum=`./dht11_hum.sh $type $_port;`

  # Update database
  if [ ! -e $DB ]; then
     ./create_humidity_db.sh $_port
  fi

  rrdtool update $DB N:$hum
}

# Read hum from DHT11 sensor
for port in $dht11_sensors
do
  update_humidity 11 $port
done

# Read hum from DHT22 sensor
for port in $dht22_sensors
do
  update_humidity 22 $port
done

