#!/usr/bin/env python
import time
from array import *
import os, sys
import RPi.GPIO as GPIO

#
# Usage: mcp3008_read.py <channel nr> [<output type> <input voltage>]
#        output types: lux, volt
#

GPIO.setmode(GPIO.BCM)
DEBUG = 0

# read SPI data from MCP3008 chip, 8 possible adc's (0 thru 7)
def readadc(adcnum, clockpin, mosipin, misopin, cspin):
        if ((adcnum > 7) or (adcnum < 0)):
                return -1
        GPIO.output(cspin, True)

        GPIO.output(clockpin, False)  # start clock low
        GPIO.output(cspin, False)     # bring CS low

        commandout = adcnum
        commandout |= 0x18  # start bit + single-ended bit
        commandout <<= 3    # we only need to send 5 bits here
        for i in range(5):
                if (commandout & 0x80):
                        GPIO.output(mosipin, True)
                else:
                        GPIO.output(mosipin, False)
                commandout <<= 1
                GPIO.output(clockpin, True)
                GPIO.output(clockpin, False)

        adcout = 0
        # read in one empty bit, one null bit and 10 ADC bits
        for i in range(12):
                GPIO.output(clockpin, True)
                GPIO.output(clockpin, False)
                adcout <<= 1
                if (GPIO.input(misopin)):
                        adcout |= 0x1

        GPIO.output(cspin, True)
        
        adcout >>= 1       # first bit is 'null' so drop it
        return adcout

# change these as desired - they're the pins connected from the
# SPI port on the ADC to the Cobbler
SPICLK = 18
SPIMISO = 23
SPIMOSI = 24
SPICS = 25

# set up the SPI interface pins
GPIO.setup(SPIMOSI, GPIO.OUT)
GPIO.setup(SPIMISO, GPIO.IN)
GPIO.setup(SPICLK, GPIO.OUT)
GPIO.setup(SPICS, GPIO.OUT)

# LDR connected to adc #0
potentiometer_adc = int(sys.argv[1]);

# read the analog pin
prev_values = array('i',[])
diff = 5
for i in range(5):
  binary_value = readadc(potentiometer_adc, SPICLK, SPIMOSI, SPIMISO, SPICS)
  prev_values.append(binary_value)
  if i > 0 and abs(prev_values[i]-prev_values[i-1]) < diff:
    break

try:
    output_format = sys.argv[2]
except IndexError:
    output_format = ""

if output_format == "lux":
    # convert mV to lux
    if 0 < binary_value < 500:
        conversion_factor = 6.4
    elif 500 < binary_value < 700:
        conversion_factor = 6.4
    elif 700 < binary_value < 1200:
        conversion_factor = 17 

    lux = binary_value * conversion_factor

    print lux
elif output_format == "volt":
    input_voltage = 12.0
    try:
      input_voltage = float(sys.argv[3])
    except (IndexError, ValueError):
      input_voltage = 12.0
    reference = 3.3
    volt = (input_voltage * binary_value) / 1024 
    print volt
else:
    print binary_value

