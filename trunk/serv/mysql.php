<?php
/************************************************************************
@file mysql.php 
Copyright Ilke Benson, A.I.E. 2009
@author Miguel Jaque Barbero (mjaque@ilkebenson.com)
@author Chema Viera Godoy (chema@ilkebenson.com)
Revisado por: $Author$ 
Revisión: $Rev$
@date $Date$
Diseñado por: Miguel Jaque Barbero
Fecha de Diseño: 9/2/2009
**************************************************************************/

class Mysql{
/**-	Base de Datos MySQL.
**/
	//Atributos
	private static $mysqli;	//Hasta que estemos en PHP 5.3, garantizamos la reutilización de conexiones.
	private static $host;
	private static $usuario;
	private static $clave;
	private static $bd;

	public function Mysql($host, $bd, $usuario, $clave){
	/**- 	Crea el objeto de base de datos.
		No utilizamos el constructor __construct para permitir que sus subclases sean singletons.
		@param $host Host del servidor de base de datos.
		@param $bd Nombre de la base de datos a la que se conectará.
		@param $usuario Nombre del usuario de conexión.
		@param $clave Clave del usuario de conexión.
	**/
		$this->conectar($host, $bd, $usuario, $clave);
	}
	public function conectar($host, $bd, $usuario, $clave){
	/**- 	Crea la conexión con la base de datos.
		@param $host Host del servidor de base de datos.
		@param $bd Nombre de la base de datos a la que se conectará.
		@param $usuario Nombre del usuario de conexión.
		@param $clave Clave del usuario de conexión.
		BUG: La conexión no es persistente. Hasta que no sea PHP 5.3, el constructor de 
		mysqli no tendrá conexiones persistentes.
	**/
		if (	($host == Mysql::$host) AND 
			($usuario== Mysql::$usuario) AND 
			($clave == Mysql::$clave) 
			AND ($clave == Mysql::$clave)
			AND (isset(Mysql::$mysqli))
		) 
			return;

		Mysql::$host = $host;
		Mysql::$usuario = $usuario;
		Mysql::$clave = $clave;
		Mysql::$bd = $bd;
		try{
			Mysql::$mysqli = new mysqli($host, $usuario, $clave, $bd);
		}
		catch (Exception $e){
			throw new ExcepcionMySQLConexion($host,$usuario,Mysql::$mysqli->connect_error);
		}
		if (!Mysql::$mysqli->set_charset('utf8')) throw new ExcepcionMySQL_UTF8();
	}
	public function actualizar($tabla, $nombreClave, $valorClave, $valores){
	/**-	Actualiza datos en una tabla MySQL con clave simple (un campo).
		@param $tabla Nombre de la tabla en la que se realizará la inserción.
		@param $nombreClave Nombre de la columna clave por la que se actualizará la tabla.
		@param $valorClave Valor de la clave en la tupla/s que se actualizarán.
		@param $valores Array asociativo con los nombres de las columnas y los valores a insertar.
		@return Devuelve el número de tuplas afectadas por la operación.
	**/
		$consulta = "UPDATE $tabla SET ";
		foreach($valores as $columna => $valor){
			if ($valor == NULL) $consulta .= "$columna = NULL,";
			else $consulta .= "$columna = '$valor',";
		}
		$consulta = substr($consulta, 0, strlen($consulta)-1);
		if ($valorClave == NULL)
			$consulta .= " WHERE $nombreClave = NULL";
		else
			$consulta .= " WHERE $nombreClave = '$valorClave'";
		$this->ejecutar($consulta);
		
		return Mysql::$mysqli->affected_rows;
	}
	public function actualizarAtributo($tabla, $atributo, $valor, $filtro){
	/**-	Actualiza el valor de un atributo en una tabla, aplicando un filtro de selección opcional.
		@param $tabla Nombre de la tabla sobre la que se realizará la actualización.
		@param $atributo Nombre del atributo que se actualizará.
		@param $valor Nuevo valor para el atributo.
		@param $filtro (opcional) Sentencia SQL de filtro.
		@return Devuelve el número de tuplas afectadas por la operación.
	**/
		$consulta = "UPDATE $tabla ";
		if ($valor == NULL)
			$consulta .= "SET $atributo = NULL ";
		else
			$consulta .= "SET $atributo = '$valor' ";
		if (isset($filtro))
			$consulta .= "WHERE $filtro";
		$this->ejecutar($consulta);
		return Mysql::$mysqli->affected_rows;
	}
	public function actualizarPorClaveCompuesta($tabla, $claveCompuesta, $valores){
	/**-	Actualiza datos en una tabla MySQL con clave simple (un campo).
		@param $tabla Nombre de la tabla en la que se realizará la inserción.
		@param $claveCompuesta Array asociativo por los nombres de las columnas que forman la clave y con los valores de cada una.
		@param $valores Array asociativo con los nombres de las columnas y los valores a insertar.
		@return Devuelve el número de tuplas afectadas por la operación.
	**/
		$consulta = "UPDATE $tabla SET ";
		foreach ($valores as $columna => $valor)
			if ($valor == NULL) $consulta .= "$columna = NULL,";
			else $consulta .= "$columna = '$valor',";
		$consulta = substr($consulta, 0, strlen($consulta)-1);
		$consulta .= " WHERE ";
		foreach ($claveCompuesta as $columna => $valor){
			if ($valor == NULL) $consulta .= "$columna = NULL AND ";
			else $consulta .= "$columna = '$valor' AND ";
		}
		$consulta = substr($consulta, 0, strlen($consulta)-strlen(' AND '));
		$this->ejecutar($consulta);
		
		return Mysql::$mysqli->affected_rows;
	}
	public function borrar($tabla, $filtro){
	/**-	Borra entradas de una tabla según el filtro indicado.
	 	@param $tabla Nombre de la tabla en la que se realizará el borrado.
		@param $filtro Array asociativo de los nombres de columnas y valores que constituirán el filtro de borrado.
	**/
		$consulta = "DELETE FROM $tabla WHERE ";
		foreach($filtro as $columna => $valor){
			if ($valor == NULL) $consulta .= "$columna = NULL AND ";
			else $consulta .= "$columna = '$valor' AND ";
		}
		$consulta = substr($consulta, 0, strlen($consulta)-5);
		$this->ejecutar($consulta);
	}
	public function cargarTabla($tabla, $fichero){
	/**-	Carga los datos y la estructura de una tabla de la base de datos de un fichero de texto.
		El fichero de carga se genera con mysqldump.
		@param $tabla Nombre de la tabla.
		@param $fichero Path del fichero.
	**/
		$comando = 'mysql -u '.Mysql::$usuario.' --password='.Mysql::$clave.' '.Mysql::$bd.' < '.$fichero;
		exec($comando, $salida, $ret);
		//TODO: Control de errores. ¿Cómo es la salida?
		if ($ret != 0) throw new ExcepcionMySQLErrorCargandoTabla($tabla, $fichero);
	}
	public function codificarResultado($resultado, $campos){
	/**-	Codifica un resultado en UTF-8.
		Esta función se utiliza cuando la base de datos está codificada en ISO.
		@param &$resultado Array de arrays asociativos con los resultados a codificar.
		@param $campos Array con los nombres de los campos a codificar en UTF-8.
		@return Devuelve el mismo array con los campos codificados.
	**/
		$maxResultado = sizeof($resultado);
		$maxCampos = sizeof($campos);
		for($i=0; $i<$maxResultado;$i++)
			for($j=0;$j<$maxCampos;$j++)
				$resultado[$i][$campos[$j]] = utf8_encode($resultado[$i][$campos[$j]]);
		return $resultado;
	}
	public function ejecutar($consulta){
	/**-	Ejecuta una sentencia SQL sin devolver datos (para inserción, actualización y borrado).
		@param $consulta Sentencia SQL de la consulta.
	**/
		//Mysql::$mysqli->query('SET NAMES utf8');
		Mysql::$mysqli->query($consulta);
		if (Mysql::$mysqli->errno){
			throw new ExcepcionMySQLError($consulta,Mysql::$mysqli->error);
		}
	}
	public function escaparCaracteres($parametros){
	/**-	Escapa los caracteres de un array de parámetros para evitar ataques tipo SQL Injection.
		Debe utilizarse para todos los parámetros antes de llamar a funciones de la clase Mysql.
		@param $parametros Array con los parámetros a escapar.
		@return Array de parámetros escapados.
	**/
	//TODO: Ver como aplicar esta función.
		$max = sizeof($parametros);
		for($i=0;$i<$max;$i++)
			$parametros[$i] = mysql_real_escape_string($parametros[$i]);
		return $parametros;
	}
	public function guardarTabla($tabla, $fichero){
	/**-	Guarda los datos y la estructura de una tabla de la base de datos en un fichero de texto.
		La tabla puede cargarse de nuevo con cargarTabla.
		@param $tabla Nombre de la tabla.
		@param $fichero Path del fichero en el que se guardará la tabla.
	**/
		$comando = 'mysqldump --add-drop-table -u '.Mysql::$usuario.' --password='.Mysql::$clave.' '.Mysql::$bd.' '.$tabla.' > '.$fichero;
		exec($comando, $salida, $ret);
		if ($ret != 0) throw new ExcepcionMySQLErrorGuardandoTabla($tabla, $fichero);
	}
	public function filasAfectadas(){
		return Mysql::$mysqli->affected_rows;
	}
	public function insertar($tabla, $valores){
	/**-	Inserta datos en una tabla MySQL.
		@param $tabla Nombre de la tabla en la que se realizará la inserción.
		@param $valores Array asociativo con los nombres de las columnas y los valores a insertar.
		@return Devuelve el identificador del la línea insertada si la tabla tiene un campo autoincrementado
	**/
		$consulta = "INSERT INTO $tabla SET ";
		foreach($valores as $columna => $valor){
			if ($valor === NULL) $consulta .= "$columna = NULL,";
			else $consulta .= "$columna = '$valor',";
		}
		$consulta = substr($consulta, 0, strlen($consulta)-1);
		$this->ejecutar($consulta);
		
		return Mysql::$mysqli->insert_id;
	}
	public function seleccionar($consulta){
	/**-	Ejecuta una consulta de SELECT contra la Base de Datos.
		Puede ejecutar consultas múltiples.
	 	@param $consulta La consulta a ejecutar.
		@return Devuelve un array con todas las filas de datos. 
		Si la consulta es múltiple, devuelve un array de resultados.
		@bug: No detecta los errores en la consultas múltiples.
	**/
		Mysql::$mysqli->query('SET NAMES utf8');
		$respuesta = array();
		if (!Mysql::$mysqli->multi_query($consulta)) throw new ExcepcionMySQLError($consulta,Mysql::$mysqli->error);
		do {
			$resultado = Mysql::$mysqli->store_result();
			$respuestaParcial = array();
			while ($tupla = $resultado->fetch_array())
				array_push($respuestaParcial, $tupla);
			$resultado->close();
			array_push($respuesta, $respuestaParcial);
			if (Mysql::$mysqli->more_results()){
				if (!Mysql::$mysqli->next_result()) throw new ExcepcionMySQLError($consulta,Mysql::$mysqli->error);
			}
			else break;
		} while (true);
			
		if (sizeof($respuesta) == 1) return $respuesta[0];
		else return $respuesta;
	}
	public function seleccionarBD($bd){
	/**-	Establece la base de datos a utilizar.
		@param $bd Nombre de la base de datos.
	**/
		$resultado = Mysql::$mysqli->select_db($bd);
		if (!$resultado) throw new ExcepcionMySQLErrorSeleccionarBD($bd);
	}
	public function seleccionarAtributosPorFiltro($tabla, $atributos, $filtro, $limite=1, $orderBy=null){
	/**-	Devuelve las tuplas con los atributos de una tabla, aplicando el filtro.
		@param $tabla Nombre de la tabla.
		@param $atributos Array de atributos a seleccionar.
		@param $filtro Sentencia SQL de filtro.
		@param $limite Número máximo de resultados. Si el límite es false, no habrá límite. Si el valor es 0 la búsqueda es ilimitada.
		@param $orderBy Sentencia de ordenación de resultados.
		@return Devuelve un array con los datos del resultado.
	**/ 
		$listaAtributos = "";
		foreach($atributos as $atributo)
			$listaAtributos .= $atributo.",";
		$listaAtributos = substr($listaAtributos, 0, strlen($listaAtributos) -1);
		$consulta = "SELECT $listaAtributos FROM $tabla WHERE $filtro";
		if (isset($orderBy))
			$consulta .= " ORDER BY $orderBy";
		if ($limite != 0)
			$consulta .= " LIMIT $limite";
		$respuesta = $this->seleccionar($consulta);
		return $respuesta;
	}
	public function seleccionarPorAtributo($tabla, $atributo, $valor, $limite=1, $ordenacion=false){
	/**-	Devuelve las tuplas de una tabla con el atributo al valor indicado.
		@param $tabla Nombre de la tabla.
		@param $atributo Nombre del atributo para el filtro de selección.
		@param $valor Valor del atributo.
		@param $limite Número máximo de resultados. Si el límite es false, no habrá límite.
		@param $ordenacion Claúsula SQL para la ordenación de resultados.
		@return Devuelve un array con los datos del resultado. Se seleccionan todas las columnas de la tabla.
	**/ 
		if ($valor == NULL)
			$consulta = "SELECT * FROM $tabla WHERE $atributo = NULL";
		else
			$consulta = "SELECT * FROM $tabla WHERE $atributo = '$valor'";
		if ($limite)
			$consulta .= " LIMIT $limite";
		if  ($ordenacion)
			$consulta .=" ORDER BY $ordenacion";
		
		$respuesta = $this->seleccionar($consulta);

		return $respuesta;
	}
	public function seleccionarTabla($tabla, $limite=1000){
	/**-	Devuelve las tuplas de una tabla, sin filtro.
		@param $tabla Nombre de la tabla.
		@param $limite Número máximo de resultados. Si el límite es false, no habrá límite.
		@return Devuelve un array con los datos del resultado. Se seleccionan todas las columnas de la tabla.
	**/ 

		$consulta = "SELECT * FROM $tabla ";
		$respuesta = $this->seleccionar($consulta);
		if ($limite)
			$consulta .= " LIMIT $limite";

		return $respuesta;
	}
	public function verFechaMysql($fechaEsp, $hora=false){
	/**-	Devuelve una fecha en el formato de MySQL
		@param $fechaEsp Texto con la fecha en formato español (DD/MM/AAAA o DD/MM/AA)
		@param $hora Booleano indicando si la fecha se mostrará con hora. Por defecto, se mostrará con hora si la fecha original la tiene.
		@return Texto con la fecha en formato de MySQL (AAAA-MM-DD)
	**/
		if ($fechaEsp == null) return null;
		if ($fechaEsp == '') return null;
			
		if (strlen($fechaEsp) < 6 ) throw new ExcepcionMySQLFormatoFechaInvalido($fechaEsp);
		if (strlen($fechaEsp) > 19) throw new ExcepcionMySQLFormatoFechaInvalido($fechaEsp);
		$trozos = explode(" ",$fechaEsp);
		$formatoMysql = "%Y-%m-%d";

		//Procesamos la fecha
		$fecha = $trozos[0];
		$trozosFecha = explode("/",$fecha);
		if (sizeof($trozosFecha) != 3) throw new ExcepcionMySQLFormatoFechaInvalido($fechaEsp);
		$dia = $trozosFecha[0];
		$mes = $trozosFecha[1];
		$ano = $trozosFecha[2];
		if ((strlen($ano) == 4) AND (($ano > '2037') OR ($ano < '1901'))) throw new ExcepcionMySQLFechaFueraRango($fechaEsp);

		//Procesamos la hora
		$horas = 0;
		$minutos = 0;
		$segundos = 0;
		if ((sizeof($trozos) > 1) AND ($hora !== false)){
			$formatoMysql .= " %H:%M:%S";
			$hora = $trozos[1];
			$trozosHora = explode(":",$hora);
			if ((sizeof($trozosHora) > 3) OR (sizeof($trozosHora) < 2)) throw new ExcepcionMySQLFormatoFechaInvalido($fechaEsp);
			$horas = $trozosHora[0];
			$minutos = $trozosHora[1];
			if (sizeof($trozosHora) == 2)
				$segundos == '00';
			else
				$segundos = $trozosHora[2];
		}

		//Construimos la fecha UNIX
		try{
			$tiempo = mktime($horas, $minutos, $segundos, $mes, $dia, $ano);
		}catch(Exception $e){
			//Falla si algún argumento no es de tipo long
			throw new ExcepcionMySQLFormatoFechaInvalido($fechaEsp);
		}

		//La pasamos a formato MySQL
		return strftime($formatoMysql,$tiempo);
	}
	public function verFechaEsp($fechaMysql,$verHora = false){
	/**-	Transforma una fecha en formato Mysql a formato español (DD/MM/AAAA) incluyendo opcionalmente la hora.
		@param $fechaMysql Texto con la fecha en formato Mysql (YYYY-MM-DD HH:MM:SS).
		@param $verHora Booleano indicando si se debe devolver también la hora en formato HH:MM
		@return Texto con la fecha en formato español.
	**/
		if ($fechaMysql == null) return null;
		
		$trozos = explode(' ',$fechaMysql);
		$fecha = $trozos[0];
		$hora = $trozos[1];

		$trozosFecha = explode('-',$fecha);
		if (sizeof($trozosFecha) != 3) throw new ExcepcionMySQLFormatoFechaInvalido($fechaEsp);

		$anio = $trozosFecha[0];
		if (!is_numeric($anio)) throw new ExcepcionMySQLFormatoFechaInvalido($fechaEsp);
		$mes = $trozosFecha[1];
		if (!is_numeric($mes)) throw new ExcepcionMySQLFormatoFechaInvalido($fechaEsp);
		$dia = $trozosFecha[2];
		if (!is_numeric($dia)) throw new ExcepcionMySQLFormatoFechaInvalido($fechaEsp);

		$resultado = $dia.'/'.$mes.'/'.$anio;
		
		if ($verHora) $resultado .= " ".$hora;
	
		return $resultado;
	}
	public function verUltimoIdInsertado(){
	/**-	Devuelve el id autogenerado que se utilizó en la última consulta.
		@return Identificador.
	**/
		return Mysql::$mysqli->insert_id;
	}
	public function esFormatoFechaMysql($fecha){
	/**-	Comprueba el formato de una fecha, si fuere en mysql devuelve TRUE, si formato esp devuelve FALSE
		@param $fechaMysql Texto con la fecha
		@return Booleano: TRUE si es formato mysql y FALSE si es formato esp. 
	**/
		$trozos = explode(' ',$fecha); //Por si viene hora
		$trozosFecha = explode('-',$trozos[0]); 
		if (sizeof($trozosFecha) != 3){ //No está en formato mysql, veamos si lo está en ESP
			$trozosFecha = explode('/',$trozos[0]); 
			if (sizeof($trozosFecha) != 3) //Tampoco está en ESP
				throw new ExcepcionMySQLFormatoFechaInvalido($fecha);
			//Llegamos -> FORMATO ESP
			return 0;
		}
		//Legamos aquí -> FORMATO MYSQL
		return 1;
	}
}

