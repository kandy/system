<?php
require_once 'PHPUnit/Framework.php';

/**
 * Test class for System_View_Json.
 * Generated by PHPUnit on 2009-05-22 at 15:48:01.
 *
 * @package system.tests
 */
class System_View_JsonTest extends PHPUnit_Framework_TestCase 
{
	/**
	 * @var	System_View_Json
	 * @access protected
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 */
	protected function setUp() {
		$this->object = new System_View_Json;
	}

	/**
	 * @covers System_View_Json::render
	 */
	public function testRender() {
		$this->object->a = 'b';
		self::assertEquals(json_encode($this->object->getVars()), $this->object->render('asdasd'));
	}
}
