#! /usr/bin/env python

#IMPORTS
import sys, os, getopt, datetime, time, MySQLdb
import RPi.GPIO as GPIO
from serial import Serial, EIGHTBITS, STOPBITS_ONE, PARITY_NONE, STOPBITS_ONE

#GPIO SETTINGS
GPIO.setmode(GPIO.BCM)					#BCM GPIO numbering
GPIO.setup(10, GPIO.IN)					#GPIO pin 10 is flow meter input.
GPIO.add_event_detect(10, GPIO.BOTH)	#enable edge detection events, can be RISING, FALLING or BOTH

#STATES
IDLE_STATE, READING_DATA_STATE, PROFILE_STATE = range(0, 3)

#DATABASE SETUP & QUERIES
db			= MySQLdb.connect(host = 'localhost', user = 'root', passwd = '7920979v', db = 'project2013')
cur			= db.cursor() 
getSysAdminQry		= "SELECT `isprofiled`, `fuelppl` FROM sysadmin;"
getIsProfiledQry	= "SELECT `isprofiled` FROM sysadmin;"
getFuelPplQry		= 'SELECT `fuelppl` FROM sysadmin WHERE sysadminid = 1;'
getProfileMinDataQry	= "SELECT `litres`, `range`  FROM profile ORDER BY profileid ASC LIMIT 1;"
getProfileMaxDataQry	= "SELECT `litres`, `range`  FROM profile ORDER BY profileid DESC LIMIT 1;"
instSysAdminPrestate	= "INSERT INTO sysadmin (`isprofiled`, `fuelppl`) VALUES (%s, %s)"
instProfilePrestate	= "INSERT INTO profile (`tstamp`, `litres`, `range`) VALUES (%s, %s, %s)"
instStatsPrestate	= 'INSERT INTO stats (tstamp, currentLiters, currentLevel, ltrspm, costpm) VALUES (%s, %s, %s, %s, %s);'
clrProfilePrestate	= "DELETE FROM profile"
clrSysAdminPrestate	= "DELETE FROM sysadmin"
db.autocommit(True)

#CONSTANTS
START_BYTE_FLAG		= 'R'
END_BYTE_FLAG		= '\r'
DATA_LENGTH		= 5
PORT_NUM		= '/dev/ttyAMA0'
SERIAL_PORT		= Serial(port=PORT_NUM, baudrate=9600, stopbits=1, bytesize=8, timeout=0)
PULSES_PER_TENTH	= 71
PULSES_PER_LITRE	= 820

#VARIABLES
cnt		= 0
cntLitres	= 0
state		= IDLE_STATE
isProfiled	= 0

maxRange	= 0
minRange	= 0
maxLitres	= 0.0
minLitres	= 0.0
fuelCpl		= 1.0

#FUNCTION DECLARATIONS
def addSecs(date, secs):
	tm = date.time();
    	fulldate = datetime.datetime(100, 1, 1, tm.hour, tm.minute, tm.second)
    	fulldate = fulldate + datetime.timedelta(seconds=secs)
    	return fulldate.time()

def getLevel():
	#VARIABLES
	STBY_STATE, READING_SERIAL_STATE = range(0, 2)
	tag_buffer	= []
	dataCtr		= 0
	isRanged	= False
	fnState		= STBY_STATE

	SERIAL_PORT.open()	#open serial SERIAL_PORT

        if(SERIAL_PORT.isOpen() == False):
                SERIAL_PORT.open()

        SERIAL_PORT.flushInput()	#ensure serial input is clear
        while isRanged != True:		#read serial port

                data = SERIAL_PORT.read(1)     #read 1 byte only
                if fnState == STBY_STATE:

                        if data == START_BYTE_FLAG:
                                fnState = READING_SERIAL_STATE
                elif fnState  == READING_SERIAL_STATE:

                        if dataCtr == DATA_LENGTH:	#if dataCtr is greater then DATA_LENGTH then the data is invalid, so ignore
                                newData = "".join(tag_buffer[0:-2])	#build 'newData' from 'tag_buffer' contents
                                dataCtr = 0		#reset for next packet
                                tag_buffer = []	#empty tag buffer, to ready for new packet
                                isRanged = True

                        elif data == END_BYTE_FLAG:
                                fnState = STBY_STATE

                        elif data == '':	#ignore emtpy data
                                pass
                        else:				#otherwise add input to 'tag_buffer'
                                tag_buffer.append(data)
                                dataCtr += 1
        SERIAL_PORT.close()
	return newData

