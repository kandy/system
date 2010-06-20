<?php
/**
 * String filter
 *
 * @author Andrii Kasian <to.kandy@gmail.com>
 * @package system.controller.action.helper.gridfilter
 */
class System_Controller_Action_Helper_GridFilter_String
	extends System_Controller_Action_Helper_GridFilter_Abstract 
{
	/**
	 * @see library/System/Controller/Action/Helper/GridFilter/System_Controller_Action_Helper_GridFilter_Abstract#_getComparison()
	 */
	protected function _getComparison() {
		return 'like';
	}

	/**
	 * @see library/System/Controller/Action/Helper/GridFilter/System_Controller_Action_Helper_GridFilter_Abstract#_getValue()
	 */
	protected function _getValue() {
		return  '%'.parent::_getValue().'%';
	}
}
