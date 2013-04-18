#! /usr/bin/env python
import os
os.system ('sudo modprobe w1-gpio')
os.system ('sudo modprobe w1-therm')
print '\n==================================================\nReading Temperature from device: 28-0000040dba3e\nPlease wait...'
# Open device file 
tfile = open("/sys/bus/w1/devices/28-0000040dba3e/w1_slave") 
# Read all of the text in the file. 
text = tfile.read() 
# Close the file now that the text has been read. 
tfile.close() 
# Split the text with new lines (\n) and select the second line. 
secondline = text.split("\n")[1] 
# Split the line into words, referring to the spaces, and select the 10th word (counting from 0). 
temperaturedata = secondline.split(" ")[9] 
# The first two characters are "t=", so get rid of those and convert the temperature from a string to a number. 
temperature = float(temperaturedata[2:]) 
# Put the decimal point in the right place and display temperature. 
temperature = temperature / 1000
#symbol = chr(167)
print 'Temperature is: {} degrees celsius.\n==================================================\n'.format(temperature)
