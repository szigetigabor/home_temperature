#!/bin/bash
prefix=$(dirname $0)
$prefix/update_temp.sh
$prefix/update_temp_owfs.sh
$prefix/send_to_gdocs.sh &
$prefix/update_volt.sh
$prefix/hwmonitor/update_data.sh
$prefix/update_lux.sh
