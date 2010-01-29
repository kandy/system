<?php
$rootPath = dirname(__FILE__);
set_include_path(implode(PATH_SEPARATOR, array(
	'../',
	'./',
	get_include_path(),
		)));

putenv('APPLICATION_ENV=testing');
$environment = 'testing';

require_once 'System/Application.php';
$application = new System_Application($environment, $rootPath);
$application->bootstrap();

// TODO: find a proper way to tell PHPUnit to ignore these classes
Zend_Loader::loadClass('System_Test_TestCase');
Zend_Loader::loadClass('System_Test_DatabaseTestCase');
Zend_Loader::loadClass('System_Test_AjaxTestCase');

