<?php
/************************************************************************
@file usuario.php 
Copyright Ilke Benson, A.I.E. 2009
@author Miguel Jaque Barbero (mjaque@ilkebenson.com)
@author Chema Viera Godoy (chema@ilkebenson.com)
Revisado por: $Author$ 
Revisión: $Rev$
@date $Date$
Diseñado por: Miguel Jaque Barbero
Fecha de Diseño: 16/1/2008
**************************************************************************/

class Usuario extends Dom{
/**-	Clase de dominio correspondiente a un Usuario de la aplicación.
	Las clases de dominio se responsabilizan de: Crear, Modificar, Eliminar, Buscar y Listar.
**/
	private $config;		//Singleton de configuración.
	private $datos;			//Acceso a datos.

	public function __construct(){
	/**-	Constructor de Usuario.
	**/
		$this->config = Config::verInstancia();
		$this->datos = new DatUsuario();
	}
	public function autenticar($usuario,$clave){
	/**-	Implementa el algoritmo de autenticación de usuarios.
		1º Comprueba que exista un usuario con esa clave.
		2º Comprueba que la clave no haya caducado.
		3º Comprueba que no se haya excedido el número máximo de fallos.
		4º ...
		Si todo va bien, actualiza la fecha de último acceso.
		@param $usuario El nombre de usuario.
		@param $clave Clave encriptada del usuario.
	**/
		//Comprobamos que existe un usuario con esa clave
		if (!$this->datos->hayUsuarioClave($usuario,$clave)){
			$this->datos->incrementarFallosAcceso($usuario);
			throw new ExcepcionUsuarioNoEncontrado($usuario);
		}

		//Comprobamos que la clave no haya caducado
		$fechaAltaClave = date_create($this->datos->verFechaAltaClave($usuario));
		$tiempoAltaClave = $fechaAltaClave->format('U');	//Tiempo unix en segundos.
		$ahora = time();	//Tiempo unix en segundos.
		if ($ahora - $tiempoAltaClave > $this->config->ver('usuario','diasValidezClave')*60*60*24)
			throw new ExcepcionUsuarioClaveCaducada($usuario);

		//Comprobamos el número de fallos
		if($this->datos->verNumFallos($usuario) > $this->config->ver('usuario','numFallosLoginPermitidos')) 
			throw new ExcepcionUsuarioCuentaDesactivada($usuario);
	
		//Todo ha ido bien. Actualizamos la fecha de acceso y el número de fallos de login
		$this->datos->actualizarFechaAcceso($usuario);
		$this->datos->actualizarFallosAcceso(0);

		//Comprobamos si hay que dar el aviso de caducidad
		$tiempoParaCaducar = $this->config->ver('usuario','diasValidezClave')*60*60*24 - ($ahora - $tiempoAltaClave);
		$diasParaCaducar = round($tiempoParaCaducar/(60*60*24));
		if ($diasParaCaducar <= $this->config->ver('usuario','diasAvisoCaducidadClave'))
			throw new ExcepcionUsuarioAvisoCaducidadClave($diasParaCaducar);
	}
}

// Excepciones
class ExcepcionUsuarioNoEncontrado extends Excepcion{
	public function __construct($usuario){
		$titulo = "No se encontró ningún usuario con el nombre '$usuario'";
		$texto = "La aplicación no ha podido autenticar al usuario '$usuario' con los datos proporcionados.";
		$solucionProgramador = "Revise las condiciones de autenticación.";
		parent::__construct($titulo,$texto,$solucionProgramador);
	}
}
class ExcepcionUsuarioCuentaDesactivada extends Excepcion{
	public function __construct($usuario){
		$titulo = "La Cuenta está Desactivada";
		$texto = "Debido a un exceso de fallos de acceso la cuenta del usuario '$usuario' ha sido desactivada.";
		$solucionProgramador = "El usuario no tiene permiso de login.";
		$solucionUsuario = "Póngase en contacto con el administrador para reactivar su cuenta.";
		parent::__construct($titulo,$texto,$solucionProgramador,$solucionUsuario);
	}
}
class ExcepcionUsuarioClaveCaducada extends Excepcion{
	public function __construct($usuario){
		$titulo = "La Clave ha Caducado";
		$texto = "La clave del usuario '$usuario' está caducada. No puede autenticarse.";
		$solucionProgramador = "Compruebe la fecha de alta del usuario.";
		$solucionUsuario = "Solicite una nueva clave desde la pantalla de login de la aplicación.";
		parent::__construct($titulo,$texto,$solucionProgramador,$solucionUsuario);
	}
}
class ExcepcionUsuarioAvisoCaducidadClave extends Excepcion{
	public function __construct($dias){
		$titulo = "Su Clave va a Caducar";
		$texto = "Su clave está a punto de caducadar. Faltan $dias días para que caduque.";
		$solucionProgramador = "";
		$solucionUsuario = "Cambie su clave antes de que caduque.";
		parent::__construct($titulo,$texto,$solucionProgramador,$solucionUsuario);
	}
}
?>
