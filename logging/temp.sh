#!/bin/bash
echo Content-type: text/html
echo

sensors=`cat /sys/bus/w1/devices/w1_bus_master1/w1_master_slaves;`

message="</b><table>"
for line in $sensors
do
  value=`cat /sys/bus/w1/devices/w1_bus_master1/$line/w1_slave|tail -1|cut -d"=" -f2;`  
  l=`expr length $value`
  up=`echo $value|cut -c1-$(($l-3));`
  down=`echo $value|cut -c $(($l-2))-;`
  message=$message"<tr><td><a href=temp_graphs/"$line">"$line"</a>: </td><td><b>$up,$down "C"</b></td></tr>"
done

message=$message"</table><b>"
echo "document.write(\"$message\");"
