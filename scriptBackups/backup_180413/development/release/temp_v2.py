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
	print '\tTIMESTAMP {}, TEMPERATURE = {}'.format(timeStamp, temperature)

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
SYSTEM_BUS = '/sys/bus/w1/devices/28-0000040dba3e/w1_slave'

#VARIABLES
tSleep = 1

while True:

        time_1 = time.time();
	cur.execute(getSysAdminQry)
	row = cur.fetchone()
	if row:
		tSleep = row[0]
		print '\n -- tSleep: {}'.format(tSleep)

	readTemp()
	time_2 = time.time()
	if (time_2 - time_1) < tSleep:
		no_of_sleeps = int(round((tSleep - (time_2 - time_1)) / 0.1))
		for i in range(no_of_sleeps):
			time.sleep(0.1)
