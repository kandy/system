<?php
/**
 * Class for dynamic load acl from Db
 * @author Andrii Kasian <to.kandy@gmail.com>
 * @package system.acl.loader
 * @abstract
 */
class System_Acl_Loader_Db extends System_Acl_Loader_Options 
{
	/**
	 * Options
	 * @var array
	 */
	protected $_options = array(
		'tables' => array(
			'roles' => 'AclRole',
			'resources' => 'AclResource',
			'rules' => 'AclRule'
		)
	);

	/**
	 * Get table
	 * @param $name
	 * @return System_Db_Table
	 */
	protected function _getTable($name) {
		return System_Locator_TableLocator::getInstance()
			->get($this->_options['tables'][$name]);
	}

	/**
	 * Load acl from db
	 * @see library/System/Acl/Loader/System_Acl_Loader_LoaderInterface#load()
	 */
	public function load() {
		$options = array();
		$options[self::ACL] = $this->_loadRules();
		$options[self::ACL][self::RESOURCES] = $this->_loadResources();
		$options[self::ACL][self::ROLES] = $this->_loadRoles();
		$this->_options = $this->_mergeOptions($this->_options, $options);

		return parent::load();
	}

	/**
	 * Load resources
	 * @param array $config
	 */
	protected function _loadResources(){
		$resources = array();
		foreach ($this->_getTable('resources')->fetchAll() as $resource) {
			$resources[$resource->name] = $resource->parent;
		}
		return $resources;
	}

	/**
	 * Load roles
	 * @param array $config
	 */
	protected function _loadRoles(){
		$roles = array();
		foreach ($this->_getTable('roles')->fetchAll() as $role) {
			$roles[$role->name] = $role->parent;
		}
		return $roles;
	}

	/**
	 * Load access rules
	 * @param bool $recursiveAdd
	 */
	protected function _loadRules($recursiveAdd = true){
		$rules = array();
		foreach ($this->_getTable('rules')->fetchAll() as $rule) {
			$type = $rule->access?self::RULE_ALLOW:self::RULE_DENY;
			if (!isset($rules[$type])) {
				$rules[$type] = array();
			}
			//TODO: add $rule->privileges support
			if (!isset($rules[$type][$rule->role])) {
				$rules[$type][$rule->role] = array();
			}
			$rules[$type][$rule->role][] = $rule->resource;
		}
		return $rules;
	}
}
