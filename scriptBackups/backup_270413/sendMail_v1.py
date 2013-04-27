#! /usr/bin/env python

#IMPORTS
import datetime, time, MySQLdb, smtplib

#DATABASE SETUP, QUERIES & PRE-STATEMENTS
db  = MySQLdb.connect(host = 'localhost', user = 'root', passwd = '7920979v', db = 'project2013')
cur = db.cursor()
getSysAdminQry		= "SELECT `isprofiled`, `senderemail`, `senderpassword`, `recipientemail` FROM `sysadmin`;"
getProfileMaxDataQry    = "SELECT `range` FROM `profile` ORDER BY `profileid` DESC LIMIT 1;"
getTempQry		= "SELECT `tempdc` FROM `temp` ORDER BY `tempid` DESC LIMIT 1;"
getCurrentLevel		= "SELECT `currentLevel` FROM `range`;"
db.autocommit = False

#CONSTANTS
SMTP_SERVER = 'smtp.gmail.com'
SMTP_PORT = 587

#GLOBAL VARIABLES
maxRange = 0
currentLevel = 1
isProfiled = 0
isSent = 0
sender = ''
password = ''
recipient = ''
subject = 'Your Running Low on Oil!'
body = ''
tSleep = 10
tTimeOutStart = int(time.time())
tTimeOut = tTimeOutStart + tSleep	

while True:

	tNow = time.time()

	if tNow >= tTimeOut:
		print '\n -- TIMEDOUT ---> CHECKING STATUS'
		#GET SETTINGS
		cur.execute(getSysAdminQry)
		row = cur.fetchone()
		if row:
			isProfiled = row[0]
			sender = row[1]
			password = row[2]
			recipient = row[3]
			
		#GET MAX RANGE
		cur.execute(getProfileMaxDataQry)
		row = cur.fetchone()
		if row:
			maxRange = row[0]
		
		#GET CURRENT LEVEL
		cur.execute(getCurrentLevel)
		row = cur.fetchone()
		if row:
			currentLevel = row[0]

		db.commit()
		print '\n -- CURRENT STATUS:\n -- isProfiled: {}\n -- sender: {}\n -- password: {}\n -- recipient: {}\n -- maxRange: {}\n -- currentLevel: {}\n -- isSent: {}'.format(isProfiled, sender, password, recipient, maxRange, currentLevel, isSent)	

		if isProfiled == 1 and currentLevel >= maxRange and isSent == 0:
			print '\n -- TANK LEVEL LOW ---> BUILDING EMAIL SENDING EMAIL & SETTING CTRL FLAG'

			#GET TEMPERATURE
			cur.execute(getTempQry)
			row = cur.fetchone()
			if row:
				tempdc = row[0]
			db.commit()

			dateTimeInMills = int(time.time() * 1000)
			tstamp = datetime.datetime.fromtimestamp(dateTimeInMills / 1000)			

			#BUILD MESSAGE BODY
			body = '<h2> --- WARNING TANK LEVEL IS LOW! --- </h2><h3 style="font-weight: bold;">********* SYSTEM STATUS REPORT ********* <br> Report time stamp: {}<br> Temperature: {} celsius <br> Current range: {}cm, <br> Range when empty: {}cm</h3>'.format(tstamp, tempdc, currentLevel, maxRange)
			body = "" + body + ""			
	
			#SET HEADER
			headers = ["From: " + "info@oil.com",
					   "Subject: " + subject,
					   "To: " + recipient,
					   "MIME-Version: 1.0",
					   "Content-Type: text/html"]
			headers = "\r\n".join(headers)
		
			#SETUP EMAIL SESSION
			session = smtplib.SMTP(SMTP_SERVER, SMTP_PORT)
			session.ehlo()
			session.starttls()
			session.ehlo

			#LOGIN TO GMAIL, SEND & CLOSE SESSION
			session.login(sender, password)
			session.sendmail(sender, recipient, headers + "\r\n\r\n" + body)
			session.quit()

			#SET CTRL FLAG
			isSent = 1
		
		elif isProfiled == 1 and currentLevel < maxRange and isSent == 1:
			print '\n -- TANK LEVEL ABOVE MINIMUM ---> CLEAR CTRL FLAG'
			#CLEAR CTRL FLAG
			isSent = 0
			
		elif isProfiled == 0:
			print '\n -- SYSTEM NOT PROFILED! EMAIL DISABLED'
			
#		else:
#			print '\n -- CTRL FLAG IS SET! MESSAGE ALREADY SENT'
		
		print '\n -- RESETTING TIMEOUT COUNTER ---> WAITING FOR: {} SECONDS'.format(tSleep)
		tTimeOutStart = int(time.time())
		tTimeOut = tTimeOutStart + tSleep	
