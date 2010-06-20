<?php
/**
 * Abstarct class for json action
 * By default set header Content-type: application/json
 *
 * @package system.controller.action
 */
class System_Controller_Action_TableEditor extends System_Controller_Action_Json
{
	/**
	 * Editable table
	 * @var Zend_Db_Table
	 */
	protected $_table = null;
	
	protected function _getJsonData($name, $returnArray = false) {
		if (get_magic_quotes_gpc() == true) {
			throw new System_Exception('Magic quotes is on');
		}
		$data = json_decode($this->getRequest()->getParam($name), $returnArray);
		return $data;
	}
	
	protected function _onGrid(Zend_Db_Select $select){
	}
	
	public function gridAction() {
		$select = $this->getHelper('GridFilter')->getSelect($this->_table);
		$this->_onGrid($select);
		$this->view->assign($this->getHelper('GridFilter')->getData($select));
	}
	
	private function getRow($record) {
		$primary = $this->_table->info(Zend_Db_Table_Abstract::PRIMARY);
		$ids = array();
		foreach ((array)$primary as $id) {
			$ids[] = $record->$id;
		}
		return call_user_method_array('find', $this->_table, $ids)->current();
	}
	
	protected function _onRowCreate(System_Db_Table_Row_Abstract $row, $recordInfo) {
		$record = array();
		foreach ($recordInfo as $key=>$val) {
			if (substr($val, 0, strlen('ext-record')) !== 'ext-record') {
				$record[$key] = $val;
			}
		}
		$row->setFromArray($record);
	}
	
	protected function _onRowSave(System_Db_Table_Row_Abstract $row = null, $recordInfo) {
		if ($row === null) {
			throw new System_Exception('Row not found');
		}
		$primary = $this->_table->info(Zend_Db_Table_Abstract::PRIMARY);
		foreach ((array)$primary as $id) {
			unset($recordInfo->$id);
		}
		$row->setFromArray((array)$recordInfo);
	}
	
	public function createAction() {
		$data = $this->_getJsonData('data');
		if (!is_array($data)) { // if post one record
			$data = array($data);
		}
		$returnData = array();
		foreach ($data as $record) {
			$row = $this->_table->createRow();
			$this->_onRowCreate($row, $record);
			$row->save();
			$returnData[] = $row->toArray();
		}
		$this->view->data = $returnData;
	}
	
	public function saveAction() {
		$data = $this->_getJsonData('data');
		if (!is_array($data)) { // if post one record
			$data = array($data);
		}
		$returnData = array();
		foreach ($data as $record) {
			$row = $this->getRow($record);
			$this->_onRowSave($row, $record);
			$row->save();
			$returnData[] = $row->toArray();
		}
		
		$this->view->data = $returnData;
	}
	
	public function deleteAction() {
		$data = $this->_getJsonData('data');
		if (!is_array($data)) { // if post one record
			$data = array($data);
		}
		foreach ($data as $ids) {
			$ids = explode('-', $ids);
			$row = call_user_method_array('find', $this->_table, $ids)->current();
			$row->delete();
		}
	}
	
}
