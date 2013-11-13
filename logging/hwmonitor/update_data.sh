#!/bin/bash
 
rrdtool="/usr/bin/rrdtool"
DB="health_db.rrd";

health_info() {
  #_TOP=`top -bn1|head -1`
#echo $_TOP

  _VCGENCMD=`/opt/vc/bin/vcgencmd`;
  if [ -z $_VCGENCMD ]; then
    #_CpuTemp=`/opt/vc/bin/vcgencmd measure_temp|cut -f 2 -d "="| cut -f 1 -d "'"`;
    _VCore=`/opt/vc/bin/vcgencmd measure_volts core|cut -f 2 -d "="|cut -f 1 -d "V"`;
    _CpuSpeed=`/opt/vc/bin/vcgencmd measure_clock arm|cut -f 2 -d "="`;
    _GpuSpeed=`/opt/vc/bin/vcgencmd measure_clock core|cut -f 2 -d "="`;

  else
    _VCore=0;
    _CpuSpeed=`sudo cat /sys/devices/system/cpu/cpu0/cpufreq/cpuinfo_cur_freq`;
    _GpuSpeed=0;
  fi

  _MbTemp=0;
  _CpuTemp=`less /sys/class/thermal/thermal_zone0/temp`;
  _CpuTempfirst=`echo ${_CpuTemp:0:2}`;
  _CpuTemplast=`echo ${_CpuTemp:2:3}`;
  _CpuTemp=`echo $_CpuTempfirst.$_CpuTemplast`;


  _CpuUuserAll=`top -bn1|head -3|tail -n +3|cut -f 3 -d " "`;
  _CpuUuserAll=`top -bn1|head -3|tail -n +3|cut -f 2 -d "u"|cut -f 2 -d ":"`;
  _CpuUuserfirst=`echo $_CpuUuserAll|cut -f 1 -d ","`;
  _CpuUuserlast=`echo $_CpuUuserAll|cut -f 2 -d ","`;
  _CpuUuser=`echo "$_CpuUuserfirst.$_CpuUuserlast"`;


  _CpuUsysAll=`top -bn1|head -3|tail -n +3|cut -f 3 -d "s"`;
  _CpuUsysfirst=`echo ${_CpuUsysAll:1}|cut -f 1 -d ","`;
  _CpuUsyslast=`echo ${_CpuUsysAll:1}|cut -f 2 -d ","`;
  _CpuUsys=`echo "$_CpuUsysfirst.$_CpuUsyslast"`;

  _Plus12V=0;
  _Plus3V=0;
  _Plus5V=0;
  _Neg12V=`top -bn1|head -1|cut -f 8 -d " "`; #online users

  _CpuSpeed=`expr $_CpuSpeed / 1000000`;
  _GpuSpeed=`expr $_GpuSpeed / 1000000`;

  _LoadAvgAll=`top -bn1|head -1|cut -f 15 -d " "`;
  _LoadAvgfirst=`echo $_LoadAvgAll|cut -f 1 -d ","`;
  _LoadAvglast=`echo $_LoadAvgAll|cut -f 2 -d ","`;
  _LoadAvg=`echo "$_LoadAvgfirst.$_LoadAvglast"`;


  RETURN_VALUE=`echo $_MbTemp":"$_CpuTemp":"$_CpuUuser":"$_CpuUsys":"$_VCore":"$_Plus12V":"$_Plus3V":"$_Plus5V":"$_Neg12V":"$_CpuSpeed":"$_GpuSpeed":"$_LoadAvg`
}

### change to the script directory
cd /home/pi/logging/hwmonitor 

# Update database
if [ ! -e $DB ]; then
   ./initialize_database.sh
fi

### collect the data
health_info

echo $RETURN_VALUE
### update the database
$rrdtool update $DB --template MbTemp:CpuTemp:CpuUuser:CpuUsys:VCore:Plus12V:Plus3V:Plus5V:Neg12V:CpuSpeed:GpuSpeed:LoadAvg N:$RETURN_VALUE



