<?php
/**
 * Helper class for use filtered grid  App.grid.GridPanel (Ext) in javascript
 * @example
 * <code>
 //in action
	$table = $this->getTable('Table name');
	$select = $this->getHelper('GridFilter')->getSelect($table);
	//...add some where , group etc to $select
	$this->view->assign($this->getHelper('GridFilter')->getData($select));
	</code>
 * @package system.controller.action.helper
 */
class System_Controller_Action_Helper_GridFilter extends Zend_Controller_Action_Helper_Abstract
{
	/**
	 * Get basic select (select {$fields} from {$table}) and add filters, order and limit info from request
	 * @param Zend_Db_Table_Abstract $table
	 * @param string|array $fields array of fields or "*" Use as {@see Zend_Db_Select::from()} second params
	 * @return Zend_Db_Select
	 */
	public function getSelect(Zend_Db_Table_Abstract  $table, $fields = "*") {
		//create basic selects
		$select = $table->getAdapter()
			->select()
			->from($table->info(Zend_Db_Table_Abstract::NAME), $fields);

		$cols = $table->info(Zend_Db_Table::COLS);

		//add filters support
		foreach ((array) $this->getRequest()->getParam('filter') as $value){
			$field = $value['field'];
			if (in_array($field, $cols)) {
				$filter = System_Controller_Action_Helper_GridFilter_Abstract::factory($value['data']);
				$filter->setField($field);
				$filter->filter($select);
			}
		}

		//add sort
		$sortCol = $this->getRequest()->getParam('sort');
		if (in_array($sortCol, $cols)) {
			$select->order($sortCol.
				' '.
				$this->_getDirState('dir')
			);
		}

		//set limit
		$select->limit((int)$this->getRequest()->getParam('limit', 25), (int)$this->getRequest()->getParam('start'));

		return $select;
	}

	/**
	 * Get sort direction
	 * @param string $name dir param name
	 * @return string 'ASC' | 'DESC' | ''
	 */
	protected function _getDirState($name) {
		$dir = strtoupper($this->getRequest()->getParam($name));
		if ($dir == 'ASC' || $dir == 'DESC') {
			return $dir;
		} else {
			return '';
		}
	}


	/**
	 * Fetch data from Db end return records subset, full count records, success = true
	 * @param Zend_Db_Select $select
	 * @return array
	 */
	public function getData(Zend_Db_Select $select) {
		$adapter = new Zend_Paginator_Adapter_DbSelect($select);
		$result = array();
		$result['data'] = $select->query()->fetchAll();
		$result['total'] = $adapter->count();
		$result['success'] = true;
		return $result;
	}
}
