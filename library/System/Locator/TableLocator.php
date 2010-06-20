<?php
/**
 * Table Locator class.
 *
 * This is used to lazy-load Table Gateway instances.
 * It is used to test Table Gateway classes and classes that use TGs
 * It is used to cache TGs
 *
 * @package system.locator
 */
class System_Locator_TableLocator 
{
	/**
	 * Instance reference
	 *
	 * @var System_Locator_TableLocator
	 */
	static protected $_instance = null;

	/**
	 * Instance class name
	 *
	 * @var string
	 */
	static protected $_instanceClass = __CLASS__;

	/**
	 * Table Gateway class prefix
	 *
	 * @var string
	 */
	protected $_classPrefix = 'Model_Table_';

	/**
	 * Table Gateway instances
	 *
	 * @var array
	 */
	protected $_tables = array();

	/**
	 * Get Table Locator instance
	 *
	 * @return System_Locator_TableLocator
	 */
	static public function getInstance() {
		if (!self::$_instance) {
			self::setInstance(new self::$_instanceClass());
		}
		return self::$_instance;
	}

	/**
	 * Set Table Locator instance
	 *
	 * @var System_Locator_TableLocator
	 */
	static public function setInstance(System_Locator_TableLocator $instance) {
		self::$_instance = $instance;
	}

	// @codeCoverageIgnoreStart
	/**
	* Unset Table Locator instance
	* Should be used only inside unit tests.
	*/
	static public function unsetInstance() {
		self::$_instance = null;
	}
	// @codeCoverageIgnoreEnd

	/**
	 * Get Table reference
	 * 
	 * @param string $tableName
	 * @return System_Db_Table_Abstract
	 */
	public function get($tableName) {
		if (!isset($this->_tables[$tableName])) {
			$tableClass = $this->getTableClass($tableName);
			if (!class_exists($tableClass)) {
				throw new System_Exception("Table '$tableName' not found");
			}
			$this->set($tableName, new $tableClass());
		}
		return $this->_tables[$tableName];
	}

	/**
	 * Set Table reference
	 * 
	 * @param string $tableName
	 * @param System_Db_Table_Abstract $table
	 * @return System_Locator_TableLocator
	 */
	public function set($tableName, System_Db_Table_Abstract $table) {
		$this->_tables[$tableName] = $table;
		return $this;
	}

	/**
	 * Set Table Gateway class prefix
	 * 
	 * @var string $classPrefix
	 * @return System_Locator_TableLocator
	 */
	public function setClassPrefix($classPrefix) {
		$this->_classPrefix = $classPrefix;
		return $this;
	}

	/**
	 * Get Table Gateway class prefix
	 * 
	 * @return string
	 */
	public function getClassPrefix() {
		return $this->_classPrefix;
	}

	/**
	 * Get table class name by table name
	 * 
	 * @var string $tableName
	 * @return string
	 */
	public function getTableClass($tableName) {
		return $this->getClassPrefix() . $tableName;
	}
}
