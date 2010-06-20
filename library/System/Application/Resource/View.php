<?php
/**
 * Class use to bootstrap View resource
 *
 * @package system.application.resource
 */
class System_Application_Resource_View extends Zend_Application_Resource_View 
{
	/**
	 * @see Zend_Application_Resource_View::init
	 */
	public function init() {
		$view = $this->getView();

		$viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer($view, $this->getOptions());
		Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);

		return $view;
	}
	/**
	 * Zend_Application_Resource_View::getView
	 */
	public function getView() {
		if (null === $this->_view) {
			$options = $this->getOptions();
			$class = isset($options['class']) ? $options['class'] : 'Zend_View'; // Default class Zend_View
			if (class_exists($class, true)) {
				$this->_view = new $class($options);
			} else {
				throw new Zend_Application_Resource_Exception('Class '. $class .' not exists');
			}
		}
		return $this->_view;
	}
}
