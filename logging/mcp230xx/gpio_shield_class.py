#!/usr/bin/python


from Adafruit_MCP230xx import Adafruit_MCP230XX 
import time
import sys

class MCP23017:
    def __init__(self, addr):
        self.addr = addr
        self.num_gpios = 16

        self.mcp = Adafruit_MCP230XX(address = self.addr, num_gpios = self.num_gpios)

    def set_port_to_output(self, port):
        self.mcp.config(port, Adafruit_MCP230XX.OUTPUT)

    def set_port_to_input(self, port):
        self.mcp.config(port, Adafruit_MCP230XX.INPUT)

    def set_port(self, port, value):
        self.mcp.output(port, value)

    def read_port(self, port):
        #self.mcp.pullup(port, 1)
        value=self.mcp.input(port) >> 3
        #print "%d: %x" % (port, value)
        return value



i2c_addr = int(sys.argv[1],16) #0x24
port = int(sys.argv[2])
on_off = int(sys.argv[3])

obj=MCP23017(i2c_addr)
obj.set_port_to_output(port)
obj.set_port(port, on_off)

