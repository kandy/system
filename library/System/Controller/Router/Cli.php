<?php
/**
 * Simple cli router
 * @author kandy
 *
 */
class System_Controller_Router_Cli extends Zend_Controller_Router_Abstract
{
	/**
	 * @see Zend_Controller_Router_Interface::route
	 */
	public function route(Zend_Controller_Request_Abstract $request) {
		if($request->getModuleName() == '') {
			$request->setModuleName('cli');
		}
		
		if($request->getControllerName() == '') {
			$request->setControllerName('index');
		}
		
		return true;
	}
	/**
	 * @see Zend_Controller_Router_Interface::assemble
	 */
	public function assemble($userParams, $name = null, $reset = false, $encode = true) {
		
	}

}