#! /usr/bin/env python

#IMPORTS
import sys, os, getopt, datetime, time, MySQLdb
import RPi.GPIO as GPIO
from serial import Serial, EIGHTBITS, STOPBITS_ONE, PARITY_NONE, STOPBITS_ONE

#GPIO SETTINGS
GPIO.setmode(GPIO.BCM)					#BCM GPIO numbering
GPIO.setup(10, GPIO.IN)					#GPIO pin 10 is flow meter input.
GPIO.add_event_detect(10, GPIO.BOTH)	#enable edge detection events, can be RISING, FALLING or BOTH

#FUNCTION DECLARATIONS
def addSecs(date, secs):
	tm = date.time();
    	fulldate = datetime.datetime(100, 1, 1, tm.hour, tm.minute, tm.second)
    	fulldate = fulldate + datetime.timedelta(seconds=secs)
    	return fulldate.time()

def getLevel():
	#FUNCTION LOCAL VARIABLES
	STBY_STATE, READING_SERIAL_STATE = range(0, 2)
	tag_buffer	= []
	dataCtr		= 0
	isRanged	= False
	fnState		= STBY_STATE

	SERIAL_PORT.open()						#open serial SERIAL_PORT

        if(SERIAL_PORT.isOpen() == False):
                SERIAL_PORT.open()

        SERIAL_PORT.flushInput()					#ensure serial input is clear
        while isRanged != True:						#read serial port

                data = SERIAL_PORT.read(1)     				#read 1 byte only
                if fnState == STBY_STATE:

                        if data == START_BYTE_FLAG:
                                fnState = READING_SERIAL_STATE
                elif fnState  == READING_SERIAL_STATE:

                        if dataCtr == DATA_LENGTH:			#if dataCtr is greater then DATA_LENGTH then the data is invalid, so ignore
                                newData = "".join(tag_buffer[0:-2])	#build 'newData' from 'tag_buffer' contents
                                dataCtr = 0				#reset for next packet
                                tag_buffer = []				#empty tag buffer, to ready for new packet
                                isRanged = True

                        elif data == END_BYTE_FLAG:
                                fnState = STBY_STATE

                        elif data == '':				#ignore emtpy data
                                pass
                        else:						#otherwise add input to 'tag_buffer'
                                tag_buffer.append(data)
                                dataCtr += 1
        SERIAL_PORT.close()
	return newData							#return range in string format

def getProfile():
	#FUNCTION LOCAL VARIABLES
	isFnProfiled		= 0
	cntTenths		= 0
	rangeToLiquid		= 10
	fnCnt			= 0
	tFnMaxRead		= 10
	
	cur = db.cursor()
	cur.execute(clrProfileQry)					#delete previous profile values
	db.commit()
	print '\n -- PROFILE TABLE CLEARED'
	while isFnProfiled == 0:
		print '\n -- SET TIME'
		tFnStart = datetime.datetime.now()			#time out setup
		tFnEnd = addSecs(tFnStart, tFnMaxRead)

		while isFnProfiled == 0 and fnCnt != PULSES_PER_TENTH:
			
			tFnNow = datetime.datetime.now().time()
                        if(tFnNow > tFnEnd):
				print '\n -- TIMEDOUT, UPDATE CTRL'				#on time out check if the system is profiled
				cur.execute(getIsProfiledQry)
				row = cur.fetchone()
				if row:
					isFnProfiled = row[0]
				print 'ISFNPROFILED: {}'.format(isFnProfiled)
				db.commit()
				break
			
			if GPIO.event_detected(10):
				fnCnt += 1
		
		if isFnProfiled == 0 and fnCnt == PULSES_PER_TENTH:	
			cntTenths += 0.1
			tempRange = int(getLevel()) 			#parse an integer from the returned string

			if tempRange > rangeToLiquid:			#remove irregular sensor readings
				rangeToLiquid = tempRange
			else:
				rangeToLiquid = rangeToLiquid
		
			print 'litre(s): {} @ range: {}'.format(cntTenths, rangeToLiquid)	
			fnTimeStamp = (int(os.popen('date +%s%N').readline()) / 1000000)
			cur.execute(insProfileQry, (fnTimeStamp, cntTenths, rangeToLiquid))
			db.commit()
			fnCnt = 0

	print '\n -- PROFILING COMPLETE'

