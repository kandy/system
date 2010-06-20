<?php
/**
 * Date filter
 *
 * @author Andrii Kasian <to.kandy@gmail.com>
 * @package system.controller.action.helper.gridfilter
 */
class System_Controller_Action_Helper_GridFilter_Date
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
		if (empty($this->_data['value']) || false === strtotime($this->_data['value'])) {
			return date(DATE_ISO8601, $_SERVER['REQUEST_TIME']);
		} else {
			return date(DATE_ISO8601, strtotime($this->_data['value']));
		}
	}

	/**
	 * @see library/System/Controller/Action/Helper/GridFilter/System_Controller_Action_Helper_GridFilter_Abstract#filter($select)
	 */
	public function filter(Zend_Db_Select $select){
		if ($this->_getComparison() == '=') {
			$select->where('DATE('.$this->_field. ') ' . $this->_getComparison() . ' DATE(?) ', $this->_getValue());
		} else {
			parent::filter($select);
		}
	}
}
