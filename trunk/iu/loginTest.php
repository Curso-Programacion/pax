<?php
//Ejecutar desde el directorio raÃ­z de la aplicaciÃ³n con "phpunit clase_test fichero_test.php"
//OJO, caso de test con salida (Output) carga las extensiones correspondientes.
require_once('serv/config.php');
require_once($config->ver('dir','serv').'PHPUnit/Extensions/OutputTestCase.php');
require_once($config->ver('dir','serv')."autoload.php");
define('EVITAR_FILTROS', 'sí');

class LoginTest extends PHPUnit_Extensions_OutputTestCase{
	protected $fixture;

	protected function setUp(){
		$this->fixture = new Login();
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

	public function testComprobarParametros(){
		$this->setExpectedException('ExcepcionParametroInexistente');
		$this->fixture->autenticar();
	}
	public function testAutenticarCorrecto(){
		$_POST['nombre'] = 'icruz';
		$_POST['clave'] = '3e258bd342de83aab021c8c7a0b79681';
		$_POST['claveClaro'] = '';
		$_POST['localidad'] = 'imss';
		$this->fixture->autenticar();
		if(!isset($_SESSION['usuario'])) $this->fail('No hay usuario en la sesiÃ³n');
		$salida = '/<resultado valor="Ok"><usuario id="37" usuario="icruz" nombre="Inmaculada" apellidos="Cruz Barrientos" id_perfil="8" idPerfil="8" correo="icruz@aytobadajoz.es" id_zona="9" idZona="9" perfil="Ayuda a Domicilio"><permiso/'; 
		$this->expectOutputRegex($salida);
		unset($_POST);
	}
	public function testAutenticarFallo(){
		$_POST['nombre'] = 'icruz';
		$_POST['clave'] = 'NO_EXISTO';
		$_POST['localidad'] = 'imss';
		$this->fixture->autenticar();
		$salida = '/<resultado valor="Error/';
		$this->expectOutputRegex($salida);
		unset($_POST);
	}
	public function testAutenticarClaveCaducada(){
	/** CU-Login escenario d **/
		$_POST['nombre'] = 'acreyes';
		$_POST['clave'] = '9d6dcf66d467ee992bcdcdc7d34f3345';
		$_POST['localidad'] = 'imss';
		$this->fixture->autenticar();
		$salida = '/<resultado valor="Error" mensaje="Su clave ha caducado/';
		$this->expectOutputRegex($salida);
		unset($_POST);
	}
	public function testAutenticarAvisoCaducidad(){
	/** CU-Login escenario d **/
		$nombre = 'faliseda';
		$config = Config::verInstancia();
		$timeHoy = mktime(0,0,1,date("m"),date("d"),date("Y"));
		$timeAviso = $timeHoy;
		$timeAviso -= ($config->ver('usuario','diasValidezClave')*24*3600);
		$timeAviso += ($config->ver('usuario','diasAvisoCaducidadClave')*24*3600);
		$timeAviso -= 24*3600;
		$consulta = "UPDATE usuario SET fecha_alta_clave = '".date('Y-m-d 00:00:00', $timeAviso)."' WHERE usuario='$nombre'";
		$bd = new Mysql($config->ver('bd','host'),$config->ver('bd','nombre'),$config->ver('bd','usuario'),$config->ver('bd','clave'));
		$bd->ejecutar($consulta);
		$_POST['nombre'] = $nombre;
		$_POST['clave'] = '5ef43e82fba0902c6c44adb54b65713a';
		$this->fixture->autenticar();
		$diasAviso = $config->ver('usuario','diasAvisoCaducidadClave') - 1;
		$salida = '/<resultado valor="Ok" mensaje="Su clave caducará en '.$diasAviso.' días. Cámbiela cuanto antes./';
		$this->expectOutputRegex($salida);
		unset($_POST);
	}
	public function estSalir(){
		$this->assertTrue(method_exists($this->fixture, 'salir'));
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
