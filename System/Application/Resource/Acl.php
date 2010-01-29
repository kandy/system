<?php
/**
 * Class use to bootstrap Acl resource
 *
 * @package system.application.resource
 */
class System_Application_Resource_Acl extends System_Application_Resource_ResourceAbstract 
{
	protected $_loaderClass = 'Options';

	public function setLoaderClass($class) {
		$this->_loaderClass = $class;
	}

	public function getLoaderClass() {
		return $this->_loaderClass;
	}

	/**
	 * Init Zend_Acl resource
	 *
	 * @return Zend_Acl
	 */
	public function init() {
		$this->bootstrapDependencies();

		$acl = new Zend_Acl();

		$loader = System_Acl_Loader::factory($this->getLoaderClass(), $acl, $this->getOptions());
		$loader->load();

		return $acl;
	}

}
