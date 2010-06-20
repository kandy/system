<?php

class System_Mail extends Zend_Mail
{
	/**
	 * Sets Reply-to header
	 * @param string $email
	 * @param string $name
	 * @return System_Mail
	 */
	public function setReplyTo($email, $name=null) {
        $this->addHeader('Reply-To', $email);
        return $this;
    }
}
