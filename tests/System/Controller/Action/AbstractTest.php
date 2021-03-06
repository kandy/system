<?php
require_once 'PHPUnit/Framework.php';

/**
 * Test class for System_Controller_Action_Abstract.
 * Generated by PHPUnit on 2009-09-29 at 16:32:58.
 */
class System_Controller_Action_AbstractTest extends System_Test_TestCase
{
	/**
	 * @var System_Controller_Action_Abstract
	 */
	protected $_object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$requestMock = $this->getMock('Zend_Controller_Request_Abstract');
		$responseMock = $this->getMock('Zend_Controller_Response_Abstract');
		$this->_object = new System_Controller_Action_AbstractMock($requestMock, $responseMock);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
		System_Locator_TableLocator::unsetInstance();
	}

	public function testGetTable() {
		$tableMock = $this->getMock('Model_Table_User');
		System_Locator_TableLocator::getInstance()->set('User', $tableMock);

		self::assertEquals($tableMock, $this->_object->getTable('User'));
	}
}

class System_Controller_Action_AbstractMock extends System_Controller_Action_Abstract
{
}
