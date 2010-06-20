<?php
class System_Serializer_Dom_Exception extends System_Serializer_Dom_Abstract
{
	public function serialize($value, $parentElement)
	{
		$parentElement->setAttribute('message', $value->getMessage());
		$parentElement->setAttribute('code', $value->getCode());

		$trace = $this->_testNodeNameAndAddNode('trace', $parentElement);
		$this->getOwnerSerializer()->serialize($value->getTraceAsString(), $trace);
	}
}