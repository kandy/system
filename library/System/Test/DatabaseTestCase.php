<?php
// @codeCoverageIgnoreStart
/**
 * TestCase class for testing database-related functionality
 *
 * @package system.test
 */
class System_Test_DatabaseTestCase extends System_Test_TestCase 
{

	/**
	 * Get DB Adapter.
	 * 
	 * Returns DB adapter configured in System_Application.
	 * 
	 * @return Zend_Db_Adapter_Abstract
	 */
	public function getDb() {
		
		return Zend_Db_Table::getDefaultAdapter();
	}

	/**
	 * Query SQL in application DB
	 * 
	 * @param string $sql
	 */
	public function query($sql) {
		try {
			$this->getDb()->query($sql);
		} catch (Exception $e) {
			echo $e, "\n";
			throw $e;
		}
	}
	
	/**
	 * Setup method.
	 * 
	 * Automatically begins transaction before each test method call.
	 */
	protected function setUp() {
		$this->getDb()->beginTransaction();
	}
	
	/**
	 * Tear down method.
	 * 
	 * Automatically rolls back transaction after each test method call.
	 */
	protected function tearDown() {
		while ($this->getDb()->getNestedTransactionsCounter() > 0) {
			$this->getDb()->rollBack();
		}
	}
}
// @codeCoverageIgnoreEnd
