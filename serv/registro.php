<?php
/************************************************************************
@file registro.php 
Copyright Ilke Benson, A.I.E. 2009
@author Miguel Jaque Barbero (mjaque@ilkebenson.com)
Revisado por: $Author$ 
Revisión: $Rev$
@date $Date$
Diseñado por: Miguel Jaque Barbero
Fecha de Diseño: 24/1/2008
**************************************************************************/

class Registro{
/**-	Registro de Operaciones (clase estática).
	Permite escribir comentarios en un fichero de log.
**/
	//Configuración
	const SEPARADOR = ' - ';

	public static function anotar($texto){
	/**-	Escribe un mensaje en el fichero de log.
		@param $msg Texto del mensaje.
	**/
		$config = Config::verInstancia();

		$fichero = fopen($config->ver('general','ficheroLog'), "a");
		$mensaje = date($config->ver('general','formatoFechaLog'));
		$mensaje .= self::SEPARADOR.$_SERVER['REMOTE_ADDR'];
		if (isset($_SESSION['usuario'])){
			if (get_class($_SESSION['usuario']) == 'Usuario')
				$nombreUsuario = $_SESSION['usuario']->atr('usuario');
			else $nombreUsuario = 'desconocido';
		}
		else $nombreUsuario = 'desconocido';

		$mensaje .= self::SEPARADOR.$nombreUsuario;
		$mensaje .= self::SEPARADOR.$texto.".\n";
		fwrite($fichero, $mensaje);
		fclose($fichero);
	}
	public static function anotarArgumento($metodo, $argumento, $tipo='entrada'){
	/**-	Escribe en el fichero de log un nombre de método y el argumento.
		Se utiliza para escribir tanto parámetros de entrada como de salida.
		El argumento se escribe en xml.
		@param $metodo Nombre del método.
		@param $argumento Argumento. Puede ser un array u objeto.
		@param $tipo 'entrada' o 'salida'
	**/
		$separador = ' -> ';
		if ($tipo == 'salida')
			$separador = ' <- ';
		$argXml = new Xml($argumento);
		$texto = $metodo.$separador.$argXml->verXML();
		Registro::anotar($texto);
	}
}

/// 	Excepción de error en la apertura de fichero.
class ExcepcionRegistroAperturaFichero extends Excepcion{
	public function __construct($fichero){
		$titulo = "No se ha podido abrir el Registro de Operaciones.";
		$texto = "La aplicación no ha podido abrir el fichero '$fichero' para utilizarlo como Registro de Operaciones.";
		$solucionProgramador = "Compruebe los permisos sobre el fichero y sobre el directorio del fichero.";
		parent::__construct($titulo,$texto,$solucionProgramador);
	}
}
?>
