<?php
/**
 * @package system.tests
 */
class System_Application_Resource_ViewTest extends PHPUnit_Framework_TestCase 
{
	/**
	 * Helper name
	 * @var string
	 */
	const NAME = 'ViewRenderer';

	/**
	 * @var System_Application_Resource_View
	 */
	protected $_view;

	/**
	 * Backaped helper
	 * @var Zend_Controller_Action_Helper_ViewRenderer
	 */
	private $_helper = null;

	protected function setUp() {
		if (Zend_Controller_Action_HelperBroker::hasHelper(self::NAME)){
			$this->_helper = Zend_Controller_Action_HelperBroker::getExistingHelper(self::NAME);
			Zend_Controller_Action_HelperBroker::removeHelper(self::NAME);
		}
		$this->_view = new System_Application_Resource_View();
	}

	protected function tearDown() {
		if ($this->_helper !== null){
			Zend_Controller_Action_HelperBroker::addHelper($this->_helper);
		}
	}

	/**
	 * @covers System_Application_Resource_View::init
	 */
	public function testInit() {
		try {
			Zend_Controller_Action_HelperBroker::getExistingHelper(self::NAME);
			self::fail('Expected Zend_Controller_Action_Exception not thrown');
		} catch (Zend_Controller_Action_Exception $e) {
			self::assertContains('has not been registered', $e->getMessage());
		}

		self::assertType('Zend_View', $this->_view->init());

		$viewRenderer = Zend_Controller_Action_HelperBroker::getExistingHelper(self::NAME);
		self::assertType('Zend_Controller_Action_Helper_ViewRenderer', $viewRenderer);
	}

	/**
	 * @covers System_Application_Resource_View::getView
	 */
	public function testGetViewReturnsView() {
		self::assertType('Zend_View', $this->_view->getView());
	}

	/**
	 * @covers System_Application_Resource_View::getView
	 */
	public function testGetViewReturnsDifferentView() {
		$this->_view->setOptions(array('class' => 'System_Application_Resource_ViewTest_ViewMock'));
		self::assertType('System_Application_Resource_ViewTest_ViewMock', $this->_view->getView());
	}



	/**
	 * @covers System_Application_Resource_View::getView
	 */
	public function testGetNotExistsView() {
		$this->_view->setOptions(array('class' => 'NotExistsClass'));
		try{
			$this->_view->getView();
			self::fail('Not throw Zend_Application_Resource_Exception');
		}catch (Zend_Application_Resource_Exception $e){};
	}
}

class System_Application_Resource_ViewTest_ViewMock extends Zend_View 
{};
