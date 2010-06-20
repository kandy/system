<?php
/**
 * Class for dynamic load acl
 *
 * @author Andrii Kasian <to.kandy@gmail.com>
 * @package system.acl
 * @abstract
 */
abstract class System_Acl_Loader implements System_Acl_Loader_LoaderInterface {
	/**
	 * Plugin loader
	 * @var Zend_Loader_PluginLoader_Interface
	 */
	private static $pluginLoader = null;

	/**
	 * Acl
	 * @var Zend_Acl
	 */
	protected $_acl;
	/**
	 * Options
	 * @var array
	 */
	protected $_options = array();

	/**
	 * Get plugin loader
	 *
	 * @return Zend_Loader_PluginLoader_Interface
	 */
	public static function getPluginLoader() {
		if (self::$pluginLoader == null) {
			$pluginLoader = new Zend_Loader_PluginLoader();
			$pluginLoader->addPrefixPath('System_Acl_Loader', 'System/Acl/Loader');
			self::$pluginLoader = $pluginLoader;
		}
		return self::$pluginLoader;
	}

	/**
	 *	Set plugin loader
	 * @param Zend_Loader_PluginLoader_Interface $pluginLoader
	 */
	public static function setPluginLoader(Zend_Loader_PluginLoader_Interface $pluginLoader = null){
		self::$pluginLoader = $pluginLoader;
	}

	/**
	 * Factory class
	 * @param string $class
	 * @param Zend_Acl $acl
	 * @param array|Zend_Config $options
	 * @return System_Acl_Loader_LoaderInterface
	 */
	public static function factory($class, Zend_Acl $acl, $options = array()) {
		if ($options instanceof  Zend_Config) {
			$options = $options->toArray();
		}

		$loader = self::getPluginLoader();
		$loader->load($class, false);

		if ($loader->isLoaded($class)) {
			$className = $loader->getClassName($class);
			return new $className($acl, $options);
		} else {
			throw new Zend_Acl_Exception('Class '. $class. ' do not loaded');
		}
	}

	/**
	 * Class constructor
	 * @param Zend_Acl $acl
	 * @param array $options
	 */
	public function __construct(Zend_Acl $acl = null, $options= array()) {
		if ($acl instanceof  Zend_Acl) {
			$this->setAcl($acl);
		} else {
			$this->setAcl(new Zend_Acl);
		}
		$this->setOptions($this->_mergeOptions($this->_options, $options));
	}

	/**
	 * Merge options recursively
	 *
	 * @param  array $array1
	 * @param  mixed $array2
	 * @return array
	 */
	protected function _mergeOptions(array $array1, $array2 = null) {
		if (is_array($array2)) {
			foreach ($array2 as $key => $val) {
				if (is_array($array2[$key])) {
					$array1[$key] = (array_key_exists($key, $array1) && is_array($array1[$key]))
						? $this->mergeOptions($array1[$key], $array2[$key])
						: $array2[$key];
				} else {
					$array1[$key] = $val;
				}
			}
		}
		return $array1;
	}

	/**
	 * Set options
	 * @param array $options
	 */
	public function setOptions($options) {
		$this->_options = (array)$options;
	}

	/**
	 * Get options
	 * @return array
	 */
	public function getOptions() {
		return $this->_options;
	}

	/**
	 * Set acl
	 * @param $acl
	 */
	public function setAcl(Zend_Acl $acl) {
		$this->_acl = $acl;
	}

	/**
	 * Get acl
	 * @return Zend_Acl
	 */
	public function getAcl() {
		return $this->_acl;
	}
}