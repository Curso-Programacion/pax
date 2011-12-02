<?php
//Ejecutar desde el directorio raíz de la aplicación con "phpunit clase_test fichero_test.php"
require_once('serv/config.php'); 		//Cargamos la configuración
$config = Config::verInstancia();
require_once($config->ver('dir','serv').'PHPUnit/Framework.php');
require_once($config->ver('dir','serv')."autoload.php");
define('EVITAR_FILTROS', 'sí');

class ParametroTest extends PHPUnit_Framework_TestCase{
	protected $fixture;

	public function testParametroString(){
		$_POST['nombreParametro'] = 'valorParametro';
		$this->fixture = new Parametro('nombreParametro');
		$this->assertEquals('valorParametro', $this->fixture->verValor());
	}
	public function testParametroPostNoNuloPorDefecto(){
		$_POST['nombreParametro'] = '';
		$this->fixture = new Parametro('nombreParametro');
		$this->setExpectedException('ExcepcionParametroInexistente');
		$this->assertEquals('valorParametro', $this->fixture->verValor());
	}
	public function testParametroGet(){
		$_GET['nombreParametro'] = 'valorParametro';
		$this->fixture = new Parametro('nombreParametro', false, INPUT_GET);
		$this->assertEquals('valorParametro', $this->fixture->verValor());
	}
	public function testParametroNulo(){
		$_POST['nombreParametro'] = '';
		$this->fixture = new Parametro('nombreParametro', true);
		$this->assertNull($this->fixture->verValor());
		unset($_POST);
	}
	public function testParametroNoDefinido(){
		$this->fixture = new Parametro('nombreParametro', true);
		$this->assertNull($this->fixture->verValor());
	}
	public function testParametroUndefined(){
		$_POST['nombreParametro'] = 'undefined';
		$this->fixture = new Parametro('nombreParametro', true);
		$this->assertNull($this->fixture->verValor());
	}
	public function testExcepcionParametroSinNombre(){
		$this->setExpectedException('ExcepcionParametroNombreInvalido');
		$this->fixture = new Parametro('');
	}
	public function testExcepcionParametroInexistente(){
		$this->setExpectedException('ExcepcionParametroInexistente');
		$this->fixture = new Parametro('no_existo');
		$this->fixture->verValor();
	}
	public function testExcepcionParametroIndefinido(){
		$this->setExpectedException('ExcepcionParametroIndefinido');
		$_POST['nombreParametro'] = 'undefined';
		$this->fixture = new Parametro('nombreParametro');
		$this->fixture->verValor();
	}
}
?>
