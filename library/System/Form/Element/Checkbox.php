<?php
/**
 * Checkbox element with suport "on" as checked value
 * @package system.form.element
 */
class System_Form_Element_Checkbox extends Zend_Form_Element_Checkbox
{
	/**
	 * @see Zend_Form_Element_Checkbox::setValue
	 */
	public function setValue($value) {
		if ('on' === $value){
			$this->_value = $this->getCheckedValue();
			$this->checked = true;
			return $this;
		} else {
			return parent::setValue($value);
		}
		
	}
}
