<?php
class System_View_Xslt_PartialRenderer 
{
	/**
	 * @var DomDocument
	 */
	protected $_document = null;
	
	/**
	 * Content node
	 * @var DomElement
	 */
	protected $_contentNode= null;
	
	/**
	 * Template name
	 * @var string
	 */
	protected $_template = null;
	
	/**
	 * Path inflector
	 * @var Zend_Filter_Inflector
	 */
	protected static $_inflector = null;
	
	/**
	 * Process partial render
	 */
	public static function process($nodeList, $template) {
		$renderer = new self(); // @todo: use getInstance() ?
		$renderer->addNodes($nodeList);
		$renderer->setTemplate($template);
		return $renderer->render();	
	}
	
	/**
	 * Get path inflector
	 * @return Zend_Filter_Inflector
	 */
	public static function getInflector() {
		if (null === self::$_inflector) {
			$inflector = new Zend_Filter_Inflector();
			$inflector
				->addRules(array(
					':module'	 => array('Word_CamelCaseToDash', 'StringToLower'),
					':controller' => array('Word_CamelCaseToDash', new Zend_Filter_Word_UnderscoreToSeparator('/'), 'StringToLower', new Zend_Filter_PregReplace('/\./', '-')),
					':action'	 => array('Word_CamelCaseToDash', new Zend_Filter_PregReplace('#[^a-z0-9' . preg_quote('/', '#') . ']+#i', '-'), 'StringToLower'),
				))
				->setTarget('../application/modules/:module/views/scripts/:controller/:action.xsl');
			self::setInflector($inflector);
		}
		return self::$_inflector;
	}
	
	/**
	 * Set inflector
	 * @param Zend_Filter_Inflector $inflector
	 */
	public static function setInflector(Zend_Filter_Inflector $inflector) {
		self::$_inflector = $inflector;
	}
	
	/**
	 * Constructor
	 * @return unknown_type
	 */
	public function __construct() {
		$this->_document = new DOMDocument();
		//create structute /xml/content
		$this->_document->appendChild($this->_document->createElement('xml'));
		$this->_contentNode = $this->_document->createElement('content');
		$this->_document->documentElement->appendChild($this->_contentNode);
	}
	
	/**
	 * Add nodes to content node
	 * @param $nodes
	 */
	public function addNodes($nodes) {
		foreach ((array)$nodes as $node) {
			$this->_contentNode->appendChild($this->_document->importNode($node, true));
		}
	}
	/**
	 * Set template name
	 * @param $template In format module/controller/action
	 */
	public function setTemplate($template) {
		$templateParts = array_combine(
			array('action', 'controller', 'module'), 
			array_reverse(explode('/', $template)) 
		);
		$this->_template = self::getInflector()->filter($templateParts); 
	}
	/**
	 * Ger xslt processor
	 * @return XSLTProcessor
	 */
	protected function _getProcessor() {
		return new XSLTProcessor();
	}
	/**
	 * Process transform
	 * @return DomDocument
	 */
	public function render() {
		$processor = $this->_getProcessor();
		$processor->importStylesheet(DOMDocument::load($this->_template));
		return $processor->transformToDoc($this->_document);
	}
}