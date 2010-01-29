<?php
require_once 'PHPUnit/Framework.php';

/**
 * Test class for System_Controller_Action_Helper_GridFilter_Boolean.
 * Generated by PHPUnit on 2009-09-30 at 12:18:27.
 */
class System_Controller_Action_Helper_GridFilter_BooleanTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var System_Controller_Action_Helper_GridFilter_Boolean
     */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->object = new System_Controller_Action_Helper_GridFilter_Boolean;
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
	}
	

	public function provider()
	{
		return array(
			array('true', 1),
			array('1', 1),
			array('null', 1),
			array('0', 0),
			array('', 0),
		);
	}
	
	/**
	 * @dataProvider provider
	 */
	public function testFilter($data, $value)
	{
		$class = $this->object;
		$class->setFilterData(array('value'=> $data));
		$class->setField('field');
		$select = new Zend_Db_Select($this->getMockForAbstractClass('Zend_Db_Adapter_Abstract', array(),  '',  false));
		$class->filter($select);
		self::assertEquals(array("(field = (".$value.") )"), $select->getPart(Zend_Db_Select::WHERE));
	}

}
?>
