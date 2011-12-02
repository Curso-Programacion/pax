<?php
//Ejecutar desde el directorio raÃ­z de la aplicaciÃ³n con "phpunit clase_test fichero_test.php"
require_once('serv/config.php');
require_once($config->ver('dir','serv').'PHPUnit/Framework.php');
require_once($config->ver('dir','serv')."autoload.php");

class UsuarioTest extends PHPUnit_Framework_TestCase{
	protected $fixture;

	protected function setUp(){
		$this->fixture = new Usuario();
	}
	public static function testIniciarDatos(){
	//Esto no es un test, es para guardar los datos y cargar los de prueba.
		$config = Config::verInstancia();
		$mysql = new Mysql($config->ver('bd','host'),'espantaperros2',$config->ver('bd','usuario'),$config->ver('bd','clave'));
		$mysql->guardarTabla("usuario", "test/datos/usuario.bak.sql");
		$mysql->cargarTabla("usuario", "test/datos/usuario.sql"); 
		$mysql->guardarTabla("usuario_permiso", "test/datos/usuario_permiso.bak.sql");
		$mysql->cargarTabla("usuario_permiso", "test/datos/usuario_permiso.sql"); 
	}

	public function testConstructor(){
		$this->assertNotNull($this->fixture);
	}
	public function testAutenticarExito(){
		$usuario = 'permiso';
		$clave = 'a30f3f79678725865e3fd6bd2f910aba';
		$resultado = $this->fixture->autenticar($usuario, $clave, null);
		$this->assertEquals('Usuario', get_class($resultado));
		$this->assertEquals('45',$this->fixture->atr('id'));
		$this->assertEquals('permiso',$this->fixture->atr('usuario'));
		$this->assertEquals('Permiso',$this->fixture->atr('nombre'));
		$this->assertEquals('Usuario',$this->fixture->atr('apellidos'));
		$this->assertEquals('8',$this->fixture->atr('id_perfil'));
		$this->assertEquals('Ayuda a Domicilio',$this->fixture->atr('perfil'));
		$this->assertEquals('permiso@ibenson.com',$this->fixture->atr('correo'));
		$this->assertEquals('9',$this->fixture->atr('id_zona'));
		$permisos = array('usuario.login','usuario.recuperarClave','usuario.salir');
		$permisosLeidos = $this->fixture->atr('permisos');
		//$this->assertEquals(sizeof($permisos),sizeof($permisosLeidos));
		$diferencias = array_diff($permisos,$permisosLeidos);
		
		//if (count($diferencias)!= 0) $this->fail("Falla los permisos");
	}	
	public function testAutenticarCuentaDesactivada(){
		$usuario = 'emartin';
		$clave = '3d2d30f1d96d9740abd20d5447c6b73c';
		$this->setExpectedException('ExcepcionUsuarioCuentaDesactivada');
		$resultado = $this->fixture->autenticar($usuario, $clave, null);
	}	
	public function testAutenticarFallo(){
		//$this->markTestIncomplete();
		$this->setExpectedException('ExcepcionDatUsuarioInexistente');
		$usuario = 'NO_EXISTO';
		$clave = 'NO_EXISTO';
		$resultado = $this->fixture->autenticar($usuario, $clave, null);
	}
	public function testAutenticarUsuarioNoEncontrado(){
		$nombre = 'icruz';
		$clave = 'NO_EXISTO';
		$this->setExpectedException('ExcepcionUsuarioNoEncontrado');
		$this->fixture->autenticar($nombre, $clave, $clave);
	}
	public function testDesactivarCuenta(){
	/**-	Para CU-Login 7c	**/
		//Hacemos un login correcto para reponer el contador
		$nombre = 'icruz';
		$clave = '3e258bd342de83aab021c8c7a0b79681';
		$this->fixture->autenticar($nombre, $clave, $clave);
		$config = Config::verInstancia();
		$this->setExpectedException('ExcepcionUsuarioCuentaDesactivada');
		$usuario = 'icruz';
		$clave = 'NO_EXISTO';
		for ($i=0; $i<$config->ver('usuario','numFallosLoginPermitidos'); $i++){	//Fallamos cinco veces
			try{
				$this->fixture->autenticar($usuario, $clave, null);
			}
			catch(Exception $e){
				$this->assertEquals('ExcepcionUsuarioNoEncontrado', get_class($e));
			}
		}
		$this->fixture->autenticar($usuario, $clave, null);
		$this->assertFalse($this->fixture->estaActivo(37));
	}
	public function testClaveCaducada(){
	//CU-Login escenario d
		$this->setExpectedException('ExcepcionUsuarioClaveCaducada');
		$usuario = 'acreyes';
		$clave = '9d6dcf66d467ee992bcdcdc7d34f3345';
		$this->fixture->autenticar($usuario, $clave, null);
	}
	public function testReiniciarNumFallos(){
		#$this->markTestIncomplete();
		$usuario = 'faliseda';
		#$clave = '3e258bd342de83aab021c8c7a0b79681';
		$clave = 'MALA';
		$numFallos = $this->fixture->verNumFallos($usuario);
		#echo "El num de fallos primero es $numFallos";
		$this->fixture->reiniciarNumFallos($usuario);
		$numFallos = $this->fixture->verNumFallos($usuario);
		#echo "\nEl num de fallos despuÃ©s es $numFallos";
		for ($i=0;$i<=2;$i++){
			try{
				$this->fixture->autenticar($usuario, $clave, NULL);
			}
			catch(Exception $e){
				$this->assertEquals('ExcepcionUsuarioNoEncontrado', get_class($e));
			}
		}
		$numFallos = $this->fixture->verNumFallos($usuario);
		#echo "\nEl num de fallos con tres intentos es $numFallos";
		$this->assertEquals('3', $numFallos);
		$this->fixture->reiniciarNumFallos($usuario);
		$numFallos = $this->fixture->verNumFallos($usuario);
		$this->assertEquals('0', $numFallos);
	}
	public function testAutenticarConAvisoCaducidad(){
	/** CU-Login escenario de éxito**/
		$nombre = 'faliseda';
		$clave = '5ef43e82fba0902c6c44adb54b65713a';
		$config = Config::verInstancia();
		$timeHoy = mktime(0,0,1,date("m"),date("d"),date("Y"));
		$timeAviso = $timeHoy;
		$timeAviso -= ($config->ver('usuario','diasValidezClave')*24*3600);
		$timeAviso += ($config->ver('usuario','diasAvisoCaducidadClave')*24*3600);
		$timeAviso -= 24*3600;
		$consulta = "UPDATE usuario SET fecha_alta_clave = '".date('Y-m-d 00:00:00', $timeAviso)."' WHERE usuario='$nombre'";
		$bd = new Mysql($config->ver('bd','host'),$config->ver('bd','nombre'),$config->ver('bd','usuario'),$config->ver('bd','clave'));
		$bd->ejecutar($consulta);
		$resultado = $this->fixture->autenticar($nombre,$clave,$clave);
//print_r($resultado);
		$this->assertEquals('2', $resultado->atr('id'));
		$this->assertEquals('14', $resultado->atr('diasParaCaducidadClave'));
	}

	public function testRestaurarDatos(){
	//Esto no es un test, es para restaurar los datos anteriores al test.
		$config = Config::verInstancia();
		$mysql = new Mysql($config->ver('bd','host'),'espantaperros2',$config->ver('bd','usuario'),$config->ver('bd','clave'));
		$mysql->cargarTabla("usuario", "test/datos/usuario.bak.sql"); 
		$mysql->cargarTabla("usuario_permiso", "test/datos/usuario_permiso.bak.sql"); 
	}

}
?>
