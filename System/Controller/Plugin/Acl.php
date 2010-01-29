<?php
/** Zend_Acl */
require_once 'Zend/Acl.php';

/** Zend_Controller_Plugin_Abstract */
require_once 'Zend/Controller/Plugin/Abstract.php';

/**
 * @package system.controller.plugin
 */
class System_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract 
{
	/**
	 * @var Zend_Acl
	 **/
	protected $_acl;

	/**
	 * @var string|Zend_Acl_Role_Interface
	 **/
	protected $_role;

	/**
	 * if true then a.b.c registred as a.b.c and a.b and a
	 * @var bool
	 */
	protected $_autoRegisterResources = true;

	/**
	 * @var array
	 **/
	protected $_errorPage = array(
		'module' => 'default',
		'controller' => 'error',
		'action' => 'denied');

	/**
	 * Constructor
	 *
	 * @param mixed $aclData
	 * @param string|Zend_Acl_Role_Interface $defaultRole
	 * @return void
	 **/
	public function __construct(Zend_Acl $aclData, $defaultRole = 'guest') {
		$this->_role = $defaultRole;

		if (null !== $aclData) {
			$this->setAcl($aclData);
		}
	}

	/**
	 * Sets the ACL object
	 *
	 * @param mixed $aclData
	 * @return void
	 **/
	public function setAcl(Zend_Acl $aclData) {
		$this->_acl = $aclData;
	}

	/**
	 * Returns the ACL object
	 *
	 * @return Zend_Acl
	 **/
	public function getAcl() {
		return $this->_acl;
	}

	/**
	 * Sets the ACL role to use
	 *
	 * @param $role string|Zend_Acl_Role_Interface $role
	 * @return void
	 **/
	public function setRole($role) {
		$this->_role = $role;
	}

	/**
	 * Returns the ACL role used
	 *
	 * @return string
	 * @author
	 **/
	public function getRole() {
		return $this->_role;
	}

	/**
	 * Sets the error page
	 */
	public function setErrorPage($errorPage) {
		$this->_errorPage = $errorPage;
	}

	/**
	 * Returns the error page
	 *
	 * @return array
	 **/
	public function getErrorPage() {
		return $this->_errorPage;
	}

	protected function _registerResource($resourceName) {
		$parentRole = null;
		$currentResourceName = '';
		foreach (explode('.', $resourceName) as $resourceNamePart){
			$currentResourceName = trim($currentResourceName. '.'.$resourceNamePart, '.');
			if (!$this->_acl->has($currentResourceName)) {
				$this->_acl->addResource($currentResourceName, $parentRole);
			}
			$parentRole = $this->_acl->get($currentResourceName);
		}
	}

	public function getResource(Zend_Controller_Request_Abstract $request) {
		$resourceName = $request->getModuleName() . '.'. $request->getControllerName() . '.' .$request->getActionName();

		if (!$this->_acl->has($resourceName) && $this->_autoRegisterResources) {
			$this->_registerResource($resourceName);
		}

		return $resourceName;
	}

	protected function _getPrivilege(Zend_Controller_Request_Abstract $request) {
		$privilege = null;
		if ($request instanceof Zend_Controller_Request_Http) {
			$privilege = $request->getMethod();
		}
		return $privilege;
	}


	/**
	 * Pre dispatch hock
	 * Checks if the current user identified by role has rights to the requested url (module/controller/action)
	 * If not, it will call denyAccess to be redirected to errorPage
	 *
	 * @return void
	 **/
	public function preDispatch(Zend_Controller_Request_Abstract $request) {
		$this->setRequest($request);
		/** Check if the controller/action can be accessed by the current user */
		if (!$this->_acl->isAllowed($this->getRole(), $this->getResource($request), $this->_getPrivilege($request))) {
			/** Redirect to access denied page */
			$this->denyAccess();
		}
	}

	/**
	 * Deny Access Function
	 * Redirects to errorPage, this can be called from an action using the action helper
	 *
	 * @return void
	 **/
	public function denyAccess() {
		$this->_request->setModuleName($this->_errorPage['module']);
		$this->_request->setControllerName($this->_errorPage['controller']);
		$this->_request->setActionName($this->_errorPage['action']);
	}
}
