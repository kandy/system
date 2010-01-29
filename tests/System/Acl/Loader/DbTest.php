<?php
require_once 'PHPUnit/Framework.php';

/**
 * Test class for System_Acl_Loader_Db.
 * Generated by PHPUnit on 2009-09-22 at 11:49:36.
 */
class System_Acl_Loader_DbTest extends PHPUnit_Framework_TestCase 
{

	/**
	 * @var System_Acl_Loader_Db
	 */
	protected $object;

	/**
	 * @var Zend_Acl
	 */
	protected $acl;

	protected $tables = array();
	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @covers System_Acl_Loader_Db
	 */
	protected function setUp() {
		$this->tables = array();
		$this->acl = new Zend_Acl();
		$this->object = new System_Acl_Loader_Db($this->acl);

	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
		foreach ($this->tables as $tableName => $table) {
			System_Locator_TableLocator::getInstance()->set($tableName, $table);
		}
	}

	private function createTableMocks($tableName, $returnData = null) {
		$tableMock = $this->getMock('Model_Table_'.$tableName, array('fetchAll'));

		$rows = array();
		if (is_array($returnData)) {
			foreach ($returnData as $aRow) {
				$rows[] = (object)$aRow;
			}
		}

		$tableMock
			->expects($this->once())
			->method('fetchAll')
			->will($this->returnValue($rows));

		$this->tables[$tableName] = System_Locator_TableLocator::getInstance()->get($tableName);
		System_Locator_TableLocator::getInstance()->set($tableName, $tableMock);
		return $tableMock;
	}

	public function testDefaultoptions() {
		$options = array(
			'tables' => array(
				'roles' => 'AclRole',
				'resources' => 'AclResource',
				'rules' => 'AclRule'
			));
		self::assertEquals($options, $this->object->getOptions());
	}

	/**
	 * @covers System_Acl_Loader_Db::load
	 * @covers System_Acl_Loader_Db::<protected>
	 */
	public function testLoadRolesNoParent() {
		$this->setExpectedException('Zend_Acl_Role_Registry_Exception');

		$this->createTableMocks('AclResource');
		$this->createTableMocks('AclRule');
		$this->createTableMocks('AclRole', array(
			array('name' => 'test', 'parent' => 'test1')
		));

		$acl = $this->object->load();

	}

	/**
	 * @covers System_Acl_Loader_Db::load
	 * @covers System_Acl_Loader_Db::<protected>
	 */
	public function testLoadRoles() {
		$this->createTableMocks('AclResource');
		$this->createTableMocks('AclRule');

		$this->createTableMocks('AclRole', array(
			array('name' => 'test', 'parent' => null),
			array('name' => 'test1', 'parent' => 'test'),
		));

		$this->object->load();
		self::assertTrue($this->acl->hasRole('test'));
		self::assertTrue($this->acl->hasRole('test1'));
		self::assertTrue($this->acl->inheritsRole('test1', 'test', true));
	}


	/**
	 * @covers System_Acl_Loader_Db::load
	 * @covers System_Acl_Loader_Db::<protected>
	 */
	public function testLoadResourcesNoParent() {
		$this->setExpectedException('Zend_Acl_Exception');
		$this->createTableMocks('AclRole');
		$this->createTableMocks('AclRule');

		$this->createTableMocks('AclResource', array(
			array('name' => 'test1', 'parent' => 'test'),
		));

		$acl = $this->object->load();
	}

	/**
	 * @covers System_Acl_Loader_Db::load
	 * @covers System_Acl_Loader_Db::<protected>
	 */
	public function testLoadResources() {
		$this->createTableMocks('AclRule');
		$this->createTableMocks('AclRole');
		$this->createTableMocks('AclResource', array(
			array('name' => 'test', 'parent' => null),
			array('name' => 'test1', 'parent' => 'test'),
		));

		$this->object->load();
		self::assertTrue($this->acl->has('test'));
		self::assertTrue($this->acl->has('test1'));
		self::assertTrue($this->acl->inherits('test1', 'test'));
	}

	/**
	 * @covers System_Acl_Loader_Db::load
	 * @covers System_Acl_Loader_Db::<protected>
	 */
	public function testLoadRules() {
		$this->createTableMocks('AclRole');
		$this->createTableMocks('AclResource');

		$this->createTableMocks('AclRule', array(
			array('role' => 'test', 'resource' => 'test', 'access' => true),
			array('role' => 'test1', 'resource' => 'res-res_res', 'access' => true),
			array('role' => 'test1', 'resource' => 'res1', 'access' => true),
		));

		$this->object->load();
		self::assertTrue($this->acl->isAllowed('test', 'test'));
		self::assertTrue($this->acl->isAllowed('test1', 'res1'));


		self::assertTrue($this->acl->has('res'));
		self::assertTrue($this->acl->inherits('res.res', 'res', true));
		self::assertTrue($this->acl->has('res.res'));
		self::assertTrue($this->acl->has('res.res.res'));

		self::assertFalse($this->acl->isAllowed('test1', 'res'));
		self::assertFalse($this->acl->isAllowed('test1', 'res.res'));
		self::assertTrue($this->acl->isAllowed('test1', 'res.res.res'));
	}
}
?>