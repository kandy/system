<?php
/**
 * @package system.controller.action.helper
 */
class System_Controller_Action_Helper_Auth extends Zend_Controller_Action_Helper_Abstract
{
	/**
	 * User table name
	 * @var string
	 */
	protected $_tableName = 'User';
	
	
	/**
	 * Get auth resource
	 * @return Zend_Auth
	 */
	public function getAuth() {
		
		$bootstrap = $this->getActionController()->getInvokeArg('bootstrap');
		if (!($bootstrap instanceof Zend_Application_Bootstrap_Bootstrap)) {
			throw new System_Exception('Bootstrap is not injected');
		}
		return $bootstrap->getResource('Auth');
	}


	/**
	 * Get logined user
	 * @return Model_Row_User
	 */
	public function getUser() {
		$auth = $this->getAuth();
		if (!$auth->hasIdentity()) {
			throw new System_Exception('No set identity');
		}

		$user = System_Locator_TableLocator::getInstance()
			->get($this->_tableName)
			->find($auth->getIdentity()->id)
			->current();

		if ($user == null) {
			throw new System_Exception('No set user');
		}

		return $user;
	}
}
