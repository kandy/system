<?php

class System_Controller_Plugin_SmartyStack extends Zend_Controller_Plugin_ActionStack
{
	/**
	 * Plugin options
	 * @var array
	 */
	protected $_options = array(
			'configPrefix' => 'xml',
			'defaultConfig' 	=> '_standard',
			'layoutPath' 	=> '/../layouts/',
			'nameFilter'	=> 'Word_CamelCaseToDash',
			'includeFile' 	=> ':controller/:action.xsl',
			'widgetName' 	=> ':action',
			'configSection' => 'stack',
		);
	/**
	 * Inflector
	 * @var Zend_Filter_Inflector
	 */
	protected $_inflector = null;
	
	/**
	 * Registry key under which actions are stored
	 * @var string
	 */
	protected $_registryKey = __CLASS__;
	
	/**
	 * ViewRenderer plugin
	 * @var Zend_Controller_Action_Helper_ViewRenderer
	 */
	protected $_renderPlugin;
	
	/**
	 *
	 * @var unknown_type
	 */
	protected $_scriptPaths = array();
	
	/**
	 * Script name
	 *
	 * @var string
	 */
	protected $_scriptName = '';
	
	/**
	 * Default view
	 * @var Zend_View_Interface
	 */
	protected $_view;
	
	/**
	 * is plugin enabled
	 *
	 * @var bool
	 */
	protected $_enabled = null;
	
	/**
	 *
	 * @var Zend_Config
	 */
	protected $_config = null;
	
	/**
	 *  Stack is loaded from config
	 * @var bool
	 */
	protected $_isStackInit = false;

	/**
	 * Valid keys for stack items
	 * @var array
	 */
	protected function getLayoutFileName(Zend_Controller_Request_Abstract $request){
		foreach ($this->_scriptPaths as $scriptPath) {
			$dir = $scriptPath. $this->_options['layoutPath'];
			$layoutName = $request->getControllerName().
					DIRECTORY_SEPARATOR.
					$request->getActionName().
					'.'.
					$this->_options['configPrefix'];
			if (file_exists($dir.$layoutName)){
				return $dir.$layoutName;
			}
				
			$layoutName = $request->getControllerName().
					DIRECTORY_SEPARATOR.
					$this->_options['defaultConfig'].
					'.'.
					$this->_options['configPrefix'];
			if (file_exists($dir.$layoutName)){
				return $dir.$layoutName;
			}
			
			$layoutName = $this->_options['defaultConfig'].'.'.$this->_options['configPrefix'];
			if (file_exists($dir.$layoutName)){
				return $dir.$layoutName;
			}
			
		}
		
	}
	
	public function enablePlugin(){
		$this->_enabled = true;
	}

	public function disablePlugin(){
		if ($this->_enabled !== null && $this->_enabled){
			$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
			$viewRenderer->setView($this->_view);
		}
		$this->_enabled = false;
	}
	
	/**
	 * routeShutdown callback
	 *
	 * @param Zend_Controller_Request_Abstract $request
	 */
	public function routeShutdown(Zend_Controller_Request_Abstract $request)
	{
		if ($request instanceof Zend_Controller_Request_Http &&	$request->isXmlHttpRequest()) {
			$this->disablePlugin();
			if ($request->getParam('_format') == 'xml') {
				$this->getResponse()->setHeader('Content-type', 'text/xml');
				$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
				$viewRenderer->setView(new System_View_Xml());
				return;
			}
			
			if ($request->getParam('_format') == 'xsl') {
				$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
				$script = $viewRenderer->getModuleDirectory().'/views/scripts/'.$viewRenderer->getViewScript($request->getActionName());
				if (file_exists($script)) {
					header('Content-type: text/xml');
					echo file_get_contents($script);
					die();
				} else {
					throw new Zend_Controller_Action_Exception('Script '.$script.' not found');
				}
			}
			
		}
		if ($this->_enabled === null || $this->_enabled) {
			$this->enablePlugin();
			$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
			$this->_view = $viewRenderer->view;
			$viewRenderer->setView(new System_View_Simple());
		}
	}
	
	
	/**
	 * Get request poll
	 *
	 * @param Zend_Config $config
	 * @return Zend_Controller_Request_Abstract
	 */
	protected function getRequestPoll(Zend_Config $config){
		$poll = array();

		if (isset($config->requests) && isset($config->requests->request)){//if stack pressent
			//add all request to stack
			$requests =  $config->requests->request;
			$requests->rewind();
			if ( $requests->key() !== 0) {
				$requestArray = array($requests);
			} else {
				$requestArray = $requests;
			}
			
			foreach ($requestArray as $requestInfo) {
				array_unshift($poll, new Zend_Controller_Request_Simple(
						(string)$requestInfo->action,
						(string)$requestInfo->controller,
						(string)$requestInfo->module,
						$requestInfo->params ? $requestInfo->params->toArray(): array()
					)
				);
			}
		}

		return $poll;
	}
	
