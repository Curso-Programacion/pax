<?php
/**
	@file dom.php
	Fichero con la clase Dom.
	@copy Copyright Ilke Benson 2008
	@author Miguel Jaque Barbero (mjaque@ilkebenson.com)
	@author Chema Viera Godoy (chema@ilkebenson.com)
	@version 1.0
	@date 2008-08-07

**/

abstract class Dom{
/**-	Clase abstracta para la derivación de objetos de dominio.
 	Reúne los métodos comunes para todos los objetos de dominio.
	La responsabilidad de las clases de dominio es ejecutar la lógica de negocio de la aplicación, pero sin conocer ni los interfaces de usuario ni los sistemas de datos utilizados.
**/
	const SIN_VALOR = "##__PAX: El valor no ha sido definido__#";

	public function atr($nombre, $valor = self::SIN_VALOR){
	/**-	Devuelve o establece el valor de un atributo.
		Si se llama con un sólo parámetro, realizará la consulta del atributo. Si se llama con dos, realizará la modificación.
		Esta función puede ser sobreescrita por las clases derivadas para controlar el acceso a atributos protegidos o privados.
		@param $nombre Nombre del atributo.
		@param $valor Valor del atributo.
		@return El valor del atributo consultado o true si es una modificación.
	 **/
		//Probar a utilizar __get y __set http://www.php.net/manual/es/language.oop5.overloading.php
		if ($valor == self::SIN_VALOR) return $this->${'nombre'};
		else $this->${'nombre'} = $valor;
		return true;
	}
	public function verAtributos(){
	/**-	Devuelve la lista de atributos públicos de un objeto.
		Esta función es utilizada por la clase XML para convertir un objeto en un documento xml.
		Si los atributos son privados para encapsularlos, debe sobreescribirse esta función.
		@return Array indexado con los valores de los atributos del objeto. El índice es el nombre del atributo.
	**/
		return get_object_vars($this);
	}

}
?>
