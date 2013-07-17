#!/bin/bash

sensor_settings_path="/home/pi/logging"
sensors_path="/sys/bus/w1/devices/w1_bus_master1/w1_master_slaves"
sensors_owfs_path="/tmp/1wire"

www_path="/var/www/temp_graphs"
colors=( "#0000FF" "#CCCCCC" "#00FF00"  "#FF0000" "#8800FF" "#00FFFF" "#888800" "#008888" "#FF00FF" "#123456" )

sensors=`cat $sensors_path;`
sensors_owfs=`ls $sensors_owfs_path;`
sensors_all=`cat $sensors_path; ls $sensors_owfs_path;`
