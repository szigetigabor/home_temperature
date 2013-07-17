#!/bin/bash
prefix=$(dirname $0)
source $prefix/config_volt.sh

cd $sensor_settings_path

# Read lux from sensor
volt=`./mcp3008_read.py 2 volt 2>adc_error;`

# Update database
if [ ! -e $DB ]; then
   ./create_volt_db.sh
fi

rrdtool update $DB N:$volt


