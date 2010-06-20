<?php

class System_Controller_Response_Http extends Zend_Controller_Response_Http {
	
	/**
     * Echo the body segments
     *
     * @return void
     */
	public function outputBody()
    {
		$body = implode('', $this->_body);
		if ($body) {
			echo $body;
		} else {
			header('HTTP/1.1 500');
		}
    }
}