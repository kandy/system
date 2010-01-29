<?php
/**
 * @package model.row
 */
class Model_Row_User extends System_Db_Table_Row_Abstract
	implements Zend_Acl_Role_Interface 
{
	/**
	 * System user id 
	 * @var integer
	 */
	const SYSTEM_USER_ID = 1;
	/**
	 * Returns the user sponsor
	 *
	 * @return Model_Row_User
	 */
	public function getSponsor() {
		return $this->getTable()->find($this->sponsorId)->current();
	}

	/**
	 * Returns user's settings
	 *
	 * @return Model_Row_UserSettings
	 */
	public function getUserSettings() {
		$userSettingsRowSet = $this->findDependentRowset('UserSettings');
		$userSettings = $userSettingsRowSet->current();
		if ($userSettings == null) {
			return array();
		} else {
			return $userSettings;
		}
	}

	/**
	 * Returns user's account by its type
	 *
	 * @param string $typeId
	 * @return Model_Row_Account
	 */
	public function getAccountByTypeId($typeId) {
		$accountTable = System_Locator_TableLocator::getInstance()->get('Account');
		$select = $accountTable->select()
			->where('ownerId=?', $this->id)
			->where('typeId=?', $typeId);
		return $accountTable->fetchRow($select);
	}

	/**
	 * Get role for acl
	 * @see Zend_Acl_Role_Interface::getRoleId
	 */
	public function getRoleId() {
		return $this->role;
	}

	/**
	 * Checks if provided password is user password
	 * @param string $password
	 * @return boolean
	 */
	public function isUserPassword($password) {
		return ($this->password == $this->_getPasswordHash($password));
	}

	/**
	 * Return passwrod hash
	 * @param string $password
	 * @return string
	 */
	protected function _getPasswordHash($password) {
		return md5($password);
	}

	/**
	 * Sets user password
	 * @param string $password
	 * @return Model_Row_User
	 */
	public function setPassword($password) {
		$this->password = $this->_getPasswordHash($password);
		return $this;
	}
}
