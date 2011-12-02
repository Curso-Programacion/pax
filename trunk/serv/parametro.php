<?php
/************************************************************************
@file parametro.php 
Copyright Ilke Benson, A.I.E. 2009
@author Miguel Jaque Barbero (mjaque@ilkebenson.com)
Revisado por: $Author$ 
Revisión: $Rev$
@date $Date$
Diseñado por: Miguel Jaque Barbero
Fecha de Diseño: 27/4/2008
**************************************************************************/
class Parametro{
/**-	Implementa un parámetro de $_REQUEST
**/

	//Atributos
	private $valor;			//Valor del Parámetro.
	private $procedencia;		//Procedencia del Parámetro.
	private $nulo;			//Booleano indicando si el Parámetro puede ser nulo.
	private $nombre;		//Nombre del Parámetro
	private $filtro = FILTER_SANITIZE_STRING;	//Filtro que se aplicará a los parámetros
	private $opciones = null;	//Opciones para el filtro

	public function __construct($nombre, $nulo = false, $procedencia = INPUT_POST){
	/**-	Lee el valor del parámetro.
		@param $nombre Nombre del parámetro. Debe ser el nombre en el array $_POST/$_GET. Por defecto, se utiliza $_POST.
		@param $nulo Booleano que indica si el Parámetro puede ser nulo (no definido). Por defecto, no pueden serlo.
		Un parámetro se considera nulo cuando:
			- No se ha recibido el parámetro (no figura en el $_POST/$_GET).
			- El parámetro tiene como valor 'undefined'.
			- El parámetro no tiene valor.
		@param $procedencia Indica la procedencia del parámetro, puede ser INPUT_POST o INPUT_GET (constantes de filter).
		Su valor por defecto es INPUT_POST.
	**/
		if ((!isset($nombre)) OR ($nombre=='')) throw new ExcepcionParametroNombreInvalido();
		$this->nombre = $nombre;
		$this->procedencia = $procedencia;
		$this->nulo = $nulo;
	}
	public function verValor(){
	/**-	Lee y devuelve el valor del parámetro
		@return Valor del parámetro
	**/
		if (defined('EVITAR_FILTROS')){
			if(EVITAR_FILTROS != 'sí'){	//Constante para pruebas.
				$this->valor = filter_input($this->procedencia, $this->nombre, $this->filtro, $this->opciones);
				if (!$this->valor) throw new ExcepcionParametroFiltro();
			}
			else{
				if ($this->procedencia == INPUT_POST) $this->valor = $_POST[$this->nombre];
				elseif ($this->procedencia == INPUT_GET) $this->valor = $_GET[$this->nombre];
			}
		}
		else{
			if ($this->procedencia == INPUT_POST) $this->valor = $_POST[$this->nombre];
			elseif ($this->procedencia == INPUT_GET) $this->valor = $_GET[$this->nombre];
		}
		if (!$this->nulo){
			if ($this->valor == null) throw new ExcepcionParametroInexistente($this->nombre);	//El valor también da null
			if ($this->valor == 'undefined') throw new ExcepcionParametroIndefinido($this->nombre);
		}

		//Establecimiento de valores nulos
		if ($this->valor == 'undefined') $this->valor = null;
		if ($this->valor == '') $this->valor = null;

		//Condiciones adicionales
		if (strlen($this->valor) > 250) throw new ExcepcionParametroErroneo($nombre);

		return $this->valor;
	}
}

//Excepciones
class ExcepcionParametroFiltro extends Excepcion{
	public function __construct(){
		$titulo = "Error al Filtrar Parámetros";
		$texto = "Se ha producido un error al comprobar los parámetros recibidos.";
		$solucionProgramador = "Ha fallado la función filter_input_array en la clase Parametro.";
		parent::__construct($titulo,$texto,$solucionProgramador);
	}
}
class ExcepcionParametroNombreInvalido extends Excepcion{
	public function __construct(){
		$titulo = "No se ha especificado ningún nombre para el parámetro";
		$texto = "La aplicación no ha podido leer el parámetro solicitado porque no se ha indicado ningún nombre para él.";
		$solucionProgramador = "Revise los parámetros pasados al constructor de la clase.";
		parent::__construct($titulo,$texto,$solucionProgramador);
	}
}
class ExcepcionParametroInexistente extends Excepcion{
	public function __construct($parametro){
		$titulo = "Falta el parámetro requerido '$parametro'.";
		$texto = "La aplicación no ha recibido el parámetro '$parametro', que es imprescindible para realizar la operación.";
		$solucion = "Compruebe que ha completado todos los campos obligatorios del formulario y reintente la operación.";
		parent::__construct($titulo,$texto,$solucion);
	}
}
class ExcepcionParametroIndefinido extends Excepcion{
	public function __construct($parametro){
		$titulo = "El Parámetro '$parametro' No está Definido.";
		$texto = "La aplicación ha recibido el valor 'undefined' para el parámetro '$parametro', por lo que es imposible realizar la operación.";
		$solucionProgramador = "Compruebe que ha asignado valores a los campos del formulario y que se asignan valores por defecto.";
		$solucionUsuario = "Compruebe que ha completado todos los campos obligatorios del formulario y reintente la operación.";
		parent::__construct($titulo,$texto,$solucion, $solucionUsuario);
	}
}
class ExcepcionParametroErroneo extends Excepcion{
	public function __construct($parametro){
		$titulo = "El Parámetro '$parametro' es Erróneo";
		$texto = "El parámetro '$parametro' no cumple con los requerimientos establecidos.";
		$solucionProgramador = "El parámetro no ha superado los tests realizados en la clase Parametro.";
		$solucionUsuario = "Compruebe los valores introducidos y reintente la operación.";
		parent::__construct($titulo,$texto,$solucionProgramador,$solucionUsuario);
	}
}
?>
