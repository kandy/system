<?php
require_once 'PHPTAL.php';

class System_View_Phptal extends Zend_View_Abstract
{
	/**
	 * Engine
	 * @var PHPTAL
	 */
	protected $_engine;

    /**
	 * Values starage
	 * @var array
	 */
	protected $_vars = array();

	public function __construct($config = array()) {
		parent::__construct($config);
		if (isset($config['phptal'])){
			System_Options::setOptions($this->getEngine(), (array)$config['phptal']);
		}
	}

	/**
	 * @see Zend_View_Interface::__isset
	 */
	public function  __set($name,  $value) {
		$this->_vars[$name] = $value;
	}
    
	/**
	 * @see Zend_View_Interface::__get
	 */
	public function __get($key) {
		if (isset($this->_vars[$key])) {
			return $this->_vars[$key];
		} else {
			return null;
		}
	}

	/**
	 * @see Zend_View_Interface::__isset
	 */
	public function __isset($key) {
		return isset($this->_vars[$key]);
	}

	/**
	 * @see Zend_View_Interface::__unset
	 */
	public function __unset($key) {
		unset($this->_vars[$key]);
	}

	/**
	 * @see Zend_View_Interface::assign
	 */
	public function assign($spec, $value = null) {
		if (is_array($spec)) {
			foreach ($spec as $k => $v) {
				$this->__set($k, $v);
			}
		} else {
            $this->__set($spec, $value);
		}
	}

	/**
	 * @see Zend_View_Abstract::getVars
	 */
	public function getVars() {
		return $this->_vars;
	}

	/**
	 * @see Zend_View_Interface::clearVars
	 */
	public function clearVars() {
		$this->_vars = array();
	}
    
	/**
	 * @see Zend_View_Abstract::getEngine
	 * @return PHPTAL
	 */
	public function getEngine(){
		if ($this->_engine === null) {
			$this->_engine = new PHPTAL();
		}
		return $this->_engine;
	}

    /**
	 * @see Zend_View_Abstract::_run
	 */
	protected function _run() {
		$engine = $this->getEngine();
        foreach ($this->_vars as $key => $value) {
            $this->getEngine()->set($key,  $value);    
        }
        $this->getEngine()->set('this', $this);
		$engine->setTemplate(func_get_arg(0));
		return $engine->echoExecute();
	}
}
