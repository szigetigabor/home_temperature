#!/bin/bash
prefix=$(dirname $0)
source $prefix/config_temp.sh

cd $sensor_settings_path

FILTER="all"

if [ $# -gt 0 ]; then
  FILTER=$1
fi


temperature() {
    Values=`./api_temp.sh`
    echo $Values
}

adc() {
    Values=`./api_adc.sh`
    echo $Values
}

status() {
    Values=`./api_status.sh`
    echo $Values
}  


JSON="["
case "$FILTER" in
     temp)
         TEMPS=`temperature`
         JSON=`echo $JSON$TEMPS`
         ;;

     adc)
         ADC=`adc`
         JSON=`echo $JSON$ADC`
         ;;

     status)
         STATUS=`status`
         JSON=`echo $JSON$STATUS`
         ;;

     *)
         TEMPS=`temperature`
         TEMPS=`echo "{\"temperature\": ["$TEMPS"]}"`
         ADC=`adc`
         ADC=`echo "{\"adc\": ["$ADC"]}"`
         STATUS=`status`
         STATUS=`echo "{\"status\": ["$STATUS"]}"`

         JSON=`echo -e $JSON$TEMPS",\n"$ADC",\n"$STATUS`

esac

JSON=`echo $JSON"]"`

echo $JSON
