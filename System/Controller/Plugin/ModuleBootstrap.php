<?php

class System_Controller_Plugin_ModuleBootstrap extends Zend_Controller_Plugin_Abstract 
{
	/**
	 * @var Zend_Application_Bootstrap_BootstrapAbstract
	 */
	protected $_bootstrap = null;
	
	protected $_bootstrapedModules = array();
	
	/**
	 * Constructor
	 * @param array $options
	 **/
	public function __construct($options = null) {
		if ($options !== null) {
			$this->setOptions($options);
		}
	}

	public function setOptions($options) 
	{
		foreach ((array)$options as $name => $value) {
			$setter = 'set'.ucfirst($name);
			if (is_callable(array($this, $setter))) {
				$this->$setter($value);
			}
		}	
	}
	public function setBootstrap(Zend_Application_Bootstrap_BootstrapAbstract $bootstrap) 
	{
		$this->_bootstrap = $bootstrap;
	}
	
	/**
	 * get Fron Controller
	 * @return Zend_Controller_Front
	 */
	protected function _getFront() 
	{
		return Zend_Controller_Front::getInstance();
	}
	
	public function isBootsraped($module)
	{
		return isset($this->_bootstrapedModules[$module]);
	}
	
	
	/**
	 * Get bootstraps that have been run
	 *
	 * @return Array
	 */
	public function getExecutedBootstraps()
	{
		return $this->_bootstrapedModules;
	}
	
	/**
	 * Format a module name to the module class prefix
	 *
	 * @param  string $name
	 * @return string
	 */
	protected function _formatModuleName($name)
	{
		$name = strtolower($name);
		$name = str_replace(array('-', '.'), ' ', $name);
		$name = ucwords($name);
		$name = str_replace(' ', '', $name);
		return $name;
	}
	
	public function preDispatch(Zend_Controller_Request_Abstract $request) 
	{
		$module = $request->getModuleName();
		if (empty($module)) {
			$module = $this->_getFront()->getDefaultModule();
		}
		
		if (! $this->isBootsraped($module)) {
			$moduleDirectory = $this->_getFront()->getControllerDirectory($module);
			$bootstrapClass = $this->_formatModuleName($module) . '_LasyBootstrap';
			
			if (!class_exists($bootstrapClass, false)) {
				$bootstrapPath  = dirname($moduleDirectory) . '/LasyBootstrap.php';
				if (file_exists($bootstrapPath)) {
					$eMsgTpl = 'Bootstrap file found for module "%s" but bootstrap class "%s" not found';
					include_once $bootstrapPath;
					if (!class_exists($bootstrapClass, false)) {
						throw new Zend_Application_Resource_Exception(sprintf(
							$eMsgTpl, $module, $bootstrapClass
						));
					}
				} else {
					return;
				}
			}

			$moduleBootstrap = new $bootstrapClass($this->_bootstrap);
			$moduleBootstrap->bootstrap();
			$this->_bootstrapedModules[$module] = $moduleBootstrap;
		}
		
	}
}
