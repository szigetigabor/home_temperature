#!/bin/bash
prefix=$(dirname $0)
source $prefix/config_temp.sh

cd $sensor_settings_path

JSON="["

#temperatures
IDS=`ls -d */|grep 2`

for id in $IDS
do
  # remove last character from the ID
  id=`echo "${id%?}"`

  DB=$db_prefix"_"$id.rrd
  lastMod=`date -r $DB +%F\ %r%:z`
  alias=`cat $id/alias 2> /dev/null`
  alarm=`cat $id/alarm 2> /dev/null`
  lastValue=`rrdtool lastupdate $DB|tail -1|cut -c 13-`

  JSON=`echo $JSON"{"`
  # Fill the JSON
  JSON=`echo $JSON"id:"$id","`
  JSON=`echo $JSON"Last value:"$lastValue","`
  JSON=`echo $JSON"Alias:"$alias","`
  JSON=`echo $JSON"Alarm value:"$alarm","`
  JSON=`echo $JSON"Last modification:"$lastMod`
  JSON=`echo $JSON"},"`
done

#


JSON=`echo $JSON"]"`

echo $JSON