def getProfile():
	#VARIABLES
	isFnProfiled		= 0
	cntTenths		= 0
	rangeToLiquid		= 10
	fnCnt			= 0
	tMaxRead		= 10
		
	cur.execute(clrProfilePrestate)
	print 'PROFILE TABLE CLEARED'
	while isFnProfiled == 0:
		#cur.execute(getSysAdminQry)
		#row = cur.fetchone()
		#isFnProfiled = row[0]

		tFnStart = datetime.datetime.now()
		tFnEnd = addSecs(tFnStart, tMaxRead)
				
		while fnCnt != PULSES_PER_TENTH and isFnProfiled == 0:
			
			tFnNow = datetime.datetime.now().time()
                        if(tFnNow > tFnEnd):
				cur.execute(getIsProfiledQry)
				row = cur.fetchone()
				isFnProfiled = row[0]
				break
			
			if GPIO.event_detected(10):
				fnCnt += 1
		
		if isFnProfiled == 0 and fnCnt == PULSES_PER_TENTH:		
			cntTenths += 0.1
			tempRange = int(getLevel()) 

			if tempRange > rangeToLiquid:
				rangeToLiquid = tempRange
			else:
				rangeToLiquid = rangeToLiquid
		
			print 'litre(s): {} @ range: {}'.format(cntTenths, rangeToLiquid)	
			fnTimeStamp = (int(os.popen('date +%s%N').readline()) / 1000000)
			cur.execute(instProfilePrestate %(fnTimeStamp, cntTenths, rangeToLiquid))
		
		fnCnt = 0

	print 'PROFILING COMPLETE'
		
#MAIN LOOP STATE MACHINE
while True:
	
	if state == IDLE_STATE:						#check database for profile data & decide which state to move to
		print '\n -- STATE: IDLE_STATE'
		cur.execute(getSysAdminQry)				#check if system has been profiled & what the current fuel price is
		row = cur.fetchone()
		isProfiled = row[0]					#get system profiled flag
		fuelCpl = row[1]					#get the user set price of fuel
		#print 'ISPROFILED: {}, FUELCPL: {}'.format(isProfiled, fuelCpl)
		
		if isProfiled == 1:
			cur.execute(getProfileMaxDataQry)
			row = cur.fetchone()
			maxLitres = row[0]                              #get the maximum litres
			maxRange  = row[1]                              #get the maximum range
			
			cur.execute(getProfileMinDataQry)
                        row = cur.fetchone()
			#minLitres = row[0]				#get the minimum litres (already set to 0.0)
			minRange  = row[1]				#get the minimum range

			print ('\n----------------------------\nLITRES WHEN FULL:\t{}\nLITRES WHEN EMPTY:\t{}' + 
			'\nRANGE WHEN FULL:\t{}cm\nRANGE WHEN EMPTY:\t{}cm\nFUEL COST PL:\t\t{}\nIS PROFILED:\t\t{}' + 
			'\n----------------------------\n').format(maxLitres, minLitres, minRange, maxRange, fuelCpl, isProfiled)
			state = READING_DATA_STATE			#if system has profiled, move to normal read data state
			
		elif isProfiled == 0:
			state = PROFILE_STATE				#if system has not profiled, move to profile state
		
	elif state == READING_DATA_STATE:				#read flow & range every tenth of a litre
		print '\n -- STATE: READING_DATA_STATE'
		tLitreStart = datetime.datetime.now()			#get the start time
		
		while cnt != PULSES_PER_LITRE:				#count pulses for one litre
			if GPIO.event_detected(10):			#detect edges on GPIO pin 10
				cnt += 1
		
		cntLitres += 1
		tLitreEnd = datetime.datetime.now()			#get the end time
		tElapsed = (tLitreEnd - tLitreStart)			#calculate the read time
		timeRow = divmod(tElapsed.total_seconds(), 60)
		print timeRow
		tLitreRead = (timeRow[0] * 60) + timeRow[1]		#WORKING HERE
		print 'readTime: {}'.format(tLitreRead)
		flowLpm = (60 / tLitreRead)				#convert read time to litres per minute (devide the read time into sixty seconds)
		flowCpm = (flowLpm * fuelCpl)				#calculate the cost per minute (flow per minute x fuel cost per litre)
		currentLevel = int(getLevel())				#function call to retrieve the current range, then calculate the current tank level 
		currentLitres = (maxLitres - cntLitres)
		timeStamp = (int(os.popen('date +%s%N').readline()) / 1000000)
		cur.execute(instStatsPrestate, (timeStamp, currentLitres, currentLevel, flowLpm, flowCpm))	
		
		cnt = 0
		state = IDLE_STATE					#set state back to IDLE_STATE
	
	elif state == PROFILE_STATE:					#read flow vs range store in profile database
		print '\n -- STATE: PROFILE_STATE'
		getProfile()
		state = IDLE_STATE					#set state back to IDLE_STATE

sys.exit(1)	#if a crash occurs exit with status 1
	