#DATABASE SETUP, QUERIES & PRE-STATEMENTS
db  = MySQLdb.connect(host = 'localhost', user = 'root', passwd = '7920979v', db = 'project2013')
cur = db.cursor()
getSysAdminQry          = "SELECT `isprofiled`, `fuelppl` FROM `sysadmin`;"
getIsProfiledQry        = "SELECT `isprofiled` FROM `sysadmin`;"
getProfileMinDataQry    = "SELECT `litres`, `range` FROM `profile` ORDER BY `profileid` ASC LIMIT 1;"
getProfileMaxDataQry    = "SELECT `litres`, `range` FROM `profile` ORDER BY `profileid` DESC LIMIT 1;"
insProfileQry           = "INSERT INTO `profile` (`tstamp`, `litres`, `range`) VALUES (%s, %s, %s);"
insStatsQry             = "INSERT INTO `stats` (`tstamp`, `currentlevel`, `ltrspm`, `costpm`) VALUES (%s, %s, %s, %s);"
updRangeQry             = "UPDATE `range` SET `tstamp` = %s, `currentlevel` = %s WHERE `rangeid` = '1';"
clrProfileQry           = "DELETE FROM `profile`"
db.autocommit = False

#STATES
IDLE_STATE, READING_DATA_STATE, PROFILE_STATE = range(0, 3)

#CONSTANTS
START_BYTE_FLAG         = 'R'
END_BYTE_FLAG           = '\r'
DATA_LENGTH             = 5
PORT_NUM                = '/dev/ttyAMA0'
SERIAL_PORT             = Serial(port=PORT_NUM, baudrate=9600, stopbits=1, bytesize=8, timeout=0)
PULSES_PER_TENTH        = 73
PULSES_PER_LITRE        = 800

#GLOBAL VARIABLES
cnt             = 0
cntLitres       = 0
state           = IDLE_STATE
isProfiled      = 0
isFull		= 0
tMaxRead        = 5
tLitreStart	= 0

maxRange        = 0
minRange        = 0
maxLitres       = 0.0
minLitres       = 0.0
fuelCpl         = 1.0

#MAIN LOOP STATE MACHINE
cur.execute(getProfileMaxDataQry)
row = cur.fetchone()
if row:
	maxLitres = row[0]                                      #get the maximum litres
	maxRange  = row[1]                                      #get the maximum range

cur.execute(getProfileMinDataQry)
row = cur.fetchone()
if row:
	#minLitres = row[0]                                     #get the minimum litres (already set to 0.0)
	minRange  = row[1]					#get minimum range

cur.execute(getSysAdminQry)                             #check if system has been profiled & what the current fuel price is
row = cur.fetchone()
if row:
	isProfiled = row[0]                                     #get system profiled flag
	fuelCpl = row[1]      
print ('\n----------------------------\nLITRES WHEN FULL:\t{}\nLITRES WHEN EMPTY:\t{}' +
'\nRANGE WHEN FULL:\t{}cm\nRANGE WHEN EMPTY:\t{}cm\nFUEL COST PL:\t\t{}\nIS PROFILED:\t\t{}' +
'\n----------------------------\n').format(maxLitres, minLitres, minRange, maxRange, fuelCpl, isProfiled)

