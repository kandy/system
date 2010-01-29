<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Application
 * @subpackage Resource
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * Resource for initializing the locale
 *
 * @uses       Zend_Application_Resource_ResourceAbstract
 * @category   Zend
 * @package    Zend_Application
 * @subpackage Resource
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
//WARN: temp moved to system, remove on pressent in Zend !!!
class System_Application_Resource_Log
	extends Zend_Application_Resource_ResourceAbstract
{
	/**
	 * @var Zend_Log
	 */
	protected $_log;

	/**
	 * Defined by Zend_Application_Resource_Resource
	 *
	 * @return Zend_Log
	 */
	public function init()
	{
		return $this->getLog();
	}

	/**
	 * Attach logger
	 * 
	 * @param  Zend_Log $log 
	 * @return Zend_Application_Resource_Log
	 */
	public function setLog(Zend_Log $log)
	{
		$this->_log = $log;
		return $this;
	}

	public function getLog()
	{
		if (null === $this->_log) {
			$options = $this->getOptions();
			$log = new Zend_Log;
			foreach($options as $name => $writer) {
				$className = 'Zend_Log_Writer_'.ucfirst($name); 
				$log->addWriter(new $className($writer));
			}
			$this->setLog($log);
		}
		return $this->_log;
	}
	
	/**
	 * Factory to construct the logger and one or more writers
	 * based on the configuration array
	 *
	 * @param  array|Zend_Config Array or instance of Zend_Config
	 * @return Zend_Log
	 */
	static public function factory($config = array())
	{
		if ($config instanceof Zend_Config) {
			$config = $config->toArray();
		}

		if (!is_array($config) || empty($config)) {
			/** @see Zend_Log_Exception */
			require_once 'Zend/Log/Exception.php';
			throw new Zend_Log_Exception('Configuration must be an array or instance of Zend_Config');
		}


		return $log;
	}
}
