<?php
/**
 * Interface for acl loader
 * @author Andrii Kasian <to.kandy@gmail.com>
 * @package system.acl.loader
 */
interface System_Acl_Loader_LoaderInterface {
	public function setOptions($options);
	public function setAcl(Zend_Acl $acl);

	/**
	 * Load acl
	 * @return Zend_Acl
	 */
	public function load();
}
