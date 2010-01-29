<?php
class System_Serializer_Dom_Row extends System_Serializer_Dom_Array
{
	public function serialize($value, $parentElement)
	{
		parent::serialize($value->toArray(), $parentElement);
		return $parentElement;
	}
}