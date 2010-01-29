<?php
/**
 * Class for dynamic load acl from config files
 *
 * @author Andrii Kasian <to.kandy@gmail.com>
 * @package system.acl.loader
 */
class System_Acl_Loader_Config extends System_Acl_Loader_Options 
{
	/*
	 * @see library/System/Acl/Loader/System_Acl_Loader_Options#load()
	 */
	public function load() {
		if (isset($this->_options['config'])) {
			$config = $this->_loadConfig($this->_options['config']);
			$this->_options = $this->_mergeOptions($this->_options, $config->toArray());
		}
		return parent::load();
	}

	/**
	 * Load config file
	 *
	 * @param $config
	 * @return Zend_Config
	 */
	protected function _loadConfig($config) {
		switch (pathinfo($config, PATHINFO_EXTENSION)) {
			case 'ini' :
				return new Zend_Config_Ini($config, null, true);
			case 'xml' :
				return new Zend_Config_Xml($config, null, true);
			default :
				throw new Zend_Config_Exception();
		}
	}
}
