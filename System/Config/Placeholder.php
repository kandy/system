<?php
/**
 * This class allows to use placeholders in config values.
 * ${name1.name2} replaced by $this->name1->name2 etc.
 * Sample
 * config.ini
 *  a = "1"
 *  b = "is placeholder ${a}"
 * <php
 * 	$configIni = new Zend_Config_Ini('config.ini');
 * 	$config = new System_Config_Placeholder($configIni);
 *  echo $config->b;
 * Say:
 *  "is placeholder 1"
 *
 * @package system.config
 */
class System_Config_Placeholder extends Zend_Config 
{
	/**
	 * Class constructor
	 * @var array|Zend_Config
	 */
	public function __construct($config) {
		if (is_array($config)) {
			$config = new Zend_Config($config);
		}

		if (!($config instanceof Zend_Config)) {
			throw new Zend_Config_Exception('Config must be an array or Zend_Config');
		}

		parent::__construct(array(), true);
		$this->merge($config);
		$this->_processPlaceholders($this);
		$this->setReadOnly();
	}

	/**
	 * Recursively process placeholders in Zend_Config node
	 * @var Zend_Config
	 */
	protected function _processPlaceholders(Zend_Config $node) {
		foreach ($node as $key => $item) {
			if ($item instanceof Zend_Config) {
				$this->_processPlaceholders($item);
			} else {
				$node->$key = $this->_processPlaceholder($item);
			}
		}
	}

	/**
	 * Recursively placeholders in config node's value and returns
	 * the string with placeholders replaced by their values.
	 * @var string
	 * @return string
	 */
	protected function _processPlaceholder($item) {
		if (preg_match_all('~\%{([^\}]*?)}~iu', $item, $matches) && isset($matches[1])) {
			foreach ($matches[1] as $placeholder) {
				$placeholderParts = explode('.', $placeholder);
				$node = $this;
				foreach ($placeholderParts as $placeholderPart) {
					$node = $node->$placeholderPart;
				}
				$value = $this->_processPlaceholder($node);
				$item = str_replace('%{'.$placeholder.'}', $value, $item);
			}
		}
		return $item;
	}
}
