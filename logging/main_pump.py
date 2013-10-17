#!/usr/bin/python
import os
import sys
import time
import RPi.GPIO as GPIO
GPIO.setmode(GPIO.BOARD)

mypid = os.getpid()
myfile = sys.argv[0][2:]

# Get the other running scripts
g = os.popen("ps -ef | grep " + myfile + " | grep -v grep | awk '{ print $2 }'")
pids = g.readlines()
if sys.argv[1] != "stop" :
  #Remove own pid from the list
  pids.remove( str(mypid) + "\n" )

print "%s" % (pids)

# Kill all running process
for pid in pids:
  try:
    os.kill(int(pid[:-1]), 9)
  except OSError:
    exit 

pin    = int(sys.argv[1])
on_off = str(sys.argv[2]).lower()
if on_off in ['true', '1', 't', 'y', 'yes']:
   on_off=True
else:
   on_off=False


GPIO.setup(pin, GPIO.OUT)

while True:
  print "LED %s" % (on_off)
  GPIO.output(pin, on_off)
  time.sleep(10)

#GPIO.cleanup()
