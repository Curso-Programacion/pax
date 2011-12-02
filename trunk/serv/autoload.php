<?php
/**
	@file autoload.php
	Fichero con la función de autocarga de clases.
	Copyright Ilke Benson 2008
	@author Miguel Jaque Barbero (mjaque@ilkebenson.com)
	@version 1.0
	@date 2008-01-21

**/

function __autoload($clase){
/**-	Carga la clase solicitada.
	@param $clase Nombre de la clase.
**/
	$config = Config::verInstancia();
	$primeraLetra = substr($clase,0,1);
	$clase = substr_replace($clase, strtolower($primeraLetra),0,1);

	//Buscamos en los directorios de IU, APP, DOM, DAT y SERVICIOS
	$dirs = array($config->ver('dir','iu'), $config->ver('dir','app'), $config->ver('dir','dom'), $config->ver('dir','dat'), $config->ver('dir','serv'));
	foreach ($dirs as $dir)
		if(file_exists($dir.$clase.".php")){
			require_once($dir.$clase.".php");
			return;
		}
	//throw new ExcepcionAutoloadClaseNoEncontrada($clase);
}

//// 	Excepción de clase no encontrada.
class ExcepcionAutoloadClaseNoEncontrada extends Excepcion{
	public function __construct($clase){
		$titulo = "No se pudo cargar la clase $clase";
		$texto = "La aplicación no encontró la clase $clase en ninguno de los archivos.";
		$solucion = "Compruebe el nombre de la clase y los directorios definidos en el fichero de configuración.";
		parent::__construct($titulo,$texto,$solucion);
	}
}
?>
