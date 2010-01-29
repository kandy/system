<?php
/**
 * Class use to bootstrap Acl resource
 *
 * @package system.application.resource
 */
class System_Application_Resource_FileTransfer extends Zend_Application_Resource_ResourceAbstract
{
	protected $_destination = '';
	
	public function setDestination($destination) {
		$this->_destination = $destination;
	}
	
	public function getDestination() {
		return $this->_destination;
	}
	
	/**
	 * Init FileTransfer resource
	 *
	 * @return Zend_File_Transfer
	 */
	public function init() {
		$adapter = new Zend_File_Transfer_Adapter_Http();
		$adapter->setDestination($this->getDestination());
		return $adapter;
	}

}
