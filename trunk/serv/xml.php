<?php
/**
	@file xml.php
	Fichero de la clase XML.
	Copyright Ilke Benson 2008
	@author Miguel Jaque Barbero (mjaque@ilkebenson.com)
	@version 1.0
	@date 2008-02-06
**/

class XML{
/**-	Clase XML.
**/
	private $xml;		//El objeto XML
	private $encoding = 'UTF-8';

	public function __construct($objeto){
	/**-	Construye un documento XML a partir de un objeto.
		@param $objeto Objeto de origen.
	**/
		$this->xml = new DomDocument('1.0', $this->encoding);
		$nodoRaiz = $this->crearXML($objeto, get_class($objeto));
		$this->xml->appendChild($nodoRaiz);
	}
	private function crearXML($objeto, $nombreElemento='respuesta'){
	//TODO: Valorar la posibilidad de utilizar un Iterador.

		if(!$nombreElemento) $nombreElemento = 'respuesta';
		if(is_object($objeto)){
			$nombreElemento = get_class($objeto);
			$elemento = $this->xml->createElement($nombreElemento);
			if (method_exists($objeto, "verAtributos"))
				$vars = $objeto->verAtributos();
			else
				$vars = get_object_vars($objeto);
		}
  
		if(is_array($objeto)){
			$elemento = $this->xml->createElement($nombreElemento);
			$vars = $objeto;
		}

		if(is_scalar($objeto)){
			//Creamos un elemento con un nodo de texto
			$elemento = $this->xml->createElement($nombreElemento);
			$nodo = $this->xml->createTextNode($objeto);
			$elemento->appendChild($nodo);
			return $elemento;
		}
 
		foreach($vars as $atributo => $valor){
			if ($valor == null) continue;	//Los valores nulos no se incluyen.
			if(is_scalar($valor) OR ($valor == '')){		//Si el atributo es un valor escalar
				//if (is_numeric($atributo)) $atributo="item";
				if (is_numeric($atributo)) 			//El array no es asociativo, creamos un elemento por cada miembro del array
					$elemento->appendChild($this->crearXML($valor,'item'));
				else
					//$elemento->setAttribute($atributo, utf8_encode($valor));
					$elemento->setAttribute($atributo, $valor);
			}elseif(is_array($valor)){	//Si el atributo es un array
				if (is_numeric($atributo)) $atributo="item";
				$elemento->appendChild($this->crearXML($valor, $atributo));
			}elseif(is_object($valor)) {	//Si el atributo es un objeto
				$elemento->appendChild($this->crearXML($valor, get_class($valor)));
			}else throw new ExcepcionXMLAtributoDesconocido($atributo);
		}
		
		return $elemento;
	}
	public function verXML(){
	/**-	Devuelve el texto XML correspondiente al objeto.
	**/
		$this->xml->encoding = $this->encoding;		//Por algún motivo, se pierde.
		return $this->xml->saveXML();
	}
}

class ExcepcionXMLAtributoDesconocido extends Excepcion{
/// 	Excepción de XML, atributo desconocido.
	public function __construct($atributo){
		$titulo = "No se pudo construir el atributo de un objeto XML";
		$texto = "La aplicación intentó crear un objeto XML, pero el atributo ($atributo) del objeto php es de un tipo desconocido.";
		$solucion = "Debe tratarse de un error de programación.";
		parent::__construct($titulo,$texto,$solucion);
	}
}


return true;	//Indicador de carga para __autoload
?>
