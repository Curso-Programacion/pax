<?php
//Ejecutar desde el directorio raÃ­z de la aplicaciÃ³n con "phpunit clase_test fichero_test.php"
require_once('serv/config.php');
require_once($config->ver('dir','serv').'PHPUnit/Framework.php');
require_once($config->ver('dir','serv')."autoload.php");

class RegistroTest extends PHPUnit_Framework_TestCase{
	protected $fixture;

	public function testAnotar(){
	//TODO: Este test peta la memoria si el fichero en un pelín grande
		$config = Config::verInstancia();
		$texto = "Anotación de Prueba";
		Registro::anotar($texto);
		$lineas = file($config->ver('general','ficheroLog'));
		$ultimaLinea = $lineas[sizeof($lineas)-1];
		$textoLeido = substr($ultimaLinea,strlen($ultimaLinea)-strlen($texto)-2,strlen($texto));
		$this->assertEquals($texto,$textoLeido);
	}
}
?>
