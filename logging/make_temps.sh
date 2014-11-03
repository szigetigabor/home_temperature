#!/bin/bash
prefix=$(dirname $0)
source $prefix/config_temp.sh

if [ ! -e $www_path ]; then
    sudo mkdir $www_path
    sudo chmod 777 $www_path
fi

for line in $sensors
do
  if [ `echo $line|cut -c1-3` != "28-" ]; then
     continue
  fi
  if [ ! -e $www_path/$line ]; then
      sudo mkdir $www_path/$line
      sudo chown www-data:www-data $www_path/$line
  fi
  DB=$db_prefix"_"$line.rrd
  if [ -e  $DB ]; then
      continue
  fi
  rrdtool create $DB --start N --step 60 \
  DS:temp:GAUGE:600:U:U \
  RRA:AVERAGE:0.5:1:60 \
  RRA:AVERAGE:0.5:2:800 \
  RRA:AVERAGE:0.5:13:800 \
  RRA:AVERAGE:0.5:56:800 \
  RRA:AVERAGE:0.5:657:800
done

