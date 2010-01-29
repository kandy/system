<?php
/**
 * @package tests
 * @covers System_Db_Adapter_Pdo_Mysql
 */
class System_Db_Table_NestedSetsTest extends System_Test_DatabaseTestCase 
{
	/**
	 * @var System_Db_Table_NestedSets
	 */
	private $table = null;
	 
	protected function setUp() {
		parent::setUp();
		$this->table = System_Locator_TableLocator::getInstance()->get('Tree');
	}
	
	public function testAddNode(){
		$node = $this->table->createRow();
		$node->parentId = System_Db_Table_NestedSets::ROOT_NODE_ID;
		$node->save();
		$id = $node->id;
		
		self::assertEquals(2, $node->lft);
		self::assertEquals(3, $node->rgt);
		self::assertEquals(1, $node->level);
		
		$node = $this->table->createRow();
		$node->parentId = $id; 
		$node->save();
		
		$node = $this->table->createRow();
		$node->parentId = $id; 
		$node->save();
		
		self::assertEquals(5, $node->lft);
		self::assertEquals(6, $node->rgt);
		self::assertEquals(2, $node->level);
		
	}
	public function testUpdate(){
		$this->setExpectedException('Zend_Db_Exception', 'Cannot move node');
		
		$node = $this->table->createRow();
		$node->parentId = System_Db_Table_NestedSets::ROOT_NODE_ID;
		$node->save();
		$node->parentId = 555;
		$node->save();
	}
	
	public function testDelete(){
		
		$node = $this->table->createRow();
		$node->parentId = System_Db_Table_NestedSets::ROOT_NODE_ID;
		$node->save();
		
	}
}	