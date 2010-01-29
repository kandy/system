<?php
/**
 *	Implement NestedSets behavior for table
 */
class System_Db_Table_NestedSets extends System_Db_Table 
{
	const ROOT_NODE_ID = 1;
	
	/**
	 * Inserts a new row.
	 *
	 * @param  array  $data  Column-value pairs.
	 * @return mixed		 The primary key of the row inserted.
	 */
	public function insert(array $data)
	{

		if (empty($data['parentId'])) {
			throw new Zend_Db_Exception('Do not set parent node');
		}
		try{
			$this->getAdapter()->beginTransaction();
			
			$parentNode = $this->find($data['parentId'])->current();
			if (! $parentNode instanceof Zend_Db_Table_Row_Abstract) {
				throw new Zend_Db_Exception('Do not found parent node');
			}
			
			$upadeteRL = array(
				'rgt'=> new Zend_Db_Expr('rgt + 2'),
				'lft'=> new Zend_Db_Expr('IF(lft > '.((int)$parentNode->rgt).', lft + 2, lft)'),
			);
			parent::update($upadeteRL, 'rgt >= '.$parentNode->rgt);
			
			$data['lft'] = $parentNode->rgt;
			$data['rgt'] = $parentNode->rgt + 1; 
			$data['level'] = $parentNode->level + 1;
			
			$pKey = parent::insert($data);
			$this->getAdapter()->commit();
		}catch (Exception $e) {
			$this->getAdapter()->rollBack();
			throw $e;
		}
		
		return $pKey;
	}
	
	/**
	 * Updates existing rows.
	 *
	 * @param  array		$data  Column-value pairs.
	 * @param  array|string $where An SQL WHERE clause, or an array of SQL WHERE clauses.
	 * @return int		  The number of rows updated.
	 */
	public function update(array $data, $where)
	{	
		if (isset($data['lft']) || isset($data['rgt']) || isset($data['level'])|| isset($data['parentId'])) {
			throw new Zend_Db_Exception('Cannot move node'); //@todo implement node move;
		}
		$tableSpec = ($this->_schema ? $this->_schema . '.' : '') . $this->_name;
		return $this->_db->update($tableSpec, $data, $where);
	}
	
	/**
	 * Deletes existing rows.
	 *
	 * @param  array|string $where SQL WHERE clause(s).
	 * @return int		  The number of rows deleted.
	 */
	public function delete($where)
	{
		throw new Zend_Db_Exception('Cannot delete node');
	}
}