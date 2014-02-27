#!/usr/bin/env python

import sqlite3

import os
import sys
import time
import glob
import calendar

# global variables
speriod=(15*60)-1
dbname='templog.db'



# store the temperature in the database
def log_temperature( id, temp):

    conn=sqlite3.connect(dbname)
    curs=conn.cursor()

    timev=calendar.timegm(time.gmtime())

    curs.execute("INSERT INTO temps values(datetime('now'), (?), (?))", (id,temp,))
    #curs.execute("INSERT INTO temps2 values((?), (?), (?))", (timev,id,temp,))

    # commit the changes
    conn.commit()

    conn.close()


# display the contents of the database
def display_data():

    conn=sqlite3.connect(dbname)
    curs=conn.cursor()

    for row in curs.execute("SELECT * FROM temps"):
        print str(row[0])+"	"+str(row[1])

    conn.close()



# get temerature
# returns None on error, or the temperature as a float
def get_temp(devicefile):

    try:
        fileobj = open(devicefile,'r')
        lines = fileobj.readlines()
        fileobj.close()
    except:
        return None

    # get the status from the end of line 1 
    status = lines[0][-4:-1]

    # is the status is ok, get the temperature from line 2
    if status=="YES":
        print status
        tempstr= lines[1][-6:-1]
        tempvalue=float(tempstr)/1000
        print tempvalue
        return tempvalue
    else:
        print "There was an error."
        return None



# main function
# This is where the program starts 
def main( id, temperature):

    log_temperature( id, temperature)

        # display the contents of the database
#        display_data()

#        time.sleep(speriod)


if __name__=="__main__":
    main( sys.argv[1], sys.argv[2])




