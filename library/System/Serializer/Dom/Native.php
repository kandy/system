<?php
class System_Serializer_Dom_Native extends System_Serializer_Dom_Abstract
{
	public function serialize($value, $parentElement)
	{
		$element =  $parentElement->ownerDocument->createTextNode((string) $value);
		$parentElement->appendChild($element);
		return $element;
	}
}