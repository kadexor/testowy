<?php

/**
 * Relay Board PHP Class
 * www.ucproject.eu
 * @author Mariusz Angielski <kontakt@mariuszangielski.pl>
 *
 */

class relayBoard {

	protected $_port;
	protected $_handler;
	
	protected $portOn = [
						1 => [0x00, 0x28],
						2 => [0x01, 0x76],
						3 => [0x02, 0x94],
						4 => [0x03, 0xca],
						5 => [0x04, 0x49],
						6 => [0x05, 0x17],
						7 => [0x06, 0xf5],
						8 => [0x07, 0xab]
	];
	
	protected $portOff = [ 
						1 => [0x00, 0x9a],
						2 => [0x01, 0xc4],
						3 => [0x02, 0x26],
						4 => [0x03, 0x78],
						5 => [0x04, 0xfb],
						6 => [0x05, 0xa5],
						7 => [0x06, 0x47],
						8 => [0x07, 0x19]
	];
	
	public function __construct($port) 
	{
		$this->_port = $port;
		exec("mode ".$this->_port." BAUD=57600 PARITY=n DATA=8 STOP=1 xon=off octs=off rts=on");
		$this->_handler = fopen($this->_port, "wb+");
	}
	
	public function disconnect()
	{
		fclose($this->_handler);
	}
	
	public function on($port) 
	{
		fputs($this->_handler,pack('C*', 0x55, 0x01, 0x4f)); 
		foreach($this->portOn[$port] as $hData) {
			fputs($this->_handler,pack('C*', $hData));
		}
	}
	
	public function off($port)
	{
		fputs($this->_handler,pack('C*', 0x55, 0x01, 0x46)); 
		foreach($this->portOff[$port] as $hData) {
			fputs($this->_handler,pack('C*', $hData));
		}
	}

}

/*
	START RELAY BOARD....
*/

$oRelayBoard = new relayBoard('COM2');

for($i=1;$i<=8;$i++) {

	$oRelayBoard->on($i);
	usleep(100);
}

for($i=1;$i<=8;$i++) {

	$oRelayBoard->off($i);
	usleep(100);
}

$oRelayBoard->disconnect();

