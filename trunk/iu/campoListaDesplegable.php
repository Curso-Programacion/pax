<?php
/**
	@file campoListaDesplegable.php
	Fichero de la clase del campoListaDesplegable.
	@copy Copyright Ilke Benson 2008
	@author Miguel Jaque Barbero (mjaque@ilkebenson.com)
	@author Chema Viera Godoy (chema@ilkebenson.com)
	@version 1.0
	@date 2008-02-19
**/

class CampoListaDesplegable extends Campo{
/**-	Representa un campo de lista desplegable. 
	Está formado por un select.
**/
	protected $atributos = array('id','clase','tabla','columna','editable','tabindex','nulo');

	public function __construct($nodo){
	/**-	Construye el campo
		Los atributos operativos son:
			- id: Identificador que se asignará al campo.
			- clase: Clase de CSS que se asignará al campo.
			- lista: Nombre de la ListaAuxiliar asociada al campo.
			- editable: Indica si la lista es inicialmente editable (sí/no).
			- nulo: Indica si la lista contendrá una opción nula (sí/no).
			- tabindex: Valor del índice de tabulación.
		@param $nodo Nodo campoLista de PAX (<pax:campoLista .../>).
	**/
		parent::__construct($nodo);
		if (!isset($this->id)) throw new ExcepcionCampoAtributoObligatorioNoDefinido('id',get_class($this));
	}
	public function verNodo(){
	/**-	Devuelve el nodo que constituye el campo.
		@return DOMElement con el nodo que representa el campo
	**/
		$xml = new DOMDocument();
		// Creamos el nodo del campo de texto
		$select = $xml->createElement("select");
		$select->setAttribute("id", $this->id);
		if (isset($this->tabindex))
			$select->setAttribute("tabindex", $this->tabindex);
		if (isset($this->editable))
			if ($this->editable == 'no')
				$select->setAttribute("disabled", "disabled");
		if (isset($this->nulo))
			if ($this->nulo == 'sí'){
				$option = $xml->createElement('option');
				$option->setAttribute('value', null);
				$nombre = $xml->createTextNode('Sin definir');
				$option->appendChild($nombre);
				$select->appendChild($option);
			}
		
		// Cargamos los datos
		if (!isset($this->tabla)) return $select;

		$lista = new ListaAuxiliar();
		if (isset($this->columna)){
			$datos = $lista->verEnum($this->tabla, $this->columna);	//Cargamos la lista de un enum de la columna
			for($i=0; $i<sizeof($datos); $i++){
				$option = $xml->createElement("option");
				$option->setAttribute("value", $datos[$i]);
				$nombre = $xml->createTextNode($datos[$i]);
				$option->appendChild($nombre);
				$select->appendChild($option);
			}
		}
		else{
			$datos = $lista->verTabla($this->tabla);	//Cargamos la lista de una tabla
			for($i=0; $i<sizeof($datos); $i++){
				$option = $xml->createElement("option");
				$option->setAttribute("value", $datos[$i]['id']);
				$nombre = $xml->createTextNode($datos[$i]['nombre']);
				$option->appendChild($nombre);
				$select->appendChild($option);
			}
		}

		return $select;
	}
}

///	Excepciones.
?>
