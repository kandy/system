<?php
/**
 * Implements console request
 * @author kandy
 *
 */
class System_Controller_Request_Cli extends Zend_Controller_Request_Abstract
{
	/**
	 * Options parser
	 * @var Zend_Console_Getopt
	 */
	protected $_getopt = null;
	
	/**
	 * Constructor 
	 * @param Zend_Console_Getopt $getopt
	 */
	public function __construct(Zend_Console_Getopt $getopt)
	{
		$this->_getopt = $getopt;
		try{
			$getopt->parse();
		} catch (Zend_Console_Getopt_Exception $e) {
			echo $e->getUsageMessage();
			exit(-1);
		}
		
		if ($getopt->{$this->getModuleKey()}) {
			$this->setModuleName($getopt->{$this->getModuleKey()});
		}
		if ($getopt->{$this->getControllerKey()}) {
			$this->setControllerName($getopt->{$this->getControllerKey()});
		}

		if ($getopt->{$this->getActionKey()}) {
			$this->setActionName($getopt->{$this->getActionKey()});
		}
	}
	/**
	 * Get options paser
	 * @return Zend_Console_Getopt
	 */
	public function getCliOptions()
	{
		return $this->_getopt;
	}
}
