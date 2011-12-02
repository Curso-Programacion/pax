<?php
/************************************************************************
@file excepcionIu.php 
Copyright Ilke Benson, A.I.E. 2009
@author Miguel Jaque Barbero (mjaque@ilkebenson.com)
@author Chema Viera Godoy (chema@ilkebenson.com)
Revisado por: $Author$ 
Revisión: $Rev$
@date $Date$
Diseñado por: Miguel Jaque Barbero
Fecha de Diseño: 9/2/2009
**************************************************************************/

class ExcepcionIu extends PaxDemoIu{
/**-	Interfaz para la visualización XHTML de excepciones.
**/

	private $excepcion;
	const INTERFAZ = "xhtml/excepcion.xml";
	public $config;

	public function __construct($excepcion){
	/**-	Constructor de la clase.
		@param $excepcion Excepción para crear el interfaz de excepción.
	**/
		$this->excepcion = $excepcion;
		$this->config = Config::verInstancia();
		$this->controlador = Controlador::verInstancia(); 
	}
	public function ver(){
	/**-	Muestra la pantalla de la Excepción.
	**/
		//$this->responderExcepcionPorXHTML($e);
		$dom = $this->verDOMInterfaz($this->config->ver('dir','iu').self::INTERFAZ);

		//Cargamos la lista de bds
		$this->cargarBBDD($dom);
			
		//Insertamos en el interfaz los valores de la excepción
		$this->sustituirTexto($dom, 'tituloExcepcion', $this->excepcion->titulo);
		$this->sustituirTexto($dom, 'texto', $this->excepcion->texto);
		$this->sustituirTexto($dom, 'solucion', $this->excepcion->solucionUsuario);
		$this->sustituirTexto($dom, 'solucionProgramador', $this->excepcion->solucionProgramador);
		$this->sustituirTexto($dom, 'nombreExcepcion', get_class($this->excepcion));

		$xhtml = $this->verXHTML($dom);
		$this->enviarXHTML($xhtml);
		exit;	//Dejamos de ejecutar cualquier cosa que hubiera pendiente.
	}
}
?>
