#! /usr/bin/env python

from serial import Serial, EIGHTBITS, STOPBITS_ONE, PARITY_NONE, STOPBITS_ONE
import time, sys

#Constants
START_BYTE_FLAG = 'R'
END_BYTE_FLAG = '\r'
DATA_LENGTH = 5  
		
#States
IDLE_STATE, READING_DATA_STATE = range(0, 2)
state = IDLE_STATE  
		
#temp
tag_buffer = []
dataCtr = 0
isRanged = False

#setup the serial port.. 
port_num = '/dev/ttyAMA0'
port = Serial(port=port_num, baudrate=9600, stopbits=1, bytesize=8, timeout=0)

port.open()
print 'port ' + port.portstr + ' open'

if(port.isOpen() == False):
	print 'failed to open, retrying'
	port.open()       

port.flushInput() 
#read the range finder for ever..
while isRanged != True:
	
	data = port.read(1)     #Read 1 Byte only
	#print 'data = ' + data		
	if state == IDLE_STATE:
		               
		if data == START_BYTE_FLAG:
			state = READING_DATA_STATE
	elif state  == READING_DATA_STATE:
		
		#If dataCtr is greater then DATA_LENGTH then the data is invalid, so ignore
		if dataCtr == DATA_LENGTH:
			newData = "".join(tag_buffer[0:-2]) #Do something with ur new data here!
			print 'RANGE in CM = ' + newData
			#sys.stdout.write('Distance to target = ' + newData + '\r')
			dataCtr = 0    #Reset for next packet
			tag_buffer = []#empty tag buffer, to ready for new packet
			isRanged = True			

                elif data == END_BYTE_FLAG:
                        state = IDLE_STATE

                elif data == '': ##Ignore emtpy data
                    	pass 
		else:
			tag_buffer.append(data) 
			dataCtr = (dataCtr + 1)

port.close()
print 'port ' + port.portstr + ' closed'
########################################################################################################
