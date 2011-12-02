<?php
/**
	@file lanzador.php
	Fichero con la clase de servicio Lanzador
	Copyright Ilke Benson 2008
	@author Miguel Jaque Barbero (mjaque@ilkebenson.com)
	@version 1.0
	@date 2008-01-26

**/

class Lanzador{
//<	Representa una aplicación.
	private $dirIu;
	private $claseDefecto;
	private $metodoDefecto;

	public function __construct($dirIu, $claseDefecto, $metodoDefecto){
		$this->dirIu = $dirIu;
		$this->claseDefecto = $claseDefecto;
		$this->metodoDefecto = $metodoDefecto;
	}
	public function ejecutar(){
	/**-	Busca y ejecuta la acción solicitada por el usuario
		Los parámetros de ejecución se consultan en $_REQUEST.
		Requiere un acción definida como parámetro "PaxAccion".
	**/
		global $config;
		//Buscamos el iu y el método que hay que instanciar y llamar.
		if(isset($_REQUEST['PaxAccion'])){//Hay acción definida.
			$accion = $_REQUEST['PaxAccion'];
			$trozos = explode(".",$accion);
			if (sizeof($trozos) > 1){ //La acción tiene el formato "objeto.metodo"
				try{
					include_once($this->dirIu.$trozos[0].".php");
				}
				catch (Exception $e){
					 throw new ExcepcionLanzadorClaseIuInexistente($trozos[0]);	//Solo se instancias clases de IU
				}
				eval('$iu = new '.$trozos[0].'();');	//Instaciamos el IU solicitado por el usuario.
				$metodo = $trozos[1];				//Llamaremos al método solicitado por el usuario.
			}
			else{ //La acción tiene el formato "metodo"
				try{
					//Ponemos en minúscula la primera letra de la clase
					$clase = $this->claseDefecto;
					$clase = strtolower($clase[0]).substr($clase,1,strlen($clase));
					include_once($this->dirIu.$clase.".php");
				}
				catch (Exception $e){
					 throw new ExcepcionLanzadorClaseIuInexistente($config->ver('general', 'claseIuDefecto'));	//Solo se instancias clases de IU
				}
				eval('$iu = new '.$this->claseDefecto.'();');	//Instaciamos el IU por defecto
				$metodo = $accion;
			}
		}
		else{//No hay acción definida.
			try{
				//Ponemos en minúscula la primera letra de la clase
				$clase = $this->claseDefecto;
				$clase = strtolower($clase[0]).substr($clase,1,strlen($clase));
				include_once($this->dirIu.$clase.".php");
			}
			catch(Exception $e){
				throw new ExcepcionLanzadorClaseIuInexistente($config->ver('general', 'claseIuDefecto'));	//Solo se instancias clases de IU
			}
			eval('$iu = new '.$this->claseDefecto.'();');	//Instaciamos el IU por defecto
			$metodo = $this->metodoDefecto;
		}
		if (!method_exists($iu, $metodo)) throw new ExcepcionLanzadorMetodoInexistente(get_class($iu),$metodo);
		call_user_func(array(&$iu, $metodo));
	}
	public function gestionarError($num, $texto, $archivo, $linea){
		$mensaje = "Error nº: $num - $texto\nEn el archivo $archivo, línea $linea";
		Registro::anotar("Error interno: $mensaje");
	}
}

//	Excepciones.
class ExcepcionLanzadorClaseIuInexistente extends Excepcion{
/// 	Excepción de clase de interfaz de usuario inexistente.
	public function __construct($clase){
		$titulo = "No se pudo instanciar la clase de interfaz '$clase'";
		$texto = "La aplicación no pudo crear un objeto de la clase de interfaz de usuario '$clase'.";
		$solucion = "Compruebe los parámetros de la acción llamada. Sólo pueden instanciarse clases de interfaz de usuario.";
		parent::__construct($titulo,$texto,$solucion);
	}
}
class ExcepcionLanzadorMetodoInexistente extends Excepcion{
/// 	Excepción de clase de interfaz de usuario inexistente.
	public function __construct($clase, $metodo){
		$titulo = "El método solicitado no existe.";
		$texto = "Ha llamado a un método ($metodo) que no existe en la clase $clase.";
		$solucionProgramador = "Compruebe los parámetros de la acción llamada.";
		parent::__construct($titulo,$texto,$solucionProgramador);
	}
}
?>
