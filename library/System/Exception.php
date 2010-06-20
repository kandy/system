<?php

/**
 * System Exception
 * 
 * @package system
 */
class System_Exception extends Zend_Exception 
{
	protected $_values = array();

	public function __construct($message='', $code = '') {
		$this->_values['exceptionCode']=$code;
		parent::__construct($message, 0);
	}

	public function __set($name, $value) {
		$this->_values[$name] = $value;
	}

	public function __get($name) {
		if (array_key_exists($name, $this->_values)) {
			return $this->_values[$name];
		}
		return null;
	}

	public function __isset($name) {
		return isset($this->_values[$name]);
	}

	public function __unset($name) {
		unset($this->_values[$name]);
	}
}
