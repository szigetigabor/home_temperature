#!/usr/bin/python

import subprocess
import re
import os
import sys
import time
import datetime
import gspread

directory="/home/pi/logging"

# ===========================================================================
# Google Account Details
# ===========================================================================

# Account details for google docs
email       = 'xx@gmail.com'
password    = 'xx'
spreadsheet = 'xx'

# ===========================================================================
# Example Code
# ===========================================================================


# Login with your Google account
try:
  gc = gspread.login(email, password)
except:
  print "Unable to log in.  Check your email address/password"
  sys.exit()

# Open a worksheet from your spreadsheet using the filename
try:
  worksheets = gc.open(spreadsheet)
  # Alternatively, open a spreadsheet using the spreadsheet's key
  # worksheet = gc.open_by_key('0BmgG6nO_6dprdS1MN3d3MkdPa142WFRrdnRRUWl1UFE')
except:
  print "Unable to open the spreadsheet.  Check your filename: %s" % spreadsheet
  sys.exit()


# Continuously append data
for root, dirnames, filenames in os.walk(directory):
  for dirname in dirnames:
    if (dirname[0:2] != "28"):
      continue

    # Run the rrdtool program to get the temperature
    temp = subprocess.check_output([directory+"/get_value_from_rrd.sh", dirname]);
    #print dirname+" "+temp
    temp = float(temp)
    #print "Temperature: %.3f C" % temp
 
    # Create sheet if not exist
    try:
      worksheet = worksheets.worksheet(dirname)
    except:
      worksheets.add_worksheet(dirname,1,2)
      worksheet = worksheets.worksheet(dirname)
 
    # Append the data in the spreadsheet, including a timestamp
    try:
      values = [datetime.datetime.now(), temp]
      worksheet.append_row(values)
      print "Wrote a row to "+spreadsheet+" ("+dirname+")"
    except:
      print "Unable to append data.  Check your connection?"
      sys.exit()