while True:

	if state == IDLE_STATE:						#check database for profile data & decide which state to move to
		print '\n -- STATE: IDLE_STATE'
		cur.execute(getSysAdminQry)                             #check if system has been profiled & what the current fuel price is
		row = cur.fetchone()
		if row:
			#print '\n -- UPDATING CRTL'
			isProfiled = row[0]                                     #get system profiled flag
			fuelCpl = row[1]
		#else:
			#print '\n -- NOT UPDATED'
		print '\n -- ISPROFILED: {}, FUELCPL: {}'.format(isProfiled, fuelCpl)
		
		if isProfiled == 1:
			state = READING_DATA_STATE			#if system has profiled, move to normal read data state
			
		elif isProfiled == 0:
			state = PROFILE_STATE				#if system has not profiled, move to profile state
		
		db.commit()		

	elif state == READING_DATA_STATE:				#read flow & range every tenth of a litre
		print '\n -- STATE: READING_DATA_STATE'
		
		print '\n -- START TIME OUT COUNTER'
		if cnt == 0:
			print '\n -- START READ COUNTER'
			tLitreStart = datetime.datetime.now()

		tTimeOutStart = int(time.time())
		tTimeOut = tTimeOutStart + tMaxRead
		#tStart = datetime.datetime.now()                      #time out setup
                #tEnd = addSecs(tStart, tMaxRead)

                while isProfiled == 1:
                        tNow = time.time()
                        if(tNow >= tTimeOut):                            #on time out check if the system is profiled
                                print '\n -- TIMEDOUT! RETURN TO IDLE ...'
				
				currentLevel = int(getLevel())
				timeStamp = (int(os.popen('date +%s%N').readline()) / 1000000)  #generate time stamp in miliseconds
				
				if currentLevel >= maxRange and isFull == 1:
					isFull = 0
				
				if currentLevel <= minRange and isFull == 0:
					isFull = 1
					cur.execute(insStatsQry, (timeStamp, minRange, 0.0, 0.0))
				
				print '\n\t ------ CURRENT LEVEL: {}'.format(currentLevel)
				
				cur.execute(updRangeQry, (timeStamp, currentLevel))
				db.commit()
                                break

                        if GPIO.event_detected(10):
				print 'HERE: {}'.format(cnt)
                                cnt += 1

			if isProfiled == 1 and cnt == PULSES_PER_LITRE:
				cntLitres += 1
				print '\n\t ------ LITRE(S): {}'.format(cntLitres)
				tLitreStop = datetime.datetime.now()#int(time.time())					#get the end time
				tLitreRead = (tLitreStop - tLitreStart)#int(tLitreStop - tLitreStart)				#calculate the read time
				timeRow = divmod(tLitreRead.total_seconds(), 60)		#divmod returns quotient and remainder
				tLitre = (timeRow[0] * 60) + timeRow[1]             #multipy quotient by sixty and add remainder

				print '\n\t ------ tLitreStart: {}, tLitreStop: {}, tLitreRead: {}'.format(tLitreStart, tLitreStop, tLitreRead)
				
				flowLpm = (60 / tLitre)				#convert read time to litres per minute (devide the read time into sixty seconds)
				flowCpm = (flowLpm * fuelCpl)				#calculate the cost per minute (flow per minute x fuel cost per litre)
				currentLevel = int(getLevel())
				#currentLevel = maxRange - int(getLevel())		#function call to retrieve the current range, then calculate the current tank level 
				timeStamp = (int(os.popen('date +%s%N').readline()) / 1000000)	#generate time stamp in miliseconds
				cur.execute(insStatsQry, (timeStamp, currentLevel, flowLpm, flowCpm))	#write data to the stats table of database	
				cur.execute(updRangeQry, (timeStamp, currentLevel))
				db.commit()
				cnt = 0
				
				break
		
		print '........................................................'
		state = IDLE_STATE					#set state back to IDLE_STATE
	
	elif state == PROFILE_STATE:					#read flow vs range store in profile database
		print '\n -- STATE: PROFILE_STATE'
		getProfile()

		cur.execute(getProfileMaxDataQry)
		row = cur.fetchone()
		if row:
			maxLitres = row[0]					#get the maximum litres
			maxRange  = row[1]					#get the maximum range

		cur.execute(getProfileMinDataQry)
		row = cur.fetchone()
		if row:
			#minLitres = row[0]					#get the minimum litres (already set to 0.0)
			minRange  = row[1]					#get the minimum range

		print ('\n----------------------------\nLITRES WHEN FULL:\t{}\nLITRES WHEN EMPTY:\t{}' +
		'\nRANGE WHEN FULL:\t{}cm\nRANGE WHEN EMPTY:\t{}cm\nFUEL COST PL:\t\t{}\nIS PROFILED:\t\t{}' +
		'\n----------------------------\n').format(maxLitres, minLitres, minRange, maxRange, fuelCpl, isProfiled)
		
		cnt = 0
		cntLitres = 0
		state = IDLE_STATE					#set state back to IDLE_STATE

sys.exit(1)	#if a crash occurs exit with status 1
