<?php
class System_Application_Resource_Mail extends Zend_Application_Resource_ResourceAbstract
{
	/**
	 * Transport class
	 * @var string
	 */
	protected $_class = 'Zend_Mail_Transport_Smtp';
	
	/**
	 * Transport parameters
	 * @var unknown_type
	 */
	protected $_params = array();
	
	/**
	 * Set parameters 
	 * @param array $params
	 */
	public function setParams(array $params) {
		$this->_params = $params;
	}
	/**
	 * Get parameters 
	 * @return array
	 */
	public function getParams() {
		return $this->_params;
	}
	
	public function setClass($class) {
		if (! empty($class)) {
			$this->_class = $class;
		}
	}
	
	/**
	 * Init resource
	 */
	public function init()
	{
		switch ($this->_class) {
			case 'Zend_Mail_Transport_Smtp':
				$transport = new Zend_Mail_Transport_Smtp(
					isset($options['host'])?$options['host']:null, 
					$this->getParams()
				);
				break;
			default:
				$transport = new $this->_class($this->getParams()); 
		}
		Zend_Mail::setDefaultTransport($transport);

		$mail = new System_Mail('utf-8');
		$mail->setHeaderEncoding(Zend_Mime::ENCODING_BASE64);

		return $mail;
	}
}
