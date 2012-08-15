#!/usr/bin/python

import cgi
import os
import re

temps={}
path="/sys/bus/w1/devices"
devlist=os.listdir(path)
for dev in devlist:
  if dev !="w1_bus_master1" and dev !=".":
    tf=open("%s/%s/w1_slave"%(path, dev))
    null=tf.readline()
    temp=tf.readline()
    r=re.match(r'.*t=(\d+)$', temp)
    if r:
      temps[dev]=float(r.group(1))/1000
    tf.close()

print "Content-Type: text/html"
print
print "<html><head><title>Temperature</title></head><body><h1>Temperature</h1>"
print "<dl>"

for t in temps:
  print "<dt>%s</dt><dd>%s C</dd>"%(t,temps[t])
print "</dl></body></html>"


