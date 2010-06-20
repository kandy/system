<?php
/**
 * @package system.view
 */
class System_View_Json extends System_View_Simple
{
	protected $_vars = array(
		'success' => true
	);

	/**
	 * Render
	 * @param $name
	 * @return string
	 */
	public function render($name) {
		return Zend_Json::encode($this->_vars);
	}
}
