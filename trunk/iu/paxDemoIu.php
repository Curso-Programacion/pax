<?php
/************************************************************************
@file paxIu.php 
Copyright Ilke Benson, A.I.E. 2009
@author Miguel Jaque Barbero (mjaque@ilkebenson.com)
@author Chema Viera Godoy (chema@ilkebenson.com)
Revisado por: $Author$ 
Revisión: $Rev$
@date $Date$
Diseñado por: Miguel Jaque Barbero
Fecha de Diseño: 21/4/2009
**************************************************************************/

abstract class PaxDemoIu extends Iu{
/**-	Clase abstracta de interfaz de usuario. El resto de clases de interfaz de usario derivan de ella. 
	Aporta métodos comunes propios de la Aplicación para todos los interfaces gráficos.
	La responsabilidad de esta clase es la generación de elementos gráficos propios de la Aplicación.
**/
	const NS_PAXDEMO = "http://www.ilkebenson.com/paxDemo";
	const PLANTILLA = "iu/xhtml/plantilla.xml";
	protected $controlador;

	public function __construct(){
	/**- Constructor de la clase, obtiene la instancia del controlador Espantaperros.
	**/
		$this->controlador = Controlador::verInstancia(); 
		parent::__construct();
	}
	public function ver(){
	/**-	Comportamiento general del método ver de un interfaz de Espantaperros.
		Comprueba el permiso de Espantaperros y presenta una excepción si no lo tiene.
	**/
		try{
			$DOMInterfaz = $this->verDOMInterfaz($this->config->ver('dir','iu').$this->interfaz);
			$this->cargarBBDD($DOMInterfaz);
			$this->procesarInterfaz(&$DOMInterfaz);	//Llamada a las plantillas de clases derivadas para permitir personalizar el interfaz.
			$xhtml = parent::verXHTML($DOMInterfaz);
			$this->enviarXHTML($xhtml);
		}
		catch(Exception $e){
			Registro::anotar($e);
			$excepcion = new ExcepcionIu($e);
			$excepcion->ver();
		}
	}
	protected function cargarBBDD($DOMInterfaz){
	/**-	Carga la lista desplegable de bases de datos y selecciona la que corresponda.
	**/
		if (!isset($_SESSION['conexion'])) return;
		if (!$this->verElementoPorId($DOMInterfaz, 'bbdd')) return;

		$bbdd = $this->controlador->verBBDD();
		$this->ponerOpcionLista($DOMInterfaz,'bbdd','','Sin definir');
		for ($i=0; $i<sizeof($bbdd);$i++)
			$this->ponerOpcionLista($DOMInterfaz,'bbdd',$bbdd[$i]['Database']);
		$bd = $_SESSION['conexion']->verBD();
		if ($bd){
			$this->seleccionarOpcionLista($DOMInterfaz, 'bbdd', $bd);
			$tablas = $this->controlador->verTablasBD($bd);
			for ($i=0; $i<sizeof($tablas);$i++)
				$this->ponerOpcionLista($DOMInterfaz,'tablas',$tablas[$i][0]);
			$tabla = $_SESSION['conexion']->verTabla();
			$this->seleccionarOpcionLista($DOMInterfaz, 'tablas', $tabla);
		}
	}
	protected function procesarInterfaz(&$DOMInterfaz){
	/**-	MÃ©todo de proceso del interfaz por defecto.
		@param &$DOMInterfaz DOMDocument con el interfaz que se estÃ¡ procesando.
	**/
		return;
	}
	protected function verInterfaz($ficheroInterfaz){
	/**-	Genera el XHTML correspondiente a un interfaz.
		Procesa el interfaz en XML generando la salida en XHTML.
		@param $interfaz Texto con el path del fichero XML del interfaz.
		@return Texto con el XHTML del interfaz procesado.
	**/
		$interfaz = $this->verDOMInterfaz($ficheroInterfaz);
		return parent::verXHTML($interfaz);
	}
	protected function verDOMInterfaz($ficheroInterfaz){
	/**-	Devuelve el DOM XML correspondiente a un interfaz.
		Procesa el interfaz en XML.
		@param $interfaz Texto con el path del fichero XML del interfaz.
		@return DOMDocument correspondiente al intefaz.
	**/
		$path = realpath(".")."/";
		$plantilla = $this->cargarPlantilla($path.self::PLANTILLA);
		$this->cargarInterfazEnPlantilla($path.$ficheroInterfaz, $plantilla);
		$this->procesar($plantilla);
		return $plantilla;
	}
	protected function procesar(&$xml){
	/**-	Procesa un documento XML para susituir los nodos propios de Espantaperros por elementos XHTML estándar.
		También llama a Iu para procesar los nodos de PAX.
		@param &$xml Referencia al Documento XML
	**/
		parent::procesar($xml, self::NS_PAXDEMO);
		parent::procesar($xml);	//Para que se procesen los nodos PAX
	}
}

//	Excepciones.
class ExcepcionEspantaperrosIuMetodoInexistente extends Excepcion{
	public function __construct($metodo){
		$titulo = "El método '$metodo' no existe";
		$texto = "La aplicación no ha podido llamar al método '$metodo' porque no existe en la clase.";
		$solucionProgramador = "Comprueba el nombre del método y la clase del objeto this. Es posible que no hayas creado el método en la clase Espantaperros.";
		parent::__construct($titulo,$texto,$solucionProgramador);
	}
}
?>
