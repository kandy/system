<?php
class System_Application_Resource_Cli extends Zend_Application_Resource_ResourceAbstract
{
	
	protected $_getOptRules = array(
		'module|m-s' => 'Module name (optional)',
		'controller|c=s' => 'Controller name (required)',
		'action|a=s' => 'Action name (required)'
	);
	protected $_getopt = null;
	
	public function init()
	{
		$this->getBootstrap()
			->getResource('FrontController')
			->setRequest(new System_Controller_Request_Cli($this->getGetOpt()))
			->setResponse(new Zend_Controller_Response_Cli())
			->setRouter(new System_Controller_Router_Cli);
	}

	// CLI specific methods for option management
	public function setGetOpt(Zend_Console_Getopt $getopt)
	{
		$this->_getopt = $getopt;
	}

	public function getGetOpt()
	{
		if (is_null($this->_getopt)) {
			$this->_getopt = new Zend_Console_Getopt($this->getOptionRules());
		}
		return $this->_getopt;
	}

	public function getOptionRules()
	{
		return $this->_getOptRules;
	}
}