<?php
/**
 * @package model.table
 */
class Model_Table_UserSettings extends System_Db_Table_Abstract 
{
	protected $_name = 'UserSettings';
	protected $_rowClass = 'Model_Row_UserSettings';

	protected $_referenceMap = array(
		'User' => array(
			'columns' => 'userId',
			'refTableClass' => 'Model_Table_User',
			'refColumns' => 'id'
		),
	);

}
