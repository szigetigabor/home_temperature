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

####################################
#  read the current switch status  #
####################################
status=`dd if=$switch_output bs=1 count=1|hexdump|head -1`
status=${status:10:2};

status_bin=`echo "obase=2; ibase=16; $status" | bc`

prefix=""
for (( c=${#status_bin}; c<8; c++ ))
do
   prefix="0"$prefix
done
status_bin=$prefix$status_bin

#echo $status_bin

#######################
#  set the new value  #
#######################
next_value_bin=$status_bin
next_value_bin=`echo $next_value_bin | sed s/./$value/$port`

#echo $next_value_bin

next_value=`echo "obase=10; ibase=2; $next_value_bin" | bc`
#echo $next_value

# POSIX
# chr() - converts decimal value to its ASCII character representation
# ord() - converts ASCII character to its decimal value

chr() {
  [ ${1} -lt 256 ] || return 1
  printf \\$(printf '%03o' $1)
}

ord() {
  LC_CTYPE=C printf '%d' "'$1"
}

next_value=`chr $next_value`
echo `echo \$next_value` |dd of=$switch_output bs=1 count=1

#dd if=$switch_output bs=1 count=1|hexdump

