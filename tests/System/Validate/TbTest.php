<?php
/**
 * @package system.tests
 * @covers System_Validate_Tb
 */
class System_Validate_TbTest extends System_Test_TestCase
{
	private $validator;

	protected function setUp() {
		$this->validator = new System_Validate_Tb();
	}

	/**
	 * @covers System_Validate_Tb::isValid
	 */
	public function testIsValidEmpty() {
		$a = null;
		self::assertFalse($this->validator->isValid($a));
		$a = '';
		self::assertFalse($this->validator->isValid($a));
	}

	public static function isValidFalseProvider() {
		return array(
			array('0.001'),
			array('a'),
			array(''),
		);
	}

	/**
	 * @dataProvider isValidFalseProvider
	 * @covers System_Validate_Tb::isValid
	 */
	public function testIsValidFalse($value) {
		self::assertFalse($this->validator->isValid($value));
	}

	public static function isValidTrueProvider() {
		return array(
			array('0.01'),
			array('0'),
			array('0.'),
			array('0,01'),
			array('0,'),
		);
	}

	/**
	 * @dataProvider isValidTrueProvider
	 * @covers System_Validate_Tb::isValid
	 */
	public function testIsValidTrue($value) {
		self::assertTrue($this->validator->isValid($value));
	}
}
