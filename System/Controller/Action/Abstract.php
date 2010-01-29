<?php
/**
 * Abstract class for default actions
 * Provide helper methods
 *
 * @package system.controller.action
 */
abstract class System_Controller_Action_Abstract extends Zend_Controller_Action {
	/**
	 * Get table from Locator
	 * @param string $name
	 * @return System_Db_Table_Abstract
	 */
	public function getTable($name){
		return System_Locator_TableLocator::getInstance()->get($name);
	}
	
	/**
	 * Get Db from table from Locator
	 * @param string $name Use table
	 * @return Zend_Db_Adapter_Abstract
	 */
	public function getDb($name = 'User'){
		return $this->getTable($name)->getAdapter();
	}
	
	protected function _putFlashMessagesForAjaxRequests() { 
		if($this->getRequest()->isXmlHttpRequest()) {
			$this->view->flashMessages = $this->_formatFlashMessages(
				$this->_helper
					->getHelper('FlashMessenger')
					->getCurrentMessages()
				
			);
			$this->_helper->getHelper('FlashMessenger')->clearCurrentMessages();
		}
	}

	protected function _formatFlashMessages($messages) {
		$result = array();
		foreach ($messages as $entry) {
			// if the token is an array - it contains status
			if (count($entry)>1) {
				$result[] = array(
					'token' => $entry[0],
					'status'	=> $entry[1]
				);

			// else if the entry is just a string, then it is a success message
			} else {
				$result[] = array(
					'token'	=> $entry,
					'status'	=> 'success'
				);
			}
		}
		return $result;
	}
	
	protected function _redirect($url, array $options = array()) {
		if (!$this->getRequest()->isXmlHttpRequest() || (isset($options['force']) && $options['force'])) {
			parent::_redirect($url, $options);
		} else {
			//redirect sample
			$this->view->redirect = $url;
		}
	}
}
