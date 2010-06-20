<?php
/**
 * Validator for treebune bucks value
 *
 * @package system.validate
 */
class System_Validate_Tb extends Zend_Validate_Abstract
{
	const INVALID = 'tbInvalid';
	const NOT_TB = 'notTb';

	/**
	* @var array
	*/
	protected $_messageTemplates = array(
		self::INVALID => "Invalid type given, value should be XXXX.XX",
		self::NOT_TB => "'%value%' does not appear to be a TB value"
	);

	/**
     * Returns true if and only if $value meets treebune bucks validation requirements
     *
     * If $value fails validation, then this method returns false, and
     * getMessages() will return an array of messages that explain why the
     * validation failed.
     *
     * @param  mixed $value TB value
     * @return boolean
     * @throws Zend_Valid_Exception If validation of $value is impossible
     */
	public function isValid($value) {
		$this->_setValue($value);

		if (!is_string($value) && !is_int($value) && !is_float($value)) {
			$this->_error(self::INVALID);
			return false;
		}
		
		if (empty($value) && $value != '0') {
			$this->_error(self::INVALID);
			return false;
		}
		
		$__value = str_replace(',', '.', $value);
		
		if ((string) round($__value,2) != $__value) {
			$this->_error(self::NOT_TB);
			return false;
		}

		return true;
	}
}
