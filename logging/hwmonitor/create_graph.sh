#/bin/bash

### change to the script directory
cd /home/pi/logging/hwmonitor

## Graph for last 24 hours 
rrdtool graph /var/www/temp_graphs/health_of_system.png \
-w 785 -h 151 -a PNG \
--slope-mode \
--logarithmic --units=si \
--start end-86400 --end now \
--font DEFAULT:7: \
--title "system health" \
--watermark "`date`" \
--vertical-label "hw.sensors" \
--right-axis-label "speeds" \
--right-axis 100:0 \
--x-grid MINUTE:10:HOUR:1:MINUTE:120:0:%R \
--alt-y-grid --rigid \
DEF:MbTemp=health_db.rrd:MbTemp:MAX \
DEF:CpuTemp=health_db.rrd:CpuTemp:MAX \
DEF:CpuUuser=health_db.rrd:CpuUuser:MAX \
DEF:CpuUsys=health_db.rrd:CpuUsys:MAX \
DEF:VCore=health_db.rrd:VCore:MAX \
DEF:Plus12V=health_db.rrd:Plus12V:MAX \
DEF:Plus3V=health_db.rrd:Plus3V:MAX \
DEF:Plus5V=health_db.rrd:Plus5V:MAX \
DEF:Neg12V=health_db.rrd:Neg12V:MAX \
DEF:CpuSpeed=health_db.rrd:CpuSpeed:MAX \
DEF:GpuSpeed=health_db.rrd:GpuSpeed:MAX \
DEF:LoadAvg=health_db.rrd:LoadAvg:MAX \
CDEF:scaled_CpuUuser=CpuUuser,0.01,* \
CDEF:scaled_CpuUsys=CpuUsys,0.01,* \
CDEF:scaled_CpuSpeed=CpuSpeed,0.01,* \
CDEF:scaled_GpuSpeed=CpuSpeed,0.01,* \
LINE1:MbTemp#009900:"MB Temp " \
GPRINT:MbTemp:LAST:"Cur\: %5.2lf" \
GPRINT:MbTemp:AVERAGE:"Avg\: %5.2lf" \
GPRINT:MbTemp:MAX:"Max\: %5.2lf" \
GPRINT:MbTemp:MIN:"Min\: %5.2lf\t\t" \
LINE1:scaled_CpuUuser#FF9900:"CPU % (user) " \
GPRINT:CpuUuser:LAST:"Cur\: %5.2lf" \
GPRINT:CpuUuser:AVERAGE:"Avg\: %5.2lf" \
GPRINT:CpuUuser:MAX:"Max\: %5.2lf" \
GPRINT:CpuUuser:MIN:"Min\: %5.2lf\n" \
LINE1:CpuTemp#00D600:"CPU temp" \
GPRINT:CpuTemp:LAST:"Cur\: %5.2lf" \
GPRINT:CpuTemp:AVERAGE:"Avg\: %5.2lf" \
GPRINT:CpuTemp:MAX:"Max\: %5.2lf" \
GPRINT:CpuTemp:MIN:"Min\: %5.2lf\t\t" \
LINE1:scaled_CpuUsys#FF1A00:"CPU % (system) " \
GPRINT:CpuUsys:LAST:"Cur\:  %5.2lf" \
GPRINT:CpuUsys:AVERAGE:"Avg\:  %5.2lf" \
GPRINT:CpuUsys:MAX:"Max\:  %5.2lf" \
GPRINT:CpuUsys:MIN:"Min\:  %5.2lf\n" \
LINE1:VCore#D600D6:"CPU 1.1V" \
GPRINT:VCore:LAST:"Cur\: %5.2lf" \
GPRINT:VCore:AVERAGE:"Avg\: %5.2lf" \
GPRINT:VCore:MAX:"Max\: %5.2lf" \
GPRINT:VCore:MIN:"Min\: %5.2lf\t\t" \
LINE1:scaled_CpuSpeed#FF0066:"CPU Freq" \
GPRINT:CpuSpeed:LAST:"Cur\: %5.2lf" \
GPRINT:CpuSpeed:AVERAGE:"Avg\: %5.2lf" \
GPRINT:CpuSpeed:MAX:"Max\: %5.2lf" \
GPRINT:CpuSpeed:MIN:"Min\: %5.2lf\n" \
LINE1:scaled_GpuSpeed#FF0066:"GPU Freq" \
GPRINT:GpuSpeed:LAST:"Cur\: %5.2lf" \
GPRINT:GpuSpeed:AVERAGE:"Avg\: %5.2lf" \
GPRINT:GpuSpeed:MAX:"Max\: %5.2lf" \
GPRINT:GpuSpeed:MIN:"Min\: %5.2lf\n" \
LINE1:Plus12V#990099:"+12V    " \
GPRINT:Plus12V:LAST:"Cur\: %5.2lf" \
GPRINT:Plus12V:AVERAGE:"Avg\: %5.2lf" \
GPRINT:Plus12V:MAX:"Max\: %5.2lf" \
GPRINT:Plus12V:MIN:"Min\: %5.2lf\n" \
LINE1:Plus3V#99004D:"+3.3V   " \
GPRINT:Plus3V:LAST:"Cur\: %5.2lf" \
GPRINT:Plus3V:AVERAGE:"Avg\: %5.2lf" \
GPRINT:Plus3V:MAX:"Max\: %5.2lf" \
GPRINT:Plus3V:MIN:"Min\: %5.2lf\n" \
LINE1:Plus5V#4D0099:"+5V     " \
GPRINT:Plus5V:LAST:"Cur\: %5.2lf" \
GPRINT:Plus5V:AVERAGE:"Avg\: %5.2lf" \
GPRINT:Plus5V:MAX:"Max\: %5.2lf" \
GPRINT:Plus5V:MIN:"Min\: %5.2lf\n" \
LINE1:Neg12V#330066:"Users Nr" \
GPRINT:Neg12V:LAST:"Cur\: %5.0lf" \
GPRINT:Neg12V:AVERAGE:"Avg\: %5.0lf" \
GPRINT:Neg12V:MAX:"Max\: %5.0lf" \
GPRINT:Neg12V:MIN:"Min\: %5.0lf\n" \
LINE1:LoadAvg#0000FF:"Load Avg" \
GPRINT:LoadAvg:LAST:"Cur\: %5.2lf" \
GPRINT:LoadAvg:AVERAGE:"Avg\: %5.2lf" \
GPRINT:LoadAvg:MAX:"Max\: %5.2lf" \
GPRINT:LoadAvg:MIN:"Min\: %5.2lf\n" \



