<?php
class System_Form_Abstract extends Zend_Form
{
	protected $_checkSend = true;
	
	public function setCheckIsSend($check)
	{
		$this->_checkSend = $check;
		return $this;
	}
	
	public function getCheckIsSend()
	{
		return $this->_checkSend;
	}
	
	
	public function __construct($options = null)
	{
		//set form element prefix
		//$prefix = $this->_getNameLastPart();
		//$this->setElementsBelongTo($prefix);
		$this->setAttrib('id', strtolower(str_replace('_', '-',get_class($this)))); // @todo: use Zend_Filter_inflector?
		//$this->setMethod(Zend_Form::METHOD_POST);
		$this->getPluginLoader(Zend_Form::ELEMENT)->addPrefixPath('System_Form', "System/Form");
		parent::__construct($options);
	}
	
	private function _getNameLastPart(){
		$parts = explode('_', get_class($this));
		$lastPart = end($parts);
		$lastPart[0] = strtolower($lastPart[0]);
		return $lastPart;
	}
	
	/**
	 * Retrieve all form element values
	 *
	 * @param  bool $suppressArrayNotation
	 * @return array
	 */
	public function getValues($suppressArrayNotation = true)
	{
		return parent::getValues($suppressArrayNotation);
	}
	
	public function getMessages($name = null, $suppressArrayNotation = true)
	{
		return parent::getMessages($name, $suppressArrayNotation);
	}
	
	/**
	 * Extract the value by walking the array using given array path.
	 *
	 * Given an array path such as foo[bar][baz], returns the value of the last
	 * element (in this case, 'baz').
	 *
	 * @param  array $value Array to walk
	 * @param  string $arrayPath Array notation path of the part to extract
	 * @return array|null
	 */
	protected function _dissolveArrayValue($value, $arrayPath)
	{
		// As long as we have more levels
		while ($arrayPos = strpos($arrayPath, '[')) {
			// Get the next key in the path
			$arrayKey = trim(substr($arrayPath, 0, $arrayPos), ']');
			
			// Set the potentially final value or the next search point in the array
			if (isset($value[$arrayKey])) {
				$value = $value[$arrayKey];
			}else{
				$value = null;
			}
			
			// Set the next search point in the path
			$arrayPath = trim(substr($arrayPath, $arrayPos + 1), ']');
		}

		if (isset($value[$arrayPath])) {
			$value = $value[$arrayPath];
		}else{
			$value = null;
		}
		return $value;
	}
	
	/**
	 * Retrieve all form element values
	 *
	 * @param  bool $suppressArrayNotation
	 * @return array
	 */
	public function ______isValid($data)
	{
		
		if ($this->getCheckIsSend() && $this->isArray()) {
			if ($this->_dissolveArrayValue($data, $this->getElementsBelongTo()) === null) {
				return false;
			}
		}

		return parent::isValid($data);
	}
}
