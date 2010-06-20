<?php
class System_Application_Resource_LazyModules extends Zend_Application_Resource_ResourceAbstract
{

	public function init() 
	{
	
		$modulePlugin = new System_Controller_Plugin_ModuleBootstrap();
		$modulePlugin->setBootstrap($this->getBootstrap());
		
		$this->getBootstrap()
			->bootstrap('FrontController')
			->getResource('FrontController')
			->registerPlugin($modulePlugin);
			
		return $modulePlugin;
	}
}