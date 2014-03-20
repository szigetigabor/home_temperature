#/bin/bash

# Parameters:
#   * $1: sensor ID

deviceID=$1;

prefix=$(dirname $0)
source $prefix/config.sh

main_mode=`cat $sensor_settings_path/current_mode;`
#echo "Main mode: ".$main_mode
alarm=5;    #freeze temperature
if [ $main_mode == "OFF" ]; then
    echo $alarm

elif [ $main_mode == "Manual" ]; then
    if [ ! -e $sensor_settings_path/$deviceID/alarm ]; then
        echo $alarm
    else
        alarm_from_file $deviceID
    fi
    
elif [ $main_mode == "Auto" ]; then
    if [ ! -e $sensor_settings_path/$deviceID/mode ]; then
        #echo "No mode set for the following device: $deviceID"
        #echo "Use the alarm value for this device."
        alarm_from_file $deviceID
    else
        mode=`cat $sensor_settings_path/$deviceID/mode;`
        if [ "$mode" == "" ]; then
          alarm_from_file $deviceID
          exit 0
        fi
        mode_value=`cat $sensor_settings_path/modes/$mode;`

        # split the mode valus to array
        delimiter=" "
        declare -a arr 
        arr=(`echo ${mode_value//$delimiter/ };`);

        switching_time=${arr[0]}
        off_temp=`echo ${arr[1]/*=/ }`
        for i in `seq 1 $switching_time`;
        do
          let "index=5*($i-1)"
          #Turn ON time
          onh=${arr[$index+2]}
          if [ ${#onh} = 1 ]; then
            onh=0$onh
          fi
          onm=${arr[$index+3]}
          if [ ${#onm} = 1 ]; then
            onm=0$onm
          fi
          on=$onh:$onm

          #Turm OFF time
          offh=${arr[$index+4]}
          if [ ${#offh} = 1 ]; then
            offh=0$offh
          fi
          offm=${arr[$index+5]}
          if [ ${#offm} = 1 ]; then
            offm=0$offm
          fi
          off=$offh:$offm
          on_temp=`echo ${arr[$index+6]/*=/ }`
          #echo $on-$off $on_temp
          current_time=`date +"%H:%M"`

          #Is current time inside this time interval?
          if [[ "$on" < "$current_time" ]] && 
             [[ "$current_time" < "$off" ]]; then
            echo $on_temp
            exit 0
          fi
        done
        echo $off_temp
        exit 0
    fi
else
    echo $alarm
fi

