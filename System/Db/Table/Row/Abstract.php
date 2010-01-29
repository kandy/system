<?php
// @codeCoverageIgnoreStart
/**
 * Row Gateway class that uses table locator to get related table's rows.
 * This overrides default Zend implementation that uses "new <TableClass>"
 * to get Table Gateway references.
 *
 * @package system.db.table.row
 */
abstract class System_Db_Table_Row_Abstract extends Zend_Db_Table_Row_Abstract {
	/**
	 * Query a dependent table to retrieve rows matching the current row.
	 *
	 * @param string|Zend_Db_Table_Abstract  $dependentTable
	 * @param string                         OPTIONAL $ruleKey
	 * @param Zend_Db_Table_Select           OPTIONAL $select
	 * @return Zend_Db_Table_Rowset_Abstract Query result from $dependentTable
	 * @throws Zend_Db_Table_Row_Exception If $dependentTable is not a table or is not loadable.
	 */
	public function findDependentRowset($dependentTable, $ruleKey = null, Zend_Db_Table_Select $select = null) {
		if (is_string($dependentTable)) {
			$dependentTable = System_Locator_TableLocator::getInstance()->get($dependentTable);
		}

		return parent::findDependentRowset($dependentTable, $ruleKey, $select);
	}

	/**
	 * Query a parent table to retrieve the single row matching the current row.
	 *
	 * @param string|Zend_Db_Table_Abstract $parentTable
	 * @param string                        OPTIONAL $ruleKey
	 * @param Zend_Db_Table_Select          OPTIONAL $select
	 * @return Zend_Db_Table_Row_Abstract   Query result from $parentTable
	 * @throws Zend_Db_Table_Row_Exception If $parentTable is not a table or is not loadable.
	 */
	public function findParentRow($parentTable, $ruleKey = null, Zend_Db_Table_Select $select = null) {
		if (is_string($parentTable)) {
			$parentTable = System_Locator_TableLocator::getInstance()->get($parentTable);
		}

		return parent::findParentRow($parentTable, $ruleKey, $select);
	}

	/**
	 * @param  string|Zend_Db_Table_Abstract  $matchTable
	 * @param  string|Zend_Db_Table_Abstract  $intersectionTable
	 * @param  string                         OPTIONAL $callerRefRule
	 * @param  string                         OPTIONAL $matchRefRule
	 * @param  Zend_Db_Table_Select           OPTIONAL $select
	 * @return Zend_Db_Table_Rowset_Abstract Query result from $matchTable
	 * @throws Zend_Db_Table_Row_Exception If $matchTable or $intersectionTable is not a table class or is not loadable.
	 */
	public function findManyToManyRowset($matchTable, $intersectionTable, $callerRefRule = null, $matchRefRule = null, Zend_Db_Table_Select $select = null) {
		if (is_string($matchTable)) {
			$matchTable = System_Locator_TableLocator::getInstance()->get($matchTable);
		}

		if (is_string($intersectionTable)) {
			$intersectionTable = System_Locator_TableLocator::getInstance()->get($intersectionTable);
		}

		return parent::findManyToManyRowset($matchTable, $intersectionTable, $callerRefRule, $matchRefRule, $select);
	}
	
	/**
	 * _getTableFromString
	 *
	 * @param string $tableName
	 * @return Zend_Db_Table_Abstract
	 */
	protected function _getTableFromString($tableName)
	{
		return System_Locator_TableLocator::getInstance()->get($tableName);
	}
}
// @codeCoverageIgnoreEnd
