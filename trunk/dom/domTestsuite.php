<?php
//Ejecutar desde el directorio raíz de la aplicación con "php fichero_test_suite.php"
if (!defined('PHPUnit_MAIN_METHOD')) {
	define('PHPUnit_MAIN_METHOD', 'Dom_testsuite::main');
}

require_once('serv/config.php');
$config = Config::verInstancia();
require_once($config->ver('dir','serv').'autoload.php');
require_once($config->ver('dir','serv').'PHPUnit/Framework.php');
require_once($config->ver('dir','serv').'PHPUnit/TextUI/TestRunner.php');

class DomTestsuite{
	
	public static function main(){
		PHPUnit_TextUI_TestRunner::run(self::suite());
	}

	public static function suite(){
		$suite = new PHPUnit_Framework_TestSuite('Nombre de la Suite');

		//Suites y tests incluidos en la suite
		//$suite->addTest(clase_suite::suite());	//Para añadir una suite
		//$suite->addTestSuite('clase_test');		//Para añadir un test
		$suite->addTestSuite('DomTest');
		$suite->addTestSuite('UsuarioTest');

		return $suite;
	}
}
 
if (PHPUnit_MAIN_METHOD == 'Dom_testsuite::main') {
	DomTestsuite::main();
}
?>
