<?php
// @codeCoverageIgnoreStart
/**
 * TestCase class with ajax extra functionality.
 *
 * @package system.test
 */
class System_Test_AjaxTestCase extends Zend_Test_PHPUnit_ControllerTestCase
{

	protected function setUp() {
		$this->_frontController = System_Application::getInstance()
			->getBootstrap()
			->getResource('FrontController');

		$this->frontController->setParam('bootstrap', System_Application::getInstance()->getBootstrap());
		$this->getRequest()->setBaseUrl($this->frontController->getBaseUrl());
		Zend_Session::$_unitTestEnabled = true;
	}

	/**
	 * Retrieve test case response object
	 *
	 * @return System_Controller_Response_AjaxTestCase
	 */
	public function getResponse() {
		if (null === $this->_response) {
			require_once 'System/Controller/Response/AjaxTestCase.php';
			$this->_response = new System_Controller_Response_AjaxTestCase;
		}
		return $this->_response;
	}
}
// @codeCoverageIgnoreEnd
