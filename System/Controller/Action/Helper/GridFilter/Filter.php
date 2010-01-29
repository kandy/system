<?php
/**
 * Grid filter inteface
 *
 * @author Andrii Kasian <to.kandy@gmail.com>
 * @package system.controller.action.helper.gridfilter
 */
interface System_Controller_Action_Helper_GridFilter_Filter {

	/**
	 * Set filter date
	 * @param $data
	 */
	public function setFilterData($data);

	/**
	 * Set filter field
	 * @param string $field
	 */
	public function setField($field);

	/**
	 * Add filter to select
	 * @param Zend_Db_Select $select
	 */
	public function filter(Zend_Db_Select $select);
}
