<?php
/************************************************************************
@file iu.php 
Copyright Ilke Benson, A.I.E. 2009
@author Miguel Jaque Barbero (mjaque@ilkebenson.com)
@author Chema Viera Godoy (chema@ilkebenson.com)
Revisado por: $Author$ 
Revisión: $Rev$
@date $Date$
Diseñado por: Miguel Jaque Barbero
Fecha de Diseño: 21/4/2009
**************************************************************************/

abstract class Iu{
/**-	Clase abstracta de interfaz de usuario. El resto de clases de interfaz de usario derivan de ella. 
	Aporta métodos comunes para todos los interfaces gráficos y varios parámetros de configuración.
	La responsabilidad de las clases de interfaz de usuario es recibir las peticiones de los usuarios, extraer los parámetros del array $_REQUEST, comprobar que son válidos, llamar al controlador para obtener la respuesta y presentársela al usuario.
	También se encargan de la construcción de los interfaces gráficos (XHTML).
**/

	//public $formatoFecha = 'Y-m-d';			//Formato internacional de fecha ISO.
	//public $formatoFechaHora = 'Y-m-d H:i:s';		//Formato internacional ISO.
	public $formatoFechaHora = 'd/m/Y H:i:s';		//Formato español.
	const NS_PAX = "http://www.ilkebenson.com/pax";
	const CABECERA_XHTML = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">';
	const CABECERA_HTTP_XHTML = "Content-Type: application/xhtml+xml; charset=utf-8; Cache-control: no-cache";
	const CABECERA_HTTP_AJAX = "Content-Type: application/xml; charset=utf-8; Cache-control: no-cache;";
	public $config;
	protected $aviso;	//Excepción que se utilizará para dar avisos por el interfaz.

