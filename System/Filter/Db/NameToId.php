<?php
/**
 * @see Zend_Filter_Interface
 */
require_once 'Zend/Filter/Interface.php';

/**
 * Class to filter names to id from db table
 *
 * @package system.filter.db
 */
class System_Filter_Db_NameToId implements Zend_Filter_Interface 
{
	/**
	 * Table for get id
	 * @var Zend_Db_Table
	 */
	protected $_table = null;
	/**
	 * Name of field translated to id
	 * @var string
	 */
	protected $_field = null;

	/**
	 * Constructor
	 * @param $table string|Zend_Db_Table
	 * @param $field string
	 */
	public function __construct($table, $field) {
		if (!($table instanceof Zend_Db_Table_Abstract)) {
			$this->_table = System_Locator_TableLocator::getInstance()->get($table);
		} else {
			$this->_table = $table;
		}

		$this->_field = $field;
	}

	/**
	 * Returns id by name($value) from table
	 *
	 * @param  string $value
	 * @return string
	 */
	public function filter($value) {
		if ($value === null) {
			return null;
		}

		$select = $this->_table->select()
			->where($this->_field . ' = ?', $value);

		$row = $this->_table->fetchRow($select);
		if ($row !== null) {
			return $row[reset($this->_table->info(Zend_Db_Table::PRIMARY))];
		} else {
			return null;
		}
	}
}
