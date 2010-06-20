<?php
class System_Serializer_Dom_Form extends System_Serializer_Dom_Abstract
{
	public function serialize($form, $parentElement) {	
		$parentElement->setAttribute("action", $form->getAction());

		$this->serializeErrors($form->getErrorMessages(), $parentElement);

		$this->serializeValues($form, $parentElement);
	}

	/**
	 * Will take all form or field errors and create "errors" element with it, like that:
	 * <br/><br/>
	 * <code>
	 * &lt;errors><br/>
	 * &nbsp;&nbsp;&lt;productDoesntExist/><br/>
	 * &nbsp;&nbsp;&lt;notEnoughMoney/><br/>
	 * &lt;/errors><br/>
	 * </code>
	 * <br/><br/>
	 * Will only create the "errors" element if there are errors. Otherwise not. 
	 *
	 * @param Array $errors Zend errors list, from which we should take the form or field errors
	 * @param DOMElement $node parent element where to create "errors" element
	 */
	private function serializeErrors($errors, DOMElement $node) {
		if (count($errors)) {
			$errorsElement = $node->ownerDocument->createElement("errors");
			$node->appendChild($errorsElement);
			foreach($errors as $errorMessage) {
				$element = $node->ownerDocument->createElement($errorMessage);
				$errorsElement->appendChild($element);
			}
		}
	}

	/**
	 * Serialize form elements
	 * @param Zend_Form $form Zend form, which value to serialize
	 * @param DOMElement $domElement parent element where to create the "values" element
	 */
	private function serializeValues($form, DOMElement $domElement) {
		$values = $domElement->ownerDocument->createElement("values");
		$domElement->appendChild($values);
		
		foreach ($form->getElements() as $name => $element) {
			$node = $domElement->ownerDocument->createElement("element");
			$node->setAttribute("name", $element->getFullyQualifiedName());
			if ($element->getAttrib("disabled")) {
				$node->setAttribute("disabled", "disabled");
			}
			$node->appendChild($domElement->ownerDocument->createElement('value', htmlspecialchars($element->getValue(), ENT_NOQUOTES)));
			$this->serializeErrors($element->getErrors(), $node);
			$values->appendChild($node);
		}
	}
}