// 	Excepciones
class ExcepcionMySQLConexion extends Excepcion{
	public function __construct($host, $usuario, $error){
		$titulo = "No se pudo conectar con el servidor de MySQL";
		$texto = "La aplicación no ha podido realizar la conexión al servidor de base de datos MySQL en $host con el usuario $usuario.";
		$solucion = "Revise los datos de conexión (host, usuario y clave) y compruebe que el servidor MySQL está en funcionamiento y es accesible. El error producido es '$error'";
		parent::__construct($titulo,$texto,$solucion);
	}
}
class ExcepcionMySQLSeleccionBd extends Excepcion{
	public function __construct($bd){
		$titulo = "No se pudo seleccionar la base de datos $bd en MySQL";
		$texto = "La aplicación no ha podido seleccionar la base de datos $bd para realizar las operaciones.";
		$solucion = "Compruebe el nombre de la base de datos en los parámetros de configuración y que exista en el servidor MySQL indicado.";
		parent::__construct($titulo,$texto,$solucion);
	}
}
class ExcepcionMySQLError extends Excepcion{
	public function __construct($consulta, $error){
		$titulo = "Ha fallado la operación de base de datos.";
		$texto = "La aplicación no ha podido realizar la operación de base de datos solicitada.";
		$solucion = "La consulta que produjo el error es ($consulta) y el error producido es ($error).";
		parent::__construct($titulo,$texto,$solucion);
	}
}
class ExcepcionMySQLFormatoFechaInvalido extends Excepcion{
	public function __construct($fecha){
		$titulo = "El formato de fecha no es válido.";
		$texto = "La aplicación no ha podido utilizar una fecha recibida ($fecha) porque el formato es inválido.";
		$solucion = "Los formatos de fecha soportados son: DD/MM/AA, DD/MM/AAAA, DD/M/AAAA, DD/M/AA con o sin horas con el formato HH:MM:SS. Por ejemplo '13/02/1968 17:45:32'";
		parent::__construct($titulo,$texto,$solucion);
	}
}
class ExcepcionMySQLFechaFueraRango extends Excepcion{
	public function __construct($fecha){
		$titulo = "La fecha está fuera de rango.";
		$texto = "La aplicación no puede transformar la fecha ($fecha) porque excede los rangos permitidos.";
		$solucionProgramador = "La función Mysql::verFechaMysql utiliza la función mktime, que tiene limitado su rango de validez. Tendrás que programar otra función.";
		$solucionUsuario = "Revise el año de la fecha introducida. Si realmente necesita una fecha con ese año, póngase en contacto con el administrador.";
		parent::__construct($titulo,$texto,$solucionProgramador, $solucionUsuario);
	}
}
class ExcepcionMySQLErrorCargandoTabla extends Excepcion{
	public function __construct($tabla, $fichero){
		$titulo = "Error al cargar la tabla '$tabla' del fichero '$fichero'.";
		$texto = "La aplicación no ha podido cargar la tabla del fichero indicado.";
		$solucionProgramador = "Revise los parámetros de la llamada y los permisos del fichero.";
		parent::__construct($titulo,$texto,$solucionProgramador);
	}
}
class ExcepcionMySQLErrorGuardandoTabla extends Excepcion{
	public function __construct($tabla, $fichero){
		$titulo = "Error al guardar la tabla '$tabla' en el fichero '$fichero'.";
		$texto = "La aplicación no ha podido guardar la tabla en el fichero indicado.";
		$solucionProgramador = "Revise los parámetros y los permisos.";
		parent::__construct($titulo,$texto,$solucionProgramador);
	}
}
class ExcepcionMySQL_UTF8 extends Excepcion{
	public function __construct(){
		$titulo = "Error al intentar cargar el juego de caracteres UTF-8 para la Base de Datos.";
		$texto = "La aplicación no ha cargar el juego de caracteres indicado.";
		$solucionProgramador = "Ha fallado la función set_charset de mysqli. Es posible que no haya realizado correctamente la conexión a MySQL.";
		parent::__construct($titulo,$texto,$solucionProgramador);
	}
}
class ExcepcionMySQLErrorSeleccionarBD extends Excepcion{
	public function __construct($bd){
		$titulo = "Error al seleccionar la base de datos '$bd'.";
		$texto = "La aplicación no ha podido seleccionar la base de datos '$bd'.";
		$solucionProgramador = "Ha fallado la función select_db de Mysqli. Compruebe el nombre de la base de datos y la conexión al servidor.";
		parent::__construct($titulo,$texto,$solucionProgramador);
	}
}
?>
