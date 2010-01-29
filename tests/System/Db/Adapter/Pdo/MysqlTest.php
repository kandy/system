<?php
/**
 * @package tests
 * @covers System_Db_Adapter_Pdo_Mysql
 */
class System_Db_Adapter_Pdo_MysqlTest extends System_Test_DatabaseTestCase 
{
	/**
	 * @var System_Db_Table_Abstract
	 */
	protected $_testTable;
	
	protected function setUp() {
		// not calling parent::setUp
		$this->_createTestTable();
	}
	
	protected function tearDown() {
		parent::tearDown();
		
		$this->_dropTestTable();
	}
	
	protected function _createTestTable() {
		$tableName = uniqid('tbl');
		$sql = "CREATE TABLE $tableName (a INTEGER, PRIMARY KEY (a)) ENGINE=InnoDB";
		try {
			$this->getDb()->query($sql);
		} catch (Exception $e) {
			echo $e, "\n";
			throw $e;
		}
		
		$params = array(
			'name' => $tableName,
		);
		$this->_testTable = new System_Db_Table($params);
	}
	
	protected function _dropTestTable() {
		$tableName = $this->_testTable->info('name');
		$sql = "DROP TABLE $tableName";
		try {
			$this->getDb()->query($sql);
		} catch (Exception $e) {
			echo $e, "\n";
			throw $e;
		}
		$this->_testTable = null;
	}

	/**
	 * @covers System_Db_Adapter_Pdo_Mysql::_beginTransaction
	 * @covers System_Db_Adapter_Pdo_Mysql::_rollBack
	 */
	public function testTransactionRollback() {
		$this->getDb()->beginTransaction();
		
		$testRow = $this->_testTable->createRow();
		$testRow->a = 10;
		$testRow->save();
		
		$this->getDb()->rollBack();
		
		$this->setExpectedException('Zend_Db_Table_Row_Exception', 'Cannot refresh row as parent is missing');
		$testRow->refresh();
	}
	
	/**
	 * @covers System_Db_Adapter_Pdo_Mysql::_beginTransaction
	 * @covers System_Db_Adapter_Pdo_Mysql::_commit
	 */
	public function testTransactionCommit() {
		$this->getDb()->beginTransaction();
		
		$testRow = $this->_testTable->createRow();
		$testRow->a = 10;
		$testRow->save();
		
		$this->getDb()->commit();
		
		$testRow->refresh();
		self::assertEquals(10, $testRow->a);
	}
	
	/**
	 * @covers System_Db_Adapter_Pdo_Mysql::_beginTransaction
	 * @covers System_Db_Adapter_Pdo_Mysql::_rollBack
	 */
	public function testTransactionNestedRollback() {
		$this->getDb()->beginTransaction();
		$this->getDb()->beginTransaction();
		
		$testRow = $this->_testTable->createRow();
		$testRow->a = 10;
		$testRow->save();
		
		$this->getDb()->rollBack();
		$this->getDb()->rollBack();
		
		$this->setExpectedException('Zend_Db_Table_Row_Exception', 'Cannot refresh row as parent is missing');
		$testRow->refresh();
	}
	
	/**
	 * @covers System_Db_Adapter_Pdo_Mysql::_beginTransaction
	 * @covers System_Db_Adapter_Pdo_Mysql::_commit
	 */
	public function testTransactionNestedCommit() {
		$this->getDb()->beginTransaction();
		$this->getDb()->beginTransaction();
		
		$testRow = $this->_testTable->createRow();
		$testRow->a = 10;
		$testRow->save();
		
		$this->getDb()->commit();
		$this->getDb()->commit();
		
		$testRow->refresh();
		self::assertEquals(10, $testRow->a);
	}
	
	/**
	 * @covers System_Db_Adapter_Pdo_Mysql::_beginTransaction
	 * @covers System_Db_Adapter_Pdo_Mysql::_rollBack
	 */
	public function testTransactionNestedRollbackInstant() {
		$this->getDb()->beginTransaction();
		$this->getDb()->beginTransaction();
		
		$testRow = $this->_testTable->createRow();
		$testRow->a = 10;
		$testRow->save();
		
		$this->getDb()->rollBack();
		
		try {
			$testRow->refresh();
			self::fail('Expected exception not thrown');
		} catch (Zend_Db_Table_Row_Exception $e) {
			self::assertContains('parent is missing', $e->getMessage());
		}
		
		$this->getDb()->rollBack();
		
		try {
			$testRow->refresh();
			self::fail('Expected exception not thrown');
		} catch (Zend_Db_Table_Row_Exception $e) {
			self::assertContains('parent is missing', $e->getMessage());
		}
	}

	/**
	 * @covers System_Db_Adapter_Pdo_Mysql::_beginTransaction
	 * @covers System_Db_Adapter_Pdo_Mysql::_rollBack
	 */
	public function testTransactionNestedBeginTransactionAfterRollback() {
		$this->getDb()->beginTransaction();
		$this->getDb()->beginTransaction();
		$this->getDb()->rollBack();
		
		try {
			$this->getDb()->beginTransaction();
			self::fail('Expected exception not thrown');
		} catch (Zend_Db_Adapter_Exception $e) {
			self::assertContains('Cannot begin transaction while in rollback mode', $e->getMessage());
		}
	}
	
	/**
	 * @covers System_Db_Adapter_Pdo_Mysql::_beginTransaction
	 * @covers System_Db_Adapter_Pdo_Mysql::_commit
	 * @covers System_Db_Adapter_Pdo_Mysql::_rollBack
	 */
	public function testTransactionNestedCommitAfterRollback() {
		$this->getDb()->beginTransaction();
		$this->getDb()->beginTransaction();
		$this->getDb()->rollBack();
		
		try {
			$this->getDb()->commit();
			self::fail('Expected exception not thrown');
		} catch (Zend_Db_Adapter_Exception $e) {
			self::assertContains('Cannot commit while in rollback mode', $e->getMessage());
		}
	}
	
	/**
	 * @covers System_Db_Adapter_Pdo_Mysql::_rollBack
	 */
	public function testTransactionRollbackWithoutBegin() {
		try {
			$this->getDb()->rollBack();
			self::fail('Expected exception not thrown');
		} catch (Zend_Db_Adapter_Exception $e) {
			self::assertContains('Nested transactions error', $e->getMessage());
		}
	}
	
	/**
	 * @covers System_Db_Adapter_Pdo_Mysql::_commit
	 */
	public function testTransactionCommitWithoutBegin() {
		try {
			$this->getDb()->commit();
			self::fail('Expected exception not thrown');
		} catch (Zend_Db_Adapter_Exception $e) {
			self::assertContains('Nested transactions error', $e->getMessage());
		}
	}
}
