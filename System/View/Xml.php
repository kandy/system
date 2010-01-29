<?php
class System_View_Xml extends System_View_Simple
{
	/**
	 * Content node name
	 * @var string
	 */
	protected $_partName = 'content';
	
	/**
	 * Constructor
	 * @param array $config
	 */
	public function __construct($config = array()) {
		parent::__construct($config);
		$this->_vars[$this->_partName] = array();
	}
	
	/**
	 *
	 * @return System_View_Xslt_XmlSerializer
	 */
	protected function _getSerializer(){
		return new System_Serializer_Dom();
	}
	
	public function render($name)
	{
		$domDocument = new DOMDocument('1.0', 'UTF-8');
		$element =  $domDocument->createElement('xml');
		$domDocument->appendChild($element);
		
		$this->_getSerializer()->serialize($this->_vars, $element);
		return $domDocument->saveXML();
	}
	
		/**
	 * (non-PHPdoc)
	 * @see library/System/View/System_View_Simple#__set($key, $val)
	 */
	public function __set($key, $val)
	{
		$this->_vars[$this->_partName][$key] = $val;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see library/System/View/System_View_Simple#__get($key)
	 */
	public function __get($key)
	{
		if (isset($this->_vars[$this->_partName][$key])) {
			return $this->_vars[$this->_partName][$key];
		} else {
			return null;
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see library/System/View/System_View_Simple#__isset($key)
	 */
	public function __isset($key)
	{
		return isset($this->_vars[$this->_partName][$key]);
	}

	/**
	 * (non-PHPdoc)
	 * @see library/System/View/System_View_Simple#__unset($key)
	 */
	public function __unset($key)
	{
		unset($this->_vars[$this->_partName][$key]);
	}
	
			
}
