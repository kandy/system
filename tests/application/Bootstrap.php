<?php
/**
 * Application bootstrap.
 * Performs custom Application initialization.
 *
 * @package application.bootstrap
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap 
{
	/**
	 * Initialize custom autoloading rules.
	 */
	protected function _initAutoload() {
		$resourceLoader = new Zend_Loader_Autoloader_Resource(array(
			'basePath' => dirname(__FILE__),
			'namespace' => false
		));
		$resourceLoader->addResourceType('model', 'models', 'Model_');
		$resourceLoader->addResourceType('default-form', 'modules/default/forms', 'Form_');
	}
	
	/**
	 * Initialize path to Systems action helpers
	 */
	protected function _initActionHelperBrocker() {
		Zend_Controller_Action_HelperBroker::addPrefix('System_Controller_Action_Helper');
	}
}
