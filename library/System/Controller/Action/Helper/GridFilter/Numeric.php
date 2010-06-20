<?php
/**
 * Numeric filter
 *
 * @author Andrii Kasian <to.kandy@gmail.com>
 * @package system.controller.action.helper.gridfilter
 */
class System_Controller_Action_Helper_GridFilter_Numeric
	extends System_Controller_Action_Helper_GridFilter_Abstract
{

	/**
	 * @see library/System/Controller/Action/Helper/GridFilter/System_Controller_Action_Helper_GridFilter_Abstract#_getComparison()
	 */
	protected function _getComparison() {
		if (isset($this->_data['comparison'])) {
			$comparison = $this->_data['comparison'];
		} else {
			$comparison = '';
		}
		switch ($comparison) {
			case 'lt':
				return '<';
				break;
			case 'gt':
				return '>';
				break;
			default:
				return parent::_getComparison();
		}
	}

	/**
	 * @see library/System/Controller/Action/Helper/GridFilter/System_Controller_Action_Helper_GridFilter_Abstract#_getValue()
	 */
	protected function _getValue() {
		if (empty($this->_data['value']) || !is_numeric($this->_data['value'])) {
			return 0;
		} else {
			return $this->_data['value'];
		}
	}
}
