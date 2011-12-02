<?php
/************************************************************************
@file controlador.php 
Copyright Ilke Benson, A.I.E. 2009
@author Miguel Jaque Barbero (mjaque@ilkebenson.com)
@author Chema Viera Godoy (chema@ilkebenson.com)
Revisado por: $Author$ 
Revisión: $Rev$
@date $Date$
Diseñado por: Miguel Jaque Barbero
Fecha de Diseño: 21/4/2009
**************************************************************************/

class Controlador{
/**-	Controlador (singleton).
	Su responsabilidad es dirigir el flujo de la aplicación.
	Es decir, se encarga de atender las peticiones de los interfaces de usuario, 
	determinar que pantalla va después de cada petición según su resultado y 
	controla si el usuario tiene permiso para ejecutar la acción solicitada.
	Para ello, instancia objetos de dominio (dom), pero no debe realizar accesos a datos.
**/

	private static $instancia;
	public static $conexion;

	private function __construct(){
	/**-	Constructor privado. Inaccesible para garantizar la unicidad de la instancia según el patrón singleton.
	**/
	}
	public function __call($metodo, $args){
	/**-	Registra la llamada realizada y llama al método correspondiente.
		La mayoría de los métodos del controlador empiezan por '_' y son privados para garantizar que todas las operaciones quedan registradas.
		Aun así, hay que registrar manualmente la salida.
		@param $metodo Nombre del método llamado.
		@param $args Array con los argumentos utilizados.
	**/
		Registro::anotarArgumento($metodo,$args);
		if (!method_exists($this,'_'.$metodo)) throw new ExcepcionControladorMetodoInexistente($metodo);
		return call_user_func_array(array($this,'_'.$metodo),$args);
	}
	public function __clone(){
	/**-	Método de clonación. Inoperativo para garantizar la unicidad de la instancia según el patrón singleton.
	**/
		throw new Exception("Operación de clonación sobre la clase Controlador NO PERMITIDA. Es un Singleton.");
	}
	private function _loginAutenticar($nombre,$clave){
	/**-	Autentifica a un usuario y pone su información en la sesión.
		Busca al usuario por su nombre y clave. Si lo encuentra, conecta con la base de datos y 
		mete la conexión en la sesión del usuario. 
		@param $nombre Nombre de usuario.
		@param $clave Clave de usuario.
	**/
		unset($_SESSION['usuario']);
		$usuario = new Usuario();
		$iu = new MenuPrincipal();
		try{
			try{
//Depurador::mostrar($_REQUEST);
				$usuario->autenticar($nombre,$clave);
			}
			catch(Exception $e){
				if (get_class($e) != 'ExcepcionUsuarioAvisoCaducidadClave')
					throw($e);	//Seguimos con la excepción.
				$iu->registrarAviso($e);
			}
			$_SESSION['usuario'] = $usuario;
			$nombreCompleto = $usuario->atr('nombre').' ' .$usuario->atr('apellidos');
			Registro::anotar("Login válido del usuario ($nombre), ".$nombreCompleto." desde la dirección ".$_SERVER['REMOTE_ADDR'].".");
			$_SESSION['usuario'] = $usuario;
//Depurador::mostrar($_SESSION);
			$iu->ver();
			Registro::anotarArgumento(__METHOD__,$usuario,'salida');
		}
		catch(Exception $e){
			//Registramos el fallo de login.
			Registro::anotar("Error de login para el usuario $nombre desde ".$_SERVER['REMOTE_ADDR']);
			throw ($e);
		}
	}
	private function _desconectar(){
	/**-	Elimina la sesión actual del usuario.
	**/
		session_destroy();
	}
	public static function verInstancia(){
	/**-	Devuelve la instancia del controlador Espantaperros.
		@return La instancia del controlador.
	**/
		if (self::$instancia === null) self::$instancia = new self;
		return self::$instancia;
	}
}

// 	Excepciones.
class ExcepcionControladorAccesoNoAutorizado extends Excepcion{
	public function __construct(){
		$titulo = "No hay ninguna conexión con la base de datos.";
		$texto = "Necesita establecer una conexión previa para operar con la base de datos.";
		$solucion = "Realice un login válido antes de intentar la operación.";
		parent::__construct($titulo,$texto,$solucion);
	}
}
class ExcepcionControladorMetodoInexistente extends Excepcion{
	public function __construct($metodo){
		$titulo = "No Existe el Método.";
		$texto = "Ha llamado al método '$metodo' que no existe en la clase Controlador.";
		$solucion = "Se trata de un error de programación. Notifíqueselo al administrador.";
		$solucionProgramador = "Revise los nombres de los métodos utilizados.";
		parent::__construct($titulo,$texto,$solucion,$solucionProgramador);
	}
}
?>
