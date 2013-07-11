#!/bin/bash
prefix="/home/pi/logging"
$prefix/update_temp.sh
$prefix/update_temp_owfs.sh
$prefix/hwmonitor/update_data.sh
$prefix/update_lux.sh
