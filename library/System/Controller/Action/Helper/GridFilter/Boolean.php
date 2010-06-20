<?php
/**
 * Boolean filter
 *
 * @author Andrii Kasian <to.kandy@gmail.com>
 * @package system.controller.action.helper.gridfilter
 */
class System_Controller_Action_Helper_GridFilter_Boolean
	extends System_Controller_Action_Helper_GridFilter_Abstract
{
	/**
	 * @see library/System/Controller/Action/Helper/GridFilter/System_Controller_Action_Helper_GridFilter_Abstract#_getValue()
	 */
	protected function _getValue() {
		return empty($this->_data['value'])?0:1;
	}
}
