#!/bin/bash
prefix=$(dirname $0)
source $prefix/config_temp.sh

cd $sensor_settings_path

JSON=""

  STATUS="running"
  NAME="home temperature"

  JSON=`echo $JSON"{"`
  # Fill the JSON
  JSON=`echo $JSON"\"status\":\""$STATUS\"","`
  JSON=`echo $JSON"\"name\":\""$NAME\"`
  JSON=`echo $JSON"}"`


echo $JSON
