<?php
// @codeCoverageIgnoreStart
require_once 'Zend/Controller/Response/HttpTestCase.php';
/**
 * Class for test ajax resources
 *
 * @package system.test
 */
class System_Controller_Response_AjaxTestCase extends Zend_Controller_Response_HttpTestCase
{
	/**
	 * Get decoded ajax responce body
	 * @param bool $returnArray return array if true
	 * @return StdClass|Array
	 */
	public function getAjaxBody($returnArray = false) {
		return json_decode($this->outputBody(),$returnArray);
	}
}
// @codeCoverageIgnoreEnd
