<?php

require_once 'Zend/Application.php';
require_once 'Zend/Registry.php';


/**
 * System Application holds configuration, initializes environment and bootstrap
 *
 * @author oleg
 * @package system.application
 */
class System_Application extends Zend_Application
{
	/**
	 * Key name for Zend_Registry where instance of application lives
	 * @var string
	 */
	const APPLICATION = 'application';
	
	protected static $configDir = '/application/configs';
	/**
	 * Constructor
	 *
	 * Initialize application. Potentially initializes include_paths, PHP
	 * settings, and bootstrap class.
	 *
	 * @param  string $environment
	 * @param  string|array|Zend_Config $optionsOrRootDir String path to configuration file, or array/Zend_Config of configuration options
	 * @throws Zend_Application_Exception When invalid options are provided
	 * @return void
	 */
	public function __construct($environment, $optionsOrRootDir = null) {
		if (is_string($optionsOrRootDir) && is_dir($optionsOrRootDir)) {
			$this->rootDir = $optionsOrRootDir;
			parent::__construct($environment, $this->rootDir.self::$configDir.'/application.common.ini');
		} else {
			parent::__construct($environment, $optionsOrRootDir);
		}

		$this->getAutoloader()->registerNamespace('System_');
		Zend_Registry::set(self::APPLICATION, $this);
	}
	
	/**
	 * Get config dir
	 * @return string
	 */
	public static function getConfigDir(){
		return self::$configDir;
	}
	
	/**
	 * Set config dir
	 * @param $dir
	 */
	public static function setConfigDir($dir){
		self::$configDir = $dir;
	}
	
	/**
	 * Return previously configurated application from Zend_Registry
	 *
	 * @return System_Application
	 */
	public static function getInstance() {
		$application = Zend_Registry::get(self::APPLICATION);
		if (!($application instanceof Zend_Application)) {
			throw new System_Exception('Instance not configured');
		}
		return $application;
	}

	protected function _loadConfig($file) {
		if (extension_loaded('apc')) {
			$success = false;
			$config = apc_fetch('zend_config', $success);
			if ($success && $config) {
				return $config;
			} else {
				$config = $this->_loadConfigFromFile($file);
				apc_add('zend_config', $config, 60);
				return $config;
			}
		} else {
			return $this->_loadConfigFromFile($file);
		}
	}

	protected function _loadConfigFromFile($file) {
		$environment = $this->getEnvironment();
		$configCommon = new Zend_Config_Ini($file, $environment, true);

		$configCustom = new Zend_Config_Ini(str_replace('.common.ini', '.ini', $file), $environment);
		$configCommon->merge($configCustom);

		if (isset($this->rootDir)){
			$configCommon->path->root = $this->rootDir;
		}
		require_once 'System/Config/Placeholder.php';
		$config = new System_Config_Placeholder($configCommon);
		return $config->toArray();
	}
}
