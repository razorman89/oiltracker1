#! /usr/bin/env python

from serial import Serial, EIGHTBITS, STOPBITS_ONE, PARITY_NONE, STOPBITS_ONE
import time



serialPort = Serial("/dev/ttyAMA0", baudrate=9600, bytesize=EIGHTBITS, parity=PARITY_NONE, stopbits=STOPBITS_ONE)
serialPort.open()
print 'port ' + serialPort.portstr + ' open'

if (serialPort.isOpen() == False):
	print 'Opening serial connection'
	serialPort.open()

inStr = 'TEST'
buffin = ''
serialPort.flushInput()

#serialPort.flushOutput()
#outStr += chr(a)
#serialPort.write(outStr)
#time.sleep(0.05)
#print "outStr = " + outStr

print 'attempting to read serial data'

while (buffin != '\r'):
	buffin = serialPort.read(1)
	print 'buffin = ' + buffin
	inStr = inStr + buffin

print 'inStr =  ' + inStr

if(inStr == 'TEST'):
	print 'UNCHANGED'
else:
	print 'CHANGED'

serialPort.close()
