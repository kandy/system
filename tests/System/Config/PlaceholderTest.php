<?php
/**
 * @package system.tests
 */
class System_Config_PlaceholderTest extends System_Test_TestCase 
{
	/**
	 * @covers System_Config_Placeholder::__construct
	 * @covers System_Config_Placeholder::<protected>
	 */
	public function testConstructorUsingConfig() {
		$configRaw = new Zend_Config(array(
			'name' => 'Foo',
			'greeting' => 'Hello, %{name}'
		));
		
		$config = new System_Config_Placeholder($configRaw);
		
		self::assertEquals('Hello, Foo', $config->greeting);
	}
	
	/**
	 * @covers System_Config_Placeholder::__construct
	 * @covers System_Config_Placeholder::<protected>
	 */
	public function testConstructorUsingArray() {
		$configRaw = array(
			'name' => 'Foo',
			'greeting' => 'Hello, %{name}'
		);
		
		$config = new System_Config_Placeholder($configRaw);
		
		self::assertEquals('Hello, Foo', $config->greeting);
	}
	
	/**
	 * @covers System_Config_Placeholder::__construct
	 * @covers System_Config_Placeholder::<protected>
	 */
	public function testConstructorDeep() {
		$configRaw = new Zend_Config(array(
			'name' => array(
				'secret' => 'Foo',
			),
			'messages' => array(
				'greeting' => 'Hello, %{name.secret}'
			),
		));
		
		$config = new System_Config_Placeholder($configRaw);
		
		self::assertEquals('Hello, Foo', $config->messages->greeting);
	}
	
	/**
	 * @covers System_Config_Placeholder::__construct
	 * @covers System_Config_Placeholder::<protected>
	 */
	public function testConstructorExceptionInvalidConfig() {
		$this->setExpectedException('Zend_Config_Exception', 'Config must be an array or Zend_Config');
		new System_Config_Placeholder('foo');
	}
	
	/**
	 * @covers System_Config_Placeholder::__construct
	 * @covers System_Config_Placeholder::<protected>
	 */
	public function testRecursivePlaceholders() {
		$configRaw = new Zend_Config(array(
			'bar' => 'bar',
			'foo' => '%{bar}',
			'name' => 'Name: %{foo}',
		));
		
		$config = new System_Config_Placeholder($configRaw);
		
		self::assertEquals('Name: bar', $config->name);
	}
	
	/**
	 * @covers System_Config_Placeholder::__construct
	 * @covers System_Config_Placeholder::<protected>
	 */
	public function testReverseRecursivePlaceholders() {
		$configRaw = new Zend_Config(array(
			'name' => 'Name: %{foo}',
			'foo' => '%{bar}',
			'bar' => 'bar',
		));
		
		$config = new System_Config_Placeholder($configRaw);
		
		self::assertEquals('Name: bar', $config->name);
	}
}
