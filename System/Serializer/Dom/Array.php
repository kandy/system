<?php
class System_Serializer_Dom_Array extends System_Serializer_Dom_Abstract
{
	public function serialize($value, $parentElement)
	{
		foreach ($value as $key=>$value){
			$element = $this->_testNodeNameAndAddNode($key, $parentElement);
			$this->getOwnerSerializer()->serialize($value, $element);
		}
		return $parentElement;
	}
}