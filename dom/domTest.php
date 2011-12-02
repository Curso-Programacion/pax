<?php
//Ejecutar desde el directorio raíz de la aplicación con "phpunit clase_test fichero_test.php"
require_once('serv/config.php'); 		//Cargamos la configuración
$config = Config::verInstancia();
require_once($config->ver('dir','serv').'PHPUnit/Framework.php');
require_once($config->ver('dir','serv')."autoload.php");

class DomTest extends PHPUnit_Framework_TestCase{
	protected $fixture;

	protected function setUp(){
		$this->fixture = new ObjetoDom();
		$this->nivelError = error_reporting(E_ERROR);
	}
	
	protected function tearDown(){
		error_reporting($this->nivelError);
	}

	public function testVerAtributo(){
		$this->assertNotNull($this->fixture);
		$this->assertEquals("Escalar", $this->fixture->atr("atr1"));
		$this->assertEquals(array("2.1","2.2","2.3"), $this->fixture->atr("atr2"));
		$this->assertEquals("Exception", get_class($this->fixture->atr("atr3")));
	}
	public function testPonerAtributo(){
		$this->fixture->atr("atr1", "Otro Valor");
		$this->assertEquals("Otro Valor", $this->fixture->atr("atr1"));
		$this->fixture->atr("atr2", array("a","b","c"));
		$this->assertEquals(array("a","b","c"), $this->fixture->atr("atr2"));
		$this->fixture->atr("atr3", new Prueba());
		$this->assertEquals("Prueba", get_class($this->fixture->atr("atr3")));
		$this->fixture->atr("nuevoAtributo", "nuevoValor");
		$this->assertEquals("nuevoValor", $this->fixture->atr("nuevoAtributo"));
		$this->fixture->atr("atr3", null);
		$this->assertNull($this->fixture->atr("atr3"), "Error al asignar el valor nulo a un atributo existente");
		$this->fixture->atr("nuevoAtributo2", null);
		$this->assertNull($this->fixture->atr("nuevoAtributo2"), "Error al asignar el valor nulo a un atributo nuevo");
	}
	public function testVerAtributosPublicos(){
		$atributos = $this->fixture->verAtributos();
		$this->assertEquals("Escalar",$atributos["atr1"]);
		$this->assertEquals(array("2.1","2.2","2.3"),$atributos["atr2"]);
		$this->assertEquals("Exception",get_class($atributos["atr3"]));
	}
	public function testVerAtributosSobreescrita(){
		$atributos = $this->fixture->verAtributos();
		$this->assertEquals(3,sizeof($atributos));
		$this->assertEquals("Escalar",$atributos["atr1"]);
		$this->assertEquals(array("2.1","2.2","2.3"),$atributos["atr2"]);
		$this->assertEquals("Exception",get_class($atributos["atr3"]));
	}
}

class ObjetoDom extends Dom{
	public $atr1;
	public $atr2;
	public $atr3;

	public function __construct(){
		$this->atr1 = "Escalar";
		$this->atr2 = array("2.1","2.2","2.3");
		$this->atr3 = new Exception("Objeto");
	}
};

class Prueba{};

class ObjetoDom2 extends Dom{
	private $atr1;
	private $atr2;
	private $atr3;
	private $atr4;

	public function __construct(){
		$this->atr1 = "Escalar";
		$this->atr2 = array("2.1","2.2","2.3");
		$this->atr3 = new Exception("Objeto");
		$this->atr4 = "no se devuelve";
	}
	public function verAtributos(){
		return array(
			"atr1" => $this->atr1,
			"atr2" => $this->atr2,
			"atr3" => $this->atr3
			);
	}
};

?>
