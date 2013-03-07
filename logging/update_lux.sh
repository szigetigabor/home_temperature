#!/bin/bash
sensor_settings_path="/home/pi/logging"

cd $sensor_settings_path

DB="lux.rrd"

# Read lux from sensor
lux=`./adafruit_mcp3008.py 2>adc_error;`

# Update database
if [ ! -e $DB ]; then
   ./create_lux_db.sh
fi

rrdtool update $DB N:$lux


