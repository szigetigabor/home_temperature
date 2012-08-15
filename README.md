home_temperature
================

At the moment it is fixed at GPIO 4, maybe the new Linux pinctrl concept will change this and then this patch is not needed anymore, but if you want to access some one-wire chips, you can use this patch until then.

Installation:
   * install Debian to the Raspberry
   * you can install my pre-compiled kernel and modules like this on the Debian image (login as root, or do a "sudo bash" if logged in as "pi") :
      * start scripts/install.sh
      * after the reboot start the scripts/install2.sh
      

 Enjoy your 1-Wire devices.