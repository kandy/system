<?php
/**
 * Class for dynamic load acl from options
 *
 * @author Andrii Kasian <to.kandy@gmail.com>
 * @package system.acl.loader
 */
class System_Acl_Loader_Options extends System_Acl_Loader 
{
	/**
	 * Separators for explode
	 */
	const PART_SEPARATOR = '.';

	/**
	 * Acl options part name
	 */
	const RULE_ALLOW = 'allow';
	const RULE_DENY = 'deny';
	const RESOURCES = 'resources';
	const ROLES = 'roles';
	const ACL = 'acl';

	/**
	 * Alias for PART_SEPARATOR
	 * @var string
	 */
	protected $_separators = '-_/:';

	/**
	 * Load acl from options
	 * @see library/System/Acl/Loader/System_Acl_Loader_LoaderInterface#load()
	 * @return Zend_Acl
	 */
	public function load() {
		if (isset($this->_options[self::ACL])) {
			$this->_configureResources($this->_options[self::ACL]);
			$this->_configureRoles($this->_options[self::ACL]);
			$this->_configureRules($this->_options[self::ACL], self::RULE_ALLOW);
			$this->_configureRules($this->_options[self::ACL], self::RULE_DENY);
		}

		return $this->_acl;
	}

	/**
	 * Replace alias separator to PART_SEPARATOR
	 * @param $string
	 * @return string
	 */
	protected  function _transformSeparator($string) {
		return strtr($string, $this->_separators, str_repeat(self::PART_SEPARATOR, strlen($this->_separators)));
	}

	/**
	 * Normalize parent role
	 * @param $parent
	 * @return string|null
	 */
	protected function _normalizeParentRole($parent){
		if (empty($parent)) {
			return null;
		} else {
			$parent = (string) $parent;
			return $parent;
		}
	}

	/**
	 * Normalize parent resource
	 * @param $parent
	 * @return string|null
	 */
	protected function _normalizeParentResource($parent){
		if (empty($parent)) {
			return null;
		} else {
			return (string) $parent;
		}
	}

	/**
	 * Configure resources
	 * @param array $config
	 */
	protected function _configureResources($config){
		if (!isset($config[self::RESOURCES]) || !is_array($config[self::RESOURCES])) {
			return;
		}
		foreach ($config[self::RESOURCES] as $resourceName => $parent) {
			$this->_acl->addResource(
				$this->_transformSeparator($resourceName, $this->_separators) ,
				$this->_normalizeParentResource($parent)
			);
		}
	}

	/**
	 * Configure roles
	 * @param array $config
	 */
	protected function _configureRoles($config){
		if (!isset($config[self::ROLES]) || !is_array($config[self::ROLES])) {
			return;
		}
		foreach ($config[self::ROLES] as $roleName => $parent) {
			$this->_acl->addRole($roleName, $this->_normalizeParentRole($parent));
		}

	}

	/**
	 * Get role by name
	 * @param $roleName
	 * @return Zend_Acl_Role_Interface
	 */
	protected function _getRole($roleName) {
		if (!$this->_acl->hasRole($roleName)) {
			$this->_acl->addRole($roleName);
		}
		return $this->_acl->getRole($roleName);
	}

	/**
	 * Get resource by name
	 * If $recursiveAdd is true then  recursive add all part as parent
	 *  example for a.b.c => a set as parent for a.b, a.b set as parent for a.b.c
	 * @param $roleName
	 * @return Zend_Acl_Resource_Interface
	 */
	protected function _getResource($resourceName, $recursiveAdd = true) {
		$resourceName = $this->_transformSeparator($resourceName);

		if (!$this->_acl->has($resourceName)) {
			$parentRole = null;
			$curentResourceName = '';
			foreach (explode(self::PART_SEPARATOR, $resourceName) as $resourceNamePart){
				$curentResourceName = trim($curentResourceName. self::PART_SEPARATOR.$resourceNamePart, self::PART_SEPARATOR);
				if (!$this->_acl->has($curentResourceName)) {
					$this->_acl->addResource($curentResourceName, $parentRole);
				}
				$parentRole = $this->_acl->get($curentResourceName);
			}
		}
		return $this->_acl->get($resourceName);
	}

	/**
	 * Configure access rules
	 * @param array $config
	 * @param string $rule  allow,deny
	 * @param bool $recursiveAdd
	 */
	protected function _configureRules($config, $rule, $recursiveAdd = true){
		if (!isset($config[$rule]) || !is_array($config[$rule])) {
			return;
		}
		foreach ($config[$rule] as $roleName => $resources) {
			foreach ($resources as $resourceName) {
				$this->_acl->{$rule}(
					$this->_getRole($roleName),
					$this->_getResource($resourceName, $recursiveAdd)
				);
			}
		}
	}
}
