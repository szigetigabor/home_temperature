#!/bin/bash
prefix="/home/pi/logging"
$prefix/update_temp.sh
$prefix/hwmonitor/update_data.sh
$prefix/update_lux.sh
