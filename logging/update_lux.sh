#!/bin/bash
prefix=$(dirname $0)
source $prefix/config_lux.sh

cd $sensor_settings_path

# Read lux from sensor
lux=`./adafruit_mcp3008.py 2>adc_error;`

# Update database
if [ ! -e $DB ]; then
   ./create_lux_db.sh
fi

rrdtool update $DB N:$lux


