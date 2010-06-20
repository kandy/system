<?php
class System_Form_Ext extends Zend_Form 
{
	/**
	 *
	 */
	public function render() {
		$elements = array();
		foreach ($this->getElements() as $name => $element) {
			//$element = new Zend_Form_Element();
			$elementArray = array();
			$elementArray['name'] = $element->getName();
			$elementArray['fieldLabel'] = $element->getLabel(); 
			$elementArray += $element->getAttribs();
			$elements[] = $elementArray;
		}
		return $elements;
	}
	
	public function loadDefaultDecorators() {}
}