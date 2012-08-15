#!/bin/bash
cd /boot
wget http://www.frank-buss.de/raspberrypi/kernel-rpi-w1.tgz
tar -xzf kernel-rpi-w1.tgz
rm -f kernel-rpi-w1.tgz
cd /lib/modules
wget http://www.frank-buss.de/raspberrypi/modules-rpi-w1.tgz
tar -xzf modules-rpi-w1.tgz
rm -f modules-rpi-w1.tgz
sync
reboot
