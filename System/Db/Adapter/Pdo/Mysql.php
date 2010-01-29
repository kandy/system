<?php
/**
 * PDO MySQL adapter that emulates nested transactions support.
 *
 * @package system.db.adapter.pdo
 */
class System_Db_Adapter_Pdo_Mysql extends Zend_Db_Adapter_Pdo_Mysql 
{
	/**
	 * Nested transactions counter
	 * @var int
	 */
	protected $_nestedTransactionsCounter = 0;
	
	/**
	 * Are we in rollback mode?
	 * @var bool
	 */
	protected $_isInRollback = false;
	
	/**
	 * Get nested transactions counter
	 * @return int
	 */
	public function getNestedTransactionsCounter() {
		return $this->_nestedTransactionsCounter;
	}
	
	/**
	 * Begin transaction
	 * @return bool
	 */
	protected function _beginTransaction() {
		if ($this->_isInRollback) {
			throw new Zend_Db_Adapter_Exception('Cannot begin transaction while in rollback mode');
		}
		
		$this->_nestedTransactionsCounter++;
		if ($this->_nestedTransactionsCounter == 1) {
			return parent::_beginTransaction();
		}
		return true;
	}
	
	/**
	 * Commit transaction
	 * @return bool
	 */
	protected function _commit() {
		if ($this->_nestedTransactionsCounter <= 0) {
			throw new Zend_Db_Adapter_Exception('Nested transactions error');
		}
		
		if ($this->_isInRollback) {
			throw new Zend_Db_Adapter_Exception('Cannot commit while in rollback mode');
		}
		
		$this->_nestedTransactionsCounter--;
		if ($this->_nestedTransactionsCounter == 0) {
			return parent::_commit();
		}
		return true;
	}
	
	/**
	 * Rollback transaction
	 * @return bool
	 */
	protected function _rollBack() {
		if ($this->_nestedTransactionsCounter <= 0) {
			throw new Zend_Db_Adapter_Exception('Nested transactions error');
		}
		
		if (!$this->_isInRollback) {
			$result = parent::_rollBack();
			$this->_isInRollback = true;
		} else {
			$result = true;
		}
		
		$this->_nestedTransactionsCounter--;
		if ($this->_nestedTransactionsCounter == 0) {
			$this->_isInRollback = false;
		}
		
		return $result;
	}
}
