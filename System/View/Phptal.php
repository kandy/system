<?php
require_once 'PHPTAL.php';

class System_View_Phptal extends System_View_Simple
{
	/**
	 * Engine
	 * @var PHPTAL
	 */
	protected $_engine;

	public function __construct($config = array()) {
		parent::__construct($config);
		if (isset($config['phptal'])){
			System_Options::setOptions($this->getEngine(), (array)$config['phptal']);
		}
	}

	/**
	 * @see Zend_View_Abstract::getEngine
	 * @return PHPTAL
	 */
	public function getEngine(){
		if ($this->_engine === null) {
			$this->_engine = new PHPTAL();
		}
		return $this->_engine;
	}

	public function  __set($name,  $value) {
		$this->getEngine()->set($name,  $value);
	}
	
    /**
     * Finds a view script from the available directories.
     *
     * @param $name string The base name of the script.
     * @return void
     */
    protected function _script($name)
    {
        if ($this->isLfiProtectionOn() && preg_match('#\.\.[\\\/]#', $name)) {
            require_once 'Zend/View/Exception.php';
            throw new Zend_View_Exception('Requested scripts may not include parent directory traversal ("../", "..\\" notation)');
        }

		$scriptPaths = $this->getScriptPaths();

        if (0 == count($scriptPaths)) {
            require_once 'Zend/View/Exception.php';
            throw new Zend_View_Exception('no view script directory set; unable to determine location for view script',
                $this);
        }

        foreach ($scriptPaths as $dir) {
            if (is_readable($dir . $name)) {
                return $dir . $name;
            }
        }

        require_once 'Zend/View/Exception.php';
        $message = "script '$name' not found in path ("
                 . implode(PATH_SEPARATOR, $scriptPaths)
                 . ")";
        throw new Zend_View_Exception($message, $this);
    }
		/**
	 * @see Zend_View_Abstract::_run
	 */
	protected function _run() {
		$engine = $this->getEngine();
		$engine->setTemplate(func_get_arg(0));
		return $engine->echoExecute();
	}
}
