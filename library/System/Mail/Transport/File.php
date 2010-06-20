<?php
/**
 * File for send mail in file
 * @package system.mail.transport
 */
class System_Mail_Transport_File extends Zend_Mail_Transport_Abstract
{
	const EMAIL_SEPARATOR = "\n -=-=-=-=-=-=-=- \n\n";
	/**
	 * File name for email
	 * @var string
	 */
	protected $_fileName = null;
	
	/**
	 * Constructor - implement configurable object pattern
	 * @param $options
	 * @return unknown_type
	 */
	public function __construct($options = null) 
	{
		System_Options::setConstructorOptions($this, $options);
	}
	
	/**
	 * Set class options
	 * @param array $options
	 * @return unknown_type
	 */
	public function setOptions(array $options) 
	{
		System_Options::setOptions($this, $options);
	}

	/**
	 * Set outpurt file name
	 * @param $name
	 * @return unknown_type
	 */
	public function setFileName($name) {
		$this->_fileName = $name;
		return $this;
	}

	/**
	 * Get output file name
	 * @return string
	 */
	public function getFileName() {
		return $this->_fileName;
	}
	/**
	 * @see Zend_Mail_Transport_Abstract::_sendMail
	 */
	protected function _sendMail(){
		file_put_contents($this->getFileName(),$this->header. $this->body . self::EMAIL_SEPARATOR, FILE_APPEND | LOCK_EX);
	}
}