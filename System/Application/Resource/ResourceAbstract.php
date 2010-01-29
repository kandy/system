<?php
/**
 * Class for bootstrap dependent resources
 *
 * @author Andrii Kasian <to.kandy@gmail.com>
 * @package system.application.resource
 */
abstract class System_Application_Resource_ResourceAbstract extends Zend_Application_Resource_ResourceAbstract
{
	/**
	 * List of dependent resources
	 * @var array
	 */
	protected $_dependencies = array();

	/**
	 * Set list of dependent resources
	 * @param array $dependencies
	 */
	public function setDependencies($dependencies) {
		$this->_dependencies = (array) $dependencies;
	}

	/**
	 * Get list of dependent resources
	 * @return array
	 */
	public function getDependencies() {
		return $this->_dependencies;
	}


	/**
	 * Add dependent resource
	 * @param string $dependency
	 */
	public function addDependency($dependency) {
		$this->_dependencies[] = $dependency;
	}

	/**
	 * Bootstrap dependent resources
	 */
	public function bootstrapDependencies() {
		foreach ($this->getDependencies() as $dependency) {
			$this->getBootstrap()->bootstrap($dependency);
		}
	}
}
