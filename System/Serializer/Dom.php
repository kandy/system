<?php
/**
 * Class for serialization into DomDocument representation
 */
class System_Serializer_Dom
{
	/**
	 * Plugin loader
	 * @var Zend_Loader_PluginLoader_Interface
	 */
	protected static $pluginLoader = null;
	
	/**
	 * plugins for native type
	 * @var array
	 */
	protected static $nativePlugins = array(
		'_boolean' => 'Native',
		'_integer' => 'Native',
		'_double' => 'Native',
		'_string' => 'Native',
		'_null' => 'Native',
		'_array' => 'Array',
		'_resource' => 'Resource'
	);
	
	/**
	 * Registred plugin
	 * @var array
	 */
	protected $plugins = array(
		'Zend_Form' => 'Form',
		'Zend_Db_Table_Row_Abstract' => 'Row',
		'Zend_Db_Table_Rowset_Abstract' => 'Row',
		'Exception'=>'Exception',
		'DOMDocument'=>'DomDocument'
	);
	/**
	 * Set plugin name for native type
	 * @param $nativeType
	 * @param $name
	 * @static
	 */
	public static function setPluginName($nativeType, $name) {
		self::$nativePlugins[$nativeType] = $name;
	}

	/**
	 * Get plugin name for native type
	 * @param $nativeType
	 * @param $name
	 * @static
	 */
	public static function getPluginName($nativeType) {
		return self::$nativePlugins[$nativeType];
	}
	
	/**
	 * Class constructor. Configure class use options
	 * @param array $options
	 */
	public function __construct($options = null) {
		if (isset($options['plugins']) && is_array($options['plugins'])) {
			foreach ($options['plugins'] as $interface => $plugin) {
				$this->addPlugin($interface, $plugin);
			}
		}
	}
	
	/**
	 * Add plugin to list register plugins
	 * @param string $interface
	 * @param string $plugin
	 */
	public function addPlugin($interface, $plugin)
	{
		$this->plugins[$interface] = $plugin;
	}
	
	/**
	 * Get list of register plugin
	 * @return array
	 */
	public function getPlugins() {
		return $this->plugins;
	}

	/**
	 * Get plugin loader
	 *
	 * @return Zend_Loader_PluginLoader_Interface
	 */
	public static function getPluginLoader() {
		if (self::$pluginLoader == null) {
			$pluginLoader = new Zend_Loader_PluginLoader();
			$pluginLoader->addPrefixPath(__CLASS__, 'System/Serializer/Dom');
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
	 * Get plugin by name
	 * @return System_Serializer_Dom_Interface
	 */
	public function getPlugin($pluginName) {
		$pluginClass = 'System_Serializer_Dom_'.$pluginName;
		if (class_exists($pluginClass, true)){
			$plugin = new $pluginClass();
		}else{
			
		}
		
		$loader = self::getPluginLoader();
		$loader->load($pluginName, false);

		if ($loader->isLoaded($pluginName)) {
			$pluginName = $loader->getClassName($pluginName);
			$plugin = new $pluginName();
		} else {
			throw new System_Serializer_Exception('Plugin class not found');
		}
		
		$plugin->setOwnerSerializer($this);
		return $plugin;
	}
	
	/**
	 * Get type of $value
	 * @param $value
	 * @return mixed
	 */
	protected function _getType($value) {
		$typeName = '_'.strtolower(gettype($value));
		if ($typeName == '_object') {
			$typeName = get_class($value);
		}
		return $typeName;
	}
	
	
	/**
	 * Find plugin based on value type
	 * @param $value
	 * @return unknown_type
	 */
	protected function _findPlugin($value) {
		$type = $this->_getType($value);
		if (isset(self::$nativePlugins[$type])){
			return  self::$nativePlugins[$type];
		}else{
			foreach ($this->getPlugins() as $interface => $plugin) {
				if ($value instanceof $interface){// if $interface not exists then throw fatal error
					return  $plugin;
				}
			}
		}
		
		throw new System_Serializer_Exception('Cannot serialize class '.get_class($value));
	}
	
	/**
	 * Serialize variable into DomDocument nodes
	 *
	 * @param mixed $value
	 * @param DomElement $element
	 */
	public function serialize($value, $parentElement) {
		$plugin = $this->getPlugin($this->_findPlugin($value));
		$plugin->serialize($value, $parentElement);
	}
}
