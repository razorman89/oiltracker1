#! /usr/bin/env python

#IMPORTS
import sys, os, getopt, datetime, time, MySQLdb
import RPi.GPIO as GPIO
from serial import Serial, EIGHTBITS, STOPBITS_ONE, PARITY_NONE, STOPBITS_ONE

#GPIO SETTINGS
GPIO.setmode(GPIO.BCM)                                  #BCM GPIO numbering
GPIO.setup(10, GPIO.IN)                                 #GPIO pin 10 is flow meter input.
GPIO.add_event_detect(10, GPIO.BOTH)    #enable edge detection events, can be RISING, FALLING or BOTH

#STATES
IDLE_STATE, READING_DATA_STATE, PROFILE_STATE = range(0, 3)

#DATABASE SETUP & QUERIES
db  = MySQLdb.connect(host = 'localhost', user = 'root', passwd = '7920979v', db = 'project2013')
cur = db.cursor()
getSysAdminQry          = "SELECT `isprofiled`, `fuelppl` FROM sysadmin;"
insProfileQry           = "INSERT INTO profile (`tstamp`, `litres`, `range`) VALUES (%s, %s, %s);"
getProfileQry           = "SELECT `tstamp`, `litres`, `range` FROM profile;"
db.autocommit = True

isProfiled = 0
fuelPpl = 0

cur.execute(getSysAdminQry)
rows = cur.fetchone()

if rows:
        isProfiled = rows[0]
        fuelPpl = rows[1]
print 'STATUS: {}, FUEL PRICE: {}'.format(isProfiled, fuelPpl)

cur.execute(insProfileQry %(1.23, 2.13, 3.12))

val1 = 0
val2 = 0
val3 = 0

cur.execute(getProfileQry)
rows = cur.fetchone()

if rows:
        val1 = rows[0]
        val2 = rows[1]
	val3 = rows[2]

print 'VAL1: {}, VAL2: {}, VAL3 {}'.format(val1, val2, val3)

#db.commit()
cur.close()
db.close()
sys.exit(0)
