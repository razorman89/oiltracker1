#! /usr/bin/env python

#IMPORTS
import time, os, sys, getopt, MySQLdb

#FUNCTION DECLARATIONS
def readTemp():
	
	tfile = open(SYSTEM_BUS)			#Open device file
	text = tfile.read()				#Read all of the text in the file.
	tfile.close()					#Close the file now that the text has been read.
	secondline = text.split("\n")[1]		#Split the text with new lines (\n) and select the second line.
	temperaturedata = secondline.split(" ")[9]	#Split the line into words, referring to the spaces, and select the 10th word of the second line
	temperature = float(temperaturedata[2:])	#The first two characters are "t=", so get rid of those and convert the temperatureData to a float
	temperature /= 1000				#Put the decimal point in the right place and display temperature.
	timeStamp = (int(os.popen('date +%s%N').readline()) / 1000000)
	cur.execute(insTempQry, (timeStamp, temperature))
	db.commit()
#	print '\tTIMESTAMP {}, TEMPERATURE = {}'.format(timeStamp, temperature)

#SYSTEM COMMANDS
os.system ('sudo modprobe w1-gpio')
os.system ('sudo modprobe w1-therm')

#DATABASE SETUP, QUERIES & PRE-STATEMENTS
db  = MySQLdb.connect(host = 'localhost', user = 'root', passwd = '7920979v', db = 'project2013')
cur = db.cursor()
db.autocommit = False
insTempQry = "INSERT INTO `temp` (`tstamp`, `tempdc`) VALUES (%s, %s);"
getSysAdminQry = "SELECT `tsleep` FROM `sysadmin`;"

#CONSTANTS
IDLE_STATE, READ_STATE, WAIT_STATE = range(0, 3)
SYSTEM_BUS = '/sys/bus/w1/devices/28-0000040dba3e/w1_slave'

#VARIABLES
isTimedOut = 1
tSleep	= 0
tNow	= 0
tWait	= 0
tTemp	= 0
tTimeOutStart = 0
tTimeOut = 0
tMaxRead = 10
state = IDLE_STATE

while True:

        if state == IDLE_STATE:
#		print 'IDLE'
		if isTimedOut == 1:
			isTimedOut = 0
			cur.execute(getSysAdminQry)
			row = cur.fetchone()
			if row:
				tTemp = row[0]
				if (tTemp != tWait):
					tWait = tTemp
					tSleep = time.time() + tWait
#					print 'UPDATED: {}'.format(tWait)

			db.commit()
		state = WAIT_STATE

	elif state == READ_STATE:
#		print 'READ'
		readTemp()
		tSleep = time.time() + tWait
		state = IDLE_STATE

	elif state == WAIT_STATE:
#		print 'WAIT'
		tTimeOutStart = int(time.time())
		tTimeOut = tTimeOutStart + tMaxRead
		tNow = time.time()
		while tNow < tSleep:
			tNow = time.time()
			if tNow >= tTimeOut:
				state = IDLE_STATE
				isTimedOut = 1
				break
		
		if tNow >= tSleep:
#			print '----- FINISHED WAITING -----'
			state = READ_STATE
