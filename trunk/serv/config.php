<?php
/**
	@file config.php
	Fichero con la clase Config.
	@copy Copyright Ilke Benson 2008
	@author Miguel Jaque Barbero (mjaque@ilkebenson.com)
	@version 1.0
	@date 2008-01-22

**/
class Config{
/**-	Clase de Configuración. Es un singleton.
**/
	const FICHERO = 'config.ini';
	//Atributos
	private static $instancia;
	private static $fichero;
	private static $datos;

	private function __construct(){
	/**-	Constructor privado. Inaccesible para garantizar la unicidad de la instancia según el patrón singleton.
	**/
	}
	public function __clone(){
	/**-	Método de clonación. Inoperativo para garantizar la unicidad de la instancia según el patrón singleton.
	**/
		throw new Exception("Operación de clonación sobre singleton NO PERMITIDA");
	}
	public static function verInstancia(){
	/**-	Devuelve la instancia del config, que actúa como Singleton.
		@return La instancia del config.
	**/
		if (self::$instancia === null){
			self::inicializar();
			self::$instancia = new self;
		}
		return self::$instancia;
	}
	private function inicializar(){
		self::$datos = parse_ini_file(self::FICHERO, true); //Configuración general de PAX
		//Cargamos la configuración de la aplicación
		if(isset(self::$datos['general']['config']))
			self::$datos['app'] = parse_ini_file(self::$datos['general']['config'], true);	
	}
	public function ver($seccion,$nombre){
	/**-	Devuelve el valor de un parámetro de configuración general de PAX.
		@param $nombre Nombre del parámetro.
		@param $seccion Nombre de la sección del parámetro.
	**/
		return self::$datos[$seccion][$nombre];
	}
}
?>
