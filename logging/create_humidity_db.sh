#!/bin/bash
prefix=$(dirname $0)
source $prefix/config_humidity.sh

DB=$db_prefix"_"$1".rrd"
if [ -e  $DB ]; then
    exit 0
fi

rrdtool create $DB --start N --step 60 \
DS:hum:GAUGE:600:U:U \
RRA:AVERAGE:0.5:1:60 \
RRA:AVERAGE:0.5:2:800 \
RRA:AVERAGE:0.5:13:800 \
RRA:AVERAGE:0.5:56:800 \
RRA:AVERAGE:0.5:657:800

