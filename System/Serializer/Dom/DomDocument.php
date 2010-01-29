<?php
class System_Serializer_Dom_DomDocument extends System_Serializer_Dom_Abstract
{
	public function serialize($document, $parentElement) {
		$parentElement->parentNode->appendChild(
			$parentElement->ownerDocument->importNode($document->documentElement, true)
		);
		$parentElement->parentNode->removeChild($parentElement);
	}
}