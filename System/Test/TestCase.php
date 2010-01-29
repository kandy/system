<?php
// @codeCoverageIgnoreStart
/**
 * TestCase class with System-specific extra functionality.
 *
 * @package system.test
 */
class System_Test_TestCase extends PHPUnit_Framework_TestCase 
{
	/**
	 * Get Application
	 * 
	 * @return System_Application
	 */
	public function getApplication() {
		return System_Application::getInstance();
	}
	
	/**
	 * Asserts if class or object contains getter and setter methods and
	 * if they work as expected.
	 * If $object is string, then trying to test static getters and setters for
	 * corresponding class.
	 *
	 * @var object|string $object	Object reference or class name
	 * @var string $methodSuffix	Method suffix for getter and setter
	 * @var mixed $expected			Expected initial value or any possible value of expected type
	 * @var bool $exceptionIfNotSet	Is getter expected to throw exception if initially called before setter?
	 */
    static public function assertGetterAndSetter($object, $methodSuffix, $expected, $exceptionIfNotSet = false, $getterArguments = array(), $setterArguments = array()) {
    	$methodGetter = 'get' . $methodSuffix;
    	$methodSetter = 'set' . $methodSuffix;
    	
    	if ($exceptionIfNotSet) {
    		try {
    			call_user_func_array(array($object, $methodGetter), $getterArguments);
    			self::fail('Exception not thrown on initial getter call');
    		} catch (PHPUnit_Framework_AssertionFailedError $e) {
    			throw $e;
    		} catch (Exception $e) {}
    	} else {
    		$expectedType = gettype($expected);
    		if (is_object($expected)) {
    			$expectedType = get_class($expected);
    		}
    		
   			$default = call_user_func_array(array($object, $methodGetter), $getterArguments);
    		if (!is_null($default)) {
    			self::assertType($expectedType, $default, 'Default value for getter is of wrong type');
    		}
    	}

		if (empty($setterArguments)) {
			$setterArguments = array($expected);
		}
    	call_user_func_array(array($object, $methodSetter), $setterArguments);
    	$result = call_user_func_array(array($object, $methodGetter), $getterArguments);
    	self::assertSame($expected, $result, 'The value received from getter is not the same as the value passed to setter');
    }
}
// @codeCoverageIgnoreEnd
