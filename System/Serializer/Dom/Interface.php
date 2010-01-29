<?php
interface System_Serializer_Dom_Interface
{
	public function setOwnerSerializer($serializer);
	public function serialize($value, $parentElement);
}