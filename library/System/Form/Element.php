<?php
class System_Form_Element extends Zend_Form_Element
{
	private function replaceMessages(Zend_Validate_Abstract $validator) {
		// replace $phrase to token 
		$result = array();
		foreach ($validator->getMessageTemplates() as $token => $phrase) {
			$result[$token] = $token;
		}
		$validator->setMessages($result);
	}
	
	/**
	 * Add validator to validation chain
	 *
	 * Note: will overwrite existing validators if they are of the same class.
	 *
	 * @param  string|Zend_Validate_Interface $validator
	 * @param  bool $breakChainOnFailure
	 * @param  array $options
	 * @return Zend_Form_Element
	 * @throws Zend_Form_Exception if invalid validator type
	 */
	public function addValidator($validator, $breakChainOnFailure = false, $options = array())
	{
		if ($validator instanceof Zend_Validate_Interface) {
			$name = get_class($validator);
			
			$this->replaceMessages($validator);
			
			if (!isset($validator->zfBreakChainOnFailure)) {
				$validator->zfBreakChainOnFailure = $breakChainOnFailure;
			}
		} elseif (is_string($validator)) {
			$name = $validator;
			$validator = array(
				'validator' => $validator,
				'breakChainOnFailure' => $breakChainOnFailure,
				'options' => $options,
			);
		} else {
			require_once 'Zend/Form/Exception.php';
			throw new Zend_Form_Exception('Invalid validator provided to addValidator; must be string or Zend_Validate_Interface');
		}

		$this->_validators[$name] = $validator;
		
		return $this;
	}

	protected function _loadValidator(array $validator) {
		$validator = parent::_loadValidator($validator);
		$this->replaceMessages($validator);
		return $validator;
	}
}