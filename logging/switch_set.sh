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

device_id=$1
port=$2
value=$3

if [ $port -lt 1 ] || [ $port -gt 8 ]; then
  echo "Wrong port value! [1,...,8]"
  exit 1
fi

if [ $value -ne "0" ] && [ $value -ne "1" ]; then
  echo "Wrong value! [0,1]"
  value=0
fi

switch_output="/sys/bus/w1/devices/$device_id/output"

if [ -w $switch_output ]; then
    echo "Output writeable."
else
    echo "Add write access for the other Group."
    chmod og=rw $switch_output
fi

####################################
#  read the current switch status  #
####################################
read_status() {
    # 1st parameter: path of the output's file
    status=`dd if=$1 bs=1 count=1|hexdump|head -1`
    status=${status:10:2}
    status=`echo "${status^^}"`

    echo $status
}

read_binary_status() {
    # 1st parameter: path of the output's file
    status=`read_status $1`

    # convert hexa to binary
    binary_status=`echo "obase=2; ibase=16; $status" | bc`

    prefix=""
    for (( c=${#binary_status}; c<8; c++ ))
    do
       prefix="0"$prefix
    done
    binary_status=$prefix$binary_status

    echo $binary_status
}

status_bin=`read_binary_status $switch_output`
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
next_value_bin=`echo $next_value_bin | sed s/./$value/$port`

# change the port's value
echo $next_value_bin | sed s/./$value/$port
# convert binary to hexa
next_value=`echo "obase=16; ibase=2; $next_value_bin" | bc`

control_status="N/A"
Nnext_value=$next_value
# This string manipulation need for the while condition
if [ ${#next_value} -eq 1 ]; then
    Nnext_value="0"$next_value
fi

while [ "$control_status" != "$Nnext_value" ]; do
    echo "write: $Nnext_value"
    # write to the switch
    echo -e '\x'`echo $next_value` |dd of=$switch_output bs=1 count=1

    # read back the new status
    control_status=`read_status $switch_output`
    echo "read: $control_status"
    sleep 1
done

# Turn ON/OFF the main pump
if [ "$next_value" == "0" ]
then
    echo "Pump turn OFF."
else
    echo "Pump turn ON."
fi
