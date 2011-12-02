<?php
//Ejecutar desde el directorio raíz de la aplicación con "phpunit clase_test fichero_test.php"
require_once('serv/config.php');
$config = Config::verInstancia();
require_once($config->ver('dir','serv').'autoload.php');
require_once($config->ver('dir','serv').'PHPUnit/Framework.php');

class LanzadorTest extends PHPUnit_Framework_TestCase{
	protected $fixture;

	protected function setUp(){
		$config = Config::verInstancia();
		$this->fixture = new Lanzador($config->ver('dir','iu'), $config->ver('general','claseIuDefecto'), $config->ver('general','metodoIuDefecto'));
	}
	public function testEjecutar(){
		$this->setExpectedException('ExcepcionLanzadorClaseIuInexistente');
		$_REQUEST['PaxAccion'] = 'noExisto.noExisto';
		$this->fixture->ejecutar();
		//Las clases de Servicio no deben ser accesibles
		$this->setExpectedException('ExcepcionLanzadorClaseIuInexistente');
		$_REQUEST['PaxAccion'] = 'mysql.consultar';
		$this->fixture->ejecutar();
		$this->setExpectedException('ExcepcionLanzadorMetodoInexistente');
		$_REQUEST['PaxAccion'] = 'login.noExisto';
		$this->fixture->ejecutar();
	}
}
?>
