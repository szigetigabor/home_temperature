#!/bin/bash

########################
# Script paramters:    #
#   $1 Device ID       #
#   $2 hexa (optional) #
########################

if [ $# -lt 1 ]
then 
  echo "Syntax Error, Please provide at least one device id and one optional hexa parameter."
  exit 1
fi

device_id=$1
output_type="binary"
if [ $# -gt 1 ]
then
  output_type=$2
fi

switch_output="/sys/bus/w1/devices/$device_id/output"

####################################
#  read the current switch status  #
####################################
read_status() {
    # 1st parameter: path of the output's file
    status=`dd if=$1 bs=1 count=1 2>>/dev/null|hexdump|head -1`
    status=${status:10:2}
    status=`echo "${status^^}"`

    echo $status
}

read_binary_status() {
    # 1st parameter: path of the output's file
    nr=0
    limit=5
    previous_value="N/A"
    while [ -z "${status}" ] || [ $nr -le $limit ];
    do
      # read the status from the switch
      status=`read_status $1`

      #error handling
      if [ -z "${status}" ]; then
        continue
      fi
      let "nr=nr+1"
      if [ $status == "FF" ]; then
        continue
      fi
      if [ "$previous_value" == "$status" ];
      then
        break
      fi
      previous_value=$status
      # end of the error handling
    done

    if [ $output_type == "hexa" ]
    then
      echo $status
      exit 0
    fi
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

