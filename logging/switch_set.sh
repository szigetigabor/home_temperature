#!/bin/bash

########################
# Script paramters:    #
#   $1 Device ID       #
#   $2 port number     #
#   $3 value           #
########################

if [ $# -lt 3 ]
then 
  echo "Syntax Error, Please provide at least one device id, port number, new value: {0, 1}"
  exit 1
fi

prefix=$(dirname $0)
source $prefix/config_temp.sh

script_path=$sensor_settings_path #"/home/pi/logging"

device_id=$1
port=$2
value=$3

if [ $port -lt 1 ] || [ $port -gt 8 ]; then
  echo "Wrong port value! [1,...,8]"
  exit 1
fi
let "port= 9-$port"

if [ $value -ne "0" ] && [ $value -ne "1" ]; then
  echo "Wrong value! [0,1]"
  value=0
fi

# Change the status value because it works another on the relay board
if [ $value -eq "0" ]; then
  value=1
else
  value=0
fi

switch_output="/sys/bus/w1/devices/$device_id/output"
grant=`ls -l $switch_output`

if [ `echo ${grant:7:2}` == "rw" ]; then
    echo "Output writeable."
else
    echo "Add write access for the other Group."
    chmod og=rw $switch_output
fi

####################################
#  read the current switch status  #
####################################
status_bin=`$script_path/switch_read.sh $device_id`
echo $status_bin

Pport=0
let "Pport=$port-1"
if [ ${status_bin:${Pport}:1} -eq $value ]; then
  echo "Current status is same as the new. Nothing to do."
  exit 0
fi

#######################
#  set the new value  #
#######################
next_value_bin=$status_bin

# change the port's value
next_value_bin=`echo $next_value_bin | sed s/./$value/$port`
echo $next_value_bin

# convert binary to hexa
next_value=`echo "obase=16; ibase=2; $next_value_bin" | bc`

control_status="N/A"
Nnext_value=$next_value
# This string manipulation need for the while condition
if [ ${#next_value} -eq 1 ]; then
    Nnext_value="0"$next_value
fi

nr=0
limit=5
while [ "$control_status" != "$Nnext_value" ] || [ $nr -le $limit ]; do
    echo "write: $Nnext_value"
    # write to the switch
    echo -e '\x'`echo $next_value` |dd of=$switch_output bs=1 count=1 2>>/dev/null

    # read back the new status
    control_status=`$script_path/switch_read.sh $device_id hexa`
    echo "read: $control_status"
    let "nr=nr+1"
    sleep 1
done

# Turn ON/OFF the main pump
gpio=11
if [ "$next_value" == "FF" ]
then
    echo "Pump turn OFF."
    cmd="nohup sudo $sensor_settings_path/main_pump.py $gpio false"
    $cmd &
else
    echo "Pump turn ON."
    cmd="nohup sudo $sensor_settings_path/main_pump.py $gpio true"
    $cmd &
fi
exit 0
