<?php
/**
 * Abstarct class for json action
 * By default set header Content-type: application/json
 *
 * @package system.controller.action
 */
abstract class System_Controller_Action_Json extends System_Controller_Action_Abstract {
	/**
	 * @param Zend_Controller_Request_Abstract $request
	 * @param Zend_Controller_Response_Abstract $response
	 * @param array $invokeArgs Any additional invocation arguments
	 * @return void
	 */
	public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array()) {
		parent::__construct($request, $response, $invokeArgs);
		
		$smartyStack = Zend_Controller_Front::getInstance()->getPlugin('System_Controller_Plugin_SmartyStack');
			
		if ($smartyStack instanceof System_Controller_Plugin_SmartyStack) {
			$smartyStack->disablePlugin();
		}
		$this->view = new System_View_Json();
		
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
		$viewRenderer->setView($this->view);
		
		$response->setHeader('Content-type', 'text/javascript', true);
	}
}
