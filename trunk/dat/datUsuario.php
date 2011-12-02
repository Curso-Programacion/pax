<?php
/************************************************************************
@file datUsuario.php 
Copyright Ilke Benson, A.I.E. 2009
@author Miguel Jaque Barbero (mjaque@ilkebenson.com)
@author Chema Viera Godoy (chema@ilkebenson.com)
Revisado por: $Author$ 
Revisión: $Rev$
@date $Date$
Diseñado por: Miguel Jaque Barbero
Fecha de Diseño: 9/2/2009
**************************************************************************/

class DatUsuario{
/**-	Acceso a Datos de la clase Usuario.
	Su responsabilidad es acceder a los sistemas de datos (MySQL, LDAP, Ficheros...) de la aplicación.
	Es decir, atiende las peticiones de las clases de dominio para leer o escribir datos, independizándolas del componente de almacenamiento utilizado. "Es quien conoce a la Base de Datos".
	Siempre devuelve datos simples o arrays de datos simples. Nunca instancia clases de dominio.
**/
	const TABLA = 'usuario';
	private $config;		// Singleton de configuración.
	private $bd;			//Base de Datos.
       
	public function __construct(){
	/**-	Constructor de la clase.
		Crea el objeto de Base de Datos.
	**/
		$this->config = Config::verInstancia();
		$this->bd = new Mysql($this->config->ver('bd','host'),$this->config->ver('bd','nombre'),$this->config->ver('bd','usuario'),$this->config->ver('bd','clave'));
	}
	public function hayUsuarioClave($usuario, $clave){
	/**-	Comprueba que exista en la base de datos un usuario con el nombre y la clave indicadas.
		@param $usuario Nombre del usuario.
		@param $clave Clave del usuario (encriptada).
		@return Booleano indicando si existe el usuario.
	**/
		$resultado = $this->bd->seleccionarAtributosPorFiltro(self::TABLA, array('clave'),"usuario = '$usuario'");
		if (sizeof($resultado) == 0) return false;
//Depurador::mostrar($resultado);
		$uniqid = $_SESSION['uniqid'];
		if (($clave) == md5($resultado[0]['clave'])) return true;
		//if (($clave) == md5($uniqid.$resultado[0]['clave'])) return true;
		else //Depurador::mostrar($clave.'       JARL');
			return false;
	}
	public function incrementarFallosAcceso($usuario){
	/**-	Incrementa el número de fallos de acceso de un usuario
		@param $usuario Nombre del usuario.
	**/
		$consulta = 'UPDATE usuario SET fallosAcceso = fallosAcceso + 1 ';
		$consulta .= "WHERE usuario = '$usuario'"; 
		$this->bd->ejecutar($consulta);
	}
	public function verFechaAltaClave($usuario){
	/**-	Devuelve la fecha de alta de la clave de un usuario en formato ISO.
		@param $usuario Nombre del usuario.
		@return fecha de alta de la clave en formato ISO.
	**/
		$resultado = $this->bd->seleccionarAtributosPorFiltro(self::TABLA, array('fechaAltaClave'),"usuario = '$usuario'");
		return $resultado[0]['fechaAltaClave'];
	}
	public function verNumFallos($usuario){
	/**-	Devuelve el número de fallos de acceso del usuario.
		@param $usuario Nombre del usuario.
		@return Número de fallos de acceso del usuario.
	**/
		$resultado = $this->bd->seleccionarAtributosPorFiltro(self::TABLA, array('fallosAcceso'),"usuario = '$usuario'");
		return $resultado[0]['fallosAcceso'];
	}
	public function actualizarFechaAcceso($usuario){
	/**-	Actualiza la fecha de acceso del usuario poniéndola a ahora.
		@param $usuario Nombre del usuario.
	**/
		$consulta = 'UPDATE '.self::TABLA.' SET fechaUltimoAcceso = NOW() ';
		$consulta .= "WHERE usuario = '$usuario'";
		$this->bd->ejecutar($consulta);
	}
	public function actualizarFallosAcceso($usuario){
	/**-	Actualiza los fallos de acceso del usuario poniéndolo a cero.
		@param $usuario Nombre del usuario.
	**/
		$consulta = 'UPDATE '.self::TABLA.' SET fallosAcceso = NOW() ';
		$consulta .= "WHERE usuario = '$usuario'";
		$this->bd->ejecutar($consulta);
	}
}

// 	Excepciones
?>
