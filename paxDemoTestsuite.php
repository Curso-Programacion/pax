<?php
echo "<pre>";
if (!defined('PHPUnit_MAIN_METHOD')) {
define('PHPUnit_MAIN_METHOD', 'PaxDemoTestsuite::main');
}

require_once('serv/config.php');
$config = Config::verInstancia();
require_once($config->ver('dir','serv').'autoload.php');
require_once($config->ver('dir','serv').'PHPUnit/Framework.php');
require_once($config->ver('dir','serv').'PHPUnit/TextUI/TestRunner.php');

class PaxDemoTestsuite{
	public static function main(){
		PHPUnit_TextUI_TestRunner::run(self::suite());
	}
	public static function suite(){
		$suite = new PHPUnit_Framework_TestSuite('Test Suite de Mononoke2');

		//Suites y tests incluidos en la suite
		//$suite->addTest(clase_suite::suite());	//Para añadir una suite
		//$suite->addTestSuite('clase_test');	//Para añadir un test
		$suite->addTestSuite('ServTestsuite');	
		//$suite->addTestSuite('DatTestsuite');	
		//$suite->addTestSuite('DomTestsuite');	
		//$suite->addTestSuite('AppTest');	
		//$suite->addTestSuite('IuTestsuite');	

		return $suite;
	}
}
 
if (PHPUnit_MAIN_METHOD == 'PaxDemoTestsuite::main') {
	PaxDemoTestsuite::main();
}
?>
