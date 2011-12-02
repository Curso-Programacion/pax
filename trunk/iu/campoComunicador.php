<?php
/************************************************************************
@file campoComunicador.php 
Copyright Ilke Benson, A.I.E. 2009
@author Miguel Jaque Barbero (mjaque@ilkebenson.com)
@author Chema Viera Godoy (chema@ilkebenson.com)
Revisado por: $Author$ 
Revisión: $Rev$
@date $Date$
Diseñado por: Miguel Jaque Barbero
Fecha de Diseño: 9/2/2009
**************************************************************************/

class CampoComunicador extends Campo{
/**-	Representa un Comunicador (mascota que habla con el usuario). 
	Está formada por una imagen y un div de texto, ambos en posiciones absolutas
**/
	protected $atributos = array('id');		///< Array de atributos del campo.

	public function __construct($nodo){
	/**-	Construye el campoHora
		Los atributos operativos son:
			- id: Identificador que se asignará al campo.
		@param $nodo Nodo fecha de PAX (<espantaperros:campoComunicador .../>).
	**/
		parent::__construct($nodo);
		
		if (!isset($this->id)) throw new ExcepcionCampoAtributoObligatorioNoDefinido('id',get_class($this));
	}
	public function verNodo(){
	/**-	Devuelve el nodo que constituye el campo.
		@return DOMElement con el nodo que representa el campo
	**/
		$xml = new DOMDocument();
		// Creamos el nodo de img
		$img = $xml->createElement('img');
		$img->setAttribute('id',$this->id.'Img');
		$divTexto = $xml->createElement('div');
		$divTexto->setAttribute('id',$this->id.'Texto');
		$divTexto->appendChild($xml->createTextNode(' '));	//Sin esto, anida mal.
		$imgPico = $xml->createElement('img');
		$imgPico->setAttribute('id',$this->id.'Pico');

		// Creamos un div para unirlos
		$div = $xml->createElement("div");
		$div->setAttribute('id',$this->id);
		$div->appendChild($img);
		$div->appendChild($divTexto);
		$div->appendChild($imgPico);

		return $div;
	}
}

///	Excepciones.
?>