	public function __construct(){
	/**- Constructor de la clase, obtiene la instancia del controlador Espantaperros.
	**/
		$this->config = Config::verInstancia();
	}
	public function registrarAviso($e){
	/**-	Registra una excepción de la que debe avisar el interfaz.
		@param $e Excepción.
	**/
		$this->aviso = $e;
	}
	protected function verAviso($atributo){
	/**-	Devuelve el valor de uno de los atributos de la excepción de aviso.
		@param $atributo Nombre del atributo.
		@return Valor del atributo.
	**/
		switch($atributo){
			case 'titulo':
				return $this->aviso->titulo;
				break;
			case 'texto':
				return $this->aviso->texto;
				break;
			case 'solucionProgramador':
				return $this->aviso->solucionProgramador;
				break;
			case 'solucionUsuario':
				return $this->aviso->solucionUsuario;
				break;
		}
	}
	public function cabecerasCampoFecha(){
	/**-	Devuelve el código XHTML de las cabeceras requeridas para el campoFecha.
		@return El Cóodigo XHTML de las cabeceras.
	**/
		$xhtml = '<script type="text/JavaScript" src="iu/js/calendario/calendar.js"></script>'."\n";
		$xhtml .= '<script type="text/JavaScript" src="iu/js/calendario/lang/calendar-sp.js"></script>'."\n";
		$xhtml .= '<script type="text/JavaScript" src="iu/js/calendario/calendar-setup.js"></script>'."\n";
		$xhtml .= '<link href="iu/css/calendario/calendar-win2k-cold-1.css" rel="stylesheet" type="text/css"/>'."\n";

		return $xhtml;
	}	
	protected function campoFecha($id,$clase,$valor){
	/**-	Devuelve el código XHTML de un campo de fecha.
		El método cabecerasCampoFecha devuelve el código XHTML correspondiente a las cabeceras necesarias.
		@param $id Identificador que se asignará al campo. TambiÃ©n se asignará un atributo name con ese valor.
		@param $clase Clase CSS que se asignará al campo.
		@param $valor Valor inicial del campo. Si no está definido, se pone la fecha actual.
		@return Devuelve código XHTML con el campo de fecha. Este campo incluye un calendario desplegable.
	OBSOLETA POR CAMPO FECHA
	**/
	throw ("iu -> campoFecha : Esta función está obsoleta");
		$xhtml = '<input type="text" size="10" maxlength="10"';
		if (isset($id)) $xhtml .= " id=\"$id\" name=\"$id\" ";
		if ($valor=='hoy') 
			$xhtml .= "value=\"".date($this->formatoFecha)."\" ";
		else 
			$xhtml .= "value=\"$valor\" ";

		$xhtml .= "/>";
		$xhtml .= '<script type="text/javascript">';
		$xhtml .= '	Calendar.setup({';
		$xhtml .= '	inputField : "'.$id.'",';
                //$xhtml .= '	ifFormat   : "%Y-%m-%d",';	//OJO, deberÃ­a formarse utilizando $this->formatoFecha
                $xhtml .= '	ifFormat   : "%d/%m/%Y",';	//OJO, deberÃ­a formarse utilizando $this->formatoFecha
                $xhtml .= ' 	button     : "'.$id.'"})';
		$xhtml .= '</script>';

		return $xhtml;
	}
	protected function cargarInterfazEnPlantilla($ficheroInterfaz, &$plantilla){
	/**-	Carga un fichero de interfaz en formato XML dentro de una plantilla XML.
		Para hacerlo, busca el elemento <pax:interfaz/> en la plantilla y lo sustituye por el documento XML del interfaz.
		También carga el script y el estilos propios del interfaz si así se indica.
		El script y el estilo propios corresponden a ficheros con el mismo nombre que el interfaz (nombre.js e nombre.css),
		se cargarán si el nodo página del interfaz tiene los atributos scriptPropio y estiloPropio con el valor "sí".
		@param $ficheroInterfaz Path del fichero XML del interfaz a cargar.
		@param &$plantilla Documento XML (DOMDocument) en el que se cargará el interfaz.
	**/
		$docInterfaz = new DOMDocument();
		if (!$docInterfaz->load($ficheroInterfaz)) throw new ExcepcionIuCargarXML($ficheroInterfaz);
		$pagina = $docInterfaz->documentElement;
		$trozos = explode("/",$ficheroInterfaz);
		$trozos = explode(".", $trozos[sizeof($trozos)-1]);
		$nombre= $trozos[0];
		if ($pagina->hasAttribute("título")){
			$nodoTitulo = $plantilla->getElementsByTagName("title")->item(0);
			$nodoTitulo->childNodes->item(0)->nodeValue .= $pagina->getAttribute("título");
		}
		if ($pagina->hasAttribute("scriptPropio"))
			if ($pagina->getAttribute("scriptPropio") == "sí")	// Añadimos el script propio del interfaz
				$this->ponerScript($plantilla, $this->config->ver('dir','js').$nombre.".js");
		if ($pagina->hasAttribute("estiloPropio"))
			if ($pagina->getAttribute("estiloPropio") == "sí")	// Añadimos el script propio del interfaz
				$this->ponerCSS($plantilla, $this->config->ver('dir','css').$nombre.".css");

		// "Reemplazamos" el nodo interfaz de la plantilla por el interfaz solicitado
		$nodoInterfaz = $plantilla->getElementsByTagNameNS(self::NS_PAX, "interfaz")->item(0);
		// El interfaz lo constituyen todos los elementos que son hijos del elemento página del fichero interfaz
		// Así­ que, primero los insertamos antes del nodo interfaz y luego eliminamos el nodo interfaz
		for ($i=0; $i<$pagina->childNodes->length; $i++){
			$nuevoNodo = $plantilla->importNode($pagina->childNodes->item($i),true);	// Es necesario importar el nodo al documento antes de hacer el reemplazo.
			$nodoInterfaz->parentNode->insertBefore($nuevoNodo, $nodoInterfaz);
		}
		$nodoInterfaz->parentNode->removeChild($nodoInterfaz);
	}
	protected function cargarPlantilla($plantilla){
	/**-	Carga la plantilla XHTML indicada
		@param $plantilla Path de la plantilla a cargar.
		@return Devuelve un documento XML (DOMDocument) con la plantilla cargada.
	**/
		$docXML = new DOMDocument();
		if (!$docXML->load($plantilla)) throw new ExcepcionIuCargarXML($fichero);
		return $docXML;
	}
	private function cerrarTag($tag, $xml){
	/**-	Cierra elementos vacíos de un documento XML.
		Algunos elementos, como script y select no pueden ir vacÃ­os en el XHTML porque FF e IE los presentan mal.
		@param $tag Nombre del elemento que debe ser cerrado.
		@param $xhtml Texto XML a procesar.
		@return Texto XML con los elementos vacÃ­os corregidos.
	**/
		$indice = 0;
		while ($indice< strlen($xml)){
			$pos = strpos($xml, "<$tag ", $indice);
			if ($pos){
				$posCierre = strpos($xml, ">", $pos);
				if ($xml[$posCierre-1] == "/"){
					$xml = substr_replace($xml, "></$tag>", $posCierre-1, 2);
				}
				$indice = $posCierre;
			}
			else break;
		}
		return $xml;
	}
	protected function enviarXHTML($xhtml){
	/**-	Genera la salida de un texto XHTML. EnvÃ­a las cabeceras HTTP necesarias.
		@param $xhtml Texto del XHTML
	**/
		if (!headers_sent())	//Las cabeceras pueden haberse enviado en los test
			header(Iu::CABECERA_HTTP_XHTML);
		echo ($xhtml);
	}
	private function ponerCSS(&$xml,$path){
	/**-	Añade un fichero de hoja de estilo a la cabecera del documento
		@param &$xml Documento XML al que se añadirá el script.
		@param $path Path del fichero con la hoja de estilo.
	**/
		$head = $xml->getElementsByTagName("head")->item(0);
		$link = $xml->createElement("link");
		$link->setAttribute("rel", "stylesheet");
		$link->setAttribute("type", "text/css");
		$link->setAttribute("href", $path);	
		$head->appendChild($link);
	}
	private function ponerFecha(&$xml, &$nodo){
	/**-	Añade un campo de Fecha al documento XML.
		El campo de fecha consiste en un campo de texto y un script.
		Además, hay que cargar en la página los scripts generales y el estilo del campo (si no están cargados).
		@param &$xml Referencia al documento XML al que añadir el campo.
		@param $nodo Nodo que debe sustituirse por el campo de Fecha.
	**/
		// Creamos el nodo del campo de texto
		$campoTexto = $xml->createElement("input");
		$campoTexto->setAttribute("type", "text");
		$campoTexto->setAttribute("size", "10");
		$campoTexto->setAttribute("maxlength", "10");
		$id = $nodo->getAttribute("id");
		$campoTexto->setAttribute("id", $id);
		$campoTexto->setAttribute("name", $id);
		$fecha = date($this->formatoFecha);
		if ($nodo->hasAttribute("valor"))
			if ($nodo->getAttribute("valor") != "hoy")
				$fecha = $nodo->getAttribute("valor");
		$campoTexto->setAttribute("value", $fecha);

		// Creamos el nodo de script
		$script = $xml->createElement("script");
		$script->setAttribute("type", "text/JavaScript");
		$codigo = '	Calendar.setup({';
		$codigo .= '	inputField : "'.$id.'",';
                //codigo .= '	ifFormat   : "%Y-%m-%d",';	//OJO, deberÃ­a formarse utilizando $this->formatoFecha
                $codigo .= '	ifFormat   : "%d/%m/%Y",';	//OJO, deberÃ­a formarse utilizando $this->formatoFecha
                $codigo .= ' 	button     : "'.$id.'"})';	
		$script->appendChild($xml->createTextNode($codigo));

		// Creamos un span para unirlos
		$span = $xml->createElement("span");
		$span->appendChild($campoTexto);
		$span->appendChild($script);

		// Reemplazamos el nodo pax:fecha por el nodo de span
		$padre = $nodo->parentNode;
		$padre->replaceChild($span, $nodo);

		// Añadimos las cabeceras de script en el head del documento
		$this->ponerScript($xml, $config->ver('dir','js').'calendario/calendar.js');
		$this->ponerScript($xml, $config->ver('dir','js').'calendario/lang/calendar-sp.js');
		$this->ponerScript($xml, $config->ver('dir','js').'calendario/calendar-setup.js');

		// Añadimos la hoja de estilo
		$this->ponerCSS($xml, $config->ver('dir','css').'calendario/calendar-win2k-cold-1.css');
	}
	protected function ponerScript(&$xml,$path){
	/**-	Añade una fichero de script a la cabecera del documento
		@param &$xml Documento XML al que se añadirá el script.
		@param $path Path del fichero con el script.
	**/
		$head = $xml->getElementsByTagName("head")->item(0);
		$script = $xml->createElement("script");
		$script->setAttribute("type", "text/JavaScript");
		$script->setAttribute("src", $path);	
		//$codigo = $xml->createCDATASection(";");		// Es necesaria una sección de código para que se ponga el tag de cierre y no haya error sintáctico.
		//$script->appendChild($codigo);
		$head->appendChild($script);
	}
	protected function procesar(&$xml, $ns=null){
	/**-	Procesa un documento XML para susituir los nodos propios de un espacio de nombres.
		Al procesar cada nodo, se sustituye por el nodo de la clase correspondiente.
		El nodo sustitutivo se obtiene invocando el método verNodo de la clase creada.
		@param &$xml Referencia al Documento XML
		@param $ns Espacio de nombres referido. Si no se indica se utilizará el de PAX.
	**/
		if (!isset($ns)) $ns = self::NS_PAX;
		$nodos = $xml->getElementsByTagNameNS($ns, "*");
		//Siempre quitamos el primer elemento, porque la lista... ¡se reorganiza sola!
		while ($nodos->length > 0){
			$nodo = $nodos->item(0);
			//echo $nodo->nodeName." - ";
			if ($nodo->nodeName == "pax:página") continue;	//Este nodo no se procesa
			$trozos = explode(":",$nodo->nodeName);
			$clase = ucfirst($trozos[1]);
			$campo = new ${'clase'}($nodo);
			
			// Reemplazamos el nodo por el de la clase
			$nuevoNodo = $xml->importNode($campo->verNodo(), true);
			$padre = $nodo->parentNode;
			$padre->replaceChild($nuevoNodo, $nodo);
		}
	}
	public function responderPorAjax($objeto){
	/**-	Devuelve un objeto en una respuesta AJAX.
	 	Transforma el objeto en un mensaje XML y lo envía al cliente.
		@param $objeto El objeto respuesta.
	**/
		$respuesta = new Xml($objeto);
		if (!headers_sent())	//Las cabeceras pueden haberse enviado en los test
			header(self::CABECERA_HTTP_AJAX);
//Registro::anotar("RESPUESTA: ".$respuesta->verXML());
		echo $respuesta->verXML();
	}
	protected function verElementoPorId($dom, $id){
	/**- 	Busca un elemento en el DOM por su atributo id.
		@param $dom Documento DOM en el que se operará.
		@param $id Valor del atributo 'id' que identifica al nodo.
		@return Nodo del dom.
	**/
		$xpath = new DOMXPath($dom);
		$nodos = $xpath->query("//*[@id = '$id']");
		if ($nodos->length == 0) throw new ExcepcionIuNodoInexistente($id);
		return $nodos->item(0);
	}
	protected function quitarNodo($dom, $id){
	/**-	Elimina un nodo del DOM.
		@param $dom Documento DOM en el que se operará.
		@param $id Valor del atributo 'id' que identifica al nodo a eliminar.
	**/
		$nodo = $this->verElementoPorId($dom, $id);
		$nodo->parentNode->removeChild($nodo);
	}
	protected function sustituirTexto(&$dom, $id, $texto){
	/**-	Sustituye el primer nodo de un elemento XML, identificado por su 'id', por un nodo de texto.
		Se utiliza para sustituir contenidos de <p>, <h1>, etc.
		@param &$dom Documento DOM en el que se operará.
		@param $id Valor del atributo 'id' que identifica al nodo del $dom sobre el que se operará.
		@param $texto Texto que contendrá el nodo de texto
	**/
		$xp = new DomXPath($dom);
		$res = $xp->query("//*[@id = '$id']");
		$nodo = $res->item(0);
		if ($nodo->hasChildNodes())
			$nodo->removeChild($nodo->firstChild);
		$nodoTexto = $dom->createTextNode($texto);
		$nodo->appendChild($nodoTexto);
	}
	protected function verXHTML($doc){
	/**-	Genera el XHTML correspondiente a un documento (generalmente una plantilla con interfaz).
		@param $doc Documento XML (DOMDocument) con la plantilla.
		@return Texto XHTML correspondiente al documento.
	**/
		$xhtml = self::CABECERA_XHTML."\n";

		$xhtml .= $doc->saveXML($doc->documentElement);
		// El problema es que saveXML no cierra correctamente los tags de script, los hace vacÃ­os y eso a FF y a IE no les gusta.
		// La opción de utilizar saveHTML hace que los atributos disabled y checked tampoco se generen conforme a XHTML 1.0
		// AsÃ­ que la Ãºnica opción que queda es procesar la salida para corregir el error.
		$xhtml = $this->cerrarTag("script", $xhtml);
		$xhtml = $this->cerrarTag("select", $xhtml);
		return $xhtml;
	}
	protected function ponerValorCampo($dom, $id, $valor){
	/**-	Establece el atributo 'value' de un elemento en el DOM.
		@param $dom DOM (Document Object Model) en el que se realizará el cambio.
		@param $id Nombre del identificador del elemento.
		@param $value Valor.
	**/
		$xpath = new DOMXPath($dom);
		$campo = $xpath->query("//*[@id = '$id']")->item(0);
		$campo->setAttribute('value', $valor);
	}
	protected function seleccionarOpcionLista($dom, $id, $valor){
	/**-	Selecciona una opción de un campo 'select' en el dom
		@param $dom DOM (Document Object Model) en el que se realizará el cambio.
		@param $id Nombre del identificador del campo 'select'.
		@param $value Valor de la opción a seleccionar.
	**/
		$xpath = new DOMXPath($dom);
		$select = $xpath->query("//*[@id = '$id']")->item(0);
		for ($i=0; $i<$select->childNodes->length; $i++){
			if ($select->childNodes->item($i)->getAttribute('value') == $valor){
				$select->childNodes->item($i)->setAttribute('selected','selected');
				break;
			}
		}
		$select->setAttribute('valor', $valor);	//Para que JavaScript tenga el valor al iniciar el campo.
	}
	protected function anadirNodoTexto($dom, $id, $texto, $quitarHijos=false){
	/**-	Añade un nodo de texto al campo del dom.
		@param $dom DOM (Document Object Model) en el que se realizará el cambio.
		@param $id Nombre del identificador del elemento al que se añadirá el nodo de texto.
		@param $texto Pues eso, el texto.
		@param $quitarHijos Booleano indicando si deben eliminarse los hijos anteriores del nodo.
	**/
		$xpath = new DOMXPath($dom);
		$nodo = $xpath->query("//*[@id = '$id']")->item(0);
		if ($quitarHijos)
			while ($nodo->hasChildNodes())
				$nodo->removeChild($nodo->firstChild);
		$nodoTexto = $dom->createTextNode($texto);
		$nodo->appendChild($nodoTexto);
	}
	protected function ponerValorRadio($dom, $nombre, $valor){
	/**-	Selecciona el radio de un grupo de radios con el valor indicado.
		@param $dom DOM (Document Object Model) en el que se realizará el cambio.
		@param $nombre Nombre del grupo de radios (según atributo 'name'). 
		@param $valor Valor del radio a seleccionar.
	**/
		$xpath = new DOMXPath($dom);
		$radios = $xpath->query("//*[@name = '$nombre']");
		for ($i=0;$i<$radios->length;$i++)	//Recorremos todos los radios con el mismo 'name'
			if ($radios->item($i)->getAttribute('value') == $valor)
				$radios->item($i)->setAttribute('checked','checked');
			else
				$radios->item($i)->removeAttribute('checked');
	}
	protected function ponerFilaEnTabla($dom, $id, $datos){
	/**-	Crea una fila de datos en una tabla.
		@param $dom DOM (Document Object Model) en el que se realizará el cambio.
		@param $id Nombre del identificador de la tabla o de su tbody.
		@param $datos Array con los datos. Se creará una celda por cada dato del array. Los datos del array podrán ser de tipo 'string' o 'DOMElement'.
		@return Identificador de la fila creada.
	**/
		$xpath = new DOMXPath($dom);
		$tabla = $xpath->query("//*[@id = '$id']")->item(0);
		if (!$tabla) throw new ExcepcionIuTablaDOMNoEncontrada($id);
		$tr = $dom->createElement('tr');
		foreach($datos as $dato){
			$td = $dom->createElement('td');

			if (is_string($dato))
				$td->appendChild($dom->createTextNode($dato));
			if (get_class($dato) == 'DOMElement')
				$td->appendChild($dato);

			$tr->appendChild($td);
			unset($td,$texto);
		}
		return $tabla->appendChild($tr);
	}
	protected function ponerOpcionLista($dom, $id, $valor, $texto){
	/**-	Añade una opción a una lista desplegable.
		@param $dom DOM (Document Object Model) en el que se realizará el cambio.
		@param $id Nombre del identificador del campo 'select'.
		@param $value Valor de la opción a insertar.
		@param $texto Texto de la opción. Si es nulo se pondrá $valor
		@return Opción insertada.
	**/
		$xpath = new DOMXPath($dom);
		$select = $xpath->query("//*[@id = '$id']")->item(0);
		$option = $dom->createElement('option');
		$option->setAttribute('value',$valor);
		if (!isset($texto))
			$texto = $valor;
		$nodoTexto = $dom->createTextNode($texto);
		$option->appendChild($nodoTexto);
		$select->appendChild($option);
		return $option;
	}
}

