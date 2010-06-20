<?php
abstract class System_Controller_Action_Cli extends System_Controller_Action_Abstract
{
	protected $_logger = null;
	public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
	{
		if (!$request instanceof System_Controller_Request_Cli) {
			throw new Zend_Controller_Action_Exception('Must run from console');
		}
		parent::__construct($request, $response, $invokeArgs);
		$this->getHelper('viewRenderer')->setNoRender();
	}

	
	public function getLog() {
		if ($this->_logger === null) {
			$this->_logger = $this->getInvokeArg('bootstrap')->getResource('Log');
		}
		return $this->_logger;
	}
	
	/**
	 * Pre-dispatch routines
	 *
	 * Called before action method. If using class with
	 * {@link Zend_Controller_Front}, it may modify the
	 * {@link $_request Request object} and reset its dispatched flag in order
	 * to skip processing the current action.
	 *
	 * @return void
	 */
	public function preDispatch()
	{
		$this->getLog()->info(" -=-=-=[ Start: ".$this->getRequest()->getActionName()." ]=-=-=-");
	}

	/**
	 * Post-dispatch routines
	 *
	 * Called after action method execution. If using class with
	 * {@link Zend_Controller_Front}, it may modify the
	 * {@link $_request Request object} and reset its dispatched flag in order
	 * to process an additional action.
	 *
	 * Common usages for postDispatch() include rendering content in a sitewide
	 * template, link url correction, setting headers, etc.
	 *
	 * @return void
	 */
	public function postDispatch()
	{
		$this->getLog()->info(" -=-=-=[  Done: ".$this->getRequest()->getActionName()." ]=-=-=-");
	}
}