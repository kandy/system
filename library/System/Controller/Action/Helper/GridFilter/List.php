<?php
/**
 * List filter
 *
 * @author Andrii Kasian <to.kandy@gmail.com>
 * @package system.controller.action.helper.gridfilter
 */
class System_Controller_Action_Helper_GridFilter_List
	extends System_Controller_Action_Helper_GridFilter_Abstract
{
	/**
	 * @see library/System/Controller/Action/Helper/GridFilter/System_Controller_Action_Helper_GridFilter_Abstract#_getComparison()
	 */
	protected function _getComparison() {
		return 'in';
	}

	/**
	 * @see library/System/Controller/Action/Helper/GridFilter/System_Controller_Action_Helper_GridFilter_Abstract#_getValue()
	 */
	protected function _getValue() {
		$values = array();
		foreach (explode(',', parent::_getValue()) as $value){
			$values[] = trim($value);
		}
		return $values;
	}
}
