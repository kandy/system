<?php
abstract class System_Serializer_Dom_Abstract implements System_Serializer_Dom_Interface{
	const ARRAY_ITEM_NAME = 'item';
	const ARRAY_KEY_ATTRIBUTE = 'key';
	const NODE_NAME_PATTERN = '~^([A-Za-z][A-Za-z0-9\-_]*:)?[A-Za-z][A-Za-z0-9\-_]*$~';
	const ATTRIBUTE_NAME_PATTERN = '~^[_@]([A-Za-z][A-Za-z0-9\-_]*:)?[A-Za-z][A-Za-z0-9\-_]*$~';
	
	/**
	 * Plugin owner
	 * @var System_Serializer_Dom
	 */
	protected $_ownerSerializer = null;
	
	/**
	 * @see library/System/Serializer/Dom/System_Serializer_Dom_Interface#setOwnerSerializer($serializer)
	 */
	public function setOwnerSerializer($serializer)
	{
		$this->_ownerSerializer = $serializer;
	}
	/**
	 * Get plugin owner
	 * @return System_Serializer_Dom
	 */
	public function getOwnerSerializer()
	{
		return $this->_ownerSerializer;
	}
	
	
	/**
	 * Test name is vaild and add node.
	 * If not create node with ARRAY_ITEM_NAME name and
	 * set old neme in ARRAY_KEY_ATTRIBUTE
	 *
	 * @param string $name
	 * @param DomElement $element
	 * @return DomElement
	 */
	protected function _testNodeNameAndAddNode($name, DomElement $element) {
		$doc = $element->ownerDocument;
	
		if (!preg_match(self::NODE_NAME_PATTERN, $name)) {
			if ( $element instanceof DOMDocument){
				$nodeName = self::ARRAY_ITEM_NAME;
			}else{
				$nodeName = rtrim($element->nodeName, 's');
			};
			$newNode = $doc->createElement($nodeName);
			$newNode->setAttribute(self::ARRAY_KEY_ATTRIBUTE, $name);
			$element->appendChild($newNode);
		} else {
			$newNode = $doc->createElement($name);
			$element->appendChild($newNode);
		}

		return $newNode;
	}
	

}