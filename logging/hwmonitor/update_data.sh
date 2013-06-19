#/bin/bash
 
rrdtool="/usr/bin/rrdtool"
DB="health_db.rrd";

health_info() {
  #_TOP=`top -bn1|head -1`
#echo $_TOP

  _MbTemp=0;
  _CpuTemp=`vcgencmd measure_temp|cut -f 2 -d "="| cut -f 1 -d "'"`;
  _CpuUuser=`top -bn1|head -3|tail -n +3|cut -f 1 -d ,|cut -f 2 -d ' '`;
  _CpuUsys=`top -bn1|head -3|tail -n +3|cut -f 2 -d ,|cut -f 3 -d ' '`;
  _VCore=`vcgencmd measure_volts core|cut -f 2 -d "="|cut -f 1 -d "V"`;
  _Plus12V=0;
  _Plus3V=0;
  _Plus5V=0;
  _Neg12V=0;

  _CpuSpeed=`vcgencmd measure_clock arm|cut -f 2 -d "="`;
  _CpuSpeed=`expr $_CpuSpeed / 1000000`;

  _GpuSpeed=`vcgencmd measure_clock core|cut -f 2 -d "="`;
  _GpuSpeed=`expr $_GpuSpeed / 1000000`;

  _LoadAvg=`top -bn1|head -1|cut -f 4 -d ,|cut -f 5 -d ' '`;

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



