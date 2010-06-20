<?php
/**
 * Abstract class for filter
 * Implement abstract factory pattern
 *
 * @author Andrii Kasian <to.kandy@gmail.com>
 * @package system.controller.action.helper.gridfilter
 */
abstract class System_Controller_Action_Helper_GridFilter_Abstract
	implements System_Controller_Action_Helper_GridFilter_Filter {
		
	/**
	 * Plugin loader
	 *
	 * @var Zend_Loader_PluginLoader_Interface
	 */
	private static $pluginLoader = null;

	/**
	 * Filter date in format:
	 *  array(
	 *  'value' => ...,
	 *  'comparison' => ...
	 *  )
	 * @var array
	 */
	protected $_data = array();

	/**
	 * @var string
	 */
	protected $_field = null;

	/**
	 * Get plugin loader
	 *
	 * @return Zend_Loader_PluginLoader_Interface
	 */
	public static function getPluginLoader() {
		if (self::$pluginLoader == null) {
			$pluginLoader = new Zend_Loader_PluginLoader();
			$pluginLoader->addPrefixPath(str_replace('Abstract','',__CLASS__), dirname(__FILE__));
			self::$pluginLoader = $pluginLoader;
		}
		return self::$pluginLoader;
	}

	/**
	 * Set plugin loader
	 *
	 * @param Zend_Loader_PluginLoader_Interface $pluginLoader
	 */
	public static function setPluginLoader(Zend_Loader_PluginLoader_Interface $pluginLoader = null){
		self::$pluginLoader = $pluginLoader;
	}

	/**
	 * Factory class
	 *
	 * @param array $data
	 * @return System_Controller_Action_Helper_GridFilter_Filter
	 */
	public static function factory($data) {
		$class = $data['type'];

		$loader = self::getPluginLoader();
		$loader->load($class, true);
		$className = $loader->getClassName($class);

		$filter = new $className();
		$filter->setFilterData($data);
		return $filter;
	}

	/**
	 * @see library/System/Controller/Action/Helper/GridFilter/System_Controller_Action_Helper_GridFilter_Filter#setField($field)
	 */
	public function setField($field) {
		$this->_field = $field;
	}

	/**
	 * @see library/System/Controller/Action/Helper/GridFilter/System_Controller_Action_Helper_GridFilter_Filter#setFilterData($data)
	 */
	public function setFilterData($data) {
		$this->_data = $data;
	}

	/**
	 * Get comparison
	 *
	 * @return string
	 */
	protected function _getComparison() {
		return "=";
	}

	/**
	 * Get values in right format
	 *
	 * @return mixed
	 */
	protected function _getValue() {
		return (string) $this->_data['value'];
	}

	/**
	 * @see library/System/Controller/Action/Helper/GridFilter/System_Controller_Action_Helper_GridFilter_Filter#filter($select)
	 */
	public function filter(Zend_Db_Select $select){
		$select->where($this->_field. ' ' . $this->_getComparison() . ' (?) ', $this->_getValue());
	}
}
