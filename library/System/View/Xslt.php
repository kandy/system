<?php
/**
 *	Xslt view 
 * @author kandy
 */
class System_View_Xslt extends System_View_Xml
{
	/**
	 * List of file  for includ in template 
	 * @var array string[]
	 */
	protected $_includes = array();
	/**
	 *
	 * @var XsltProcessor
	 */
	protected $_processor = null;
	/**
	 * Debug mode
	 * @var bool
	 */
	protected $_debugMode = false;
	
	/**
	 * Constructor
	 * Implement configurable object pattern 
	 * @param Array|Zend_Config $config
	 */
	public function __construct($config = null)
	{
		if ($config === null){
			$config = array();
		}
		
		parent::__construct((array)$config);
		$this->_processor = new XsltProcessor();
		$this->_processor->registerPHPFunctions('System_View_Xslt_PartialRenderer::process');
		if (isset($config['params'])) {
			$this->setParameters($config['params']);
		}
		
		if (isset($config['debugMode'])) {
			$this->setDebugMode($config['debugMode']);
		} 
	}
	
	/**
	 * Set debug mode 
	 * @param bool $mode
	 */
	public function setDebugMode($mode) {
		$this->_debugMode = $mode;
	}
	/**
	 * Set parameters to XsltProceror
	 * @param $parameter
	 */
	public function setParameters($parameter)
	{
		if ($parameter instanceof Zend_Config){
			$parameter = $parameter->toArray();
		}
		
		if (!is_array($parameter)) {
			return;
		}
		
		foreach ($parameter as $name => $value){
			$this->setParameter($name, (string)$value);
		}
	}
	
	/**
	 * Set parameter to XsltProceror
	 * @param string $name
	 * @param string $value
	 */
	public function setParameter($name, $value)
	{
		$this->_processor->setParameter('', (string)$name, (string)$value);
	}
	
	/**
	 * Return the template engine object
	 *
	 * Returns the object instance, as it is its own template engine
	 *
	 * @return XsltProcessor
	 */
	public function getEngine()
	{
		return $this->_processor;
	}
	
	/**
	 * Add file to list include file 
	 * @param $fileName
	 */
	public function addInclude($fileName)
	{
		$this->_includes[] = $fileName;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see library/System/View/System_View_Xml#render($name)
	 */
	public function render($name)
	{
		$domDocument = new DOMDocument('1.0', 'UTF-8');
		$element =  $domDocument->createElement('xml');
		$domDocument->appendChild($element);

		$this->_getSerializer()->serialize($this->_vars, $element);
		$xslTemplate = $this->_loadXslTemplate($this->_script($name));
		
		// @codeCoverageIgnoreStart
		if (false !== ($data = $this->_debug($domDocument, $xslTemplate))) {
			return $data;
		}// @codeCoverageIgnoreEnd
		$domDocument->formatOutput = true;
		return $this->_processTransform($domDocument, $xslTemplate);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see library/System/View/System_View_Simple#_script($name)
	 */
	protected function _script($name)
	{
		$scriptPaths = $this->getScriptPaths();
		if (0 == count($scriptPaths)) {
			throw new System_Exception('no view script directory set; unable to determine location for view script',
				$this);
		}

		foreach ($scriptPaths as $dir) {
			if (is_readable($dir . $name)) {
				return $dir . $name;
			}
		}

		$message = "script '$name' not found in path ("
				 . implode(PATH_SEPARATOR, $scriptPaths)
				 . ")";
		throw new System_Exception($message, $this);
	}
	
	// @codeCoverageIgnoreStart
	/**
	 * Use for debug xslt process
	 * @param DomDocument $domDocument
	 * @param DomDocument $xslTemplate
	 * @return unknown_type
	 */
	private function _debug(DomDocument $domDocument, DomDocument $xslTemplate)
	{
		if (!$this->_debugMode) {
			return false;
		}

		$domDocument->formatOutput=true;		
		$xmlContent = $domDocument->saveXml();
		
		$xsltContent = $xslTemplate->saveXml();

		file_put_contents("/tmp/result.xsl", $xsltContent);
		file_put_contents("/tmp/result.xml", $xmlContent);

		$request = Zend_Controller_Front::getInstance()->getRequest();
		if ($request instanceof Zend_Controller_Request_Abstract && null !== ($debugMode = $request->getParam('d'))) {
			switch ($debugMode) {
				case 'xml':
					Zend_Controller_Front::getInstance()->getResponse()->setHeader('Content-type', 'text/xml');
					return $xmlContent;
				break;
				case 'xsl':
					Zend_Controller_Front::getInstance()->getResponse()->setHeader('Content-type', 'text/xml');
					return $xsltContent;
				break;
			}
		}
		return false;
	}
	// @codeCoverageIgnoreEnd
	
	protected function _processTransform(DomDocument $domDocument, DomDocument $xslTemplate)
	{
		$this->_processor->importStylesheet($xslTemplate);
		
		$transformedXml = $this->_processor->transformToXml($domDocument);
		$transformedXml = str_replace(array("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n","<?xml version=\"1.0\"?>\n"), '', $transformedXml);
		return $transformedXml;
	}
	
	/**
	 * Add xlt include instuction to template document
	 * @param DomDocument $xslTemplate
	 */
	protected function _addIncludedFiles(DomDocument $xslTemplate)
	{
		foreach ($this->_includes as $fileName){
			foreach ($this->getScriptPaths() as $scriptPath){
				$dir 		= realpath($scriptPath);
				$importFile = $dir . DIRECTORY_SEPARATOR . $fileName;
				if (file_exists($importFile)){
					$xslIncludeNode = $xslTemplate->createElementNS('http://www.w3.org/1999/XSL/Transform', 'import');
					$xslIncludeNode->setAttribute('href', $importFile);
					$xslTemplate->documentElement->insertBefore($xslIncludeNode, $xslTemplate->documentElement->firstChild);
					break;
				}
			}
		}
	}
	
	/**
	 * Load xslt template
	 * @return DomDocument
	 */
	protected function _loadXslTemplate($file)
	{
		$xslTemplate = new DomDocument();
		$xslTemplate->substituteEntities = true;
		$xslTemplate->load($file);
		$this->_addIncludedFiles($xslTemplate);
		return $xslTemplate;
	}
}
