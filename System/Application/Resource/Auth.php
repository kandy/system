<?php
/**
 * Class use to bootstrap Authentification resource
 *
 * @package system.application.resource
 */
class System_Application_Resource_Auth extends Zend_Application_Resource_ResourceAbstract 
{
	/**
	 * Init Zend_Auth resource
	 *
	 * @return Zend_Auth
	 */
	public function init() {
		$auth = Zend_Auth::getInstance();
		return $auth;
	}
}