// Excepciones.
class ExcepcionIuCargarXML extends Excepcion{
	public function __construct($fichero){
		$titulo = "El fichero XML '$fichero' no pudo ser cargado";
		$texto = "La aplicación no ha podido cargar un fichero del tipo XML.";
		$solucionProgramador = "Compruebe que el fichero existe, que hay permisos para leerlo y que su sintaxis XML es correcta.";
		parent::__construct($titulo,$texto,$solucionProgramador);
	}
}
class ExcepcionIuInsertarInterfaz extends Excepcion{
	public function __construct($fichero){
		$titulo = "El interfaz correspondiente a '$fichero' no pudo insertarse en la plantilla";
		$texto = "La aplicación no ha podido sustituir el nodo de la plantilla con el nodo del interfaz.";
		$solucionProgramador = "Compruebe que la plantilla contiene un nodo de interfaz.";
		parent::__construct($titulo,$texto,$solucionProgramador);
	}
}
class ExcepcionIuTablaDOMNoEncontrada extends Excepcion{
	public function __construct($id){
		$titulo = "No se ha encontrada la Tabla.";
		$texto = "La aplicación no ha podido encontrar la tabla con id='$id' en el interfaz indicado.";
		$solucionProgramador = "Revisa el identificador y asegúrate de que envías un DOM válido.";
		parent::__construct($titulo,$texto,$solucionProgramador);
	}	
}
class ExcepcionIuNodoInexistente extends Excepcion{
	public function __construct($id){
		$titulo = "No se ha Encontrada el Nodo.";
		$texto = "La aplicación no ha podido encontrar la nodo con id='$id' en el interfaz indicado.";
		$solucionProgramador = "Revisa el identificador y asegúrate de que envías un DOM válido.";
		parent::__construct($titulo,$texto,$solucionProgramador);
	}	
}
?>
