#/bin/bash

### change to the script directory
cd /home/pi/logging/

port=$1
rrdtool create pressure_$port.rrd \
--step 60 \
DS:Temp:GAUGE:120:0:10000000000000 \
DS:Press:GAUGE:120:0:10000000000000 \
DS:Alt:GAUGE:120:0:10000000000000 \
RRA:AVERAGE:0.5:1:60 \
RRA:AVERAGE:0.5:2:800 \
RRA:AVERAGE:0.5:13:800 \
RRA:AVERAGE:0.5:56:800 \
RRA:AVERAGE:0.5:657:800 