	/**
	 * Init stack
	 *
	 * @param Zend_Controller_Request_Abstract $request
	 */
	protected function initStack(Zend_Controller_Request_Abstract $request)
	{
		$this->_isStackInit = true;
				
		$layoutFileName = $this->getLayoutFileName($request);
		if ($layoutFileName) {
			$this->_config = new Zend_Config_Xml($layoutFileName, $this->_options['configSection']);
			foreach ($this->getRequestPoll($this->_config) as $request) {
				// add request to stack
				$this->pushStack($request);
			}
		}
	}
	
	/**
	 * postDispatch() plugin hook -- check for actions in stack, and dispatch if any found
	 *
	 * @param  Zend_Controller_Request_Abstract $request
	 * @return void
	 */
	public function postDispatch(Zend_Controller_Request_Abstract $request)
	{
		// Don't move on to next request if this is already an attempt to
		// forward
		if (!$request->isDispatched()) {
			return;
		}

		
		if ($this->_enabled) {
		 	$helper = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
			
			if (!$this->_isStackInit) {
	
				$this->_scriptPaths = $helper->view->getScriptPaths();
				$this->_scriptName = $helper->getViewScript();
				
				$this->initStack($request);
		
				$this->_vars['content'] = $helper->view->getVars();
				$this->_vars['widgets'] = array();
				
			} else {
				$vars = $helper->view->getVars();

				$module 	= Zend_Filter::filterStatic($request->getModuleName(), $this->_options['nameFilter']);
				$controller = Zend_Filter::filterStatic($request->getControllerName(), $this->_options['nameFilter']);
				$action 	= Zend_Filter::filterStatic($request->getActionName(), $this->_options['nameFilter']);
				
				$this->_checkStructure($module, $controller);
				$this->_vars['widgets'][$module][$controller][$action] = $vars;
			}
			
			$helper->view->clearVars();
			
			$next = $this->popStack();
			if ($next) {
				$this->forward($next);
			}
		}
	}

	
	private function _checkStructure($module, $controller){
		if (!isset($this->_vars['widgets'][$module])) {
			$this->_vars['widgets'][$module] = array();
		}
		if (!isset($this->_vars['widgets'][$module][$controller])) {
			$this->_vars['widgets'][$module][$controller] = array();
		}
	}
	
	/**
	 * Get inflector
	 *
	 * @return Zend_Filter_Inflector
	 */
	public function getInflector()
	{
		if (null === $this->_inflector) {
			$this->_inflector = new Zend_Filter_Inflector();
			$this->_inflector
				 ->addRules(array(
					 ':module'	 => array('Word_CamelCaseToDash', 'StringToLower'),
					 ':controller' => array('Word_CamelCaseToDash', new Zend_Filter_Word_UnderscoreToSeparator('/'), 'StringToLower', new Zend_Filter_PregReplace('/\./', '-')),
					 ':action'	 => array('Word_CamelCaseToDash', new Zend_Filter_PregReplace('#[^a-z0-9' . preg_quote('/', '#') . ']+#i', '-'), 'StringToLower'),
				 ));
		}
		return $this->_inflector;
	}

	/**
	 * Set inflector
	 *
	 * @param  Zend_Filter_Inflector $inflector
	 * @return Zend_Controller_Action_Helper_ViewRenderer Provides a fluent interface
	 */
	public function setInflector(Zend_Filter_Inflector $inflector, $reference = false)
	{
		$this->_inflector = $inflector;
		return $this;
	}
	
	
	/**
	 * Called before Zend_Controller_Front exits its dispatch loop.
	 *
	 * @return void
	 */
	public function dispatchLoopShutdown()
	{
		if ($this->_enabled){
			foreach ($this->_scriptPaths as $scriptPath) {
				$this->_view->addScriptPath($scriptPath);
			}
	
			$this->_view->assign($this->_vars);
			
			if ($this->_view instanceof System_View_Xslt) {// autoinclude file if suport
				if (isset($this->_config)){
					if (isset($this->_config->layout)) {
						$this->_view->addInclude((string)$this->_config->layout);
					}
					$inflector = $this->getInflector();
					$inflector->setTarget($this->_options['includeFile']);
					
					foreach ($this->getRequestPoll($this->_config) as $request) {
						$this->_view->addInclude(
							$this->getInflector()->filter(
								array(
									$request->getModuleKey() 	=> $request->getModuleName(),
									$request->getControllerKey()=> $request->getControllerName(),
									$request->getActionKey() 	=> $request->getActionName(),
								)
							)
						);
					}
				}
			}
			
			$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
			$viewRenderer->setView($this->_view);
			$viewRenderer->renderScript($this->_scriptName);
		}
	}
	
}
