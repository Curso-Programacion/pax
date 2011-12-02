<?php
if (!defined('PHPUnit_MAIN_METHOD')) {
	define('PHPUnit_MAIN_METHOD', 'ServTestsuite::main');
}

require_once('serv/config.php');
$config = Config::verInstancia();
require_once($config->ver('dir','serv').'autoload.php');
require_once($config->ver('dir','serv').'PHPUnit/Framework.php');
require_once($config->ver('dir','serv').'PHPUnit/TextUI/TestRunner.php');

class ServTestsuite{
	public static function main(){
		PHPUnit_TextUI_TestRunner::run(self::suite());
	}
	public static function suite(){
		$suite = new PHPUnit_Framework_TestSuite('Nombre de la Suite');

		//Suites y tests incluidos en la suite
		//$suite->addTest(clase_suite::suite());	//Para añadir una suite
		//$suite->addTestSuite('clase_test');	//Para añadir un test
		$suite->addTestSuite('ConfigTest');
		$suite->addTestSuite('MysqlTest');
		//$suite->addTestSuite('RegistroTest');
		//$suite->addTestSuite('LanzadorTest');
		//$suite->addTestSuite('ParametroTest');

		return $suite;
	}
}
if (PHPUnit_MAIN_METHOD == 'ServTestsuite::main') {
	ServTestsuite::main();
}
?>
