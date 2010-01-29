<?php
require_once 'PHPUnit/Framework.php';

/**
 * Test class for System_Controller_Action_Helper_GridFilter_Date.
 * Generated by PHPUnit on 2009-09-30 at 12:31:20.
 */
class System_Controller_Action_Helper_GridFilter_DateTest extends PHPUnit_Framework_TestCase
{
	
	/**
	 * @var System_Controller_Action_Helper_GridFilter_Date
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->object = new System_Controller_Action_Helper_GridFilter_Date;
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
	}
	
	
	/**
	 */
	public function testFilter()
	{
		$class = $this->object;
		$class->setFilterData(array('value'=> ''));
		$class->setField('field');
		$select = new Zend_Db_Select($this->getMockForAbstractClass('Zend_Db_Adapter_Abstract', array(),  '',  false));
		$class->filter($select);
		self::assertEquals(array("(DATE(field) = DATE('".date(DATE_ISO8601, $_SERVER['REQUEST_TIME'])."') )"), $select->getPart(Zend_Db_Select::WHERE));
	}

	public function provider()
	{
		return array(
			array('2009-01-02', 'lt', '<'),
			array('2009-01-02', 'gt', '>'),
		);
	}
	
	
	
	/**
	 * @dataProvider provider
	 */
	public function testFilterUse($value, $compData, $comp)
	{
		$class = $this->object;
		$class->setFilterData(array('value'=> $value, 'comparison'=>$compData));
		$class->setField('field');
		$select = new Zend_Db_Select($this->getMockForAbstractClass('Zend_Db_Adapter_Abstract', array(),  '',  false));
		$class->filter($select);
		self::assertEquals(array("(field ".$comp." ('".date(DATE_ISO8601, strtotime($value))."') )"), $select->getPart(Zend_Db_Select::WHERE));
	}
	
	
}
?>
