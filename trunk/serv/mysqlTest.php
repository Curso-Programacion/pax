<?php
require_once('serv/config.php');
require_once($config->ver('dir','serv').'PHPUnit/Framework.php');
require_once($config->ver('dir','serv')."autoload.php");

class MysqlTest extends PHPUnit_Framework_TestCase{
	protected $fixture;

	protected function setUp(){
		$this->config = Config::verInstancia();
		//Cargamos la tabla de test
		$this->fixture = new Mysql($this->config->ver('bd','host'),$this->config->ver('bd','nombre'),$this->config->ver('bd','usuario'),$this->config->ver('bd','clave'));
	}

	public function test__Constructor(){
	//	No es un verdadero test. Actúa como un constructor
		system('mysql --default-character-set=utf8 -u '.$this->config->ver('bd','usuario').' --password='.$this->config->ver('bd','clave').' '.$this->config->ver('bd','nombre').'< sql/test.sql');
	}
	public function testFalloConexion(){
		$this->setExpectedException('ExcepcionMySQLConexion');
		$bd = new Mysql('localhost', 'no_user', 'no_passw', 'no_bd');
	}
	public function testConstructor(){
		$this->assertEquals(mysqli_connect_error(),'');
	}
	public static function datosVerFechaMysql(){
		return array(
			array('1968-02-13',"13/02/1968"),
			array('1968-02-13',"13/2/1968"),
			array('2000-12-01',"1/12/2000"),
			array('2000-12-01',"1/12/00"),
			array('2000-02-01',"1/2/00"),
			array('2000-12-31',"31/12/2000"),
			array('1968-02-13 17:45:27',"13/02/1968 17:45:27"),
			array('2000-12-31 17:45:27',"31/12/2000 17:45:27"),
			array('2037-02-13',"13/02/2037"),
			array('2001-01-01',"1/13/00"),
			array('2001-01-01',"32/12/00"),
			array(null,null)
			);
	}
	/**
	 * @dataProvider datosVerFechaMysql
	 */
	public function testVerFechaMysql($fechaMysql, $fechaEsp){
		if (strlen($fechaMysql) > 10) $verHora = true;
		else $verHora = false;
		$this->assertEquals($fechaMysql,$this->fixture->verFechaMysql($fechaEsp, $verHora));
	}
	public static function datosVerFechaMysqlFormatoInvalido(){
		return array(
			array("esto no es una fecha"),
			array("13/02/1968/89"),
			array("13/aa/1968"),
			array("aa/1/1968"),
			array("13/1/aa"),
			array("1/12")
			);
	}
	/**
	 * @dataProvider datosVerFechaMysqlFormatoInvalido
	 */
	public function testVerFechaMysqlFormatoInvalido($fechaEsp){
		$this->setExpectedException('ExcepcionMySQLFormatoFechaInvalido');
		$this->fixture->verFechaMysql($fechaEsp, false);
	}
	public static function datosVerFechaMysqlFueraRango(){
		return array(
			array("1/1/2038"),
			array("1/1/1899")
			);
	}
	/**
	 * @dataProvider datosVerFechaMysqlFueraRango
	 */
	public function testVerFechaMysqlFueraRango($fechaEsp){
		$this->setExpectedException('ExcepcionMySQLFechaFueraRango');
		$this->fixture->verFechaMysql($fechaEsp, false);
	}
	public static function datosVerFechaEsp(){
		return array(
			array('1968-02-13',"13/02/1968"),
			array('1968-2-13',"13/2/1968"),
			array('2000-12-01',"01/12/2000"),
			array('2000-12-01',"01/12/2000"),
			array('2000-02-01',"01/02/2000"),
			array('2000-12-31',"31/12/2000"),
			array('1968-02-13 17:45:27',"13/02/1968 17:45:27"),
			array('2000-12-31 17:45:27',"31/12/2000 17:45:27"),
			array('2037-02-13',"13/02/2037"),
			array(null,null)
			);
	}
	/**
	 * @dataProvider datosVerFechaEsp
	 */
	public function testVerFechaEsp($fechaMysql, $fechaEsp){
		if (strlen($fechaEsp) > 10) $verHora = true;
		else $verHora = false;
		$this->assertEquals($fechaEsp,$this->fixture->verFechaEsp($fechaMysql, $verHora));
	}
	public static function datosVerFechaEspFormatoErroneo(){
		return array(
			array('19680213'),
			array('1968-02-13-93'),
			array('aa-02-13'),
			array('1968-aa-13'),
			array('1968-02-aa')
			);
	}
	/**
	 * @dataProvider datosVerFechaEspFormatoErroneo
	 */
	public function testVerFechaEspFormatoErroneo($fechaMysql){
		$this->setExpectedException('ExcepcionMySQLFormatoFechaInvalido');
		echo $this->fixture->verFechaEsp($fechaMysql, false);
	}
	public function testCargarTabla(){
		$this->markTestIncomplete();
	}
	public function testGuardarTabla(){
		$this->markTestIncomplete();
	}
	public function testCodificarResultado(){
		//No tengo ni idea de como probar esto.
		$this->markTestIncomplete();	
	}
	public function testSeleccionar(){
		//Probamos la consulta simple.
		$consulta = 'SELECT entero, cadena, fecha, texto, fechaHora ';
		$consulta .= 'FROM test ';
		$resultado = $this->fixture->seleccionar($consulta);
//Depurador::mostrar($resultado, false);
		$this->assertEquals(1, $resultado[0]['entero']);
		$this->assertEquals('abcdefghijklmnñopqrstuvwxyz', $resultado[0]['cadena']);
		$this->assertEquals('1968-02-13', $resultado[0]['fecha']);
		//No hay forma humana de comparar un \n sacado de MySQL con uno de PHP
		$this->assertEquals(1,ereg('^En un lugar de La Mancha de cuyo nombre no quiero acordarme, no hará mucho tiempo que vivía...*En un puerto, italiano', $resultado[0]['texto']));
		//$this->assertEquals(1,ereg("En un lugar de La Mancha de cuyo nombre no quiero acordarme, no hará mucho tiempo que vivía\.\.\...En un puerto, italiano, al pie de las montañas, vive nuestro amigo Marco, en una humilde morada\.", $resultado[0]['texto']));
		$this->assertEquals('2008-12-17 13:37:36', $resultado[0]['fechaHora']);
	}	
	public function testSeleccionarBD(){
		$resultado = $this->fixture->seleccionarBD($this->config->ver('bd','nombre'));
	}	
	public function testSeleccionarBDErroneo(){
		$bd = 'no_exito';
		$this->setExpectedException('ExcepcionMySQLErrorSeleccionarBD');
		$resultado = $this->fixture->seleccionarBD($bd);
	}	
	public function testErrorSeleccionar(){
		//Probamos la consulta simple.
		$this->setExpectedException('ExcepcionMySQLError');
		$consulta = 'SELECT noExisto FROM test ';
		$resultado = $this->fixture->seleccionar($consulta);
	}	
	public function testSeleccionarConsultaMultiple(){
		//Probamos la consulta simple.
		$consulta = 'SELECT entero, cadena, fecha, texto, fechaHora ';
		$consulta .= 'FROM test ';
		$consulta .= '; SELECT entero FROM test WHERE entero > 1';
		$resultado = $this->fixture->seleccionar($consulta);
		$this->assertEquals(2, sizeof($resultado));
		$this->assertEquals(1, $resultado[0][0]['entero']);
		$this->assertEquals('abcdefghijklmnñopqrstuvwxyz', $resultado[0][0]['cadena']);
		$this->assertEquals('1968-02-13', $resultado[0][0]['fecha']);
		//No hay forma humana de comparar un \n sacado de MySQL con uno de PHP
		$this->assertEquals(1,ereg('^En un lugar de La Mancha de cuyo nombre no quiero acordarme, no hará mucho tiempo que vivía...*En un puerto, italiano', $resultado[0][0]['texto']));
		$this->assertEquals('2008-12-17 13:37:36', $resultado[0][0]['fechaHora']);

		$this->assertEquals(2, $resultado[1][0]['entero']);
	}	
	public function testErrorConsultaMultiple(){
		//Probamos error en la primera consulta.
		$this->setExpectedException('ExcepcionMySQLError');
		$consulta = 'SELEC entero FROM test; SELECT * from test;';
		$resultado = $this->fixture->seleccionar($consulta);
	}	
	public function testErrorConsultaMultiple2(){
		//Probamos error en la segunda consulta.
		$this->setExpectedException('ExcepcionMySQLError');
		$consulta = 'SELEC entero FROM test; SELECT * from noExisto;';
		$resultado = $this->fixture->seleccionar($consulta);
	}	
	public function testVerUltimoIdInsertado(){
		$consulta = 'INSERT INTO test SET cadena = \'Prueba\'';
		$this->fixture->ejecutar($consulta);
		$id = $this->fixture->verUltimoIdInsertado();
		$consulta = 'DELETE FROM test WHERE cadena = \'Prueba\'';
		$this->fixture->ejecutar($consulta);
		$consulta = 'ALTER TABLE test auto_increment = 2';
		$this->fixture->ejecutar($consulta);
		$this->assertEquals(3, $id);
	}
	public function testActualizar(){
		$this->markTestIncomplete();
	}
	public function testActualizarAtributoTabla(){
		$this->markTestIncomplete();
	}
	public function testActualizarPorClaveCompuesta(){
		$this->markTestIncomplete();
	}
	public function testBorrar(){
		$this->markTestIncomplete();
	}
	public function testEjecutar(){
		$this->markTestIncomplete();
	}
	public function testEscaparCaracteres(){
		$this->markTestIncomplete();
	}
	public function testFilasAfectadas(){
		$this->markTestIncomplete();
	}
	public function testInsertar(){
		$this->markTestIncomplete();
	}
	public function testSeleccionarAtributosPorFiltro(){
		$this->markTestIncomplete();
	}
	public function testSeleccionarPorAtributo(){
		$this->markTestIncomplete();
	}
	public function testEsFormatoFechaMysql(){
		$this->markTestIncomplete();
	}

	public function test__Destructor(){
	//	No es un verdadero test. Actúa como un destructor
	$config = Config::verInstancia();
	system('mysql -u '.$config->ver('bd','usuario').' --password='.$config->ver('bd','clave').' '.$config->ver('bd','nombre').'< sql/testDrop.sql');
	}
}
?>
