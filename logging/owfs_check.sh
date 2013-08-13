#!/bin/bash
PROCESS="owfs"

PIDS=`pidof $PROCESS`

if [ -z "$PIDS" ]; then
  echo "Process ($PROCESS) is not running." 1>&2
  sudo owfs --i2c=ALL:ALL /mnt/1wire/
  echo "Process ($PROCESS) restarted." 1>&2  
else
  echo "Process ($PROCESS) is running."
  for PID in $PIDS; do
    echo $PID
  done
fi
