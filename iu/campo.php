<?php
/**
	@file campo.php
	Fichero de la clase Campo.
	@copy Copyright Ilke Benson 2008
	@author Miguel Jaque Barbero (mjaque@ilkebenson.com)
	@author Chema Viera Godoy (chema@ilkebenson.com)
	@version 1.0
	@date 2008-03-31
**/

abstract class Campo implements InterfazCampo{
/**-	Representa un campo de datos. Clase abstracta de la que derivan el resto de campos. 
	Los campos de datos son campos avanzados de XHTML. Se forman mediante combinaciones de varios campos y ayudan a simplificar la programación de interfaces de usuario.
**/

	protected $atributos = array();
	
	public function __construct($nodo){
	/**-	Construye el campo asignando sus atributos desde el nodo a los atributos de la instancia.
		@param $nodo DOMNode del campo en el interfaz.
	**/
		foreach($this->atributos as $atributo){
			if ($nodo->hasAttribute($atributo))
				$this->${'atributo'} = $nodo->getAttribute($atributo);
		}
	}
}

// Excepciones.
class ExcepcionCampoAtributoObligatorioNoDefinido extends Excepcion{
	public function __construct($atributo,$campo){
		$titulo = "El Campo '$campo' Requiere el Atributo '$atributo'.";
		$texto = "La aplicación no ha podido crear un campo de la clase '$campo' porque no está definido el atributo '$atributo' que es obligatorio.";
		$solucionProgramador = "Compruebe que ha asignado un valor al atributo '$atributo' en el campo. Compruebe que el nombre del atributo está correctamente escrito.";
		$solucionUsuario = "Se ha detectado un error de programación. Por favor avise al administrador de la aplicación.";
		parent::__construct($titulo,$texto,$solucionProgramador, $solucionUsuario);
	}
}
?>
