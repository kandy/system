<?php
/**
 * Class for store values
 *
 * @package system.view
 */
class System_View_Simple extends Zend_View_Abstract 
{
	/**
	 * Values starage
	 * @var array
	 */
	protected $_vars = array();

	/**
	 *  @see Zend_View_Abstract::getEngine
	 */
	public function getEngine(){
		return __CLASS__;
	}

	/**
	 * @see Zend_View_Interface::__set
	 */
	public function __set($key, $val) {
		$this->_vars[$key] = $val;
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
				$this->assign($k, $v);
			}
		} else {
			$this->_vars[$spec] = $value;
		}
	}

	/**
	 * @see Zend_View_Abstract::_script
	 */
 	protected function _script($name) {
		return false;
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
	 * @see Zend_View_Abstract::_run
	 */
	protected function _run() {
		return '';
	}
}
