<?php
/**
 * @see Zend_Filter_Interface
 */
require_once 'Zend/Filter/Interface.php';

/**
 * Class to convert datatime string to need format
 *
 * @package system.filter.db
 */
class System_Filter_DateTimeFormat implements Zend_Filter_Interface 
{
	/**
	 * @var string format
	 */
	protected $_format = DATE_ISO8601;

	/**
	 * Constructor
	 * @param $format string
	 */
	public function __construct($format = null) {
		if ($format !== null){
			$this->_format = $format;
		}
	}

	/**
	 * Returns date in need format or null if wrong date
	 *
	 * @param  string $value
	 * @return string|null
	 */
	public function filter($value) {
		$datetime = strtotime($value);
		if ($datetime === false){
			return null;
		} else {
			return date($this->_format, $datetime);
		}
	}
}
