#!/bin/bash
DB="lux.rrd"

if [ -e  $DB ]; then
    exit 0
fi

rrdtool create $DB --start N --step 60 \
DS:lux:GAUGE:600:U:U \
RRA:AVERAGE:0.5:1:12 \
RRA:AVERAGE:0.5:1:288 \
RRA:AVERAGE:0.5:12:168 \
RRA:AVERAGE:0.5:12:720 \
RRA:AVERAGE:0.5:288:365

