<?php
/************************************************************************
@file depurador.php 
Copyright Ilke Benson, A.I.E. 2009
@author Miguel Jaque Barbero (mjaque@ilkebenson.com)
Revisado por: $Author$ 
Revisión: $Rev$
@date $Date$
Diseñado por: Miguel Jaque Barbero
Fecha de Diseño: 18/5/2009
**************************************************************************/

class Depurador{
/**	Clase para depuración (clase estática).
**/

	public static function mostrar($variable, $parar=true){
	/**-	Muestra por pantalla el contenido de una variable.
		@param $variable La variable a mostrar.
		@param $parar Booleano indicando si se debe detener la ejecución del script.
		Nota: De momento sólo muestra arrays.
	**/
		echo "<pre>\n";
		$tipo = gettype($variable);
		echo "Tipo: $tipo\n";
		if ($tipo == 'object')
			echo "Clase: ".get_class($variable)."\n";
		print_r($variable);
		if ($parar) exit;
	}
}

?>
